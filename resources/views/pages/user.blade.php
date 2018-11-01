@verbatim
<script id="user-template" type="text/x-handlebars-template">
    <div class="row">
        <div class="form-group col-md-6">
            <label for="firstname">First name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Firstname" value="{{ first_name }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="lastname">Lastname</label>
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Lastname" value="{{ last_name }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ email }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="{{ phone }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="confirm_password">Confirm password</label>
            <input type="password" class="form-control" id="confirm_password"  placeholder="Confirm password" autocomplete="off">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="role">Role</label>
            {{#if auth.is_admin}}
            <select name="role" class="form-control">
                <option value="admin" {{#if (eq role 'admin')}}selected{{/if}}>Admin</option>
                <option value="user" {{#if (eq role 'user')}}selected{{/if}}>User</option>
            </select>
            {{else}}
            <span class="label label-default">{{ role }}</span>
            {{/if}}
        </div>
    </div>
</script>
@endverbatim
