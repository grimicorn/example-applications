<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\HistoricalFinancial\TableRows;

class AdjustedFinancialsTrendsCSVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $space = ExchangeSpace::findOrFail($id);
        if (!$space->canAccessHistoricalFinancials() and !$space->current_member->is_seller) {
            abort(403);
        }

        (new TableRows($space))->exportAdjustedFinancialsTrendsCSV();
    }
}
