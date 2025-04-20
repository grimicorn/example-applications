<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\UserPermissionEnum;
use Symfony\Component\HttpFoundation\Response;

class StyleguideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_unless(
            $request->user()->hasPermissionTo(UserPermissionEnum::VIEW_STYLE_GUIDE),
            Response::HTTP_NOT_FOUND
        );

        if ($fakeUpload = $this->fakeFileUpload($request)) {
            return $fakeUpload;
        }

        return view('styleguide.index');
    }

    protected function fakeFileUpload(Request $request)
    {
        $file = null;

        if ($request->hasFile('input_file_example_1')) {
            $file = $request->file('input_file_example_1');
        }

        if ($request->hasFile('input_file_example_2')) {
            $file = $request->file('input_file_example_2');
        }

        if ($file) {
            return [
                'data' => [
                    'status' => 'File uploaded successfully',
                    'media' => [
                        'file_name' => $file->getClientOriginalName(),
                        'full_url' =>'http://placehold.it/800x800?text=DEMO'
                    ],
                ],
            ];
        }

        $clearFile = false;
        if ($request->has('input_file_example_1')) {
            $clearFile = true;
        }

        if ($request->has('input_file_example_2')) {
            $clearFile = true;
        }

        if ($clearFile) {
            return [
                'data' => [
                    'status' => 'File deleted successfully',
                ],
            ];
        }
    }
}
