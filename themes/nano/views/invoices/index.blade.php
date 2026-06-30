@php
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;


        $userId = Auth::id();
        $query = Invoice::where('user_id', $userId);
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where('number', 'like', "%{$search}%");
        }

        $statusCounts = [
            'paid' => Invoice::where('user_id', $userId)->where('status', 'paid')->count(),
            'pending' => Invoice::where('user_id', $userId)->where('status', 'pending')->count(),
            'cancelled' => Invoice::where('user_id', $userId)->where('status', 'cancelled')->count(),
        ];
        $invoices = $query->latest()->paginate(config('settings.pagination'));

@endphp


<div class="space-y-4">
    @if(isset($statusCounts) && $statusCounts !== null)
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
            <div class="flex items-center justify-between mb-2">
                <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.paid') && trim(__('dashboard.paid')) !== '' ? __('dashboard.paid') : 'Paid' }}</p>
            </div>
            <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['paid'] }}</p>
        </div>
        <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
            <div class="flex items-center justify-between mb-2">
                <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.pending') && trim(__('dashboard.pending')) !== '' ? __('dashboard.pending') : 'Pending' }}</p>
            </div>
            <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['pending'] }}</p>
        </div>
        <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
            <div class="flex items-center justify-between mb-2">
                <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.cancelled') && trim(__('dashboard.cancelled')) !== '' ? __('dashboard.cancelled') : 'Cancelled' }}</p>
            </div>
            <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['cancelled'] }}</p>
        </div>
    </div>
    @endif

    <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-neutral">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('invoices.invoice_id', 'Invoice ID') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('invoices.issue_date', 'Issue Date') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('invoices.due_date', 'Due Date') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('invoices.amount', 'Amount') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('invoices.status', 'Status') }}</th>
                        <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide text-right">{{ translate('invoices.actions', 'Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral">
                    @foreach ($invoices as $invoice)
                        <tr class="hover:bg-background/50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('invoices.show', $invoice) }}" wire:navigate class="text-primary font-semibold hover:underline">
                                    #{{ $invoice->number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-base/60">
                                {{ $invoice->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-base/60">
                                @if($invoice->due_at)
                                    {{ $invoice->due_at->format('M d, Y') }}
                                @else
                                    <span class="text-base/30">{{ translate('common.na', 'N/A') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-lg text-base">{{ $invoice->formattedTotal }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5
                                    @if ($invoice->status == 'paid') text-success bg-success/15 border border-success/40
                                    @elseif($invoice->status == 'cancelled') text-error bg-error/15 border border-error/40
                                    @else text-warning bg-warning/15 border border-warning/40
                                    @endif">
                                    @if ($invoice->status == 'paid')
                                        <x-ri-checkbox-circle-fill class="size-3.5" />
                                    @elseif($invoice->status == 'cancelled')
                                        <x-ri-forbid-fill class="size-3.5" />
                                    @elseif($invoice->status == 'pending')
                                        <x-ri-error-warning-fill class="size-3.5" />
                                    @endif
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('invoices.show', $invoice) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white font-semibold text-xs rounded-lg">
                                    <x-ri-arrow-right-line class="size-3.5" />
                                    {{ translate('invoices.view', 'View') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $invoices->links() }}
    </div>
</div>
