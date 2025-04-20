<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\ValidInvalidUrl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ValidInvalidUrlValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_validates_invalid_valid_urls()
    {
        $this->assertValidInvalidUrlPasses('mailto:test.com');
        $this->assertValidInvalidUrlPasses('tel:111-111-1111');
        $this->assertValidInvalidUrlPasses('google.com');
        $this->assertValidInvalidUrlPasses('www.google.com');
        $this->assertValidInvalidUrlPasses('http://google.com');
        $this->assertValidInvalidUrlPasses('https://google.com');
        $this->assertValidInvalidUrlPasses('http://www.google.com');
        $this->assertValidInvalidUrlPasses('https://www.google.com');
        $this->assertValidInvalidUrlFails('doesnt-match');
        $this->assertValidInvalidUrlFails('tel:dsfdsafd');
    }

    /**
     * Assert the validation passes.
     *
     * @param string $url
     * @return void
     */
    protected function assertValidInvalidUrlPasses($url)
    {
        $input = ['url' => $url];
        $rules = ['url' => new ValidInvalidUrl];

        $this->assertTrue(Validator::make($input, $rules)->passes());
    }

    /**
     * Assert the validation passes.
     *
     * @param string $url
     * @return void
     */
    protected function assertValidInvalidUrlFails($url)
    {
        $input = ['url' => $url];
        $rules = ['url' => new ValidInvalidUrl];

        $this->assertTrue(Validator::make($input, $rules)->fails());
    }
}
