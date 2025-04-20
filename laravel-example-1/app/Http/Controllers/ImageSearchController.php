<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\Supports\Google\CustomImageSearch;

class ImageSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->has('query')) {
            return collect();
        }

        return (new CustomImageSearch)->execute($request->get('query'));
    }
}
