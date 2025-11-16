<?php

namespace Theograms\FilamentTurnstile\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Translation\PotentiallyTranslatedString;

class TurnstileRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if no token provided
        if (empty($value)) {
            $fail('filament-turnstile::validation.required')->translate();
            return;
        }

        // Skip validation in test mode
        if (config('filament-turnstile.test_mode', false)) {
            return;
        }

        $secretKey = config('filament-turnstile.secret_key');

        if (empty($secretKey)) {
            $fail('filament-turnstile::validation.configuration_error')->translate();
            return;
        }

        // Perform server-side verification
        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->successful()) {
                $fail('filament-turnstile::validation.verification_failed')->translate();
                return;
            }

            $result = $response->json();

            if (!isset($result['success']) || $result['success'] !== true) {
                $errorCodes = $result['error-codes'] ?? [];

                // Map error codes to user-friendly messages
                $message = $this->getErrorMessage($errorCodes);

                $fail($message)->translate();
                return;
            }

            // Optionally verify the action and hostname
            $this->verifyAction($result, $fail);
            $this->verifyHostname($result, $fail);

        } catch (\Exception $e) {
            \Log::error('Turnstile verification error: ' . $e->getMessage());
            $fail('filament-turnstile::validation.verification_failed')->translate();
        }
    }

    /**
     * Get user-friendly error message based on error codes
     */
    protected function getErrorMessage(array $errorCodes): string
    {
        if (empty($errorCodes)) {
            return 'filament-turnstile::validation.failed';
        }

        $errorCode = $errorCodes[0];

        return match ($errorCode) {
            'missing-input-secret' => 'filament-turnstile::validation.configuration_error',
            'invalid-input-secret' => 'filament-turnstile::validation.configuration_error',
            'missing-input-response' => 'filament-turnstile::validation.required',
            'invalid-input-response' => 'filament-turnstile::validation.invalid_token',
            'timeout-or-duplicate' => 'filament-turnstile::validation.expired_or_duplicate',
            'internal-error' => 'filament-turnstile::validation.server_error',
            default => 'filament-turnstile::validation.failed',
        };
    }

    /**
     * Verify the action if configured
     */
    protected function verifyAction(array $result, Closure $fail): void
    {
        $expectedAction = config('filament-turnstile.expected_action');

        if ($expectedAction && isset($result['action']) && $result['action'] !== $expectedAction) {
            $fail('filament-turnstile::validation.action_mismatch')->translate();
        }
    }

    /**
     * Verify the hostname if configured
     */
    protected function verifyHostname(array $result, Closure $fail): void
    {
        $expectedHostname = config('filament-turnstile.expected_hostname');

        if ($expectedHostname && isset($result['hostname']) && $result['hostname'] !== $expectedHostname) {
            $fail('filament-turnstile::validation.hostname_mismatch')->translate();
        }
    }
}
