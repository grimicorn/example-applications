<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use App\HistoricalFinancial;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;
use App\Support\HistoricalFinancial\TableRows;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

class ExchangeSpaceHistoricalFinancialsController extends Controller
{
    use HasResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $space = ExchangeSpace::findOrFail($id);
        $data = [
            'pageTitle' => 'Exchange Space',
            'pageSubtitle' => 'Historical Financials Preview',
            'section' => 'exchange-spaces',
            'pageSubSubtitle' => "Business: {$space->listing->title}",
            'pageSubSubtitleLink' => route('businesses.show', ['id' => $space->listing->id]),
        ];

        if ($space->emptyHistoricalFinancials() && !$space->currentUserIsSeller()) {
            return view('app.sections.exchange-space.historical-financials.blank', $data);
        }

        if (!$space->canAccessHistoricalFinancials()) {
            return view('app.sections.exchange-space.historical-financials.blocked', $data);
        }

        return  view('app.sections.exchange-space.historical-financials.show', array_merge([
            'space' => $space,
            'rows' => (new TableRows($space))->getHistoricalFinancials(),
        ], $data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'public' => 'required|boolean',
        ]);

        $space = ExchangeSpace::findOrFail($id);
        $space->historical_financials_public = $request->get('public');
        $space->save();

        return $this->successResponse(
            'Historical Financials & Trends access updated successfully!',
            $request
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
