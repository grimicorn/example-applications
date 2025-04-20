<?php
// @codingStandardsIgnoreFile
namespace Tests\Unit\Support\User;

use Tests\TestCase;
use App\Support\User\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->enableScout();
    }

    public function tearDown()
    {
        $this->disableScout();

        parent::tearDown();
    }

    /**
    * @test
    */
    public function it_searches_for_users_by_occupation()
    {
        $occupation = 'Accountant';

        // Make some users that will not match since the occupation is null.
        $unmatched = factory('App\User', 10)->states('listed')->create();

        // Make some users that will match.
        $matched = factory('App\User', 10)->states('listed')->create()
        ->map(function ($user) use ($occupation) {
            $user = $user->fresh();
            $user->professionalInformation->occupation = $occupation;
            $user->professionalInformation->save();

            return $user->fresh();
        });

        // Search
        $results = (new Search(compact('occupation')))->execute();

        // Check the results
        $this->assertEquals(
            $matched->pluck('id')->sort()->values(),
            collect($results->items())->pluck('id')->sort()->values()
        );
    }

    /**
    * @test
    */
    public function it_searches_for_users_by_occupation_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $keyword = 'Name';
        $occupation = 'Accountant';

        // Make some users that will not match since the occupation is null.
        $unmatched = factory('App\User', 10)->states('listed')->create(['name' => $keyword]);

        // Make some users that will match.
        $matched = factory('App\User', 10)->states('listed')->create(['name' => $keyword])
        ->map(function ($user) use ($occupation) {
            $user = $user->fresh();
            $user->professionalInformation->occupation = $occupation;
            $user->professionalInformation->save();

            return $user->fresh();
        });

        // Search
        $results = (new Search(compact('occupation')))->execute();

        // Check the results
        $this->assertEquals(
            $matched->pluck('id')->sort()->values(),
            collect($results->items())->pluck('id')->sort()->values()
        );
    }

    /**
    * @test
    */
    public function it_sorts_users_by_last_name_a_to_z()
    {
        $names = collect([
            'Aname',
            'Bname',
            'Cname',
            'Dname',
            // 'ename',
            'Ename',
            'Fname',
            'Gname',
            'Hname',
        ]);

        $names->shuffle()->each(function ($last_name) {
            factory('App\User')->states('listed')->create([
                'last_name' => $last_name,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'title_a_to_z',
        ]))->execute();

        $this->assertEquals(
            $names,
            $results->pluck('last_name')
        );
    }

    /**
    * @test
    */
    public function it_sorts_users_by_last_name_a_to_z_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $keyword = 'First';
        $names = collect([
            'Aname',
            'Bname',
            'Cname',
            'Dname',
            // 'ename',
            'Ename',
            'Fname',
            'Gname',
            'Hname',
        ]);

        $names->shuffle()->each(function ($last_name) use($keyword) {
            factory('App\User')->states('listed')->create([
                'last_name' => $last_name,
                'first_name' => $keyword,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'title_a_to_z',
            'keyword' => $keyword,
        ]))->execute();

        $this->assertEquals(
            $names,
            $results->pluck('last_name')
        );
    }

    /**
    * @test
    */
    public function it_sorts_users_by_last_name_z_to_a()
    {
        $names = collect([
            'Aname',
            'Bname',
            'Cname',
            'Dname',
            'Ename',
            // 'ename',
            'Fname',
            'Gname',
        ]);

        $names->shuffle()->each(function ($last_name) {
            factory('App\User')->states('listed')->create([
                'last_name' => $last_name,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'title_z_to_a',
        ]))->execute();

        $this->assertEquals(
            $names->reverse()->values(),
            $results->pluck('last_name')
        );
    }

    /**
    * @test
    */
    public function it_sorts_users_by_last_name_z_to_a_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $keyword = 'First';
        $names = collect([
            'Aname',
            'Bname',
            'Cname',
            'Dname',
            'Ename',
            // 'ename',
            'Fname',
            'Gname',
        ]);

        $names->shuffle()->each(function ($last_name) use ($keyword) {
            factory('App\User')->states('listed')->create([
                'last_name' => $last_name,
                'first_name' => $keyword,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'title_z_to_a',
            'keyword' => $keyword,
        ]))->execute();

        $this->assertEquals(
            $names->reverse()->values(),
            $results->pluck('last_name')
        );
    }

    /**
     * @test
     */
    public function it_searches_for_professionals_by_keyword()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $occupation = 'Accountant';
        $keyword = 'the search query';
        $matched = collect([]);

        // Make some users that will not match.
        $unmatched = collect(factory('App\User', 10)->states('listed')->create([
            'bio' => 'Not Matching', 'first_name' => 'Not Matching', 'tagline' => 'Not Matching'
        ]));


        // Build up some users with the search value in unique places.
        $matched->push($this->makeListedUser(['first_name' => $keyword], ['occupation' => $occupation]));
        $matched->push(factory('App\User')->states('listed')->create(['tagline' => "{$keyword} and something else"]));
        $matched->push(factory('App\User')->states('listed')->create(['bio' => "something before and {$keyword}"]));


        // Make some more users that will not match.
        $unmatched = collect(factory('App\User', 10)->create([
            'bio' => 'Not Matching', 'first_name' => 'Not Matching', 'tagline' => 'Not Matching'
        ]));

        $results = (new Search(compact('keyword')))->execute();

        $this->assertEquals($matched->pluck('id')->sort()->values(), $results->pluck('id')->sort()->values());
        $this->assertNotEquals($unmatched->pluck('id')->sort()->values(), $results->pluck('id')->sort()->values());
    }

    /**
    * @test
    */
    public function it_only_searches_for_listed_users()
    {
        $listedUser = $this->makeListedUser();
        $unlistedUser = $this->makeUnlistedUser();

        $results = (new Search())->execute();

        $this->assertNotContains($unlistedUser->id, $results->pluck('id'));
        $this->assertContains($listedUser->id, $results->pluck('id'));
    }

    /**
    * @test
    */
    public function it_only_searches_for_listed_users_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $keyword = 'Name';
        $listedUser = $this->makeListedUser(['first_name' => $keyword]);
        $unlistedUser = $this->makeUnlistedUser(['first_name' => $keyword]);
        $results = (new Search([
            'keyword' => $keyword,
        ]))->execute();

        $this->assertNotContains($unlistedUser->id, $results->pluck('id'));
        $this->assertContains($listedUser->id, $results->pluck('id'));
    }

    public function makeListedUser($userParam = [], $infoParam = [], $criteriaParam = [])
    {
        return $this->makeUser(
            factory('App\User')->states('listed')->create($userParam)
        );
    }

    public function makeUnlistedUser($userParam = [], $infoParam = [], $criteriaParam = [])
    {
        return $this->makeUser(
            factory('App\User')->states('unlisted')->create($userParam)
        );
    }

    protected function makeUser($user, $infoParam = [], $criteriaParam = [])
    {
        $user = $user->fresh();

        // Add professional information
        if (!empty($infoParam)) {
            $professionalInfo = $user->professionalInformation;
            $professionalInfo->fill($infoParam);
            $professionalInfo->save();
        }

        // Add desired purchase criteria.
        if (!empty($criteriaParam)) {
            $criteria = $user->desiredPurchaseCriteria;
            $criteria->fill($infoParam);
            $criteria->save();
        }

        $user->fresh()->searchable();

        return $user;
    }
}
