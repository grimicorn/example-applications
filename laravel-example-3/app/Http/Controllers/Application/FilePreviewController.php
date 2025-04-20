<?php

namespace App\Http\Controllers\Application;

use App\Support\FilePreview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FilePreviewController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $file_url = request()->get('file_url', '');
        if (!$file_url) {
            abort('404');
        }

        return view('app.file-preview', [
            'file_url' => $file_url,
            'is_image' => (new FilePreview($file_url))->isImage(),
        ]);
    }
}
