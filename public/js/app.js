$(document).ready(function()
{
    new Controller();
});

function Controller()
{
    var container = $('#main-container');

    $(window).on('hashchange', this.handle.bind(this));
    container.on('submit', '#login-form', this.onLogin.bind(this));
    container.on('click', '#logout', this.onLogout.bind(this));
    container.on('click', '.js-delete-user', this.onUserDelete.bind(this));
    container.on('click', '#search_params li a', this.onChangeSearchBy.bind(this));
    container.on('submit', '#search_form', this.onSearch.bind(this));
    container.on('submit', '#create_user_form', this.onUserCreate.bind(this));
    container.on('submit', '#edit_user_form', this.onUserUpdate.bind(this));
    container.on('submit', '#profile_form', this.onUserProfileUpdate.bind(this));

    this.template = new Template();
    this.handle();
}

Controller.prototype.handle = function ()
{
    var router = this.getRouter(),
        self = this,
        user = this.getUserData(),
        settings = this.getTemplateSettings(user);

    if(user === null)
    {
        this.go('page=login');
        router.page = 'login';
    }

    switch (router.page)
    {
        case '':
            $.ajax({
                method: "GET",
                url: "/api/users",
                headers: self.getAjaxHeaders()
            }).done(function(json){
                json.auth = settings.auth;
                self.template.buildPage(router.page, settings, json);
            }).fail(function(response){
                if(response.status === 401)
                {
                    self.go('page=login');
                }
            });
            self.go('');
            break;
        case 'login': self.template.buildPage(router.page, {}, {});  break;
        case 'profile':
            $.ajax({
                method: "GET",
                url: "/api/users/" + user.id,
                headers: self.getAjaxHeaders()
            }).done(function(json){
                json.auth = settings.auth;
                self.template.buildPage(router.page, settings, json);
            }).fail(function(response){
                if(response.status === 401)
                {
                    self.go('page=login');
                }
            });
             break;
        case 'user':
            switch (router.do)
            {
                case 'create':
                    if(settings.auth.is_admin)
                    {
                        self.template.buildPage(router.page + '_' + router.do, settings, {});
                    }
                    else
                    {
                        self.go('');
                    }
                    break;
                case 'edit':
                    if(settings.auth.is_admin)
                    {
                        $.ajax({
                            method: "GET",
                            url: "/api/users/" + router.id,
                            headers: self.getAjaxHeaders()
                        }).done(function(json){
                            json.auth = settings.auth;
                            self.template.buildPage(router.page + '_' + router.do, settings, json);
                        }).fail(function(response){
                            if(response.status === 401)
                            {
                                self.go('page=login');
                            }
                            else if(response.status === 404)
                            {
                                self.go('');
                            }
                        });
                    }
                    else
                    {
                        self.go('');
                    }
                    break;
                default: self.go('');
            }
            break;
        case 'search':
            var query = router.query === undefined ? '' : router.query,
                by = router.by === undefined ? '' : router.by;
            settings.query = decodeURIComponent(query);

            $.ajax({
                method: "GET",
                url: "/api/users?query=" + query + "&by=" + by,
                headers: self.getAjaxHeaders()
            }).done(function(json){
                json.auth = settings.auth;
                self.template.buildPage(router.page, settings, json);
                $('#search_params').find('a[data-by='+by+']').click();
            }).fail(function(response){
                if(response.status === 401)
                {
                    self.go('page=login');
                }
            });
            break;
        default: self.go('');
    }
};

Controller.prototype.getRouter = function ()
{
    var hashParams = {page: ''},
        e,
        reg = /([^&;=]+)=?([^&;]*)/g,
        query = window.location.hash.substring(1);

    while (e = reg.exec(query))
        hashParams[e[1]] = e[2];

    return hashParams;
};

Controller.prototype.getTemplateSettings = function(user)
{
    return user === null ? {} : {auth: {is_admin: user.role === 'admin', id: user.id}};
};

Controller.prototype.onLogin = function (e)
{
    e.preventDefault();

    var $this = $(e.currentTarget),
        self = this,
        url   = $this.attr('action'),
        data  = $this.serialize();

    $.post(url, data, function(json) {
        localStorage.setItem('user',JSON.stringify({
            access_token: json.access_token,
            role: json.role,
            id: json.id
        }));
        self.go('');

    }).fail(function() {
        toastr.error('Wrong login or password!');
    });
};

Controller.prototype.onLogout = function(e)
{
    e.preventDefault();

    var self = this;

    $.ajax({
        method: "POST",
        url: "/api/logout",
        headers: self.getAjaxHeaders()
    }).done(function(json){
        localStorage.clear();
        self.go('page=login');
    }).fail(function(){
        console.log('error');
    });
};

Controller.prototype.onUserDelete = function(e)
{
    var $this = $(e.currentTarget),
        self = this;

    if(confirm('Are you sure?'))
    {
        var userID = $this.attr('data-id');

        $.ajax({
            method: "DELETE",
            url: "/api/users/" + userID,
            headers: self.getAjaxHeaders()
        }).done(function(json){
            $this.closest('tr').remove();
            toastr.success('User '+ $this.attr('data-fullname') +' was deleted');

        }).fail(function(response){
            if(response.status === 403)
            {
                toastr.error(response.responseJSON.error);
            }
            else
            {
                toastr.error('Something go wrong.');
            }
        });
    }
};

