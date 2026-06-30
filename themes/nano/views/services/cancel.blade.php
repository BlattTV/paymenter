<div class="flex flex-col gap-6">
    <div class="flex items-start gap-3 text-sm text-[#617589] dark:text-gray-400">
        <svg class="size-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ __('services.cancel_are_you_sure') }}</span>
    </div>

    <x-form.select name="type" label="{{ __('services.cancel_type') }}" required wire:model="type">
        <option value="end_of_period">{{ __('services.cancel_end_of_period') }}</option>
        <option value="immediate">{{ __('services.cancel_immediate') }}</option>
    </x-form.select>

    <x-form.textarea name="reason" label="{{ __('services.cancel_reason') }}" required wire:model="reason" />

    <template x-if="$wire.type === 'immediate'">
        <div class="bg-orange-500/20 text-orange-300 border border-orange-500 p-4 rounded-xl">
            <div class="flex items-start gap-3">
                <svg class="size-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="text-sm">{{ __('services.cancel_immediate_warning') }}</span>
            </div>
        </div>
    </template>

    <div class="flex justify-end gap-3 pt-4">
        <button wire:click="$parent.$set('showCancel', false)" class="px-4 py-2.5 text-sm font-medium text-[#617589] hover:text-[#111418] dark:hover:text-white bg-[#f8fafc] dark:bg-gray-800/50 border border-[#e2e8f0] dark:border-gray-700 rounded-lg transition-colors">
            {{ __('common.cancel') }}
        </button>
        <button wire:confirm="Are you sure?" wire:click="cancelService" class="px-4 py-2.5 text-sm font-medium text-white bg-red-700 hover:bg-red-600 rounded-lg transition-colors">
            <span wire:loading.remove wire:target="cancelService">{{ __('services.cancel') }}</span>
            <x-loading target="cancelService" />
        </button>
    </div>
</div>