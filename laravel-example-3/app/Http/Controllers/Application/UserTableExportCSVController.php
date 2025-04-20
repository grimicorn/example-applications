<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\TableFilters\UserTableFilter;

class UserTableExportCSVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = (new UserTableFilter($request))->get();

        // Create a CSV to write to.
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());

        // Add the headers for the CSV.
        $csv->insertOne($headers = [
            'Name',
            'Roles Selected',
            'Email Address',
            'Account Balance',
            'Account Type',
            'Active Businesses',
            'Last Activity',
        ]);

        // Add the users to the CSV.
        $users->each(function($user) use ($headers, $csv) {
            // We need to pad the output for now since we do not have the data.
            $csv->insertOne(array_pad([
                $user->name,
                is_array($user->primary_roles) ? implode(' ', $user->primary_roles) : '',
                $user->email,
            ], count($headers), ''));
        });

        $date = date('n-j-Y');
        $csv->output("users-{$date}.csv");
    }
}
