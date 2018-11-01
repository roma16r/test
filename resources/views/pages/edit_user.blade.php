@verbatim
<script id="edit_user-template" type="text/x-handlebars-template">
    <div class="panel panel-default">
        <div class="panel-heading">Edit user</div>
        <div class="panel-body">
            <form action="/api/users/{{id}}" id="edit_user_form">
                <div id="edit_user-container"></div>
                <button type="submit" class="btn btn-default">Edit</button>
            </form>
        </div>
    </div>
</script>
@endverbatim