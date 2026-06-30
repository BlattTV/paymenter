<div class="space-y-4">
<div class="flex items-center gap-2 mb-6">
    <a href="{{ route('dashboard') }}" class="text-base/70 hover:text-primary transition">
        <x-ri-home-4-line class="size-5" />
    </a>

    <x-ri-arrow-right-s-line class="size-4 text-base/30" />

    <span class="text-base text-base">
        {{ translate('ticket.create_ticket', 'Create Ticket') }}
    </span>
</div>
    
    <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden">
        <div class="p-6">
            <div class="mb-6">
                    <h1 class="text-2xl font-bold mb-1">{{ translate('ticket.create_ticket', 'Create Ticket') }}</h1>
                    <p class="text-base/60 text-sm">{{ translate('ticket.submit_ticket_description', 'Submit a ticket to get help with your issue') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <x-form.input :withSecondaryBg="false" wire:model="subject" label="{{ translate('ticket.subject', 'Subject') }}" name="subject" required />
                </div>
                
                @if (count($departments) > 0)
                    <div>
                        <x-form.select :withSecondaryBg="false" wire:model="department" label="{{ translate('ticket.department', 'Department') }}" name="department" required>
                            <option value="">{{ translate('ticket.select_department', 'Select Department') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                @endif
                
                <div>
                    <x-form.select :withSecondaryBg="false" wire:model="priority" label="{{ translate('ticket.priority', 'Priority') }}" name="priority" required>
                        <option value="">{{ translate('ticket.select_priority', 'Select Priority') }}</option>
                        <option value="low" selected>{{ translate('ticket.low', 'Low') }}</option>
                        <option value="medium">{{ translate('ticket.medium', 'Medium') }}</option>
                        <option value="high">{{ translate('ticket.high', 'High') }}</option>
                    </x-form.select>
                </div>
                
                <div>
                    <x-form.select :withSecondaryBg="false" wire:model="service" label="{{ translate('ticket.service', 'Service') }}" name="service">
                        <option value="">{{ translate('ticket.select_service', 'Select Service') }}</option>
                        @foreach ($services as $product)
                            <option value="{{ $product->id }}">{{ $product->product->name }} ({{ ucfirst($product->status) }})
                                @if ($product->expires_at)
                                    - {{ $product->expires_at->format('Y-m-d') }}
                                @endif
                            </option>
                        @endforeach
                    </x-form.select>
                </div>
            </div>

            <div class="border-t border-neutral/50 pt-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">{{ translate('ticket.reply', 'Reply') }}</label>
                    <div class="bg-background/50 rounded-xl border border-neutral/50 p-1">
                        <form wire:submit.prevent="create" wire:ignore>
                            <textarea id="editor" placeholder="{{ translate('ticket.describe_issue', 'Describe your issue') }}"></textarea>
                        </form>
                        <x-easymde-editor />
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button wire:click="create" class="cursor-pointer px-6 py-2 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-colors flex items-center gap-2">
                        <x-ri-send-plane-fill class="size-4" />
                        {{ translate('ticket.create', 'Create') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
