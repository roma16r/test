@verbatim
    <script id="login-template" type="text/x-handlebars-template">
        <div class="panel panel-default col-sm-4 col-sm-offset-4">
            <div class="panel-body">
                <form action="/api/login" id="login-form">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-default pull-right">Login</button>
                </form>
            </div>
        </div>
    </script>
@endverbatim