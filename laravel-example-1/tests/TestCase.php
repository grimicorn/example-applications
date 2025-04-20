<?php

namespace Tests;

use App\Models\User;
use App\Domain\Supports\Geocoder;
use Illuminate\Support\Facades\Artisan;
use App\Models\Location as LocationModel;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        FakerEnumProvider::register();
        $this->disableGeocoding();
    }

    protected function refreshScoutIndex()
    {
        try {
            Artisan::call('scout:index', ['name' => 'locations']);
        } catch (\Exception $e) {
            // silence index already exists exception
        }

        Artisan::call('scout:flush', ['model' => LocationModel::class]);
    }

    protected function loginUser(?User $user = null)
    {
        $user = $user ?? User::factory()->create();
        auth()->setUser($user);

        return $user;
    }

    protected function disableGeocoding()
    {
        return resolve(Geocoder::class)->disable();
    }

    protected function enableGeocoding()
    {
        return resolve(Geocoder::class)->enable();
    }
}
