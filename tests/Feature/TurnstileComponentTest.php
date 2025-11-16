<?php

namespace Theograms\FilamentTurnstile\Tests\Feature;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Livewire\Livewire;
use Theograms\FilamentTurnstile\Forms\Components\Turnstile;
use Theograms\FilamentTurnstile\Tests\TestCase;

class TurnstileComponentTest extends TestCase
{
    /** @test */
    public function it_can_render_turnstile_component(): void
    {
        $component = Turnstile::make('captcha');

        $this->assertInstanceOf(Turnstile::class, $component);
    }

    /** @test */
    public function it_can_set_theme(): void
    {
        $component = Turnstile::make('captcha')->theme('dark');

        $this->assertEquals('dark', $component->getTheme());
    }

    /** @test */
    public function it_can_set_size(): void
    {
        $component = Turnstile::make('captcha')->size('compact');

        $this->assertEquals('compact', $component->getSize());
    }

    /** @test */
    public function it_can_set_language(): void
    {
        $component = Turnstile::make('captcha')->language('es');

        $this->assertEquals('es', $component->getLanguage());
    }

    /** @test */
    public function it_has_validation_rule_attached(): void
    {
        $component = Turnstile::make('captcha');

        $rules = $component->getValidationRules();

        $this->assertNotEmpty($rules);
    }
}
