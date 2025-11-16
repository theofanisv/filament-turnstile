@php
    $statePath = $getStatePath();
    $siteKey = $getSiteKey();
    $theme = $getTheme() ?? config('filament-turnstile.theme', 'auto');
    $size = $getSize() ?? config('filament-turnstile.size', 'normal');
    $language = $getLanguage() ?? config('filament-turnstile.language', 'auto');
    $trackAction = $getTrackAction();
    $cData = $getCData();
    $appearance = $getAppearance() ?? 'always';
    $refreshExpired = $getRefreshExpired() ? 'auto' : 'never';
    $retryInterval = $getRetryInterval() ?? '8000';
    $isTestMode = $isTestMode();

    // Generate unique ID for this widget instance
    $widgetId = 'turnstile-' . md5($statePath . uniqid());
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            widgetId: null,
            scriptLoaded: false,

            init() {
                // Load Turnstile script if not already loaded
                if (!window.turnstile) {
                    this.loadTurnstileScript();
                } else {
                    this.renderWidget();
                }

                // Listen for reset events
                window.addEventListener('reset-turnstile', () => {
                    this.resetWidget();
                });

                // Listen for Livewire validation errors
                Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                    fail(() => {
                        this.resetWidget();
                    });
                });
            },

            loadTurnstileScript() {
                const script = document.createElement('script');
                script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
                script.async = true;
                script.defer = true;
                script.onload = () => {
                    this.scriptLoaded = true;
                    this.renderWidget();
                };
                document.head.appendChild(script);
            },

            renderWidget() {
                if (!window.turnstile) return;

                // Wait for container to be ready
                this.$nextTick(() => {
                    const container = this.$refs.turnstileContainer;
                    if (!container) return;

                    try {
                        this.widgetId = turnstile.render(container, {
                            sitekey: '{{ $siteKey }}',
                            theme: '{{ $theme }}',
                            size: '{{ $size }}',
                            language: '{{ $language }}',
                            @if($trackAction)
                            action: '{{ $trackAction }}',
                            @endif
                            @if($cData)
                            cData: '{{ $cData }}',
                            @endif
                            appearance: '{{ $appearance }}',
                            'refresh-expired': '{{ $refreshExpired }}',
                            'retry-interval': {{ $retryInterval }},
                            callback: (token) => {
                                this.state = token;
                            },
                            'error-callback': (error) => {
                                console.error('Turnstile error:', error);
                                this.state = null;
                            },
                            'expired-callback': () => {
                                this.state = null;
                            }
                        });
                    } catch (error) {
                        console.error('Failed to render Turnstile widget:', error);
                    }
                });
            },

            resetWidget() {
                if (this.widgetId !== null && window.turnstile) {
                    try {
                        turnstile.reset(this.widgetId);
                        this.state = null;
                    } catch (error) {
                        console.error('Failed to reset Turnstile widget:', error);
                    }
                }
            }
        }"
        wire:ignore
        class="filament-turnstile-field"
    >
        <div
            x-ref="turnstileContainer"
            id="{{ $widgetId }}"
            class="cf-turnstile-container"
        ></div>

        @if($isTestMode)
            <div class="mt-2 text-sm text-warning-600 dark:text-warning-400">
                ⚠️ Turnstile is in test mode - validation is disabled
            </div>
        @endif
    </div>
</x-dynamic-component>
