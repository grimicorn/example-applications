<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;

class ExchangeSpaceFileController extends Controller
{
    use HasResponse;

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'media_id' => 'required|numeric',
        ]);

        $space = ExchangeSpace::findOrFail($id);
        $media_id = $request->get('media_id');
        $space->getMedia()->filter(function ($file) use ($media_id) {
            return $file->id === $media_id;
        })->each(function ($file) {
            // Only owners should be able to delete files.
            $owner_id = $file->getCustomProperty('user_id');
            if ($owner_id !== auth()->id()) {
                abort(403, 'You are not allowed to delete this file.');
            }

            $file->delete();
        });

        return $this->successResponse('File deleted succesfully!', $request);
    }
}
