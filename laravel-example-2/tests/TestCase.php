<?php

namespace Tests;

use App\Job;
use App\User;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signInAdmin(?User $user = null)
    {
        $user = $user ?? create(User::class);
        $user->assignRole(UserRoleEnum::ADMINISTRATOR);
        return $this->signIn($user);
    }

    protected function signInDeveloper(?User $user = null)
    {
        $user = $user ?? create(User::class);
        $user->assignRole(UserRoleEnum::DEVELOPER);
        return $this->signIn($user);
    }

    protected function signIn(?User $user = null)
    {
        Auth::login($user = $user ?? create(User::class));

        return $user;
    }

    public function setUp(): void
    {
        parent::setUp();

        app(\DatabaseSeeder::class)->call(\SharedEnvSeeder::class);
        // Fixes memory error related to $faker text
        gc_collect_cycles();
    }

    protected function mergeWithJobAppends(array $attributes = [])
    {
        return array_merge($attributes, Job::getAppends(), ['media']);
    }
}
