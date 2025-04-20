<?php

namespace Tests\Feature\Application;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

// @codingStandardsIgnoreFile
class UserTableFilterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_paginates_users()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Create 20 users. We will only want to retrieve 10.
        $users = factory(User::class, 20)->create();

        // Retrieve the users.
        $response = $this->json('GET', route('admin.user-table'), ['page' => '1']);

        // Assert we only received 10 users.
        $this->assertEquals(10, count(collect($response->json())->get('data')));
    }

    /**
     * @test
     */
    public function it_sorts_users_by_email_address_ascending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Set some test emails to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 emails to be safe with the admin.
        $emails = [
            'atest@example.com',
            'btest@example.com',
            'ctest@example.com',
            'dtest@example.com',
            'etest@example.com',
            'ftest@example.com',
            'gtest@example.com',
            'htest@example.com',
            'itest@example.com',
        ];
        shuffle($emails);

        // Create some users to sort.
        foreach ($emails as $email) {
            factory(User::class)->create(['email' => $email]);
        }

        // Get the users sorted by email address ascending.
        $response = $this->json('GET', route('admin.user-table'), [
            'sortKey' => 'email_address',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Add the admin email and sort them for testing
        $emails[] = $developer->email;
        sort($emails);

        // Assert the emails where sorted correctly.
        $user_emails = collect(collect($response->json())->get('data'))->pluck('email')->toArray();
        $this->assertEquals($emails, $user_emails);
    }

    /**
     * @test
     */
    public function it_sorts_users_by_email_address_descending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Set some test emails to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 emails to be safe with the admin.
        $emails = [
            'atest@example.com',
            'btest@example.com',
            'ctest@example.com',
            'dtest@example.com',
            'etest@example.com',
            'ftest@example.com',
            'gtest@example.com',
            'htest@example.com',
            'itest@example.com',
        ];
        shuffle($emails);

        // Create some users to sort.
        foreach ($emails as $email) {
            factory(User::class)->create(['email' => $email]);
        }

        // Get the users sorted by email address ascending.
        $response = $this->json('GET', route('admin.user-table'), [
            'sortKey' => 'email_address',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Add the admin email and sort them for testing
        $emails[] = $developer->email;
        sort($emails);
        $emails = array_reverse($emails);

        // Assert the emails where sorted correctly.
        $user_emails = collect(collect($response->json())->get('data'))->pluck('email')->toArray();
        $this->assertEquals($emails, $user_emails);
    }

    /**
     * @test
     */
    public function it_sorts_users_by_roles_selected_ascending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents(factory(User::class)->create(['primary_roles' => ['Advisor']]));

        // Set some test roles to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 roles to be safe with the admin.
        $roles = [
            'Advisor',
            'Broker',
            'Buyer',
            'Seller',
        ];
        shuffle($roles);

        // Create some users to sort.
        foreach ($roles as $role) {
            factory(User::class)->create(['primary_roles' => [$role]]);
        }

        // Get the users sorted by roles ascending.
        $response = $this->json('GET', route('admin.user-table'), [
            'sortKey' => 'roles_selected',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Add the admin role and sort them for testing
        $roles[] = implode(',', $developer->primary_roles);
        sort($roles);

        // Assert the roles where sorted correctly.
        $user_roles = collect(collect($response->json())->get('data'))->pluck('primary_roles')->flatten()->toArray();
        $this->assertEquals($roles, $user_roles);
    }

    /**
     * @test
     */
    public function it_sorts_users_by_roles_selected_descending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents(factory(User::class)->create(['primary_roles' => ['Advisor']]));

        // Set some test roles to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 roles to be safe with the admin.
        $roles = [
            'Advisor',
            'Broker',
            'Buyer',
            'Seller',
        ];
        shuffle($roles);

        // Create some users to sort.
        foreach ($roles as $role) {
            factory(User::class)->create(['primary_roles' => [$role]]);
        }

        // Get the users sorted by roles descending.
        $response = $this->json('GET', route('admin.user-table'), [
            'sortKey' => 'roles_selected',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Add the admin role and sort them for testing
        $roles[] = implode(',', $developer->primary_roles);
        sort($roles);
        $roles = array_reverse($roles);

        // Assert the roles where sorted correctly.
        $user_roles = collect(collect($response->json())->get('data'))->pluck('primary_roles')->flatten()->toArray();
        $this->assertEquals($roles, $user_roles);
    }

    /**
     * @test
     */
    public function it_sorts_users_by_last_name_ascending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Set some test names to sort and shuffle them so it is not just a coincidence that the order is correct.
        // Only the first name matters since it is the letter in the cell.
        // The pagination limit is set to 10 so there are 10 names to be safe with the admin.
        $last_names = [
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
        shuffle($last_names);

        // Create some users to sort.
        foreach ($last_names as $last_name) {
            factory(User::class)->create(['last_name' => $last_name]);
        }

        // Get the users sorted by name address ascending.
        $response = $this->json('GET', route('admin.user-table'), [
            'sortKey' => 'name',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Add the admin name and sort them for testing
        $last_names[] = $developer->last_name;
        sort($last_names);

        // Assert the names where sorted correctly.
        $user_names = collect(collect($response->json())->get('data'))->pluck('last_name')->toArray();
        $this->assertEquals(array_values($last_names), array_values($user_names));
    }

    /**
     * @test
     */
    public function it_sorts_users_by_last_name_descending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Set some test names to sort and shuffle them so it is not just a coincidence that the order is correct.
        // Only the first name matters since it is the letter in the cell.
        // The pagination limit is set to 10 so there are 10 names to be safe with the admin.
        $last_names = [
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
        shuffle($last_names);

        // Create some users to sort.
        foreach ($last_names as $last_name) {
            factory(User::class)->create(['last_name' => $last_name]);
        }

        // Get the users sorted by name address descending.
        $response = $this->json('GET', route('admin.user-table'), [
            'sortKey' => 'name',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Add the admin name and sort them for testing
        $last_names[] = $developer->last_name;
        sort($last_names);
        $last_names = array_reverse($last_names);

        // Assert the names where sorted correctly.
        $user_names = collect(collect($response->json())->get('data'))->pluck('last_name')->toArray();
        $this->assertEquals(array_values($last_names), array_values($user_names));
    }

    /**
     * @test
     */
    public function it_sorts_users_by_listing_count_ascending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Set some test listing counts to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 9 so there are 9 listing counts to be safe with the admin.
        $listing_counts = range(1, 9);
        shuffle($listing_counts);

        foreach ($listing_counts as $listing_count) {
            factory('App\Listing', $listing_count)->create([
                'user_id' => factory(User::class)->create()->id,
            ]);
        }

        // Get the users sorted by name address ascending.
        $response = $this->json('GET', route('admin.user-table'), [
            'sortKey' => 'active_listings',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Add a zero for the developer account
        $listing_counts[] = 0;

        // Sort the listing counts
        sort($listing_counts);

        // Assert the listings where sorted correctly.
        $sorted_counts = collect(collect($response->json())->get('data'))->pluck('active_listings_count')->toArray();
        $this->assertEquals(array_values($listing_counts), array_values($sorted_counts));
    }

    /**
     * @test
     */
    public function it_sorts_users_by_listing_count_descending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Set some test listing counts to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 9 so there are 9 listing counts to be safe with the admin.
        $listing_counts = range(1, 9);
        shuffle($listing_counts);

        foreach ($listing_counts as $listing_count) {
            factory('App\Listing', $listing_count)->create([
                'user_id' => factory(User::class)->create()->id,
            ]);
        }

        // Get the users sorted by name address ascending.
        $response = $this->json('GET', route('admin.user-table'), [
            'sortKey' => 'active_listings',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Add a zero for the developer account
        $listing_counts[] = 0;

        // Sort the listing counts
        sort($listing_counts);
        $listing_counts = arraY_reverse($listing_counts);

        // Assert the listings where sorted correctly.
        $sorted_counts = collect(collect($response->json())->get('data'))->pluck('active_listings_count')->toArray();
        $this->assertEquals(array_values($listing_counts), array_values($sorted_counts));
    }

    /**
     * @test
     */
    public function it_searches_users()
    {
        // Setup
        $search_query = 'thisshouldbefoundwhensearching';
        $expected_user_ids = [];

        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Create users with names column that could match.
        $expected_user_ids[] = factory(User::class)->create(['first_name' => $search_query])->id;
        $expected_user_ids[] = factory(User::class)->create(['last_name' => $search_query])->id;
        $expected_user_ids[] = factory(User::class)->create(['email' => "{$search_query}@example.com"])->id;
        $expected_user_ids[] = factory(User::class)->create(['primary_roles' => $search_query])->id;

        // Create some more random users that will not match.
        factory(User::class)->create();

        // Get the users that match the search query.
        $response = $this->json('GET', route('admin.user-table'), [
            'search' => $search_query,
        ]);

        // Assert the the correct users where found. We do not care about the order.
        $user_ids = collect(collect($response->json())->get('data'))->pluck('id')->toArray();
        sort($user_ids);
        sort($expected_user_ids);
        $this->assertEquals($expected_user_ids, $user_ids);
    }

    /**
     * @test
     */
    public function it_searches_for_users_full_name()
    {
        // Setup
        $search_query_half_1 = 'thisshouldbefound';
        $search_query_half_2 = 'whensearching';
        $search_query = implode(' ', [
            $search_query_half_1,
            $search_query_half_2,
        ]);
        $expected_user_ids = [];

        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Create users with names column that could match.
        $expected_user_ids[] = factory(User::class)->create([
            'first_name' => $search_query_half_1,
            'last_name' => $search_query_half_2,
        ])->id;

        // Create some more random users that will not match.
        factory(User::class)->create();

        // Get the users that match the search query.
        $response = $this->json('GET', route('admin.user-table'), [
            'search' => $search_query,
        ]);

        // Assert the the correct users where found. We do not care about the order.
        $user_ids = collect(collect($response->json())->get('data'))->pluck('id')->toArray();
        sort($user_ids);
        sort($expected_user_ids);
        $this->assertEquals($expected_user_ids, $user_ids);
    }

    /**
    * @test
    */
    public function it_sorts_users_by_last_login_ascending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Add the developer to users.
        $users = collect([
            $developer,
        ]);

        // Set some test last logins to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 9 last logins to be safe with the admin.
        for ($i = 0; $i < 9; $i++) {
            $users->push(factory(User::class)->create([
                'last_login' => $this->faker->dateTimeBetween('-1 Year'),
            ]));
        }
        $users = $users->shuffle();

        // Get the users sorted by last login ascending.
        $response = $this->json('GET', route('admin.user-table'), [
            'search' => '',
            'sortKey' => 'last_login',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Sort the last logins
        $users = $users->sortBy('last_login')->pluck('id')->values()->toArray();

        // Get the sorted logins
        $sorted_logins = collect(collect($response->json())->get('data'))->pluck('id')->values()->toArray();

        // Get the sorted logins
        $this->assertEquals($users, $sorted_logins);
    }

    /**
    * @test
    */
    public function it_sorts_users_by_last_login_descending()
    {
        // Sign in a "developer".
        $developer = $this->signInDeveloperWithEvents();

        // Add the developer to users.
        $users = collect([
            $developer,
        ]);

        // Set some test last logins to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 9 last logins to be safe with the admin.
        for ($i = 0; $i < 9; $i++) {
            $users->push(factory(User::class)->create([
                'last_login' => $this->faker->dateTimeBetween('-1 Year'),
            ]));
        }
        $users = $users->shuffle();

        // Get the users sorted by last login ascending.
        $response = $this->json('GET', route('admin.user-table'), [
            'search' => '',
            'sortKey' => 'last_login',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Sort the last logins
        $users = $users->sortByDesc('last_login')->pluck('id')->values()->toArray();

        // Get the sorted logins
        $sorted_logins = collect(collect($response->json())->get('data'))->pluck('id')->values()->toArray();

        // Get the sorted logins
        $this->assertEquals($users, $sorted_logins);
    }
}
