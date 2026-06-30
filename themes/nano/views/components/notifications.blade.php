<div>
    <x-dropdown width="w-84" :showArrow="false" buttonClass="p-0">
        <x-slot:trigger>
            <div class="cursor-pointer relative flex size-9 mr-3 items-center justify-center rounded-[var(--button-radius)] text-muted hover:bg-background-secondary transition-colors" x-data="{ hasNew: false }" x-on:new-notification.window="hasNew = true"
                @click="hasNew = false">
                <x-ri-notification-3-fill class="size-4" ::class="{'animate-wiggle': hasNew}"/>
                @if($this->notifications->where('read_at', null)->count() > 0)
                <span
                    class="absolute text-white -right-0.5 -top-0.5 flex size-4 items-center justify-center rounded-full bg-error text-[10px] font-bold border border-transparent hover:border-error/50">
                    {{ $this->notifications->where('read_at', null)->count() }}
                </span>
                @endif
            </div>
        </x-slot:trigger>
        <x-slot:content>
            <div class="w-full max-h-96 overflow-y-auto">
                @if ($this->notifications->isEmpty())
                <div class="p-4 text-center text-sm text-base/80">
                    {{ __('No new notifications') }}
                </div>
                @else
                @foreach ($this->notifications as $notification)
                <div wire:click="goToNotification({{ $notification->id }})"
                    class="block px-4 py-3 hover:bg-background-secondary cursor-pointer @if (!$loop->last) border-b border-neutral/60 @endif">
                    <div class="flex items-start gap-3">
                        <x-ri-notification-3-fill
                            class="size-5 mt-1 flex-shrink-0 {{ $notification->read_at ? 'text-base/80' : 'text-primary' }}" />
                        <div class="flex flex-col">
                            <span class="font-medium">{{ $notification->title }}</span>
                            <span class="text-sm text-base/80">{{ $notification->body }}</span>
                            <div class="flex flex-row justify-between mt-1 text-xs text-base/60">
                                <p>
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>

                                <button wire:click.stop="markAsRead({{ $notification->id }})" class="cursor-pointer"
                                    type="button">
                                    {{ __('Mark as read') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </x-slot:content>
    </x-dropdown>
</div>