Controller.prototype.onChangeSearchBy = function(e)
{
    e.preventDefault();
    var $this = $(e.currentTarget),
        $searchBy = $('#search_by'),
        $searchActiveParam = $('#search_active_param'),
        searchBy = $searchBy.val(),
        searchActiveParam = $searchActiveParam.html();

    $searchActiveParam.html($this.html());
    $searchBy.val($this.attr('data-by'));
    $this.attr('data-by',searchBy).html(searchActiveParam);
};

Controller.prototype.onSearch = function(e)
{
    e.preventDefault();

    var searchText = $('#search_input').val();

    if(searchText == '')
    {
        toastr.warning('Write something in search input');
    }
    else
    {
        this.go('page=search&query=' + encodeURIComponent(searchText) + '&by=' + $('#search_by').val());
    }
};

Controller.prototype.onUserCreate = function(e)
{
    e.preventDefault();

    var $this = $(e.currentTarget),
        self = this,
        $password = $('#password');

    if($password.val() === $('#confirm_password').val() && $password !== '')
    {
        $.ajax({
            method: "POST",
            url: "/api/user",
            data: $this.serialize(),
            headers: self.getAjaxHeaders()
        }).done(function(json){
            self.go('')

        }).fail(function(response){
            if(response.status === 422)
            {
                toastr.error(self.getErrors(response));
            }
            else
            {
                toastr.error('Something go wrong.');
            }
        });
    }
    else
    {
        toastr.warning('Password and confirm password don\'t match');
    }
};

Controller.prototype.onUserUpdate = function(e)
{
    e.preventDefault();

    var $this = $(e.currentTarget),
        self = this;

    if($('#password').val() === $('#confirm_password').val())
    {
        $.ajax({
            method: "PATCH",
            url: $this.attr('action'),
            data: $this.serialize(),
            headers: self.getAjaxHeaders()
        }).done(function(json){
            self.go('');
        }).fail(function(response){
            if(response.status === 422)
            {
                toastr.error(self.getErrors(response));
            }
            else
            {
                toastr.error('Something go wrong.');
            }
        });
    }
    else
    {
        toastr.warning('Password and confirm password don\'t match');
    }
};

Controller.prototype.onUserProfileUpdate = function(e)
{
    e.preventDefault();

    var $this = $(e.currentTarget),
        self = this;

    if($('#password').val() === $('#confirm_password').val())
    {
        $.ajax({
            method: "PATCH",
            url: $this.attr('action'),
            data: $this.serialize(),
            headers: self.getAjaxHeaders()
        }).done(function(json){
            var user = self.getUserData();
            user.role = json.role;
            localStorage.setItem('user', JSON.stringify(user));
            self.go('');
        }).fail(function(response){
            if(response.status === 422)
            {
                toastr.error(self.getErrors(response));
            }
            else
            {
                toastr.error('Something go wrong.');
            }
        });
    }
    else
    {
        toastr.warning('Password and confirm password don\'t match');
    }
};

Controller.prototype.go = function (route)
{
    window.location.hash = route;

};

Controller.prototype.getErrors = function (response)
{
    var errorJson = response.responseJSON,
        errorMessage = '';

    for (var key in errorJson.errors)
    {
        errorMessage += errorJson.errors[key][0]+ '<br>';
    }

    return errorMessage;
};

Controller.prototype.getAjaxHeaders = function ()
{
    var user = this.getUserData();

    return {
        'Authorization': 'Bearer '+ user.access_token
    };
};

Controller.prototype.getUserData = function ()
{
    return localStorage.getItem('user') === null ? null : JSON.parse(localStorage.getItem('user'));

};
//**********************************************************************************************************************
function Template()
{
    Handlebars.registerHelper({
        eq: function (v1, v2) {
            return v1 === v2;
        }
    });
}
Template.prototype.buildPage = function(route, settings, data)
{
    var map = {
        '' : {'#main-container': ['#dashboard-template', settings],
              '#dashboard-container': ['#users_table-template', {}],
              '#user_table-container': ['#user_table_row-template',data]
        },
        'login' : {'#main-container': ['#login-template',{}]},
        'profile': {
            '#main-container': ['#dashboard-template',settings],
            '#dashboard-container': ['#profile-template', settings],
            '#profile-container': ['#user-template',data]
        },
        'user_create' : {
            '#main-container': ['#dashboard-template',settings],
            '#dashboard-container': ['#create_user-template', {}],
            '#create_user-container': ['#user-template', settings]
        },
        'user_edit': {
            '#main-container': ['#dashboard-template',settings],
            '#dashboard-container': ['#edit_user-template', data],
            '#edit_user-container': ['#user-template', data]
        },
        'search': {
            '#main-container': ['#dashboard-template', settings],
            '#dashboard-container': ['#users_table-template', settings],
            '#user_table-container': ['#user_table_row-template',data]
        }
    };
    this.render(map[route]);

};

Template.prototype.render = function (obj)
{
    var templateId, containerId, compiledTemplate, data;

    for (containerId in obj)
    {
        if(obj.hasOwnProperty(containerId))
        {
            templateId = obj[containerId][0];
            data = obj[containerId][1];

            if($(containerId).length)
            {
                compiledTemplate = Handlebars.compile($(templateId).html());
                $(containerId).html(compiledTemplate(data));
            }
        }
    }
};
