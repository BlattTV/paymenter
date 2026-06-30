<div>
    <div class="mt-6">
        @if($this->supportsPush())
        <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden mb-6" x-data="pushNotifications">
            <div class="p-6 border-b border-neutral">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-xl font-semibold">{{ translate('account.push_notifications', 'Push Notifications') }}</h2>
                        <p class="text-xs text-base/50">{{ __('account.push_notifications_description') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="md:w-1/3">
                            <label class="text-sm font-semibold">{{ translate('account.status', 'Status') }}</label>
                            <p class="text-xs text-base/50 mt-1">{{ translate('account.push_subscription_status', 'Your push notification subscription status') }}</p>
                        </div>
                        <div class="md:w-2/3 flex items-center gap-4">
                            <x-button.primary type="button" @click="subscribe"
                                x-bind:disabled="subscriptionStatus !== 'not_subscribed'" class="px-6">
                                <x-ri-notification-line class="size-4" />
                                {{ translate('account.enable_push_notifications', 'Enable Push Notifications') }}
                            </x-button.primary>
                            <div x-show="subscriptionStatus !== 'unknown'">
                                <template x-if="subscriptionStatus === 'not_supported'">
                                    <p class="text-sm text-red-600">{{ translate('account.push_status.not_supported', 'Push notifications are not supported on your device') }}</p>
                                </template>
                                <template x-if="subscriptionStatus === 'denied'">
                                    <p class="text-sm text-red-600">{{ translate('account.push_status.denied', 'Push notifications are denied on your device') }}</p>
                                </template>
                                <template x-if="subscriptionStatus === 'subscribed'">
                                    <p class="text-sm text-green-600">{{ translate('account.push_status.subscribed', 'Push notifications are subscribed on your device') }}</p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @script
        <script>
            Alpine.data('pushNotifications', () => ({
                    subscriptionStatus: 'unknown',
                    init() {
                        console.log(this.subscriptionStatus)
                        if ('serviceWorker' in navigator && 'PushManager' in window) {
                            navigator.serviceWorker.ready.then((registration) => {
                                registration.pushManager.getSubscription().then((subscription) => {
                                    if (subscription) {
                                        this.subscriptionStatus = 'subscribed';
                                    } else {
                                        this.subscriptionStatus = Notification.permission === 'denied' ? 'denied' : 'not_subscribed';
                                    }
                                });
                            });
                        } else {
                            this.subscriptionStatus = 'not_supported';
                        }
                    },
                    subscribe() {
                        if ('serviceWorker' in navigator && 'PushManager' in window) {
                            navigator.serviceWorker.ready.then((registration) => {
                                registration.pushManager.getSubscription().then((subscription) => {
                                    if (subscription) {
                                        @this.call('storePushSubscription', JSON.stringify(subscription));
                                        this.subscriptionStatus = 'subscribed';
                                        return;
                                    }
                                    registration.pushManager.subscribe({
                                        userVisibleOnly: true,
                                        applicationServerKey: urlBase64ToUint8Array('{{ config('settings.vapid_public_key') }}')
                                    }).then((newSubscription) => {
                                        @this.call('storePushSubscription', JSON.stringify(newSubscription));
                                        this.subscriptionStatus = 'subscribed';
                                    }).catch((e) => {
                                        if (Notification.permission === 'denied') {
                                            this.subscriptionStatus = 'denied';
                                        } else {
                                            console.error('Failed to subscribe the user: ', e);
                                            this.subscriptionStatus = 'not_subscribed';
                                        }
                                    });
                                });
                            });
                        } else {
                            this.subscriptionStatus = 'not_supported';
                        }
                    }
                }));
                function urlBase64ToUint8Array(base64String) {
                    const padding = '='.repeat((4 - base64String.length % 4) % 4);
                    const base64 = (base64String + padding)
                        .replace(/\-/g, '+')
                        .replace(/_/g, '/');
                    const rawData = window.atob(base64);
                    const outputArray = new Uint8Array(rawData.length);
                    for (let i = 0; i < rawData.length; ++i) {
                        outputArray[i] = rawData.charCodeAt(i);
                    }
                    return outputArray;
                }
        </script>
        @endscript
        @endif

        <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden">
            <div class="p-6 border-b border-neutral">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-xl font-semibold">{{ translate('account.notification', 'Notification') }}</h2>
                        <p class="text-xs text-base/50">{{ translate('account.notifications_description', 'Manage your notification preferences') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full" x-data="{ preferences: $wire.entangle('preferences') }">
                        <thead>
                            <tr class="border-b border-neutral/50">
                                <th class="text-left py-3 px-4 text-sm font-semibold">
                                    {{ translate('account.notification', 'Notification') }}
                                </th>
                                <th class="text-center py-3 px-4 text-sm font-semibold whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <x-ri-mail-line class="size-4" />
                                        <span class="whitespace-nowrap">{{ translate('account.email_notifications', 'Email Notifications') }}</span>
                                    </div>
                                </th>
                                <th class="text-center py-3 px-4 text-sm font-semibold whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <x-ri-notification-line class="size-4" />
                                        <span class="whitespace-nowrap">{{ translate('account.in_app_notifications', 'In-App Notifications') }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->notifications as $notification)
                            <tr class="border-b border-neutral/10 hover:bg-background/50 transition-colors last:border-b-0">
                                <td class="py-4 px-4 text-sm text-base/70">
                                    {{ $notification->name }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex justify-center items-center">
                                        <x-form.toggle :disabled="!$notification->mail_controllable"
                                            wire:model.defer="preferences.{{ $notification->key }}.mail_enabled" />
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex justify-center items-center">
                                        <x-form.toggle :disabled="!$notification->in_app_controllable"
                                            wire:model.defer="preferences.{{ $notification->key }}.in_app_enabled" />
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 pt-6 border-t border-neutral/50 flex justify-end">
                    <x-button.primary wire:click="savePreferences" wire:loading.attr="disabled" class="px-6">
                        <x-loading wire:loading wire:target="savePreferences" />
                        <x-ri-save-line class="size-4" wire:loading.remove wire:target="savePreferences" />
                        <span wire:loading.remove wire:target="savePreferences">
                            {{ __('general.save') }}
                        </span>
                    </x-button.primary>
                </div>
            </div>
        </div>
    </div>
</div>
