<?php

// phpcs:ignoreFile

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Tag;
use App\Models\Taggable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationControllerIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_only_allows_users_to_be_to_view_their_own_locations_on_index()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_view_locations()
    {
        $this->get(route('locations.index'))
            ->assertRedirect('/login');
    }

    /** @test */
    public function it_sorts_locations_by_name_ascending_by_default()
    {
        $user = $this->loginUser();

        $location3 = Location::factory()->oldRedingsMillBridge()->create([
            // 'created_at' => now()->subDays(3),
            'user_id' => $user->id,
        ]);
        $location1 = Location::factory()->blueSpring()->create([
            // 'created_at' => now()->subDays(1),
            'user_id' => $user->id,
        ]);
        $location2 = Location::factory()->maramecSpringPark()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('locations.index'))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            [
                $location1->id,
                $location2->id,
                $location3->id,
            ],
            collect($locations)->pluck('id')->values()->toArray()
        );
    }

    /** @test */
    public function it_sorts_locations_by_distance_ascending()
    {
        $user = $this->loginUser();

        $location1 = Location::factory()->blueSpring()->create(['user_id' => $user->id]); // ~124 miles
        $location2 = Location::factory()->oldRedingsMillBridge()->create(['user_id' => $user->id]); // ~280 miles
        $location3 = Location::factory()->maramecSpringPark()->create(['user_id' => $user->id]); // ~98 miles

        $response = $this->get(route('locations.index', [
            'sort=distance',
            'address' => '5711 Huntington Valley Ct. Apt. F, St. Louis, MO 63129',
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            [
                $location3->id,
                $location1->id,
                $location2->id,
            ],
            collect($locations)->pluck('id')->values()->toArray()
        );
    }

    /** @test */
    public function it_sorts_locations_by_distance_descending()
    {
        $user = $this->loginUser();

        $location1 = Location::factory()->blueSpring()->create(['user_id' => $user->id]); // ~124 miles
        $location2 = Location::factory()->oldRedingsMillBridge()->create(['user_id' => $user->id]); // ~280 miles
        $location3 = Location::factory()->maramecSpringPark()->create(['user_id' => $user->id]); // ~98 miles

        $response = $this->get(route('locations.index', [
            'sort=-distance',
            'address' => '5711 Huntington Valley Ct. Apt. F, St. Louis, MO 63129',
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            [
                $location2->id,
                $location1->id,
                $location3->id,
            ],
            collect($locations)->pluck('id')->values()->toArray()
        );
    }

    /** @test */
    public function it_filters_locations_within_a_max_distance()
    {
        $user = $this->loginUser();

        $location1 = Location::factory()->blueSpring()->create(['user_id' => $user->id]); // ~124 miles
        $location2 = Location::factory()->oldRedingsMillBridge()->create(['user_id' => $user->id]); // ~280 miles
        $location3 = Location::factory()->maramecSpringPark()->create(['user_id' => $user->id]); // ~98 miles

        $response = $this->get(route('locations.index', [
            'sort=distance',
            'address' => '5711 Huntington Valley Ct. Apt. F, St. Louis, MO 63129',
            'filter' => [
                'max_distance' => 150,
            ],
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            [
                $location3->id,
                $location1->id,
            ],
            collect($locations)->pluck('id')->values()->toArray()
        );
    }

    /** @test */
    public function it_returns_all_locations_if_max_distance_is_empty()
    {
        $user = $this->loginUser();

        $location1 = Location::factory()->blueSpring()->create(['user_id' => $user->id]); // ~124 miles
        $location2 = Location::factory()->oldRedingsMillBridge()->create(['user_id' => $user->id]); // ~280 miles
        $location3 = Location::factory()->maramecSpringPark()->create(['user_id' => $user->id]); // ~98 miles

        $response = $this->get(route('locations.index', [
            'sort=distance',
            'address' => '5711 Huntington Valley Ct. Apt. F, St. Louis, MO 63129',
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            [
                $location3->id,
                $location1->id,
                $location2->id,
            ],
            collect($locations)->pluck('id')->values()->toArray()
        );
    }

    /** @test */
    public function it_allows_configuration_of_pagination_per_page()
    {
        $user = $this->loginUser();

        Location::factory()->count(50)->create(['user_id' => $user->id]);
        $response = $this->get(route('locations.index', [
            'per_page' => 25,
        ]))
            ->assertStatus(200);

        $this->assertCount(25, $response->getOriginalContent()->getData()['locations']->items());
        $this->assertEquals(25, $response->getOriginalContent()->getData()['locations']->perPage());
    }

    /** @test */
    public function it_defaults_pagination_per_page_to_15()
    {
        $user = $this->loginUser();

        Location::factory()->count(50)->create(['user_id' => $user->id]);
        $response = $this->get(route('locations.index'))
            ->assertStatus(200);
        $paginatedLocations = collect($response->getOriginalContent()->getData()['locations']->items());
        $this->assertCount(15, $paginatedLocations);
    }

    /** @test */
    public function it_filter_locations_by_min_rating()
    {
        $user = $this->loginUser();
        $match1 = Location::factory()->create(['rating' => 3, 'user_id' => $user->id]);
        Location::factory()->create(['rating' => null, 'user_id' => $user->id]);
        Location::factory()->create(['rating' => 1, 'user_id' => $user->id]);
        Location::factory()->create(['rating' => 2, 'user_id' => $user->id]);
        $match2 = Location::factory()->create(['rating' => 4, 'user_id' => $user->id]);
        $match3 = Location::factory()->create(['rating' => 5, 'user_id' => $user->id]);

        $response = $this->get(route('locations.index', [
            'filter' => [
                'min_rating' => 3,
            ],
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            collect([
                $match1->id,
                $match2->id,
                $match3->id,
            ])->sort()->values()->toArray(),
            collect($locations)->pluck('id')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_filter_locations_that_are_unrated()
    {
        $user = $this->loginUser();
        $match1 = Location::factory()->create(['rating' => null, 'user_id' => $user->id]);
        Location::factory()->create(['rating' => 3, 'user_id' => $user->id]);
        Location::factory()->create(['rating' => 1, 'user_id' => $user->id]);
        Location::factory()->create(['rating' => 2, 'user_id' => $user->id]);
        $match2 = Location::factory()->create(['rating' => null, 'user_id' => $user->id]);
        $match3 = Location::factory()->create(['rating' => null, 'user_id' => $user->id]);

        $response = $this->get(route('locations.index', [
            'filter' => [
                'min_rating' => -1,
            ],
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            collect([
                $match1->id,
                $match2->id,
                $match3->id,
            ])->sort()->values()->toArray(),
            collect($locations)->pluck('id')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_filters_location_by_tags()
    {
        $user = $this->loginUser();
        Location::factory()->count(2)->create(['user_id' => $user->id]);
        $matches = Location::factory()->count(3)->create(['user_id' => $user->id]);
        Location::factory()->count(2)->create(['user_id' => $user->id]);

        $tags = collect(['Tag 1', 'Tag 2', 'Tag 3'])->map(function ($tag, $key) use ($matches) {
            Taggable::factory()->create([
                'tag_id' => $tag = Tag::factory()->create(['name' => $tag]),
                'taggable_id' => $matches->get($key)->id,
                'taggable_type' => Location::class,
            ]);

            return $tag->id;
        });

        $response = $this->get(route('locations.index', [
            'filter' => [
                'tags' => $tags->toArray(),
            ],
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            collect([
                $matches->get(0)->id,
                $matches->get(1)->id,
                $matches->get(2)->id,
            ])->sort()->values()->first(),
            collect($locations)->pluck('id')->sort()->values()->first()
        );
    }

    /** @test */
    public function it_requires_tags_to_be_an_array()
    {
        $user = $this->loginUser();
        Location::factory()->count(2)->create(['user_id' => $user->id]);

        $this->get(route('locations.index', [
            'filter' => [
                'tags' => 1,
            ],
        ]))
            ->assertSessionHasErrors(['filter.tags']);
    }

    /** @test */
    public function it_filters_only_locations_that_have_been_visited()
    {
        $user = $this->loginUser();
        $location1 = Location::factory()->create(['visited' => true, 'user_id' => $user->id]);
        $location2 = Location::factory()->create(['visited' => false, 'user_id' => $user->id]);
        $location3 = Location::factory()->create(['visited' => true, 'user_id' => $user->id]);

        $response = $this->get(route('locations.index', [
            'filter' => [
                'visited' => true,
            ],
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            collect([
                $location3->id,
                $location1->id,
            ])->sort()->values()->toArray(),
            collect($locations)->pluck('id')->values()->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_filters_only_locations_that_have_not_been_visited()
    {
        $user = $this->loginUser();

        $location1 = Location::factory()->create(['visited' => false, 'user_id' => $user->id]);
        $location2 = Location::factory()->create(['visited' => true, 'user_id' => $user->id]);
        $location3 = Location::factory()->create(['visited' => false, 'user_id' => $user->id]);

        $response = $this->get(route('locations.index', [
            'filter' => [
                'visited' => false,
            ],
        ]))
            ->assertStatus(200);

        $locations = $response->getOriginalContent()->getData()['locations']->items();

        $this->assertEquals(
            collect([
                $location3->id,
                $location1->id,
            ])->sort()->values()->toArray(),
            collect($locations)->pluck('id')->values()->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_requires_visited_to_be_boolean()
    {
        $user = $this->loginUser();
        Location::factory()->count(2)->create(['user_id' => $user->id]);

        $this->get(route('locations.index', [
            'filter' => [
                'visited' => 'true',
            ],
        ]))
            ->assertSessionHasErrors(['filter.visited']);
    }

    /** @test */
    public function it_searches_by_keyword()
    {
        $this->refreshScoutIndex();
        $user = $this->loginUser();

        Location::factory()->create(['user_id' => $user->id]);
        $match = Location::factory()->create(['user_id' => $user->id, 'name' => $search = 'dasfdasfdsfsdafdasdf']);
        Location::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('locations.index', [
            'search' => $search,
        ]))
            ->assertStatus(200);

        $locations = r_collect($response->getOriginalContent()->getData()['locations']->items());

        $this->assertCount(1, $locations);
        $this->assertEquals($match->id, $locations->first()->id);
    }

    /** @test */
    public function it_requires_search_to_be_a_string()
    {
        $user = $this->loginUser();
        Location::factory()->count(2)->create(['user_id' => $user->id]);

        $this->get(route('locations.index', [
            'search' => ['search term'],
        ]))
            ->assertSessionHasErrors(['search']);
    }
}
