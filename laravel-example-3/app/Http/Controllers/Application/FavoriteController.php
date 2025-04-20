<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use App\Favorite;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\TableFilters\FavoriteTableFilter;

class FavoriteController extends Controller
{
    use HasResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = (new FavoriteTableFilter(
            request()
        ))->paginated();

        if (request()->expectsJson()) {
            return $favorites;
        }

        return view('app.sections.favorite.index', [
            'pageTitle' => 'Favorites',
            'section' => 'favorites',
            'paginatedFavorites' => $favorites,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required',
        ]);

        $favorite = new Favorite;
        $favorite->user_id = Auth::id();
        $favorite->listing_id = $request->get('listing_id');
        $favorite->save();

        $message = 'Business Favorited Successfully';
        $data = ['favorite_id' => $favorite->fresh()->id];
        return $this->successResponse($message, $request, null, $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        Favorite::destroy($id);

        return $this->successResponse('Business Un-Favorited Successfully', $request);
    }
}
