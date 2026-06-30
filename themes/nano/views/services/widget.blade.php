<div>
    @forelse ($services as $service)
        <a href="{{ route('services.show', $service) }}" wire:navigate>
            <div class="group flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-neutral' : '' }} hover:bg-background transition-colors cursor-pointer">
                <div class="flex flex-col">
                    <p class="text-base text-sm font-semibold">{{ $service->product->name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($service->status == 'active')
                            <span class="size-1.5 rounded-full bg-success"></span>
                            <p class="text-base/50 text-xs">
                                {{ translate('dashboard.active', 'Active') }}
                                @if($service->expires_at)
                                    • {{ translate('services.renews', 'Renews') }} {{ $service->expires_at->format('M d') }}
                                @endif
                            </p>
                        @elseif($service->status == 'suspended')
                            <span class="size-1.5 rounded-full bg-warning"></span>
                            <p class="text-base/50 text-xs">{{ translate('services.suspended', 'Suspended') }}</p>
                        @elseif($service->status == 'pending')
                            <span class="size-1.5 rounded-full bg-warning"></span>
                            <p class="text-base/50 text-xs">{{ translate('services.pending', 'Pending') }}</p>
                        @elseif($service->expires_at && $service->expires_at->isPast())
                            <span class="size-1.5 rounded-full bg-warning"></span>
                            <p class="text-base/50 text-xs">{{ translate('services.expiring', 'Expiring') }} • {{ $service->expires_at->diffInDays(now()) }} {{ translate('services.days_left', 'Days left') }}</p>
                        @endif
                    </div>
                </div>
                <x-ri-arrow-right-s-line class="text-neutral size-4 group-hover:text-primary transition-colors" />
            </div>
        </a>
    @empty
        <div class="p-4 text-center text-base/50 text-sm">{{ translate('services.no_active_services', 'No active services') }}</div>
    @endforelse
</div>