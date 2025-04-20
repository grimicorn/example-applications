<?php

namespace Tests\Support;

use App\User;
use App\BusinessCategory;
use App\Support\HasStates;
use App\Support\HasListingTypes;
use App\Support\HasUserPrimaryRoles;
use App\UserDesiredPurchaseCriteria;
use App\UserProfessionalInformation;
use App\Support\HasBusinessCategories;
use App\Application\HasProfileEditData;
use App\Support\Listing\HasSearchOptions;

class TestApplicationData
{
    use HasSearchOptions;
    use HasUserPrimaryRoles;
    use HasBusinessCategories;
    use HasProfileEditData;
    use HasListingTypes;
    use HasStates;

    protected $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Gets a search listing updated at.
     *
     * @return string
     */
    public function getSearchListingUpdatedAt()
    {
        return $this->faker->randomElement($this->listingUpdatedAt());
    }

    /**
     * Gets a test transaction type.
     *
     * @return string
     */
    public function getTestTransactionType()
    {
        return $this->faker->randomElement($this->transactionTypes());
    }

    /**
     * Get test business categories.
     *
     * @return string
     */
/*    public function getTestBusinessType()
    {
        return collect($this->getListingTypes())->random();
    }*/

    /**
     * Gets test business categories.
     *
     * @return array
     */
    public function getTestBusinesCategories()
    {
        $categories = BusinessCategory::all()->pluck('id')->toArray();
        $categories = array_values($this->faker->randomElements(
            $categories,
            $this->faker->numberBetween(1, round(count($categories) * .5))
        ));

        sort($categories);

        return $categories;
    }

    /**
     * Gets test roles.
     *
     * @return array
     */
    public function getTestPrimaryRoles()
    {
        $roles = array_values($this->faker->randomElements(
            $this->getUserPrimaryRoles(),
            $this->faker->numberBetween(1, 4)
        ));

        sort($roles);

        return $roles;
    }

    /**
     * Gets random test locations (city/state).
     *
     * @return array
     */
    public function getTestLocations()
    {
        $faker = $this->faker;
        $locations = [];

        for ($i = 0; $i <= $faker->numberBetween(1, 5); $i++) {
            $locations[] = [
                'state' => $this->getTestStateAbbreviation(),
            ];
        }

        return $locations;
    }

    /**
     * Gets a random state abbreviation
     *
     * @return string
     */
    public function getTestStateAbbreviation()
    {
        $stateAbbreviations = array_keys($this->getStates());

        return $this->faker->randomElement($stateAbbreviations);
    }

    /**
     * Gets an occupation.
     *
     * @return string
     */
    public function getTestOccupation($addOther = true)
    {
        $occupations = $this->getOccupations();

        if ($addOther = true) {
            $occupations[] = $this->faker->jobTitle;
        }

        return $this->faker->randomElement($occupations);
    }

    /**
     * Gets test links.
     *
     * @return array
     */
    public function getTestLinks()
    {
        $links = [];
        for ($i=0; $i < $this->faker->numberBetween(1, 5); $i++) {
            $links[] = $this->faker->url;
        }

        return $links;
    }

    /**
     * Gets test license qualifications.
     *
     * @return array
     */
    public function getTestDesignations()
    {
        $faker = $this->faker;

        // Pick a few from the available.
        $data = $faker->randomElements(
            $this->getDesignations(),
            $faker->numberBetween(2, 5)
        );

        // Handle other.
        $data[] = implode(',', $faker->words($faker->randomDigitNotNull));

        return $data;
    }
}
