<?php

namespace App\Support\User;

use Illuminate\Http\Request;
use App\Rules\ValidInvalidUrl;

trait ControllerHelpers
{
    protected function validateRequest(Request $request, $isPreview = false)
    {
        $this->validate($request, array_filter([
            'first_name' => $isPreview ? '' : 'required',
            'last_name' => $isPreview ? '' : 'required',
            'email' => $isPreview ? 'email' : 'required|email',
            'tagline' => 'max:400',
            'bio' => 'max:2500',
            'photo_url_file' => 'nullable|mimes:jpg,jpeg,png|max:4096',
            'company_logo_url_file' => 'nullable|mimes:jpg,jpeg,png|max:4096',
            'years_of_experience' => 'nullable|numeric',
            'professionalInformation.links.*' => new ValidInvalidUrl,
        ]));
    }


    protected function validatePreviewRequest(Request $request)
    {
        $this->validateRequest($request, $isPreview = true);
    }
}
