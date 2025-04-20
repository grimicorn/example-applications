<?php

namespace App\Support\User;

use App\User;
use App\Support\HasForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\User\UpdateBasicDetails;
use Illuminate\Support\Facades\Validator;
use App\Support\User\UpdateDesiredPurchaseCriteria;
use App\Support\User\UpdateProfessionalInformation;

trait UpdatesInformation
{
    use HasForms;

    /**
     * Updates user's basic information.
     *
     * @param  Request $request
     * @param  boolean $preview
     *
     * @return App\User
     */
    protected function updateUser(Request $request, $preview = false)
    {
        return (new UpdateBasicDetails($request, $preview))->update();
    }

    /**
     * Updates user's professional information.
     *
     * @param  Request $request
     * @param  boolean $preview
     *
     * @return App\UserProfessionalInformation
     */
    protected function updateUserProfessionalInformation(Request $request, $preview = false)
    {
        return (new UpdateProfessionalInformation($request, $preview))->update();
    }

    /**
     * Updates user's desired purchase criteria.
     *
     * @param  Request $request
     * @param  boolean $preview
     *
     * @return App\UserDesiredPurchaseCriteria
     */
    protected function updateUserDesiredPurchaseCriteria(Request $request, $preview = false)
    {
        return (new UpdateDesiredPurchaseCriteria($request, $preview))->update();
    }
}
