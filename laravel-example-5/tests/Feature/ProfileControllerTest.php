<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_updates_a_user()
    {
        $user = $oldUser = create(User::class, [
            'password' => 'oldpassword',
        ]);
        $newUser = make(User::class);

        $this->signIn($user);

        Storage::fake('test');

        $this->patch(route('profile.update', ['user' => $user]), [
            'first_name' => $newUser->first_name,
            'last_name' => $newUser->last_name,
            'email' => $newUser->email,
            'password' => $password = 'myn3wp@$$w0rd',
            'password_confirmation' => $password,
            'avatar' => UploadedFile::fake()->image($file_name = 'avatar.jpg'),
        ]);

        $user = $user->fresh();
        $this->assertEquals($user->first_name, $newUser->first_name);
        $this->assertEquals($user->last_name, $newUser->last_name);
        $this->assertEquals($user->email, $newUser->email);
        $this->assertNotEquals($oldUser->password, $user->password);
        $this->assertNotNull($user->getFirstMedia('avatar'));
        $this->assertEquals(
            $file_name,
            pathinfo($user->avatar, PATHINFO_BASENAME)
        );
    }

    /**
    * @test
    */
    public function it_only_updates_a_users_password_if_supplied()
    {
        $user = $oldUser = create(User::class, [
            'password' => 'oldpassword',
        ]);
        $newUser = make(User::class);

        $this->signIn($user);

        $this->patch(route('profile.update', ['user' => $user]), [
            'first_name' => $newUser->first_name,
            'last_name' => $newUser->last_name,
            'email' => $newUser->email,
            'password' => $password = '',
            'password_confirmation' => $password,
        ]);

        $user = $user->fresh();
        $this->assertEquals($user->first_name, $newUser->first_name);
        $this->assertEquals($user->last_name, $newUser->last_name);
        $this->assertEquals($user->email, $newUser->email);
        $this->assertEquals($oldUser->password, $user->password);
    }

    /**
    * @test
    */
    public function it_only_a_allows_users_to_update_themselves()
    {
        $user1 = $oldUser = create(User::class, [
            'password' => 'oldpassword',
        ]);
        $newUser = make(User::class);

        $this->signIn($user2 = create(User::class));

        $this->patch(route('profile.update', ['user' => $user1]), [
            'first_name' => $newUser->first_name,
            'last_name' => $newUser->last_name,
            'email' => $newUser->email,
            'password' => $password = 'myn3wp@$$w0rd',
            'password_confirmation' => $password,
        ])->assertStatus(403);

        $user1 = $user1->fresh();
        $this->assertEquals($oldUser->first_name, $user1->first_name);
        $this->assertEquals($oldUser->last_name, $user1->last_name);
        $this->assertEquals($oldUser->email, $user1->email);
        $this->assertEquals($oldUser->password, $user1->password);
    }

    /**
    * @test
    */
    public function it_deletes_a_user()
    {
        $user = $this->signIn();
        $this->assertTrue(auth()->check());

        $this->delete(route('profile.destroy', ['user' => $user]));
        $this->assertFalse(auth()->check());
        $this->assertNull($user->fresh());
    }

    /**
    * @test
    */
    public function it_only_allows_users_to_delete_themselves()
    {
        $user1 = create(User::class);
        $user2 = $this->signIn();
        $this->assertTrue(auth()->check());

        $this->delete(route('profile.destroy', ['user' => $user1]))
            ->assertStatus(403);
        $this->assertTrue(auth()->check());
        $this->assertNotNull($user1->fresh());
    }
}
