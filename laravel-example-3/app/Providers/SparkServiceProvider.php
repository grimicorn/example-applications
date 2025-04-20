<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use App\Support\HasStripeHelpers;
use App\Support\ContactInformation;
use App\Support\User\BillingTransactionType;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    use HasStripeHelpers;

    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor' => 'Firm Exchange',
        'product' => 'Firm Exchange',
        'street' => 'PO Box 111',
        'location' => 'Your Town, NY 12345',
        'phone' => '555-555-5555',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = '';

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        'dholloran@matchboxdesigngroup.com',
        'cwhitmore@matchboxdesigngroup.com',
        'jclark@firmexchange.com',
        'tgreene@firmexchange.com'
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = false;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {
        $this->details = array_merge(ContactInformation::getSparkData());
        $this->sendSupportEmailsTo = ContactInformation::getEmail('support');

        Spark::useStripe()->noCardUpFront();

        Spark::collectBillingAddress();

        Spark::freePlan()
            ->features([]);
        Spark::plan(
            BillingTransactionType::getLabel(BillingTransactionType::MONTHLY_SUBSCRIPTION),
            BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION_SMALL)
        )->price(optional($this->getMonthlyPricing())->get('amount'))->features([]);

        Spark::useTwoFactorAuth();
    }
}
