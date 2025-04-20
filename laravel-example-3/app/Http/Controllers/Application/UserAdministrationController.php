<?php

namespace App\Http\Controllers\Application;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\HasResponse;

class UserAdministrationController extends Controller
{
    use HasResponse;

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrfail($id)->adminRemove();

        return $this->successResponse('User removed successfully');
    }
}
