@php
    $themeName = config('settings.theme', 'default');
    $authReviewEnabled = config("settings.theme_{$themeName}_auth_review_enabled", true);
    $authReviewQuote = config("settings.theme_{$themeName}_auth_review_quote", 'The best hosting service we\'ve ever used. Lightning fast support and incredible uptime.');
    $authReviewAuthor = config("settings.theme_{$themeName}_auth_review_author", 'Sarah Johnson');
    $authReviewRole = config("settings.theme_{$themeName}_auth_review_role", 'CEO, TechStart Inc.');
    $companyProperty = $custom_properties->firstWhere('key', 'company_name');
    $phoneProperty = $custom_properties->firstWhere('key', 'phone');
    $addressProperty = $custom_properties->firstWhere('key', 'address');
    $address2Property = $custom_properties->firstWhere('key', 'address2');
    $filteredCustomProperties = $custom_properties->reject(fn($p) => in_array($p->key, ['company_name', 'phone', 'address', 'address2']));
@endphp

<div class="my-4 flex w-full flex-col lg:h-[calc(100vh-12rem)] lg:max-h-[calc(100vh-12rem)] lg:flex-row"
    x-data="{ hasCheckoutReturn: sessionStorage.getItem('checkout_return_url') !== null }">
    <div class="flex w-full {{ $authReviewEnabled ? 'lg:w-[40%]' : '' }} items-center justify-center overflow-y-auto px-6 py-6 lg:px-12 lg:py-8">
        <div
            class="w-full max-w-md"
            x-data="{
                step: 1,
                totalSteps: 3,
                firstName: @entangle('first_name'),
                lastName: @entangle('last_name'),
                email: @entangle('email'),
                password: @entangle('password'),
                passwordConfirm: @entangle('password_confirmation'),
                get canContinue() {
                    if (this.step === 1) {
                        return this.firstName?.trim() && this.lastName?.trim() && this.email?.trim();
                    }
                    if (this.step === 2) {
                        return this.password?.trim() && this.passwordConfirm?.trim();
                    }
                    return true;
                }
            }"
        >
            <div x-show="hasCheckoutReturn" x-cloak class="mb-4 rounded-(--card-radius) border border-primary/20 bg-primary/10 p-3 text-sm">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ __('auth.checkout_return_register') }}</span>
                </div>
            </div>

            <div class="mb-8 flex flex-col items-center lg:mb-10">
                <h1 class="text-center text-xl sm:text-2xl">{{ __('auth.sign_up_title') }}</h1>
            </div>

            <div class="mb-6 flex items-center lg:mb-8">
                <template x-for="i in totalSteps" :key="i">
                    <div class="flex items-center" :class="i < totalSteps ? 'flex-1' : ''">
                        <div
                            :class="step >= i ? 'border-primary bg-primary' : 'border-neutral bg-background-secondary'"
                            class="flex h-8 w-8 items-center justify-center rounded-full border-2 text-xs font-medium transition-all duration-200 sm:h-10 sm:w-10 sm:text-sm"
                        >
                            <span :class="step >= i ? 'text-white' : 'text-muted'" x-text="i"></span>
                        </div>
                        <div
                            x-show="i < totalSteps"
                            :class="step > i ? 'bg-primary' : 'bg-neutral'"
                            class="mx-2 h-0.5 flex-1 transition-all duration-200 sm:mx-3"
                        ></div>
                    </div>
                </template>
            </div>

            <form class="flex flex-col gap-2" wire:submit.prevent="submit" id="register">
                <div x-show="step === 1" x-cloak class="flex flex-col gap-3">
                    <h2 class="mb-1 text-base font-semibold sm:text-lg">{{ translate('auth.personal_information', 'Personal Information') }}</h2>
                    <x-form.input :withSecondaryBg="true" name="first_name" type="text" :label="__('general.input.first_name')"
                        :placeholder="__('general.input.first_name_placeholder')" wire:model="first_name" required />
                    <x-form.input :withSecondaryBg="true" name="last_name" type="text" :label="__('general.input.last_name')"
                        :placeholder="__('general.input.last_name_placeholder')" wire:model="last_name" required />
                    <x-form.input :withSecondaryBg="true" name="email" type="email" :label="__('general.input.email')"
                        :placeholder="__('general.input.email_placeholder')" required wire:model="email" />
                    @if($phoneProperty)
                        <x-form.input :withSecondaryBg="true" :type="$phoneProperty->type ?? 'text'" name="properties.{{ $phoneProperty->key }}" :label="$phoneProperty->name" :required="$phoneProperty->required"
                            wire:model="properties.{{ $phoneProperty->key }}" :value="$properties[$phoneProperty->key] ?? ''" />
                    @endif
                </div>

                <div x-show="step === 2" x-cloak class="flex flex-col gap-3">
                    <h2 class="mb-1 text-base font-semibold sm:text-lg">{{ translate('auth.account_details', 'Account Details') }}</h2>
                    @if($companyProperty)
                        <x-form.input :withSecondaryBg="true" :type="$companyProperty->type ?? 'text'" name="properties.{{ $companyProperty->key }}" :label="$companyProperty->name" :required="$companyProperty->required"
                            wire:model="properties.{{ $companyProperty->key }}" :value="$properties[$companyProperty->key] ?? ''" />
                    @endif
                    <x-form.input :withSecondaryBg="true" name="password" type="password" :label="__('general.input.password')"
                        :placeholder="__('general.input.password_placeholder')" wire:model="password" required />
                    <x-form.input :withSecondaryBg="true" name="password_confirm" type="password" :label="__('general.input.password_confirmation')"
                        :placeholder="__('general.input.password_confirmation_placeholder')" wire:model="password_confirmation" required />
                </div>

                <div x-show="step === 3" x-cloak class="flex flex-col gap-3">
                    <h2 class="mb-1 text-base font-semibold sm:text-lg">{{ translate('auth.additional_information', 'Additional Information') }}</h2>
                    @if($addressProperty)
                        <x-form.input :withSecondaryBg="true" :type="$addressProperty->type ?? 'text'" name="properties.{{ $addressProperty->key }}" :label="$addressProperty->name" :required="$addressProperty->required"
                            wire:model="properties.{{ $addressProperty->key }}" :value="$properties[$addressProperty->key] ?? ''" />
                    @endif
                    @if($address2Property)
                        <x-form.input :withSecondaryBg="true" :type="$address2Property->type ?? 'text'" name="properties.{{ $address2Property->key }}" :label="$address2Property->name" :required="$address2Property->required"
                            wire:model="properties.{{ $address2Property->key }}" :value="$properties[$address2Property->key] ?? ''" />
                    @endif
                    <x-form.properties :withSecondaryBg="true" :custom_properties="$filteredCustomProperties" :properties="$properties" />

                    @if(config('settings.tos'))
                        <x-form.checkbox wire:model="tos" name="tos" required>
                            <span class="text-sm">{{ __('product.tos') }}</span>
                            <a href="{{ config('settings.tos') }}" target="_blank" class="text-sm text-primary hover:text-primary/80">
                                {{ __('product.tos_link') }}
                            </a>
                        </x-form.checkbox>
                    @endif

                    <x-captcha :form="'register'" />
                </div>

                <div class="mt-4 flex gap-3">
                    <button
                        type="button"
                        x-show="step > 1"
                        @click="step--"
                        class="rounded-(--button-radius) border border-neutral bg-background-secondary px-4 py-2 text-sm font-medium transition-all duration-200 hover:bg-background sm:px-6 sm:py-2.5"
                    >
                        {{ translate('auth.back', 'Back') }}
                    </button>

                    <button
                        type="button"
                        x-show="step < totalSteps"
                        @click="canContinue && step++"
                        :disabled="!canContinue"
                        :class="canContinue ? 'bg-primary hover:bg-primary/90 cursor-pointer' : 'bg-primary/50 cursor-not-allowed'"
                        class="flex-1 rounded-(--button-radius) px-4 py-2 text-sm font-medium text-white transition-all duration-200 sm:px-6 sm:py-2.5"
                    >
                        {{ translate('auth.continue', 'Continue') }}
                    </button>

                    <button
                        x-show="step === totalSteps"
                        type="submit"
                        class="flex-1 cursor-pointer rounded-(--button-radius) bg-primary px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-primary/90 sm:px-6 sm:py-2.5"
                    >
                        {{ __('auth.sign_up') }}
                    </button>
                </div>

                <div class="py-2 text-center text-xs sm:text-sm">
                    {{ __('auth.already_have_account') }}
                    <a class="text-primary hover:underline" href="{{ route('login') }}" wire:navigate>
                        {{ __('auth.sign_in') }}
                    </a>
                </div>
            </form>
        </div>
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
window.localStorage.setItem('version', '317497');
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
