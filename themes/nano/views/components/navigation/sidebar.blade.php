<aside class="ml-4 xl:ml-0 md:mr-2 min-w-68 w-68 h-screen md:flex hidden flex-col rtl:right-0 z-10">
    <div class="flex-1 overflow-y-auto relative z-10">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-3 p-4 rounded-[var(--card-radius)] shadow-[var(--card-shadow)] gradient-paper border border-white dark:from-slate-800 dark:border-neutral-800 relative overflow-hidden group">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-[var(--card-radius)] size-10 border border-white/60 shadow-[var(--card-shadow)]" style="background-image: url('{{ auth()->user()->avatar }}');"></div>
                    <div class="flex flex-col min-w-0 flex-1">
                        <p class="text-white font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-white/60 text-sm truncate opacity-80">{{ auth()->user()->email }}</p>
                    </div>
                    @if(theme('force_theme_mode', 'none') === 'none')
                    <button @click="darkMode = !darkMode" type="button" class="flex size-8 items-center cursor-pointer justify-center rounded-[var(--button-radius)] bg-white/20 hover:bg-white/30 transition-colors flex-shrink-0">
                        <template x-if="!darkMode">
                            <x-ri-sun-fill class="size-4 text-white" />
                        </template>
                        <template x-if="darkMode">
                            <x-ri-moon-fill class="size-4 text-white" />
                        </template>
                    </button>
                    @endif
                </div>
                <div class="relative z-10 mt-1">
                    <a href="{{ route('account') }}" wire:navigate class="block w-full bg-white dark:bg-background-secondary text-base dark:hover:bg-background-secondary hover:bg-white text-xs font-medium py-1.5 rounded-[var(--button-radius)] text-center">
                        Profile
                    </a>
                </div>
            </div>
            <x-navigation.sidebar-links />
            @if(theme('show-support-sidebar-widget', true))
                <div class="bg-background-secondary rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border border-neutral p-4 relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="flex items-center justify-center size-8 bg-primary/10 rounded-lg">
                                <i class="fa-brands fa-discord text-primary text-sm"></i>
                            </div>
                            <h3 class="font-semibold text-base text-sm">{{ Lang::has('dashboard.need_some_help') && trim(__('dashboard.need_some_help')) !== '' ? __('dashboard.need_some_help') : 'Need some help?' }}</h3>
                        </div>
                        <p class="text-base/60 text-[11px] mb-3 leading-relaxed">
                            {{ Lang::has('dashboard.join_discord_to_get_help') && trim(__('dashboard.join_discord_to_get_help')) !== '' ? __('dashboard.join_discord_to_get_help') : 'Join the Discord to get help with your purchase.' }}
                        </p>
                        <a href="{{ theme('social-link-discord', 'https://discord.gg/buzz') }}" target="_blank">
                            <button class="w-full bg-primary hover:bg-primary/90 text-[11px] text-white font-medium py-2 rounded-[var(--button-radius)] transition-all shadow-[var(--card-shadow)] hover:shadow-primary/20 active:scale-95">
                                <div class="flex items-center justify-center gap-1.5">
                                    <i class="fa-brands fa-discord text-xs"></i>
                                    <span>{{ Lang::has('dashboard.join_discord') && trim(__('dashboard.join_discord')) !== '' ? __('dashboard.join_discord') : 'Join the Discord' }}</span>
                                </div>
                            </button>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</aside>
