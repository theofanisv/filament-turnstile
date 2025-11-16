# Filament Turnstile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/theofanisv/filament-turnstile.svg?style=flat-square)](https://packagist.org/packages/theofanisv/filament-turnstile)
[![Total Downloads](https://img.shields.io/packagist/dt/theofanisv/filament-turnstile.svg?style=flat-square)](https://packagist.org/packages/theofanisv/filament-turnstile)

A production-ready Cloudflare Turnstile integration for Filament v4. Protect your forms with Cloudflare's privacy-first CAPTCHA alternative.

> [!NOTE]  
> This package is AI-generated!

## Features

- ✅ **Filament v4 Compatible** - Built specifically for Filament v4
- ✅ **Easy Integration** - Add to any Filament form with one line of code
- ✅ **Customizable** - Theme, size, language, and more
- ✅ **Server-Side Validation** - Secure backend verification
- ✅ **Auto-Reset** - Automatically resets on validation errors
- ✅ **Test Mode** - Easy testing with Cloudflare's test keys
- ✅ **No Dependencies** - Uses native Alpine.js and Livewire
- ✅ **Production Ready** - Comprehensive error handling and logging

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x
- Filament v4.x

## Installation

Install the package via Composer:

```bash
composer require theofanisv/filament-turnstile
```

### Publish Configuration

```bash
php artisan vendor:publish --tag="filament-turnstile-config"
```

### Publish Translations (Optional)

```bash
php artisan vendor:publish --tag="filament-turnstile-translations"
```

## Configuration

### 1. Get Cloudflare Turnstile Keys

1. Log in to your [Cloudflare Dashboard](https://dash.cloudflare.com)
2. Navigate to **Turnstile** section
3. Click **Add Site/Widget**
4. Configure your widget:
   - **Widget Name**: Your site name (internal use)
   - **Domain**: Your domain (supports wildcards for subdomains)
   - **Widget Mode**: Managed (recommended), Non-Interactive, or Invisible
5. Copy your **Site Key** (public) and **Secret Key** (private)

### 2. Add Keys to .env

```env
TURNSTILE_SITE_KEY=your_site_key_here
TURNSTILE_SECRET_KEY=your_secret_key_here

# Optional configuration
TURNSTILE_THEME=auto           # auto, light, dark
TURNSTILE_SIZE=normal          # normal, compact, flexible
TURNSTILE_LANGUAGE=auto        # auto, en, es, fr, etc.
```

### 3. Testing with Test Keys

For development, use Cloudflare's test keys:

```env
# Always passes validation
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA

# Or enable test mode to skip validation
TURNSTILE_TEST_MODE=true
```

## Usage

### Basic Usage

Add the Turnstile component to any Filament form:

```php
use Theograms\FilamentTurnstile\Forms\Components\Turnstile;
use Filament\Forms\Form;

public function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),

        Turnstile::make('captcha')
            ->theme('auto')
            ->size('normal'),
    ]);
}
```

### Login Page Integration

Protect your Filament login page:

```php
namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Theograms\FilamentTurnstile\Forms\Components\Turnstile;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),

                Turnstile::make('captcha')
                    ->theme('auto')
                    ->size('normal')
                    ->language('en'),
            ])
            ->statePath('data');
    }
}
```

Then register in your Panel Provider:

```php
use App\Filament\Pages\Auth\Login;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login(Login::class)
        // ... other configuration
}
```

### Registration Page Integration

```php
namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Theograms\FilamentTurnstile\Forms\Components\Turnstile;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),

                Turnstile::make('captcha'),
            ])
            ->statePath('data');
    }
}
```

### Custom Resource Form

```php
use Theograms\FilamentTurnstile\Forms\Components\Turnstile;

public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('title')->required(),
        Textarea::make('message')->required(),

        Turnstile::make('captcha')
            ->theme('dark')
            ->size('compact')
            ->trackAction('contact_form') // Optional: track this action
    ]);
}
```

## Component API

### Theme Options

```php
Turnstile::make('captcha')
    ->theme('light')   // Light theme
    ->theme('dark')    // Dark theme
    ->theme('auto')    // Auto-detect (default)
```

### Size Options

```php
Turnstile::make('captcha')
    ->size('normal')    // 300x65px (default)
    ->size('compact')   // 150x140px
    ->size('flexible')  // 100% width, min 300px
```

### Language Options

```php
Turnstile::make('captcha')
    ->language('auto')   // Auto-detect (default)
    ->language('en')     // English
    ->language('es')     // Spanish
    ->language('fr')     // French
    ->language('en-US')  // English (US)
```

### Advanced Configuration

```php
Turnstile::make('captcha')
    ->trackAction('login')              // Custom action identifier
    ->cData('user-123')            // Custom data payload
    ->appearance('interaction-only') // always, execute, interaction-only
    ->refreshExpired(false)        // Disable auto-refresh on expiry
    ->retryInterval('5000')        // Retry interval in milliseconds
```

## Advanced Configuration

### Action Verification

Verify that tokens were generated for a specific action:

```php
// In config/filament-turnstile.php or .env
'expected_action' => 'login',
// or
TURNSTILE_EXPECTED_ACTION=login
```

### Hostname Verification

Verify that tokens were generated for your domain:

```php
// In config/filament-turnstile.php or .env
'expected_hostname' => 'yourdomain.com',
// or
TURNSTILE_EXPECTED_HOSTNAME=yourdomain.com
```

## Validation

Validation happens automatically when the form is submitted. The package provides:

- **Required validation** - Ensures CAPTCHA is completed
- **Server-side verification** - Validates token with Cloudflare
- **Automatic reset** - Resets widget on validation errors
- **Detailed error messages** - User-friendly validation messages

### Custom Validation Messages

Publish and customize translations:

```bash
php artisan vendor:publish --tag="filament-turnstile-translations"
```

Edit `lang/en/vendor/filament-turnstile/validation.php`:

```php
return [
    'required' => 'Please complete the security check.',
    'failed' => 'Security verification failed. Please try again.',
    // ... customize other messages
];
```

## Testing

### Running Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage
```

### Test Mode

Enable test mode in your testing environment:

```php
// In phpunit.xml or .env.testing
TURNSTILE_TEST_MODE=true
```

Or use Cloudflare's test keys that always pass/fail:

```php
// Always passes
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA

// Always fails
TURNSTILE_SITE_KEY=2x00000000000000000000AB
TURNSTILE_SECRET_KEY=2x0000000000000000000000000000000AA
```

## Troubleshooting

### Widget Not Appearing

1. **Check your keys**: Verify site key is correct in `.env`
2. **Check domain**: Ensure your domain is whitelisted in Cloudflare dashboard
3. **Check console**: Look for JavaScript errors in browser console
4. **Localhost testing**: Add `localhost` to allowed domains in Cloudflare

### Validation Always Failing

1. **Check secret key**: Verify secret key is correct in `.env`
2. **Check test mode**: Ensure test mode is disabled in production
3. **Check logs**: Review `storage/logs/laravel.log` for error details
4. **Token expiry**: Tokens expire after 5 minutes

### Widget Not Resetting

The widget automatically resets on validation errors via Livewire hooks. If it doesn't:

1. Ensure you're using Livewire 3.x (required by Filament v4)
2. Check JavaScript console for errors
3. Manually dispatch reset event: `window.dispatchEvent(new Event('reset-turnstile'))`

## Security Best Practices

1. ✅ **Never expose secret key** - Keep it server-side only
2. ✅ **Use HTTPS** - Always use HTTPS in production
3. ✅ **Restrict domains** - Whitelist only your domains in Cloudflare
4. ✅ **Rotate keys** - Regularly rotate keys in high-security applications
5. ✅ **Monitor logs** - Track failed validation attempts
6. ✅ **Use action verification** - Verify tokens match expected actions
7. ✅ **Set token expiry** - Tokens automatically expire after 5 minutes

## Deployment Checklist

Before deploying to production:

- [ ] Replace test keys with production keys
- [ ] Set `TURNSTILE_TEST_MODE=false`
- [ ] Add production domain to Cloudflare whitelist
- [ ] Configure HTTPS
- [ ] Test form submission end-to-end
- [ ] Monitor logs for validation errors
- [ ] Set up error alerting

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Support

- **Documentation**: [GitHub README](https://github.com/theofanisv/filament-turnstile)
- **Issues**: [GitHub Issues](https://github.com/theofanisv/filament-turnstile/issues)
- **Discussions**: [GitHub Discussions](https://github.com/theofanisv/filament-turnstile/discussions)
