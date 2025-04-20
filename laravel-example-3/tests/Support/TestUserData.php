<?php

namespace Tests\Support;

use App\User;
use Tests\Support\HasForms;
use App\UserDesiredPurchaseCriteria;
use App\UserProfessionalInformation;

class TestUserData
{
    use HasForms;

    protected $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Gets the request data.
     *
     * @param  Illuminate\Support\Collection $testData
     *
     * @return Illuminate\Support\Collection
     */
    public function getRequest($testData)
    {
        // We do not wanter alter the collection.
        $testData = r_collect($testData->toArray());

        // Convert listed in directory to checkbox
        $testData->put('listed_in_directory', $testData->get('listed_in_directory') ? 'on' : 'off');

        // Convert data to behave like checkboxes.
        $testData->put('primary_roles', $this->covertToCheckboxOptions($testData->get('primary_roles', [])));

        // Convert professional information fields.
        $professionalInformation = $this->setCheckboxesOn($testData->get('professionalInformation'), [
            'ibba_designation',
            'cbi_designation',
            'm_a_source_designation',
            'm_ami_designation',
            'am_aa_designation',
            'abi_designation',
        ]);
        $this->convertTagsToString($professionalInformation, ['other_designations']);
        $testData->put('professionalInformation', $professionalInformation);

        return $testData;
    }

    /**
     * Get the test data.
     *
     * @param  App\User $user
     *
     * @return Illuminate\Support\Collection
     */
    public function get($user)
    {
        $faker = $this->faker;

        // Build up the user.
        $userOverrides = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'listed_in_directory' => true,
         ];
        $fields = collect(factory(User::class)->make($userOverrides)->toArray())
        ->only([
            'first_name',
            'last_name',
            'email',
            'primary_roles',
            'photo_url',
            'listed_in_directory',
            'work_phone',
            'tagline',
            'bio',
        ]);

        // Build up the criteria
        $criteria = collect(factory(UserDesiredPurchaseCriteria::class)->make(['user_id' => $user->id])->toArray())
        ->only([
            'business_categories',
            'locations',
            'asking_price_minimum',
            'asking_price_maximum',
            'revenue_minimum',
            'revenue_maximum',
            'ebitda_minimum',
            'ebitda_maximum',
            'ebitda_minimum',
            'ebitda_maximum',
            'pre_tax_income_minimum',
            'pre_tax_income_maximum',
            'discretionary_cash_flow_minimum',
            'discretionary_cash_flow_maximum',
        ]);
        $fields->put('desiredPurchaseCriteria', $criteria);

        // Build up the professional information.
        $information = collect(factory(UserProfessionalInformation::class)->make(['user_id' => $user->id])->toArray())
        ->only([
            'occupation',
            'years_of_experience',
            'company_name',
            'links',
            'professional_background',
            'areas_served',
            'ibba_designation',
            'cbi_designation',
            'm_a_source_designation',
            'm_ami_designation',
            'am_aa_designation',
            'abi_designation',
            'other_designations',
        ]);
        $fields->put('professionalInformation', $information);

        return $fields;
    }
}
