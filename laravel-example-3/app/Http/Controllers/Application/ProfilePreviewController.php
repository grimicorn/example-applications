<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Support\User\ControllerHelpers;
use App\Support\User\UpdatesInformation;
use App\Marketing\HasSiteData;

class ProfilePreviewController extends Controller
{
    use ControllerHelpers,
        UpdatesInformation;

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('app.sections.profile.preview.show', [
            'pageTitle' => 'User Profile',
            'pageSubtitle' => 'Preview',
            'section' => 'profile-preview',
            'isPreview' => true,
            'disableSidebar' => true,
            'professional' => $user = auth()->user()->getPreview(),
            'myListings' => $user->myListings(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validatePreviewRequest($request);

        auth()->user()->fillPreview(function () use ($request) {
            $preview = true;

            // User data
            $user = $this->updateUser($request, $preview);

            // Professional Information
            $user->professionalInformation = $this->updateUserProfessionalInformation($request, $preview);

            // Desired Purchase Criteria
            $user->desiredPurchaseCriteria = $this->updateUserDesiredPurchaseCriteria($request, $preview);

            return $user;
        });
    }
}
