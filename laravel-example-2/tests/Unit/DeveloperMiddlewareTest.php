<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Enums\UserRoleEnum;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeveloperMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        \Route::middleware('developer')->any('/_test/developer', function () {
            return 'OK';
        });
    }

    /** @test */
    public function non_developers_are_forbidden()
    {
        $this->withoutExceptionHandling();
        $exceptionCount = 0;
        $httpVerbs = ['get', 'post', 'put', 'patch', 'delete'];
        $user = create(User::class);

        foreach ($httpVerbs as $httpVerb) {
            try {
                $response = $this->actingAs($user)->$httpVerb('/_test/developer');
            } catch (HttpException $e) {
                $exceptionCount++;
                $this->assertEquals(Response::HTTP_FORBIDDEN, $e->getStatusCode());
            }
        }

        if (count($httpVerbs) === $exceptionCount) {
            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function guests_are_forbidden()
    {
        $this->withoutExceptionHandling();
        $exceptionCount = 0;
        $httpVerbs = ['get', 'post', 'put', 'patch', 'delete'];

        foreach ($httpVerbs as $httpVerb) {
            try {
                $response = $this->$httpVerb('/_test/developer');
            } catch (HttpException $e) {
                $exceptionCount++;
                $this->assertEquals(Response::HTTP_FORBIDDEN, $e->getStatusCode());
            }
        }

        if (count($httpVerbs) === $exceptionCount) {
            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function developer_are_not_forbidden()
    {
        $this->withoutExceptionHandling();
        $exceptionCount = 0;
        $httpVerbs = ['get', 'post', 'put', 'patch', 'delete'];
        $user = create(User::class)->assignRole(UserRoleEnum::DEVELOPER);

        foreach ($httpVerbs as $httpVerb) {
            try {
                $this->actingAs($user)
                    ->$httpVerb('/_test/developer')
                    ->assertOk();
            } catch (HttpException $e) {
                $exceptionCount++;
            }
        }

        if ($exceptionCount === 0) {
            return;
        }

        $this->fail('Expected a 200 ok');
    }
}
