<?php

namespace App\Http\Controllers\Application;

use App\User;
use App\Support\HasStates;
use App\Support\HasSelects;
use App\Support\HasResponse;
use Illuminate\Http\Request;
use App\Support\HasOccupations;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Application\HasProfileEditData;
use App\Support\User\ControllerHelpers;
use App\Support\User\UpdatesInformation;

class ProfileEditController extends Controller
{
    use HasOccupations,
        HasSelects,
        UpdatesInformation,
        HasProfileEditData,
        HasStates,
        ControllerHelpers,
        HasResponse;


    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        // Empty the preview on load
        auth()->user()->emptyPreview();

        return view('app.sections.profile.edit.edit', [
            'occupations' => $this->convertForSelect(
                $this->getUserOccupations(),
                $setValues = true
            ),
            'designations' => $this->getDesignations(),
            'pageTitle' => 'Profile',
            'section' => 'profile',
            'statesForSelect' => $this->getStatesForSelect(),
            'previewRoutes' => [
                route('profile.preview')
            ],
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
        // Validate
        $this->validateRequest($request);

        // User data
        $this->updateUser($request);

        // Professional Information
        $this->updateUserProfessionalInformation($request);

        // Desired Purchase Criteria
        $this->updateUserDesiredPurchaseCriteria($request);

        // Clear the preview cache
        auth()->user()->emptyPreview();

        return $this->successResponse('Your profile has been successfully updated!');
    }
}
