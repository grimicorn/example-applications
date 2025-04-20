<?php

namespace Tests\Feature\Application;

use App\User;
use App\Message;
use App\Listing;
use Carbon\Carbon;
use Tests\TestCase;
use App\Conversation;
use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use Illuminate\Support\Facades\Event;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ExchangeSpace\MemberRole;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceTableFilterTest extends TestCase
{
    use RefreshDatabase;
    use HasExchangeSpaceCreators;

    /**
     * @test
     */
    public function it_only_filters_the_current_users_spaces()
    {
        // This sign in a user to view their spaces
        $user = $this->signInWithEvents();

        // Create one space that will not be the users exchange space.
        factory(ExchangeSpace::class)->states('not-inquiry')->create();

        // Create one space that will be the users exchange space.
        $userSpace = factory(ExchangeSpace::class)->states('not-inquiry')->create();

        // Add the user to the space.
        factory('App\ExchangeSpaceMember')->states('approved', 'buyer')
        ->create([
            'user_id' => $user->id,
            'exchange_space_id' => $userSpace->id,
        ]);

        // Retrieve the spaces.
        $response = $this->json('GET', route('exchange-spaces.index'), ['page' => '1']);

        // Check the spaces
        $this->assertCount(1, $response->json()['data']);
    }

    /**
     * @test
     */
    public function it_paginates_spaces()
    {
        // This sign in a user to view their spaces
        $user = $this->signInWithEvents();

        // Create 20 spaces. We will only want to retrieve 10.
        $spaces = factory(ExchangeSpace::class, 20)->states('current_user', 'not-inquiry')->create();

        // Create some exchange space members.
        foreach ($spaces as $space) {
            factory('App\ExchangeSpaceMember')
            ->states('approved', 'buyer')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $space->id,
            ]);
        }

        // Retrieve the spaces.
        $response = $this->json('GET', route('exchange-spaces.index'), ['page' => '1']);

        // Assert we only received 10 spaces.
        $this->assertEquals(10, count(collect($response->json())->get('data')));
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_title_ascending()
    {
        Event::fake();

        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test names to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 names to be safe.
        $titles = [
            'Atest',
            'Btest',
            'Ctest',
            'Dtest',
            'Etest',
            'Ftest',
            'Gtest',
            'Htest',
            'Itest',
        ];
        shuffle($titles);

        // Create some exchange spaces to sort.
        foreach ($titles as $title) {
            $conversation = $this->createSpaceConversation([], $user);
            $conversation->space->title = $title;
            $conversation->space->save();
        }

        // Get the exchange spaces sorted by business name ascending.
        $response = $this->json('POST', route('exchange-spaces.index'), [
            'sortKey' => 'title',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Sort the exchange space names to assert correct order.
        sort($titles);

        // Assert the names where sorted correctly.
        $data = r_collect($response->json()['data']);
        $this->assertEquals(
            collect($titles)->values(),
            $data->map->get('title')
        );
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_title_descending()
    {
        Event::fake();

        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test names to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 names to be safe.
        $titles = [
            'Atest',
            'Btest',
            'Ctest',
            'Dtest',
            'Etest',
            'Ftest',
            'Gtest',
            'Htest',
            'Itest',
        ];
        shuffle($titles);

        // Create some exchange spaces to sort.
        foreach ($titles as $title) {
            $conversation = $this->createSpaceConversation([], $user);
            $conversation->space->title = $title;
            $conversation->space->save();
        }

        // Get the exchange spaces sorted by business name ascending.
        $response = $this->json('POST', route('exchange-spaces.index'), [
            'sortKey' => 'title',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Sort the exchange space names to assert correct order.
        sort($titles);

        // Assert the names where sorted correctly.
        $data = r_collect($response->json()['data']);
        $this->assertEquals(
            collect($titles)->reverse()->values(),
            $data->map->get('title')
        );
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_updated_at_ascending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test updated ats to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 updated ats to be safe.
        $updated_ats = [
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
        ];
        shuffle($updated_ats);

        // Create some exchange spaces to sort.
        foreach ($updated_ats as $updated_at) {
            $space = factory(ExchangeSpace::class)
            ->states('current_user', 'not-inquiry')
            ->create(['updated_at' => $updated_at]);

            factory('App\ExchangeSpaceMember')
            ->states('approved', 'buyer')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $space->id,
            ]);
        }

        // Get the exchange spaces sorted by updated at ascending.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'sortKey' => 'updated_at',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Sort the exchange space updated ats to assert correct order.
        $updated_ats = collect($updated_ats)->map(function ($updated_at) {
            return $updated_at->toDateTimeString();
        })->sort();

        // Assert the updated ats where sorted correctly.
        $this->assertEquals(
            $updated_ats->values()->toArray(),
            collect($response->json()['data'])->pluck('updated_at')->values()->toArray()
        );
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_updated_at_descending()
    {
        Event::fake();

        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test updated ats to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 updated ats to be safe.
        $updated_ats = [
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
        ];
        shuffle($updated_ats);

        // Create some exchange spaces to sort.
        foreach ($updated_ats as $updated_at) {
            $space = factory(ExchangeSpace::class)
            ->states('current_user', 'not-inquiry')
            ->create(['updated_at' => $updated_at]);

            factory('App\ExchangeSpaceMember')
            ->states('approved', 'buyer')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $space->id,
            ]);
        }

        // Get the exchange spaces sorted by updated at ascending.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'sortKey' => 'updated_at',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Sort the exchange space updated ats to assert correct order.
        $updated_ats = collect($updated_ats)->map(function ($updated_at) {
            return $updated_at->toDateTimeString();
        })->sort()->reverse();

        // Assert the updated ats where sorted correctly.
        $this->assertEquals(
            $updated_ats->values()->toArray(),
            collect($response->json()['data'])->pluck('updated_at')->values()->toArray()
        );
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_buyer_name_ascending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test names to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 names to be safe.
        $buyer_names = [
            'Atest',
            'Btest',
            'Ctest',
            'Dtest',
            'Etest',
            'Ftest',
            'Gtest',
            'Htest',
            'Itest',
        ];
        shuffle($buyer_names);

        // Create some exchange spaces and members to sort.
        foreach ($buyer_names as $buyer_name) {
            $exchange_space_id = factory(ExchangeSpace::class)->states('current_user', 'not-inquiry')->create()->id;
            $user_id = factory(User::class)->create(['first_name' => $buyer_name])->id;
            factory(ExchangeSpaceMember::class)->states('buyer', 'approved')->create(compact('exchange_space_id', 'user_id'));
            factory('App\ExchangeSpaceMember')
            ->states('approved')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $exchange_space_id,
            ]);
        }

        // Get the exchange spaces sorted by buyer name ascending.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'sortKey' => 'buyer_name',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Sort the exchange space names to assert correct order.
        sort($buyer_names);

        // Assert the names where sorted correctly.
        $exchange_space_names = collect($response->json()['data'])->map(function ($space) {
            return ExchangeSpace::find($space['id'])->buyerUser()->first_name;
        })->values()->toArray();
        $this->assertEquals(array_values($buyer_names), $exchange_space_names);
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_buyer_name_descending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test names to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 names to be safe.
        $buyer_names = [
            'Atest',
            'Btest',
            'Ctest',
            'Dtest',
            'Etest',
            'Ftest',
            'Gtest',
            'Htest',
            'Itest',
        ];
        shuffle($buyer_names);

        // Create some exchange spaces and members to sort.
        foreach ($buyer_names as $buyer_name) {
            $exchange_space_id = factory(ExchangeSpace::class)->states('current_user', 'not-inquiry')->create()->id;
            $user_id = factory(User::class)->create(['first_name' => $buyer_name])->id;
            factory(ExchangeSpaceMember::class)->states('buyer', 'approved')->create(compact('exchange_space_id', 'user_id'));
            factory('App\ExchangeSpaceMember')
            ->states('approved')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $exchange_space_id,
            ]);
        }

        // Get the exchange spaces sorted by buyer name ascending.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'sortKey' => 'buyer_name',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Sort the exchange space names to assert correct order.
        sort($buyer_names);
        $buyer_names = array_reverse($buyer_names);

        // Assert the names where sorted correctly.
        $exchange_space_names = collect($response->json()['data'])->map(function ($space) {
            return ExchangeSpace::find($space['id'])->buyerUser()->first_name;
        })->values()->toArray();
        $this->assertEquals(array_values($buyer_names), $exchange_space_names);
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_stage_ascending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test stages to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 stages to be safe.
        $stages = [
            ExchangeSpaceDealType::PRE_NDA,
            ExchangeSpaceDealType::SIGNED_NDA,
            ExchangeSpaceDealType::LOI_SIGNED,
            ExchangeSpaceDealType::COMPLETE,
        ];
        shuffle($stages);

        // Create some exchange spaces and members to sort.
        foreach ($stages as $stage) {
            $space = factory(ExchangeSpace::class)->states('current_user', 'not-inquiry')->create(['deal' => $stage]);
            factory('App\ExchangeSpaceMember')
            ->states('approved', 'buyer')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $space->id,
            ]);
        }

        // Get the exchange spaces sorted by buyer stage ascending.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'sortKey' => 'stage',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Make sure the spaces are sorted correctly
        $this->assertEquals(
            collect($stages)->sort()->values(),
            collect($response->json()['data'])->pluck('deal')
        );
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_stage_descending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test stages to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 stages to be safe.
        $stages = [
            ExchangeSpaceDealType::PRE_NDA,
            ExchangeSpaceDealType::SIGNED_NDA,
            ExchangeSpaceDealType::LOI_SIGNED,
            ExchangeSpaceDealType::COMPLETE,
        ];
        shuffle($stages);

        // Create some exchange spaces and members to sort.
        foreach ($stages as $stage) {
            $space = factory(ExchangeSpace::class)->states('current_user', 'not-inquiry')->create(['deal' => $stage]);
            factory('App\ExchangeSpaceMember')
            ->states('approved', 'buyer')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $space->id,
            ]);
        }

        // Get the exchange spaces sorted by buyer stage ascending.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'sortKey' => 'stage',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Make sure the spaces are sorted correctly
        $this->assertEquals(
            collect($stages)->sort()->reverse()->values()->toArray(),
            collect($response->json()['data'])->pluck('deal')->toArray()
        );
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_notifications_count_ascending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some notification counts to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 names to be safe.
        $counts = collect([
            2,
            4,
            6,
        ]);
        $counts->shuffle();

        // Create some exchange spaces to sort.
        foreach ($counts as $count) {
            // Create the conversation/space.
            $space = $this->createSpaceConversation([], $user)->space;

            // Set any current notification as read.
            $space->fresh()->getCurrentUserNotifications()->each(function ($notification) {
                $notification->read = true;
                $notification->save();
            });

            // Add some notifications
            factory('App\ExchangeSpaceNotification', $count)->create([
                'exchange_space_id' => $space->id,
                'user_id' => $user->id,
                'read' => false,
            ]);
        }

        // Get the exchange spaces sorted by business name ascending.
        $response = $this->json('POST', route('exchange-spaces.index'), [
            'sortKey' => 'notifications',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Assert the counts where sorted correctly.
        $this->assertEquals(
            $counts->sort()->values(),
            collect($response->json()['data'])->pluck('notification_count')
        );
    }

    /**
     * @test
     */
    public function it_orders_spaces_by_notifications_count_descending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some notification counts to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 names to be safe.
        $counts = collect([
            3,
            7,
            9,
        ]);
        $counts->shuffle();

        // Create some exchange spaces to sort.
        foreach ($counts as $count) {
            // Create the conversation/space.
            $space = $this->createSpaceConversation([], $user)->space;

            // Set any current notification as read.
            $space->fresh()->getCurrentUserNotifications()->each(function ($notification) {
                $notification->read = true;
                $notification->save();
            });

            // Add some notifications
            factory('App\ExchangeSpaceNotification', $count)->create([
                'exchange_space_id' => $space->id,
                'user_id' => $user->id,
                'read' => false,
            ]);
        }

        // Get the exchange spaces sorted by business name ascending.
        $response = $this->json('POST', route('exchange-spaces.index'), [
            'sortKey' => 'notifications',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Assert the counts where sorted correctly.
        $this->assertEquals(
            $counts->sort()->reverse()->values(),
            collect($response->json()['data'])->pluck('notification_count')
        );
    }


    /**
     * @test
     */
    public function it_searches_for_spaces()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        $search = 'The Search';
        $exchange_space_ids = [];

        // Create an exchange some exchange spaces with matching details.
        // Buyer name (Exchange Space->Members->User->first_name)
        $first_name_exchange_space_id = factory(ExchangeSpace::class)->states('current_user', 'not-inquiry')->create()->id;
        factory(ExchangeSpaceMember::class)->states('buyer', 'approved')->create([
            'user_id' => factory(User::class)->create(['first_name' => "{$search}andsomemore"])->id,
            'exchange_space_id' => $first_name_exchange_space_id,
        ]);
        $exchange_space_ids[] = $first_name_exchange_space_id;

        // Buyer name (Exchange Space->Members->User->last_name)
        $last_name_exchange_space_id = factory(ExchangeSpace::class)->states('current_user', 'not-inquiry')->create()->id;
        factory(ExchangeSpaceMember::class)->states('buyer', 'approved')->create([
            'user_id' => factory(User::class)->create(['last_name' => "{$search}andsomemore"])->id,
            'exchange_space_id' => $last_name_exchange_space_id,
        ]);
        $exchange_space_ids[] = $last_name_exchange_space_id;

        // Exchange Space Title (Exchange Space->title)
        $exchange_space_ids[] = factory(ExchangeSpace::class)->states('current_user', 'not-inquiry')->create([
            'title' => "{$search} something after",
        ])->id;

        // Create some random exchange spaces for the current user.
        $non_matching_ids = factory(ExchangeSpace::class, 20)->states('current_user', 'not-inquiry')->create()->pluck('id')->toArray();

        // Add the user to the exchange spaces.
        foreach (array_merge($non_matching_ids, $exchange_space_ids) as $id) {
            factory('App\ExchangeSpaceMember')
            ->states('approved', 'buyer')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $id,
            ]);
        }

        // Get the exchange spaces search results.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'sortOrder' => 'desc',
            'search' => $search,
            'page' => '1',
        ]);

        // Standardize both the results and added ids.
        $exchange_space_ids = collect($exchange_space_ids)->sort()->values()->toArray();
        $found_exchange_space_ids = collect($response->json()['data'])->pluck('id')->sort()->values()->toArray();

        $this->assertEquals($exchange_space_ids, $found_exchange_space_ids);
    }

    /**
     * @test
     */
    public function it_searches_for_spaces_by_deal_status()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        $statuses = ExchangeSpaceStatusType::getValues();
        $status = $this->faker->randomElement($statuses);
        $search = ExchangeSpaceStatusType::getLabel($status);
        $exchange_space_ids = [];

        // Business Name (Exchange Space->Listing->title)
        $exchange_space_ids[] = factory(ExchangeSpace::class)
                                ->states('current_user', 'not-inquiry')
                                ->create([
                                    'status' => $status,
                                ])->id;

        // Create some random exchange spaces for the current user.
        $non_matching_ids = factory(ExchangeSpace::class, 20)
        ->states('current_user', 'not-inquiry')->create([
            'status' => $this->faker->randomElement(array_diff($statuses, [$status])),
        ])->pluck('id')->toArray();

        // Add the user to the exchange spaces.
        foreach (array_merge($non_matching_ids, $exchange_space_ids) as $id) {
            factory('App\ExchangeSpaceMember')
            ->states('approved', 'buyer')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $id,
            ]);
        }

        // Get the exchange spaces search results.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'search' => $search,
        ]);

        // Standardize both the results and added ids.
        $exchange_space_ids = collect($exchange_space_ids)->sort()->values()->toArray();
        $found_exchange_space_ids = collect($response->json()['data'])->pluck('id')->sort()->values()->toArray();

        $this->assertEquals($exchange_space_ids, $found_exchange_space_ids);
    }

    /**
    * @test
    */
    public function it_filters_exchange_space_by_listing_id()
    {
        $user = $this->signInWithEvents();

        // Create some exchange spaces that would not match
        $nonmatched = factory('App\ExchangeSpace', 10)
        ->states('current_user', 'not-inquiry')
        ->create();

        // Create a listing to check.
        $listing = factory('App\Listing')->create();

        // Create some exchange spaces for the listing.
        $spaces = factory('App\ExchangeSpace', 5)
        ->states('current_user', 'not-inquiry')
        ->create(['listing_id' => $listing->id]);

        // Add the user to the exchange spaces.
        foreach ($spaces->concat($nonmatched) as $space) {
            factory('App\ExchangeSpaceMember')
            ->states('approved', 'buyer')
            ->create([
                'user_id' => $user->id,
                'exchange_space_id' => $space->id,
            ]);
        }

        // Get the exchange spaces search results.
        $response = $this->json('GET', route('exchange-spaces.index'), [
            'listing_id' => $listing->id,
        ]);

        // Assert the correct listings where retrieved.
        // We do not care about sort order just that only the listings
        $this->assertEquals(
            $spaces->map(function ($space) {
                return intval($space->listing->id);
            })->sort(),
            collect($response->json()['data'])->map(function ($space) {
                return intval($space['listing']['id']);
            })->sort()
        );
    }

    protected function getListingsFromResponse($response)
    {
        return collect(collect($response->json()['data'])->map(function ($space) {
            return $space['listing'];
        }));
    }
}
