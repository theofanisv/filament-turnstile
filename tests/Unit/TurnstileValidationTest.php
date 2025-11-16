<?php

namespace Theograms\FilamentTurnstile\Tests\Unit;

use Theograms\FilamentTurnstile\Rules\TurnstileRule;
use Theograms\FilamentTurnstile\Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class TurnstileValidationTest extends TestCase
{
    /** @test */
    public function it_passes_validation_in_test_mode(): void
    {
        config(['filament-turnstile.test_mode' => true]);

        $validator = Validator::make(
            ['turnstile' => 'any-token'],
            ['turnstile' => new TurnstileRule()]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_validation_when_token_is_empty(): void
    {
        $validator = Validator::make(
            ['turnstile' => ''],
            ['turnstile' => new TurnstileRule()]
        );

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_has_correct_configuration(): void
    {
        $this->assertNotEmpty(config('filament-turnstile.site_key'));
        $this->assertNotEmpty(config('filament-turnstile.secret_key'));
    }
}
