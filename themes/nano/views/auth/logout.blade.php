<button wire:click="logout" type="button" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-500 hover:bg-red-500/10 hover:text-red-600 transition-colors w-full group">
    <div class="flex items-center justify-center size-5 flex-shrink-0">
        <x-ri-logout-box-r-line class="size-4 text-red-500/70 group-hover:text-red-600 transition-colors" />
    </div>
    <span>{{ __('auth.logout') }}</span>
</button>