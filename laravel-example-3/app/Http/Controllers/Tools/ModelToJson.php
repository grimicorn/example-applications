<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModelToJson extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tools.model-to-json-list', [
            'model_id' => request()->get('model_id'),
            'models' => $this->possibleModels(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (!request()->has('model')) {
            return 'You are missing the required <code>model</code> parameter.';
        }

        $model = trim(request()->get('model'), '\\');
        $model = str_replace(['App\\'], '', $model);
        $model = "\App\\{$model}";

        if (request()->has('model_id')) {
            return $model::findOrFail(request()->get('model_id'));
        }

        return $model::inRandomOrder()->get()->first();
        // if (!$this->possibleModels()->contains())
    }

    protected function possibleModels()
    {
        return collect([
            'BillingTransaction',
            'BusinessCategory',
            'Conversation',
            'ConversationNotification',
            'AbuseReport',
            'ExchangeSpace',
            'ExchangeSpaceMember',
            'ExchangeSpaceNotification',
            'Expense',
            'ExpenseLine',
            'Favorite',
            'HistoricalFinancial',
            'Listing',
            'ListingCompletionScoreTotal',
            'ListingExitSurvey',
            'MarketingContactNotification',
            'Media',
            'Message',
            'RejectionReason',
            'Revenue',
            'RevenueLine',
            'SavedSearch',
            'SavedSearchNotification',
            'SystemMessage',
            'User',
            'UserDesiredPurchaseCriteria',
            'UserEmailNotificationSettings',
            'UserProfessionalInformation',
        ]);
    }
}
