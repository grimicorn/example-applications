<?php

use App\Support\SeederHelper;
use Illuminate\Database\Seeder;

class UserExampleSeeder extends Seeder
{
    use SeederHelper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make sure we have business categories
        $this->call(BusinessCategoriesSeeder::class);

        $userPhotos = [
            1 => 'user-photo-1.jpg',
            2 => 'user-photo-2.jpg',
            3 => 'user-photo-3.jpg',
            4 => null,
        ];

        $companyLogos = [
            1 => 'company-logo-1.jpg',
            2 => null,
            3 => null,
            4 => null,
        ];

        for ($i = 1; $i <= 4; $i++) {
            // Add user
            $user = factory('App\User')->states("example-{$i}")->create();
            $this->addPhoto($user, $userPhotos[$i]);
            $user->photo_id = optional($user->getFirstMedia())->id;
            $user->save();

            // Add users professional information
            $profInfoData = factory('App\UserProfessionalInformation')->states("example-{$i}")->make([
                'user_id' => $user->id
            ])->toArray();
            $profInfoData = collect($profInfoData)->except(['state_unabbreviated'])->toArray();
            $this->addPhoto($user, $companyLogos[$i]);
            $profInfoData['company_logo_id'] = optional($user->fresh()
                                            ->getMedia()
                                            ->fresh()
                                            ->where('file_name', $companyLogos[$i])->first())->id;
            $user->professionalInformation->update($profInfoData);

            // Add users desired purchase criteria.
            $user->desiredPurchaseCriteria->update(
                factory('App\UserDesiredPurchaseCriteria')->states("example-{$i}")->make([
                    'user_id' => $user->id
                ])->toArray()
            );
        }
    }
}
