@verbatim
<script id="profile-template" type="text/x-handlebars-template">
    <div class="panel panel-default">
        <div class="panel-heading">Edit Profile</div>
        <div class="panel-body">
            <form action="/api/users/{{auth.id}}" id="profile_form">
                <div id="profile-container"></div>
                <button type="submit" class="btn btn-default">Edit</button>
            </form>
        </div>
    </div>
</script>
@endverbatim