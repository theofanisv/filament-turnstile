<?php

namespace Theograms\FilamentTurnstile\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Theograms\FilamentTurnstile\Rules\TurnstileRule;

class Turnstile extends Field
{
    protected string $view = 'filament-turnstile::components.turnstile';

    protected string | Closure | null $theme = 'auto';
    protected string | Closure | null $size = 'normal';
    protected string | Closure | null $language = 'auto';
    protected string | Closure | null $trackAction = null;
    protected string | Closure | null $cData = null;
    protected string | Closure | null $appearance = 'always';
    protected bool | Closure $refreshExpired = true;
    protected string | Closure | null $retryInterval = '8000';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrated(true);

        $this->default(null);

        $this->rule(new TurnstileRule());

        $this->validationAttribute('Turnstile');

        // Don't show the field label by default
        $this->label('');

        // Disable inline labels
        $this->hiddenLabel();
    }

    /**
     * Set the widget theme (light, dark, auto)
     */
    public function theme(string | Closure | null $theme): static
    {
        $this->theme = $theme;
        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->evaluate($this->theme);
    }

    /**
     * Set the widget size (normal, compact, flexible)
     */
    public function size(string | Closure | null $size): static
    {
        $this->size = $size;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->evaluate($this->size);
    }

    /**
     * Set the widget language (ISO 639-1 code or 'auto')
     */
    public function language(string | Closure | null $language): static
    {
        $this->language = $language;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->evaluate($this->language);
    }

    /**
     * Set a custom action identifier
     */
    public function trackAction(string | Closure | null $action): static
    {
        $this->action = $action;
        return $this;
    }

    public function getTrackAction(): ?string
    {
        return $this->evaluate($this->action);
    }

    /**
     * Set custom data payload
     */
    public function cData(string | Closure | null $cData): static
    {
        $this->cData = $cData;
        return $this;
    }

    public function getCData(): ?string
    {
        return $this->evaluate($this->cData);
    }

    /**
     * Set appearance mode (always, execute, interaction-only)
     */
    public function appearance(string | Closure | null $appearance): static
    {
        $this->appearance = $appearance;
        return $this;
    }

    public function getAppearance(): ?string
    {
        return $this->evaluate($this->appearance);
    }

    /**
     * Enable/disable automatic token refresh when expired
     */
    public function refreshExpired(bool | Closure $condition = true): static
    {
        $this->refreshExpired = $condition;
        return $this;
    }

    public function getRefreshExpired(): bool
    {
        return $this->evaluate($this->refreshExpired);
    }

    /**
     * Set retry interval in milliseconds
     */
    public function retryInterval(string | Closure | null $interval): static
    {
        $this->retryInterval = $interval;
        return $this;
    }

    public function getRetryInterval(): ?string
    {
        return $this->evaluate($this->retryInterval);
    }

    /**
     * Get the site key from configuration
     */
    public function getSiteKey(): string
    {
        return config('filament-turnstile.site_key', '');
    }

    /**
     * Check if we're in testing mode
     */
    public function isTestMode(): bool
    {
        return config('filament-turnstile.test_mode', false);
    }
}
