<div>
    @forelse ($tickets as $ticket)
        @php
            $lastMessage = $ticket->messages()->orderBy('created_at', 'desc')->first();
        @endphp
        <a href="{{ route('tickets.show', $ticket) }}" wire:navigate>
            <div class="group flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-neutral' : '' }} hover:bg-background transition-colors cursor-pointer">
                <div class="flex flex-col">
                    <p class="text-base text-sm font-semibold">{{ $ticket->subject }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($ticket->status == 'open')
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-info/10 text-info tracking-wide uppercase">{{ translate('dashboard.open', 'Open') }}</span>
                        @elseif($ticket->status == 'replied')
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-info/10 text-info tracking-wide uppercase">{{ translate('dashboard.replied', 'Replied') }}</span>
                        @elseif($ticket->status == 'closed')
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-inactive/10 text-inactive tracking-wide uppercase">{{ translate('dashboard.closed', 'Closed') }}</span>
                        @else
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-info/10 text-info tracking-wide uppercase">{{ ucfirst($ticket->status) }}</span>
                        @endif
                        @if($lastMessage)
                            <p class="text-base/50 text-xs">{{ $lastMessage->created_at->diffForHumans() }}</p>
                        @elseif($ticket->created_at)
                            <p class="text-base/50 text-xs">{{ $ticket->created_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
                <x-ri-arrow-right-s-line class="text-neutral size-4 group-hover:text-primary transition-colors" />
            </div>
        </a>
    @empty
        <div class="p-4 text-center text-base/50 text-sm">{{ translate('ticket.no_open_tickets', 'No open tickets') }}</div>
    @endforelse
</div>