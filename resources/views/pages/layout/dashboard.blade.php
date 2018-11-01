@verbatim
<script id="dashboard-template" type="text/x-handlebars-template">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-apple"></span></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                <form action="" class="navbar-form navbar-left" id="search_form">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." id="search_input" name="query" value="{{query}}">
                            <input type="hidden" name="by" id="search_by" value="email">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="search_active_param">by Email</span> <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right" id="search_params">
                                    <li><a href="#" data-by="first_name">by Firstname</a></li>
                                    <li><a href="#" data-by="last_name">by Lastname</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>
                    {{#if auth.is_admin}}
                    <a href="#page=user&do=create" class="btn btn-default">Create user</a>
                    {{/if}}
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#page=profile">Profile</a></li>
                    <li><a href="#logout" id="logout">Logout <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" id="dashboard-container"></div>
</script>
@endverbatim