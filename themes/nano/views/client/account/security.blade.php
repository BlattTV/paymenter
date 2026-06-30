<div>
    <div class="mt-6">
        <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden">
            <div class="p-6 border-b border-neutral">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-xl font-semibold">{{ translate('account.sessions', 'Sessions') }}</h2>
                        <p class="text-xs text-base/50">{{ translate('account.manage_active_sessions', 'Manage your active sessions') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    @foreach (Auth::user()->sessions->filter(fn ($session) => !$session->impersonating()) as $session)
                    <div class="flex flex-col md:flex-row md:items-center gap-4 pb-4 border-b border-neutral/50 last:border-b-0">
                        <div class="md:w-1/3">
                            <label class="text-sm font-semibold">{{ translate('account.session', 'Session') }}</label>
                            <p class="text-xs text-base/50 mt-1">{{ $session->ip_address }} - {{ $session->last_activity->diffForHumans() }}</p>
                            <p class="text-xs text-base/50 mt-1">{{ $session->formatted_device }}</p>
                        </div>
                        <div class="md:w-2/3 flex justify-end">
                            <x-button.primary wire:click="logoutSession('{{ $session->id }}')" class="text-sm !w-fit">
                                {{ translate('account.logout_sessions', 'Logout Sessions') }}
                            </x-button.primary>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden mt-6">
            <div class="p-6 border-b border-neutral">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-xl font-semibold">{{ translate('account.change_password', 'Change Password') }}</h2>
                        <p class="text-xs text-base/50">{{ translate('account.update_password', 'Update your account password') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form wire:submit="changePassword">
                    <div class="space-y-6">
                        <div class="flex flex-col md:flex-row md:items-center gap-4 pb-6 border-b border-neutral/50">
                            <div class="md:w-1/3">
                                <label class="text-sm font-semibold">{{ translate('account.input.current_password', 'Current Password') }}</label>
                                <p class="text-xs text-base/50 mt-1">{{ __('account.input.current_password_placeholder') }}</p>
                            </div>
                            <div class="md:w-2/3">
                                <x-form.input name="current_password" type="password"
                                    :placeholder="translate('account.input.current_password_placeholder', 'Your current password')" wire:model="current_password"
                                    required dirty label="" />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center gap-4 pb-6 border-b border-neutral/50">
                            <div class="md:w-1/3">
                                <label class="text-sm font-semibold">{{ translate('account.input.new_password', 'New Password') }}</label>
                                <p class="text-xs text-base/50 mt-1">{{ translate('account.your_new_password', 'Your new password') }}</p>
                            </div>
                            <div class="md:w-2/3">
                                <x-form.input name="password" type="password"
                                    :placeholder="translate('account.input.new_password_placeholder', 'Your new password')" wire:model="password" required dirty label="" />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <div class="md:w-1/3">
                                <label class="text-sm font-semibold">{{ translate('account.input.confirm_password', 'Confirm Password') }}</label>
                                <p class="text-xs text-base/50 mt-1">{{ translate('account.confirm_new_password', 'Confirm your new password') }}</p>
                            </div>
                            <div class="md:w-2/3">
                                <x-form.input name="password_confirmation" type="password"
                                    :placeholder="translate('account.input.confirm_password_placeholder', 'Confirm your new password')" wire:model="password_confirmation"
                                    required dirty label="" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-neutral/50 flex justify-end">
                        <x-button.primary type="submit" class="px-6">
                            <x-ri-save-line class="size-4" />
                            {{ translate('account.change_password', 'Change Password') }}
                        </x-button.primary>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden mt-6">
            <div class="p-6 border-b border-neutral">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-xl font-semibold">{{ translate('account.two_factor_authentication', 'Two-factor Authentication') }}</h2>
                        <p class="text-xs text-base/50">{{ translate('account.add_extra_security', 'Add an extra layer of security to your account') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="md:w-1/3">
                            <label class="text-sm font-semibold">{{ translate('account.status', 'Status') }}</label>
                            <p class="text-xs text-base/50 mt-1">
                                @if ($twoFactorEnabled)
                                    {{ translate('account.two_factor_authentication_enabled', 'Two-factor authentication is enabled for your account') }}
                                @else
                                    {{ translate('account.two_factor_authentication_description', 'Add an extra layer of security to your account by enabling two-factor authentication') }}
                                @endif
                            </p>
                        </div>
                        <div class="md:w-2/3 flex justify-end">
                            @if ($twoFactorEnabled)
                            <x-button.primary x-on:click="$store.confirmation.confirm({
                                title: '{{ translate('account.two_factor_authentication_disable', 'Disable Two-factor Authentication') }}',
                                message: '{{ translate('account.two_factor_authentication_disable_description', 'Are you sure you want to disable two-factor authentication? This will remove the extra layer of security from your account') }}',
                                confirmText: '{{ translate('account.confirm', 'Confirm') }}',
                                cancelText: '{{ translate('account.cancel', 'Cancel') }}',
                                callback: () => $wire.disableTwoFactor()
                            })" class="px-6">
                                {{ translate('account.two_factor_authentication_disable', 'Disable Two-factor Authentication') }}
                            </x-button.primary>
                            @else
                            <x-button.primary wire:click="enableTwoFactor" class="px-6">
                                {{ translate('account.two_factor_authentication_enable', 'Enable Two-factor Authentication') }}
                            </x-button.primary>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($showEnableTwoFactor)
        <x-modal :title="translate('account.two_factor_authentication_enable', 'Enable Two-factor Authentication')" open="true">
            <p class="text-primary-100">{{ translate('account.two_factor_authentication_enable_description', 'To enable two factor authentication, you need to scan the QR code below with an authenticator app like Google Authenticator or Authy') }}</p>
            <div class="flex flex-col items-center mt-4">
                <img src="{{ $twoFactorData['image'] }}" alt="QR code" class="w-64 h-64" />
                <p class="text-primary-400 mt-2 text-sm text-center">
                    {{ translate('account.two_factor_authentication_secret', 'Or enter the following code manually') }}<br />{{ $twoFactorData['secret'] }}</p>
            </div>
            <form wire:submit.prevent="enableTwoFactor">
                <x-form.input divClass="mt-8" name="two_factor_code" type="text"
                    :label="translate('account.input.two_factor_code', 'Enter the code from your authenticator app')"
                    :placeholder="translate('account.input.two_factor_code_placeholder', 'Your two-factor authentication code')" wire:model="twoFactorCode"
                    required />
                <x-button.primary class="w-full mt-4" type="submit">
                    {{ translate('account.two_factor_authentication_enable', 'Enable Two-factor Authentication') }}
                </x-button.primary>
            </form>
            <x-slot name="closeTrigger">
                <button @click="document.location.reload()" class="text-primary-100">
                    <x-ri-close-fill class="size-6" />
                </button>
            </x-slot>
        </x-modal>
        @endif
    </div>
</div>
