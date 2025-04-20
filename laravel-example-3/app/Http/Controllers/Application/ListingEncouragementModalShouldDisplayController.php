<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListingEncouragementModalShouldDisplayController extends Controller
{
    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->should_display_encouragement_modal = false;

        $listing->save();
    }
}
