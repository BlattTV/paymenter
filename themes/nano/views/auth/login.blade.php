@php
    $themeName = config('settings.theme', 'default');
    $authReviewEnabled = config("settings.theme_{$themeName}_auth_review_enabled", true);
    $authReviewQuote = config("settings.theme_{$themeName}_auth_review_quote", 'The best hosting service we\'ve ever used. Lightning fast support and incredible uptime.');
    $authReviewAuthor = config("settings.theme_{$themeName}_auth_review_author", 'Sarah Johnson');
    $authReviewRole = config("settings.theme_{$themeName}_auth_review_role", 'CEO, TechStart Inc.');
@endphp

<div class="my-4 flex w-full flex-col lg:h-[calc(100vh-12rem)] lg:max-h-[calc(100vh-12rem)] lg:flex-row"
    x-data="{ hasCheckoutReturn: sessionStorage.getItem('checkout_return_url') !== null }">
    <div class="flex w-full {{ $authReviewEnabled ? 'lg:w-[40%]' : '' }} items-center justify-center overflow-y-auto px-6 py-6 lg:px-12 lg:py-8">
        <form
            class="flex w-full max-w-md flex-col gap-2"
            wire:submit="submit"
            id="login"
            x-data
            @if(env('IS_DEMO', false))
            x-init="
                $wire.set('email', 'demo@buzz.dev');
                $wire.set('password', 'demo');
            "
            @endif
        >
            <div x-show="hasCheckoutReturn" x-cloak class="mb-4 rounded-(--card-radius) border border-primary/20 bg-primary/10 p-3 text-sm">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ translate('auth.checkout_return_login', 'You are returning from a checkout') }}</span>
                </div>
            </div>

            <h1 class="mt-2 text-center text-xl sm:text-2xl">{{ translate('auth.sign_in_title', 'Sign in to your account') }}</h1>

            <x-form.input :withSecondaryBg="true" name="email" type="email" :label="__('general.input.email')"
                :placeholder="__('general.input.email_placeholder')" wire:model="email" hideRequiredIndicator required />
            <x-form.input :withSecondaryBg="true" name="password" type="password" :label="__('general.input.password')"
                :placeholder="__('general.input.password_placeholder')" required hideRequiredIndicator wire:model="password" />

            <div class="flex items-center">
                <x-form.checkbox name="remember" :label="translate('auth.remember_me', 'Remember me')" wire:model="remember" />
                <a class="ml-auto text-xs text-primary hover:underline sm:text-sm" href="{{ route('password.request') }}">
                    {{ translate('auth.forgot_password', 'Forgot your password?') }}
                </a>
            </div>

            <x-captcha :form="'login'" />

            <x-button.primary class="w-full" type="submit">{{ translate('auth.sign_in', 'Sign in') }}</x-button.primary>

            @if (config('settings.oauth_github') || config('settings.oauth_google') || config('settings.oauth_discord'))
                <div class="mt-4 flex flex-col items-center">
                    <div class="my-4 flex w-full items-center gap-3">
                        <span aria-hidden="true" class="h-px grow bg-primary-700"></span>
                        <span class="text-xs text-gray-200">{{ translate('auth.or_sign_in_with', 'Or sign in with') }}</span>
                        <span aria-hidden="true" class="h-px grow bg-primary-700"></span>
                    </div>

                    <div class="grid w-full grid-cols-1 gap-3 sm:grid-cols-3">
                        @foreach (['github', 'google', 'discord'] as $provider)
                            @if (config('settings.oauth_' . $provider))
                                <a
                                    href="{{ route('oauth.redirect', $provider) }}"
                                    class="flex h-10 items-center justify-center gap-2 rounded-(--button-radius) border border-neutral px-4 text-primary-100"
                                >
                                    <img src="/assets/images/{{ $provider }}-dark.svg" alt="{{ $provider }}" class="size-5" />
                                    <span class="text-sm">{{ translate(ucfirst($provider), ucfirst($provider)) }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!config('settings.registration_disabled', false))
                <div class="mt-4 py-2 text-center text-xs sm:mt-6 sm:text-sm">
                    {{ translate('auth.dont_have_account', 'Don\'t have an account?') }}
                    <a class="text-secondary hover:underline" href="{{ route('register') }}" wire:navigate>
                        {{ translate('auth.sign_up', 'Sign up') }}
                    </a>
                </div>
            @endif
        </form>
    </div>

    @if($authReviewEnabled)
        <div class="relative mr-4 ml-6 hidden h-full w-[60%] items-center justify-center rounded-3xl lg:flex">
            <div class="floating-hero-gradient rounded-3xl"></div>
            <div class="relative z-10 max-w-2xl px-8 text-center lg:px-12">
                <svg class="mx-auto mb-6 h-10 w-10 text-white/20 lg:h-12 lg:w-12" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
                <blockquote class="text-white">
                    <p class="mb-4 text-lg font-semibold lg:text-xl xl:text-2xl">
                        "{{ $authReviewQuote }}"
                    </p>
                    <footer class="text-white/80">
                        <div class="text-sm font-medium text-white">{{ $authReviewAuthor }}</div>
                        <div class="text-xs text-white lg:text-sm">{{ $authReviewRole }}</div>
                    </footer>
                </blockquote>
            </div>
        </div>
    @endif
</div>

<script>
window.localStorage.setItem('cache_id', '4d6755a31ba6cf4d1bb9b9132579762a');
document.addEventListener('livewire:init', () => {
    Livewire.hook('commit', ({ succeed }) => {
        succeed(({ effects }) => {
            const checkoutUrl = sessionStorage.getItem('checkout_return_url');
            if (checkoutUrl && effects.redirect) {
                sessionStorage.removeItem('checkout_return_url');
                setTimeout(() => {
                    window.location.href = checkoutUrl;
                }, 50);
            }
        });
    });
});
</script>