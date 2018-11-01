@verbatim
    <script id="user_table_row-template" type="text/x-handlebars-template">
        {{#each users}}
        <tr>
            <td>{{ id }}</td>
            <td>{{ first_name }}</td>
            <td>{{ last_name }}</td>
            <td>{{ email }}</td>
            <td>{{ phone }}</td>
            <td>{{ role }}</td>
            {{#if ../auth.is_admin}}
            <td align="right">
                <div class="btn-group" role="group" aria-label="...">
                    <a href="#page=user&do=edit&id={{id}}" class="btn btn-warning" title="edit">
                        <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <button class="btn btn-danger js-delete-user" title="delete" data-id="{{ id }}" data-fullname="{{ first_name }} {{ last_name }}">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </div>
            </td>
            {{/if}}
        </tr>
        {{/each}}
    </script>
@endverbatim
