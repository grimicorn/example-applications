<?php

namespace App\Http\Controllers\Application;

use App\SavedSearch;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Support\HasSearchInputs;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class SavedSearchController extends Controller
{
    use HasResponse;
    use HasSearchInputs;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $this->validate($request, [
            'name' => 'required',
        ]);

        $search = new SavedSearch;
        $search->saveByRequest();

        return $this->successResponse('Your search has been saved successfully!', $request);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, int $id)
    {
        // Validate
        $this->validate($request, [
            'name' => 'required',
        ]);

        $search = SavedSearch::findOrFail($id);
        $search->saveByRequest();

        return $this->successResponse('Your search has been saved successfully!', $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SavedSearch::findOrFail($id)->delete();

        return back()->with('status', 'Your search has been deleted!')
                            ->with('success', true);
    }
}
