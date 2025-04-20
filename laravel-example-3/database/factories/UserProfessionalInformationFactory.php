<?php

use Faker\Generator as Faker;
use Tests\Support\TestApplicationData;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\UserProfessionalInformation::class, function (\Faker\Generator $faker) {
    $data = new TestApplicationData;

    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'occupation' => $data->getTestOccupation(),
        'years_of_experience' => $faker->numberBetween(5, 20),
        'company_name' => $faker->company,
        'links' => $data->getTestLinks(),
        'professional_background' => $faker->paragraphs($faker->numberBetween(2, 5), true),
        'areas_served' => $data->getTestLocations(),
        'ibba_designation' => $faker->boolean(),
        'cbi_designation' => $faker->boolean(),
        'm_a_source_designation' => $faker->boolean(),
        'm_ami_designation' => $faker->boolean(),
        'am_aa_designation' => $faker->boolean(),
        'abi_designation' => $faker->boolean(),
        'other_designations' => $faker->words($faker->numberBetween(0, 10)),
        'company_logo_id' => null,
    ];
});

$factory->state(App\UserProfessionalInformation::class, 'example-1', function ($faker) {
    return [
        'occupation' => 'Investor',
        'years_of_experience' => '20+',
        'company_name' => 'Crown Acquisitions',
        'links' => ['https://crownacquisitions.com'],
        'professional_background' => 'Over twenty years buying (and very, very rarely selling) businesses.',
        'areas_served' => [
            ['state' => 'NY'],
        ],
        'ibba_designation' => null,
        'cbi_designation' => null,
        'm_a_source_designation' => null,
        'm_ami_designation' => null,
        'am_aa_designation' => null,
        'abi_designation' => null,
        'other_designations' => ['MBA', 'Gold Gloves boxer'],
    ];
});

$factory->state(App\UserProfessionalInformation::class, 'example-2', function ($faker) {
    return [
        'occupation' => 'Broker',
        'years_of_experience' => 15,
        'company_name' => 'Waterboy Deal Network',
        'links' => ['https://waterboydealnetwork.com'],
        'professional_background' => '"WDN is dedicated to helping Main Street business owners succeed and be able to pass-on their success. All of our personnel are seasoned professionals with significant experience leading large organizations. We focus our efforts on Main Street business owners generating sales volume of $1-25 million annually who often are "technical experts" in their field but lack the resources and knowledge that larger organizations take for granted. WDN facilitates learning that focuses on growth and building value so that business owners can enjoy more time and profits from their business.

        Schedule a confidential discussion with us today."',
        'areas_served' => [
            'state' => 'LA',
        ],
        'ibba_designation' => true,
        'cbi_designation' => null,
        'm_a_source_designation' => null,
        'm_ami_designation' => null,
        'am_aa_designation' => true,
        'abi_designation' => null,
        'other_designations' => null,
    ];
});

$factory->state(App\UserProfessionalInformation::class, 'example-3', function ($faker) {
    return [
        'occupation' => 'Business Owner',
        'years_of_experience' => null,
        'company_name' => null,
        'links' => null,
        'professional_background' => null,
        'areas_served' => null,
        'ibba_designation' => null,
        'cbi_designation' => null,
        'm_a_source_designation' => null,
        'm_ami_designation' => null,
        'am_aa_designation' => null,
        'abi_designation' => null,
        'other_designations' => null,
    ];
});

$factory->state(App\UserProfessionalInformation::class, 'example-4', function ($faker) {
    return [
        'occupation' => 'Accountant',
        'years_of_experience' => 10,
        'company_name' => 'The Alexander Law Firm, LLC',
        'links' => ['https://alexanderlawfirmllc.com'],
        'professional_background' => 'We specialize in IRS and state tax resolution and can handle just about any tax matter you have, including unfiled returns, full or partial installment agreements, hardships, audits, liens, levies, penalty abatements, offers in compromise, appeals and everything in between. By focusing our practice exclusively on tax matters, we have built a reputation for effective tax controversy representation. To date, our advocacy has saved our clients millions in outstanding tax liabilities.',
        'areas_served' => [
            'state' => 'MO',
        ],
        'ibba_designation' => null,
        'cbi_designation' => null,
        'm_a_source_designation' => null,
        'm_ami_designation' => null,
        'am_aa_designation' => null,
        'abi_designation' => null,
        'other_designations' => [
            'BSBA',
            'JD',
            'Bar Admissions: Missouri, U.S. Tax Court, U.S. District Court of Eastern Missouri',
        ],
    ];
});
