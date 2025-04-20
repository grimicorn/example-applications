<div class="table-responsive">
    <table class="user-list-table table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th class="user-list-table-name">Name</th>
                <th class="user-list-table-email">Email</th>
                <th class="user-list-table-created">Registered</th>
                <th class="user-list-table-role">Role</th>
                <th class="user-list-table-api-token">API Token</th>
                <th class="user-list-table-google-access-token">Google Access Token</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
            @include('admin.users-list.table-row')
            @endforeach
        </tbody>
    </table>
</div>
