<?php

namespace App\Http\Controllers;

use App\SitePage;
use Illuminate\Http\Request;

class SitePageIgnoreController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $page = SitePage::findOrFail($id);
        $this->authorize('update', $page);

        $page->ignore();

        $status = 'Page ignored successfully';
        if ($request->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = SitePage::findOrFail($id);
        $this->authorize('delete', $page);

        $page->removeIgnore();

        $status = 'Page ignore removed successfully';
        if (request()->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return back()->with('status', $status);
    }
}
