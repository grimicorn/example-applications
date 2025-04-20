<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\TableFilters\UserTableFilter;

class UserTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginatedUsers = (new UserTableFilter($request))->paginated();

        if ($request->expectsJson()) {
            return $paginatedUsers;
        }

        return view('app.sections.admin.user-table.index', [
            'paginatedUsers' => $paginatedUsers,
            'pageTitle' => 'Admin',
            'pageSubtitle' => 'Users Table',
            'section' => 'admin',
            'columns' => [
                [
                    'label' => 'Name',
                    'sortKey' => 'name',
                    'isSorted' => true,
                    'sortOrder' => 'asc',
                    'class' => 'th-name'
                ],
                [
                    'label' => 'Roles Selected',
                    'sortKey' => 'roles_selected',
                    'class' => 'th-role'
                ],
                [
                    'label' => 'Email Address',
                    'sortKey' => 'email_address',
                    'class' => 'th-email'
                ],
                [
                    'label' => 'Account</br>Balance',
                    'sortDisabled' => true,
                    'class' => 'th-balance'
                ],
                [
                    'label' => 'Account</br>Type',
                    'sortDisabled' => true,
                    'class' => 'th-type'
                ],
                [
                    'label' => 'Active</br>Businesses',
                    'class' => 'th-listings',
                    'sortKey' => 'active_listings',
                ],
                [
                    'label' => 'Last</br>Login',
                    'class' => 'th-login',
                    'sortyKey' => 'last_login',
                ],
            ],
        ]);
    }
}
