<?php

namespace Tests\Unit;

use App\Models\Link;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_url_as_name_when_name_is_null()
    {
        $link = Link::factory()->create([
            'name' => null,
            'url' => 'http://www.someurl.com/with/a/path/'
        ]);

        $this->assertNull($link->getAttributes()['name']);
        $this->assertNotNull($link->name);
        $this->assertEquals('someurl.com', $link->name);
    }
}
