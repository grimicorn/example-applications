<?php

namespace App\Http\Controllers\Application;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAccountBalanceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return [
            'account_balance' => $user->getAccountBalance(),
        ];
    }
}
