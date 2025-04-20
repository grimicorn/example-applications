<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminUserListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Cache::get('admin.user-list', function () {
            return User::all();
        }, 10);

        return view('admin.user-list', compact('users'));
    }
}
