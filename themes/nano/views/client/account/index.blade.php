<div class="mt-6">
    <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden">
        <div class="p-6 border-b border-neutral">
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="text-xl font-semibold">
                        {{ translate('account.account_information', 'Account Information') }}</h2>
                    <p class="text-xs text-base/50">
                        {{ translate('account.manage_personal_details', 'Manage your personal details') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-6">
                <div class="flex flex-col md:flex-row gap-4 pb-6 border-b border-neutral/50">
                    <div class="md:w-1/3">
                        <label class="text-sm font-semibold">{{ translate('account.name', 'Name') }}</label>
                        <p class="text-xs text-base/50 mt-1">{{ translate('account.your_full_name', 'Your full name') }}
                        </p>
                    </div>
                    <div class="md:w-2/3 grid grid-cols-2 gap-3">
                        <div>
                            <label
                                class="text-xs text-base/60 ml-1 mb-2 block">{{ translate('general.input.first_name', 'First Name') }}</label>
                            <x-form.input name="first_name" type="text" :placeholder="translate('general.input.first_name', 'First Name')" wire:model="first_name"
                                required dirty label="" />
                        </div>
                        <div>
                            <label
                                class="text-xs text-base/60 ml-1 mb-2 block">{{ translate('general.input.last_name', 'Last Name') }}</label>
                            <x-form.input name="last_name" type="text"
                                :placeholder="translate('general.input.last_name_placeholder', 'Last Name')"
                                wire:model="last_name" required dirty label="" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:items-center gap-4 pb-6 border-b border-neutral/50">
                    <div class="md:w-1/3">
                        <label
                            class="text-sm font-semibold">{{ translate('account.email_address', 'Email Address') }}</label>
                        <p class="text-xs text-base/50 mt-1">
                            {{ translate('account.your_email_address', 'Your email address') }}</p>
                    </div>
                    <div class="md:w-2/3">
                        <x-form.input name="email" type="email"
                            :placeholder="translate('general.input.email_placeholder', 'Email Address')" required
                            wire:model="email" dirty label="" />
                    </div>
                </div>

                @php
                    $phoneProperty = $custom_properties->firstWhere('key', 'phone');
                    $companyProperty = $custom_properties->firstWhere('key', 'company_name');
                    $otherProperties = $custom_properties->reject(fn($p) => in_array($p->key, ['phone', 'company_name']));
                @endphp

                @if($phoneProperty)
                    <div class="flex flex-col md:flex-row md:items-center gap-4 pb-6 border-b border-neutral/50">
                        <div class="md:w-1/3">
                            <label
                                class="text-sm font-semibold">{{ translate('account.phone_number', 'Phone Number') }}</label>
                            <p class="text-xs text-base/50 mt-1">
                                {{ translate('account.your_contact_number', 'Your contact number') }}</p>
                        </div>
                        <div class="md:w-2/3">
                            <x-form.input type="text" name="properties.phone" :placeholder="translate('account.phone_number', 'Phone Number')"
                                :required="$phoneProperty->required" wire:model="properties.phone"
                                :value="$properties['phone'] ?? ''" label="" />
                        </div>
                    </div>
                @endif

                @if($companyProperty)
                    <div class="flex flex-col md:flex-row md:items-center gap-4 pb-6 border-b border-neutral/50">
                        <div class="md:w-1/3">
                            <label
                                class="text-sm font-semibold">{{ translate('account.company_name', 'Company Name') }}</label>
                            <p class="text-xs text-base/50 mt-1">
                                {{ translate('account.your_company_or_organization', 'Your company or organization') }}</p>
                        </div>
                        <div class="md:w-2/3">
                            <x-form.input type="text" name="properties.company_name"
                                :placeholder="translate('general.input.company_name_placeholder', 'Company Name')"
                                :required="$companyProperty->required" wire:model="properties.company_name"
                                :value="$properties['company_name'] ?? ''" label="" />
                        </div>
                    </div>
                @endif

                @if($otherProperties->count() > 0)
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="md:w-1/3">
                            <label class="text-sm font-semibold">{{ translate('account.address', 'Address') }}</label>
                            <p class="text-xs text-base/50 mt-1">
                                {{ translate('account.address_details', 'Address details') }}</p>
                        </div>
                        <div class="md:w-2/3">
                            <x-form.properties :custom_properties="$otherProperties" :properties="$properties" dirty
                                hideLabels />
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-8 pt-6 border-t border-neutral/50 flex justify-end">
                <x-button.primary wire:click="submit" class="px-6">
                    <x-ri-save-line class="size-4" />
                    {{ translate('general.update', 'Update') }}
                </x-button.primary>
            </div>
        </div>
    </div>
</div>