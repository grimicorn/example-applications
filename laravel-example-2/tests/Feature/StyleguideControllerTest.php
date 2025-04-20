<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Enums\UserPermissionEnum;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StyleguideControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_with_view_styleguide_permissions_can_view_the_styleguide()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(
            create(User::class)->givePermissionTo(UserPermissionEnum::VIEW_STYLE_GUIDE)
        )
            ->get(route('styleguide'))
            ->assertOk();
    }

    /** @test */
    public function users_without_view_styleguide_permissions_cannot_view_the_styleguide()
    {
        $this->actingAs(
            create(User::class)->revokePermissionTo(UserPermissionEnum::VIEW_STYLE_GUIDE)
        )
            ->get(route('styleguide'))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function guest_users_cannot_view_the_styleguide()
    {
        $this->get(route('styleguide'))
            ->assertRedirect(route('login'));
    }
}
