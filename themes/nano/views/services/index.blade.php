@php
use App\Models\Service;
use Illuminate\Support\Facades\Auth;


        $userId = Auth::id();
        $query = Service::where('user_id', $userId);
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $statusCounts = [
            'active' => Service::where('user_id', $userId)->where('status', 'active')->count(),
            'pending' => Service::where('user_id', $userId)->where('status', 'pending')->count(),
            'suspended' => Service::where('user_id', $userId)->where('status', 'suspended')->count(),
            'cancelled' => Service::where('user_id', $userId)->where('status', 'cancelled')->count(),
            'closed' => Service::where('user_id', $userId)->where('status', 'closed')->count(),
        ];
        $services = $query->latest()->paginate(config('settings.pagination'));

@endphp


<div>
    <div>
       @if(isset($statusCounts) && $statusCounts !== null)
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-5 mb-6">
            <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.active') && trim(__('dashboard.active')) !== '' ? __('dashboard.active') : 'Active' }}</p>
                </div>
                <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['active'] }}</p>
            </div>
            <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.pending') && trim(__('dashboard.pending')) !== '' ? __('dashboard.pending') : 'Pending' }}</p>
                </div>
                <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['pending'] }}</p>
            </div>
            <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ Lang::has('dashboard.suspended') && trim(__('dashboard.suspended')) !== '' ? __('dashboard.suspended') : 'Suspended' }}</p>
                </div>
                <p class="text-base text-2xl font-bold tracking-tight">{{ $statusCounts['suspended'] }}</p>
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
                            <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('services.service_id', 'Service ID') }}</th>
                            <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('services.product', 'Product') }}</th>
                            <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('services.plan', 'Plan') }}</th>
                            <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('services.billing', 'Billing') }}</th>
                            <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('services.status', 'Status') }}</th>
                            <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide">{{ translate('services.next_renewal', 'Next Renewal') }}</th>
                            <th class="px-6 py-4 text-xs font-semibold text-base/50 uppercase tracking-wide text-right">{{ translate('services.actions', 'Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral">
                        @foreach ($services as $service)
                            <tr class="hover:bg-background/50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('services.show', $service) }}" wire:navigate class="text-primary font-semibold hover:underline">
                                        #{{ $service->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-base">{{ $service->product->name }}</div>
                                    <div class="text-sm text-base/60">{{ $service->product->category->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-base/60">
                                    {{ $service->plan->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-base/60">
                                    @if(in_array($service->plan->type, ['recurring']))
                                        {{ translate('services.every_period', 'Every Period', [
                                            'period' => $service->plan->billing_period > 1 ? $service->plan->billing_period : '',
                                            'unit' => trans_choice(translate('services.billing_cycles.' . $service->plan->billing_unit), $service->plan->billing_period)
                                        ]) }}
                                    @else
                                        {{ translate('services.one_time', 'One-Time') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5
                                        @if ($service->status == 'active') text-success bg-success/15 border border-success/40
                                        @elseif($service->status == 'suspended') text-inactive bg-inactive/15 border border-inactive/40
                                        @elseif($service->status == 'cancelled') text-error bg-error/15 border border-error/40
                                        @else text-warning bg-warning/15 border border-warning/40
                                        @endif">
                                        @if ($service->status == 'active')
                                            <x-ri-checkbox-circle-fill class="size-3.5" />
                                        @elseif($service->status == 'suspended' || $service->status == 'cancelled')
                                            <x-ri-forbid-fill class="size-3.5" />
                                        @elseif($service->status == 'pending')
                                            <x-ri-error-warning-fill class="size-3.5" />
                                        @endif
                                        {{ ucfirst($service->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-base/60">
                                    @if($service->expiry_date)
                                        {{ $service->expiry_date->format('M d, Y') }}
                                    @else
                                        <span class="text-base/30">{{ translate('common.na', 'N/A') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('services.show', $service) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white font-semibold text-xs rounded-lg">
                                        <x-ri-arrow-right-line class="size-3.5" />
                                        {{ translate('services.view', 'View') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
</div>
