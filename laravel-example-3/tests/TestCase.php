<?php

namespace Tests;

use App\User;
use Laravel\Spark\Spark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use App\Support\User\BillingTransactionType;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $faker;

    public function setUp()
    {
        parent::setUp();

        // Disable Laravel Scout since the indexing is extremly heavy
        // but can be manually re-enabled if needed at the start of the tests that require it.
        $this->disableScout();

        // Add the business categories since many tests depend on these.
        $this->seed(\BusinessCategoriesSeeder::class);

        // Setup the faker tool
        $this->faker = \Faker\Factory::create();

        // Make sure the cache is empty
        Artisan::call('cache:clear');
    }

    /**
     * Optionally creates a user and logs them in.
     *
     * @param  App\User $user
     * @param  boolean
     *
     * @return App\User
     */
    public function signIn($user = null, $allowEvents = false)
    {
        if (!$allowEvents) {
            $this->withoutEvents();
        }

        if (is_null($user)) {
            $user = factory(User::class)->create();
            $user->desiredPurchaseCriteria()->forceCreate(['user_id' => $user->id]);
            $user->professionalInformation()->forceCreate(['user_id' => $user->id]);
        }

        Auth::login($user);

        return $user;
    }

    protected function signInWithEvents($user = null)
    {
        return $this->signIn($user, true);
    }

    /**
     * Optionally creates a user and logs them in as a developer.
     *
     * @param  App\User $user
     *
     * @return App\User
     */
    protected function signInDeveloper($user = null)
    {
        $developer = $this->signIn($user);

        // Set the user as a developer.
        Spark::developers([$developer->email]);

        return $developer;
    }

    /**
     * Optionally creates a user and logs them in as a developer.
     *
     * @param  App\User $user
     *
     * @return App\User
     */
    protected function signInDeveloperWithEvents($user = null)
    {
        $developer = $this->signInWithEvents($user);

        // Set the user as a developer.
        Spark::developers([$developer->email]);

        return $developer;
    }

    protected function stripeTestToken()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe-test.secret'));

        return \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 12,
                "exp_year" => \Illuminate\Support\Carbon::now()->addYear()->format('Y'),
                "cvc" => "314"
            ],
        ]);
    }

    protected function setUserAsMonthlySubscriber(?User $user = null): User
    {
        $user = $user ?? factory('App\User')->create();
        $user->createAsStripeCustomer($stripeToken = $this->stripeTestToken()->id);

        // Give the listing owner a subscription
        $planId = BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION);
        $plan = $user->sparkPlan($planId);
        $user->fresh()->newSubscription('default', $planId)->create($stripeToken);

        return $user->fresh();
    }

    /**
     * Gets a test file for upload.
     *
     * @param  string $ext
     *
     * @return
     */
    public function getTestUploadFile($ext = 'png')
    {
        $stub = base_path("/tests/stubs/files/test.{$ext}");
        $name = str_random(8) . '.png';
        $path = sys_get_temp_dir().'/'.$name;

        copy($stub, $path);

        return new UploadedFile(
            $path,
            $name,
            filesize($path),
            mime_content_type($stub),
            null,
            true
        );
    }

    /**
     * Disables scout driver.
     */
    public function disableScout()
    {
        config(['scout.driver' => 'null']);
    }

    /**
     * Enables scout driver.
     */
    public function enableScout($driver = 'null')
    {
        config(['scout.driver' => $driver]);
    }

    /**
     * Remvoes non-fillable
     *
     * @param Model $model
     * @return void
     */
    public function removeNonFillableToArray(Model $model)
    {
        $fillable = $model->getFillable();

        return collect($model->makeVisible($model->getHidden())->toArray())
        ->filter(function ($value, $key) use ($fillable) {
            return in_array($key, $fillable);
        })->toArray();
    }
}
