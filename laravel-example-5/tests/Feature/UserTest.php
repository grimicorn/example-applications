<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_checks_if_a_user_is_an_admin()
    {
        $subscriber = create(User::class);
        $admin = factory(User::class)->states('admin')->create();

        $this->assertTrue($subscriber->isSubscriber());
        $this->assertFalse($subscriber->isAdmin());
        $this->assertFalse($admin->isSubscriber());
        $this->assertTrue($admin->isAdmin());
    }

    /**
    * @test
    */
    public function it_uses_a_users_avatar_if_it_exists()
    {
        $user = create(User::class, [
            'email' => 'dtholloran@gmail.com',
        ]);

        Storage::fake('test');
        $user->addMedia(UploadedFile::fake()->image('avatar.jpg'))->toMediaCollection('avatar');

        $user = $user->fresh();
        $this->assertNotNull($media = $user->getFirstMedia('avatar'));

        $this->assertEquals(
            $media->file_name,
            pathinfo($user->avatar, PATHINFO_BASENAME)
        );
    }

    /**
    * @test
    */
    public function it_uses_a_users_gravatar_if_the_avatar_is_empty()
    {
        $user = create(User::class, [
            'email' => 'dtholloran@gmail.com',
        ]);

        $this->assertEquals(
            'https://www.gravatar.com/avatar/beea2174d2076aa60e679c19f2aee1bf',
            $user->avatar
        );
    }

    /**
    * @test
    */
    public function it_uses_a_placeholder_avatar_if_the_avatar_is_empty_and_gravatar_is_not_available()
    {
        $user = create(User::class, [
            'email' => 'notarealemail@srcwatch.io',
        ]);

        $this->assertNotNull($user->avatar);
        $this->assertTrue(
            str_contains($user->avatar, 'data:image/png;base64'),
            'The avatar is not a base64 encoded string'
        );
    }
}
