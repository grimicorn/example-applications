<tr>
    <td class="user-list-table-name">{{ $user->name }}</td>
    <td class="user-list-table-email">{{ $user->email }}</td>
    <td class="user-list-table-created">
        {{ date('m-d-y', strtotime($user->created_at)) }}
    </td>
    <td class="user-list-table-role">{{ ucwords($user->role) }}</td>
    <td class="user-list-table-api-token text-center">
        @if ($user->api_token)
        <span class="text-success glyphicon glyphicon-ok"></span>
        @else
        <span class="text-danger glyphicon glyphicon-remove"></span>
        @endif
    </td>
    <td class="user-list-table-google-access-token text-center">
        @if ($user->google_access_token)
        <span class="text-success glyphicon glyphicon-ok"></span>
        @else
        <span class="text-danger glyphicon glyphicon-remove"></span>
        @endif
    </td>
</tr>
