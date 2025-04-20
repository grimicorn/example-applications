<?php

// phpcs:ignoreFile

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\Link;
use App\Models\Location;
use App\Enums\LocationAccessDifficultyEnum;
use App\Enums\LocationTrafficLevelEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_update_a_location()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function users_can_only_update_their_own_locations()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_view_the_location_edit_page()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_only_allows_users_to_be_to_view_the_location_edit_page_for_their_locations()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_updates_a_location()
    {
        $user = $this->loginUser();
        $oldLocation = Location::factory()->oldRedingsMillBridge()->create([
            'user_id' => $user->id,
        ]);
        $oldLocation->tags()->saveMany($originalTags = Tag::factory()->count(3)->create());
        $oldLocation->links()->saveMany($originalLinks = Link::factory()->count(3)->create());

        $this->assertCount(1, Location::all());

        $tags = Tag::factory()->count(3)->create()->pluck('id')->prepend($originalTags->first()->id);
        $links = Link::factory()->count(3)->make()->prepend($originalLinks->first());

        $this->from(route('locations.edit', ['location' => $oldLocation]))
            ->patch(route('locations.update', ['location' => $oldLocation]), [
                'name' => $name = 'Blue Spring',
                'address' => $address = 'County Road 535, Ellington, Missouri 63638, US',
                'best_time_of_day_to_visit' => $bestTimeOfDayToVisit = 'Morning',
                'best_time_of_year_to_visit' => $bestTimeOfYearToVisit = 'Summer',
                'rating' => $rating = 3,
                'visited' => 1,
                'tags' => $tags->toArray(),
                'links' => $links->map->only(['id', 'name', 'url'])->toArray(),
                'access_difficulty' => $accessDifficulty = $this->faker->randomEnum(LocationAccessDifficultyEnum::class)->value,
                'traffic_level' => $trafficLevel = $this->faker->randomEnum(LocationTrafficLevelEnum::class)->value,
                'walk_distance' => $walkDistance = 4.25,
            ])
            ->assertRedirect(route('locations.edit', ['location' => $oldLocation]))
            ->assertSessionHas('success_message');

        $location = $oldLocation->fresh();

        // New Location Attributes
        $this->assertNotNull($location);
        $this->assertEquals($name, $location->name);
        $this->assertEquals($address, $location->address);
        $this->assertEquals($bestTimeOfDayToVisit, $location->best_time_of_day_to_visit);
        $this->assertEquals($bestTimeOfYearToVisit, $location->best_time_of_year_to_visit);
        $this->assertEquals($rating, $location->rating);
        $this->assertTrue($location->visited);
        $this->assertEquals(37.1824236, $location->latitude);
        $this->assertEquals(-91.161104, $location->longitude);
        $this->assertEquals(
            'https://www.google.com/maps/dir//County+Road+535%2C+Ellington%2C+Missouri+63638%2C+US/@37.1824236,-91.1611036,13z/',
            $location->google_maps_link
        );
        $this->assertEquals($user->id, $location->user_id);
        $this->assertEquals('County Road 535', $location->route);
        $this->assertEquals('Ellington', $location->locality);
        $this->assertEquals('MO', $location->administrative_area_level_1_abbreviation);
        $this->assertEquals('Missouri', $location->administrative_area_level_1);
        $this->assertEquals('US', $location->country);
        $this->assertEquals('63638', $location->postal_code);
        $this->assertEquals('America/Chicago', $location->timezone);
        $this->assertEquals($accessDifficulty, $location->access_difficulty);
        $this->assertEquals($trafficLevel, $location->traffic_level);
        $this->assertEquals($walkDistance, $location->walk_distance);

        // New Location Tags
        $this->assertEquals(
            $tags->sort()->values()->toArray(),
            $location->tags->pluck('id')->sort()->values()->toArray()
        );

        // New Location Links
        $this->assertEquals(
            $links->pluck('url')->sort()->values()->toArray(),
            $location->links->pluck('url')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_does_not_require_name_to_update_a_location()
    {
        $oldLocation = Location::factory()->oldRedingsMillBridge()->create([
            'user_id' => $this->loginUser()->id,
        ]);

        $this->assertCount(1, Location::all());

        $this->from(route('locations.update', ['location' => $oldLocation]))
            ->patch(route('locations.update', ['location' => $oldLocation]), [
                // 'name' => $name = 'Blue Spring',
                'address' => $address = 'County Road 535, Ellington, Missouri 63638, US',
                'best_time_of_day_to_visit' => $bestTimeOfDayToVisit = 'Morning',
                'best_time_of_year_to_visit' => $bestTimeOfYearToVisit = 'Summer',
                'rating' => $rating = 3,
                'visited' => 1,
            ])
            ->assertSessionDoesntHaveErrors('name')
            ->assertRedirect(route('locations.update', ['location' => $oldLocation]))
            ->assertSessionHas('success_message');
    }

    /** @test */
    public function it_does_not_require_address_to_update_a_location()
    {
        $oldLocation = Location::factory()->oldRedingsMillBridge()->create([
            'user_id' => $this->loginUser()->id,
        ]);

        $this->assertCount(1, Location::all());

        $this->from(route('locations.update', ['location' => $oldLocation]))
            ->patch(route('locations.update', ['location' => $oldLocation]), [
                'name' => $name = 'Blue Spring',
                // 'address' => $address = 'County Road 535, Ellington, Missouri 63638, US',
                'best_time_of_day_to_visit' => $bestTimeOfDayToVisit = 'Morning',
                'best_time_of_year_to_visit' => $bestTimeOfYearToVisit = 'Summer',
                'rating' => $rating = 3,
                'visited' => 1,
            ])
            ->assertSessionDoesntHaveErrors('address')
            ->assertRedirect(route('locations.update', ['location' => $oldLocation]))
            ->assertSessionHas('success_message');
    }

    /** @test */
    public function it_requires_name_to_be_a_string_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => ["This shouldn't work..."],
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('name');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_address_to_be_a_string_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => ["This shouldn't work..."],
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('address');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_best_time_of_day_to_visit_to_be_valid_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Not a Valid Time of Day',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('best_time_of_day_to_visit');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_best_time_of_year_to_visit_to_be_valid_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Not a Valid Time of Year',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('best_time_of_year_to_visit');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_rating_to_be_a_number_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 'three',
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('rating');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_rating_to_be_greater_than_or_equal_to_1_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 0,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('rating');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_rating_to_be_less_than_or_equal_to_5_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 6,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('rating');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_visited_to_be_a_boolean_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 'yes',
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('visited');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_tags_to_be_an_array_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => 'Tag 1,Tag 2,Tag 3',
        ])
            ->assertSessionHasErrors('tags');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_tags_to_be_an_number_or_string_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => [['Tag 3']],
        ])
            ->assertSessionHasErrors('tags.*');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_links_to_be_an_array_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'links' => 'Link 1,Link 2,Link 3',
        ])
            ->assertSessionHasErrors('links');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_link_urls_to_be_valid_urls_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'links' => [
                [
                    'name' => 'Name',
                    'url' => 'notaurl',
                ]
            ],
        ])
            ->assertSessionHasErrors('links.*.url');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_link_urls_if_id_is_not_present_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $sharedAttributes = [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
        ];

        // Verify there is an error without link and url.
        $this->patch(route('locations.update', ['location' => $oldLocation]), array_merge($sharedAttributes, [
            'links' => [
                ['name' => 'Link 1'],
                ['name' => 'Link 2'],
                ['name' => 'Link 3'],
            ],
        ]))
            ->assertSessionHasErrors('links.*.url');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);

        // Verify it works with url.
        $links = Link::factory(3)->create();
        $this->patch(route('locations.update', ['location' => $oldLocation]), array_merge($sharedAttributes, [
            'links' => $links->map->only(['url'])->toArray(),
        ]))
            ->assertSessionDoesntHaveErrors();

        $this->assertEquals('New Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_link_ids_if_url_is_not_present_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $sharedAttributes = [
            'name' => 'New Name',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
        ];

        // Verify there is an error without link and id.
        $this->patch(route('locations.update', ['location' => $oldLocation]), array_merge($sharedAttributes, [
            'links' => [
                ['name' => 'Link 1'],
                ['name' => 'Link 2'],
                ['name' => 'Link 3'],
            ],
        ]))
            ->assertSessionHasErrors('links.*.id');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);

        // Verify it works with id.
        $links = Link::factory(3)->create();
        $this->patch(route('locations.update', ['location' => $oldLocation]), array_merge($sharedAttributes, [
            'links' => $links->map->only(['id'])->toArray(),
        ]))
            ->assertSessionDoesntHaveErrors();

        $this->assertEquals('New Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_notes_to_be_a_string_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
            'notes' => ["This shouldn't work..."],
        ])
            ->assertSessionHasErrors('notes');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_icon_to_be_a_number_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
            'icon_id' => 'one',
        ])
            ->assertSessionHasErrors('icon_id');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_icon_to_be_a_valid_icon_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'name' => 'Old Name',
            'user_id' => $this->loginUser()->id,
        ]);

        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
            'icon_id' => 2000, // This will probably never be a valid icon_id but we can raise it if needed...
        ])
            ->assertSessionHasErrors('icon_id');

        $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_does_not_delete_tags_when_updating_a_locations()
    {
        $location = Location::factory()->oldRedingsMillBridge()->create([
            'user_id' => $this->loginUser()->id,
        ]);

        $location->tags()->saveMany($originalTags = Tag::factory()->count(3)->create());

        $this->assertCount(1, Location::all());

        $tags = Tag::factory()->count(3)->create()->pluck('id')->prepend($originalTags->first()->id);
        $allTagIds = $tags->merge($originalTags->pluck('id'))->unique();

        $this->patch(route('locations.update', ['location' => $location]), [
            'tags' => $tags->toArray(),
        ]);

        $location = $location->fresh();

        // New Location Tags
        $this->assertEquals(
            $tags->sort()->values()->toArray(),
            $location->tags->pluck('id')->sort()->values()->toArray()
        );

        // Make sure we have the same tags
        $allTags = Tag::all();
        $this->assertEquals(
            $allTagIds->sort()->values(),
            $allTags->pluck('id')->sort()->values()
        );
    }


    /** @test */
    public function it_does_not_delete_links_when_updating_a_locations()
    {
        $location = Location::factory()->oldRedingsMillBridge()->create([
            'user_id' => $this->loginUser()->id,
        ]);
        $location->links()->saveMany($originalLinks = Link::factory()->count(3)->create());

        $newLinks = Link::factory()->count(3)->create();
        $links = $newLinks->prepend($originalLinks->first());
        $allLinkIds = $newLinks->pluck('id')->merge($originalLinks->pluck('id'))->unique();

        $this->patch(route('locations.update', ['location' => $location]), [
            'links' => $links->map->only(['id', 'name', 'url'])->toArray(),
        ]);

        $location = $location->fresh();

        // New Location Links
        $this->assertEquals(
            $links->pluck('url')->sort()->values()->toArray(),
            $location->links->pluck('url')->sort()->values()->toArray()
        );

        // Make sure we have the same links
        $allLinks = Link::all();
        $this->assertEquals(
            $allLinkIds->sort()->values(),
            $allLinks->pluck('id')->sort()->values()
        );
    }

    /** @test */
    public function it_creates_links_if_they_do_not_exist_when_updating_a_location()
    {
        $this->withoutExceptionHandling();
        $location = Location::factory()->oldRedingsMillBridge()->create([
            'user_id' => $this->loginUser()->id,
        ]);

        $location->links()->saveMany($originalLinks = Link::factory()->count(3)->create());

        $this->assertCount(1, Location::all());

        // Tests updating of the link name/url
        $originalLink = $originalLinks->first();
        $originalLink->name = $originalName = 'A New Name';
        $originalLink->url = $originalUrl = 'https://test.com/a-new-url';

        $links = Link::factory()->count(3)->create()->prepend($originalLink);

        $this->patch(route('locations.update', ['location' => $location]), [
            'links' => array_merge(
                $links->map->only(['id', 'name', 'url'])->toArray(),
                [$expected = Link::factory()->make()->only(['url', 'name'])]
            ),
        ]);

        $location = $location->fresh();

        $expected = Link::where('url', $expected['url'])->first();
        $this->assertNotNull($expected);

        $originalLink = $originalLink->fresh();
        $this->assertEquals($originalUrl, $originalLink->url);
        $this->assertEquals($originalName, $originalLink->name);

        // New Location Links
        $this->assertEquals(
            $links->pluck('id')->prepend($expected['id'])->sort()->values()->toArray(),
            $location->links->pluck('id')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_creates_tags_if_they_do_not_exist_when_creating_a_location()
    {
        $location = Location::factory()->oldRedingsMillBridge()->create([
            'user_id' => $this->loginUser()->id,
        ]);

        $location->tags()->saveMany($originalTags = Tag::factory()->count(3)->create());

        $this->assertCount(1, Location::all());

        $tags = Tag::factory()->count(3)->create()->pluck('id')->prepend($originalTags->first()->id);

        $this->patch(route('locations.update', ['location' => $location]), [
            'tags' => array_merge($tags->toArray(), [$newTagName = 'New Tag']),
        ]);

        $location = $location->fresh();

        $newTag = Tag::where('name', $newTagName)->first();
        $this->assertNotNull($newTag);

        // New Location Tags
        $this->assertEquals(
            $tags->prepend($newTag->id)->sort()->values()->toArray(),
            $location->tags->pluck('id')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_requires_access_difficulty_to_be_valid_enum_value_if_supplied_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'user_id' => $this->loginUser()->id,
            'access_difficulty' => null,
        ]);

        // Check an invalid value
        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'access_difficulty' => 'InvalidEnumValue',
        ])
            ->assertSessionHasErrors('access_difficulty');

        $this->assertNull($oldLocation->fresh()->access_difficulty);

        // Check empty value.
        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'access_difficulty' => null,
        ])
            ->assertSessionDoesntHaveErrors('access_difficulty')
            ->assertSessionHas('success_message');

        $this->assertNull($oldLocation->fresh()->access_difficulty);
    }

    /** @test */
    public function it_requires_traffic_level_to_be_valid_enum_value_if_supplied_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'user_id' => $this->loginUser()->id,
            'traffic_level' => null,
        ]);

        // Check an invalid value
        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'traffic_level' => 'InvalidEnumValue',
        ])
            ->assertSessionHasErrors('traffic_level');

        $this->assertNull($oldLocation->fresh()->traffic_level);

        // Check empty value.
        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'traffic_level' => null,
        ])
            ->assertSessionDoesntHaveErrors('traffic_level')
            ->assertSessionHas('success_message');

        $this->assertNull($oldLocation->fresh()->traffic_level);
    }

    /** @test */
    public function it_requires_walk_distance_to_be_numeric_if_supplied_to_update_a_location()
    {
        $oldLocation = Location::factory()->create([
            'user_id' => $this->loginUser()->id,
            'walk_distance' => null,
        ]);

        // Check an invalid value
        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'walk_distance' => '4.25 miles',
        ])
            ->assertSessionHasErrors('walk_distance');

        $this->assertNull($oldLocation->fresh()->walk_distance);

        // Check empty value.
        $this->patch(route('locations.update', ['location' => $oldLocation]), [
            'walk_distance' => null,
        ])
            ->assertSessionDoesntHaveErrors('walk_distance')
            ->assertSessionHas('success_message');

        $this->assertNull($oldLocation->fresh()->walk_distance);
    }
}
