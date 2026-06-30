@php
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;


$userId = Auth::id();
$query = Ticket::where('user_id', $userId);
if (request()->has('search') && request('search')) {
    $search = request('search');
    $query->where('subject', 'like', "%{$search}%");
}

$statusCounts = [
    'open' => Ticket::where('user_id', $userId)->where('status', 'open')->count(),
    'replied' => Ticket::where('user_id', $userId)->where('status', 'replied')->count(),
    'closed' => Ticket::where('user_id', $userId)->where('status', 'closed')->count(),
];
$tickets = $query->latest()->paginate(config('settings.pagination'));

@endphp

<div>
      @if(isset($statusCounts) && $statusCounts !== null)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
                <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.open') && trim(__('dashboard.open')) !== '' ? __('dashboard.open') : 'Open' }}</p>
                    </div>
                    <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['open'] }}</p>
                </div>
                <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.replied') && trim(__('dashboard.replied')) !== '' ? __('dashboard.replied') : 'Replied' }}</p>
                    </div>
                    <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['replied'] }}</p>
                </div>
                <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.closed') && trim(__('dashboard.closed')) !== '' ? __('dashboard.closed') : 'Closed' }}</p>
                    </div>
                    <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['closed'] }}</p>
                </div>
            </div>
           <div class="flex justify-end">
            <x-navigation.link :href="route('tickets.create')"
                class="w-fit mb-5 flex items-center gap-2 px-5 py-2 bg-primary text-white font-semibold rounded-[var(--button-radius)] shadow-[var(--card-shadow)] transition-colors duration-150 hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/40">
                <x-ri-add-line class="size-5" />
                <span>{{ __('ticket.create_ticket') }}</span>
            </x-navigation.link>
           </div>
    @endif

    <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-neutral">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('ticket.ticket_id', 'Ticket ID') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('ticket.subject', 'Subject') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('ticket.department', 'Department') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('ticket.status', 'Status') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('ticket.last_activity', 'Last Activity') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('ticket.created_at', 'Created At') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide text-right">{{ translate('services.actions', 'Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral">
                    @foreach ($tickets as $ticket)
                        <tr class="hover:bg-background/50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="text-primary font-semibold hover:underline">
                                    #{{ $ticket->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-base">{{ $ticket->subject }}</div>
                                <div class="text-sm text-base/60 flex items-center gap-1 mt-1">
                                    <x-ri-chat-3-line class="size-3.5" />
                                    {{ $ticket->messages()->count() }} {{ $ticket->messages()->count() === 1 ? __('ticket.message') : __('ticket.messages') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-base/60">
                                @if($ticket->department)
                                    {{ $ticket->department }}
                                @else
                                    <span class="text-base/30">{{ __('common.na') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5
                                    @if ($ticket->status == 'open') text-success bg-success/15 border border-success/40
                                    @elseif($ticket->status == 'closed') text-inactive bg-inactive/15 border border-inactive/40
                                    @else text-info bg-info/15 border border-info/40
                                    @endif">
                                    @if ($ticket->status == 'open')
                                        <x-ri-add-circle-fill class="size-3.5" />
                                    @elseif($ticket->status == 'closed')
                                        <x-ri-forbid-fill class="size-3.5" />
                                    @elseif($ticket->status == 'replied')
                                        <x-ri-chat-smile-2-fill class="size-3.5" />
                                    @endif
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-base/60">
                                {{ $ticket->messages()->orderBy('created_at', 'desc')->first()->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-base/60">
                                {{ $ticket->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white font-semibold text-xs rounded-lg">
                                    <x-ri-arrow-right-line class="size-3.5" />
                                    {{ __('common.button.view') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</div>