@php
    $statusPillClasses = match ($ticket->status) {
        'open' => 'text-success bg-success/15 border border-success/40',
        'closed' => 'text-error bg-error/15 border border-error/40',
        default => 'text-warning bg-warning/15 border border-warning/40',
    };

    $statusDotClasses = match ($ticket->status) {
        'open' => 'bg-success',
        'closed' => 'bg-error',
        default => 'bg-warning',
    };

    $statusDotPingClasses = match ($ticket->status) {
        'open' => 'bg-success/70',
        'closed' => 'bg-error/60',
        default => 'bg-warning/60',
    };
@endphp

<div class="flex flex-col h-[calc(100vh-11rem)] w-full gap-0 md:gap-4">
    <div class="bg-background-secondary md:rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border-b md:border border-neutral p-4 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
        <div class="flex flex-col gap-1.5">
            <div class="flex items-center gap-2 text-xs md:text-sm text-base/60">
                <span class="font-mono text-primary font-bold bg-primary/10 px-1.5 py-0.5 rounded">#TKT-{{ $ticket->id }}</span>
                <span class="text-base/20">|</span>
                <span class="flex items-center gap-1">
                    <i class="fa-regular fa-calendar text-[14px]"></i>
                    {{ $ticket->created_at->format('j M Y, H:i') }}
                </span>
            </div>
            <h1 class="text-xl md:text-2xl font-bold text-base tracking-tight leading-snug">{{ $ticket->subject }}</h1>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-base/60">
                <span class="inline-flex items-center gap-2">
                    <i class="fa-solid fa-flag text-[12px]"></i>
                    {{ ucfirst($ticket->priority) }}
                </span>
                @if ($ticket->department)
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-[12px]"></i>
                        {{ $ticket->department }}
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $statusPillClasses }}">
                <span class="relative flex size-2.5">
                    @if ($ticket->status === 'open')
                        <span class="absolute inline-flex size-full rounded-full {{ $statusDotPingClasses }} opacity-75 animate-ping"></span>
                    @endif
                    <span class="relative inline-flex rounded-full size-2.5 {{ $statusDotClasses }}"></span>
                </span>
                <span class="text-xs font-bold uppercase tracking-wide">{{ ucfirst($ticket->status) }}</span>
            </div>

            <div class="h-8 w-px bg-neutral/50 hidden md:block"></div>

            @if ($ticket->status !== 'closed' && !config('settings.ticket_client_closing_disabled', false))
                <button
                    type="button"
                    wire:click="closeTicket"
                    wire:confirm="{{ translate('ticket.close_ticket_confirm', 'Are you sure you want to close this ticket?') }}"
                    class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-error hover:bg-error/90 rounded-[var(--button-radius)] transition-all"
                >
                    <i class="fa-solid fa-xmark text-[14px]"></i>
                    <span>{{ translate('ticket.close_ticket', 'Close ticket') }}</span>
                </button>
            @endif
        </div>
    </div>

    <div class="flex-1 bg-background-secondary md:rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border-x md:border border-neutral flex flex-col min-h-0">
        <div class="flex-1 overflow-y-auto p-4 md:p-8 flex flex-col gap-2 bg-background/40 min-h-0" wire:poll.5s>
        @php
    $lastDateKey = null;
@endphp

@php
    $isClientMessage = false;
@endphp


            @foreach ($ticket->messages()->with(['user', 'attachments'])->orderBy('created_at')->get() as $message)
               @php
    $dateKey = $message->created_at->toDateString();
@endphp


                @if ($dateKey !== $lastDateKey)
                    <div class="flex justify-center my-2">
                        <span class="px-3 py-1 bg-background border border-neutral/50 text-base/60 text-[10px] font-semibold rounded-full uppercase tracking-wider">
                            @if ($message->created_at->isToday())
                                {{ translate('ticket.today', 'Today') }}
                            @elseif ($message->created_at->isYesterday())
                                {{ translate('ticket.yesterday', 'Yesterday') }}
                            @else
                                {{ $message->created_at->format('j M Y') }}
                            @endif
                        </span>
                    </div>
                @endif

                @php
    $lastDateKey = $dateKey;
@endphp

             @php
    $isClientMessage = $message->user_id === $ticket->user_id;
@endphp


                @if ($isClientMessage)
                    <div class="flex flex-col gap-1 items-end self-end max-w-[90%] md:max-w-[75%] group" @if ($loop->last) x-data x-init="$el.scrollIntoView()" @endif>
                        <div class="bg-black dark:bg-white text-white dark:text-black px-5 py-3.5 rounded-2xl rounded-tr-sm shadow-sm shadow-primary/10">
                            <div class="prose prose-sm max-w-none dark:prose-invert break-words overflow-x-auto text-white dark:text-black">
                                {!! Str::markdown(e($message->message), [
                                    'allow_unsafe_links' => false,
                                    'renderer' => [
                                        'soft_break' => "<br>",
                                    ],
                                ]) !!}
                            </div>

                            @if ($message->attachments->count() > 0)
                                <div class="mt-3 space-y-2">
                                    @foreach ($message->attachments as $attachment)
                                        @php
                                            $attachmentSize = $attachment->filesize >= 1024 * 1024
                                                ? number_format($attachment->filesize / 1024 / 1024, 2) . ' MB'
                                                : number_format(max(1, $attachment->filesize / 1024), 0) . ' KB';
                                        @endphp

                                        @if ($attachment->canPreview())
                                            <a href="{{ route('tickets.attachments.show', $attachment) }}" target="_blank" class="block">
                                                <img src="{{ route('tickets.attachments.show', $attachment) }}" alt="{{ $attachment->filename }}" class="max-w-xs rounded-lg border border-white/15 dark:border-black/15">
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('tickets.attachments.show', $attachment) }}"
                                                class="flex items-center gap-3 p-2 rounded-lg bg-background/10 border border-white/15 dark:border-black/15 hover:bg-background/15 transition-colors max-w-xs"
                                            >
                                                <div class="bg-white/10 p-1.5 rounded border border-white/15 text-white">
                                                    <i class="fa-solid fa-paperclip text-[16px]"></i>
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="text-xs font-medium truncate text-white">{{ $attachment->filename }}</span>
                                                    <span class="text-[10px] text-white/70">{{ $attachmentSize }}</span>
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-1 pr-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-[10px] text-base/60">{{ translate('ticket.you', 'You') }} • {{ $message->created_at->format('H:i') }}</span>
                            <i class="fa-solid fa-check-double text-[12px] text-primary"></i>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col gap-1 items-start self-start max-w-[90%] md:max-w-[75%] group" @if ($loop->last) x-data x-init="$el.scrollIntoView()" @endif>
                        <div class="flex items-end gap-3">
                            <div class="size-9 rounded-full bg-background flex items-center justify-center text-primary shrink-0 border border-neutral shadow-sm">
                                <i class="fa-solid fa-headset text-[16px]"></i>
                            </div>

                            <div class="bg-background-secondary border border-neutral text-base px-5 py-3.5 rounded-2xl rounded-tl-sm shadow-sm">
                                <div class="prose dark:prose-invert prose-sm max-w-none break-words overflow-x-auto">
                                    {!! Str::markdown(e($message->message), [
                                        'allow_unsafe_links' => false,
                                        'renderer' => [
                                            'soft_break' => "<br>",
                                        ],
                                    ]) !!}
                                </div>

                                @if ($message->attachments->count() > 0)
                                    <div class="mt-3 space-y-2">
                                        @foreach ($message->attachments as $attachment)
                                            @php
                                                $attachmentSize = $attachment->filesize >= 1024 * 1024
                                                    ? number_format($attachment->filesize / 1024 / 1024, 2) . ' MB'
                                                    : number_format(max(1, $attachment->filesize / 1024), 0) . ' KB';
                                            @endphp

                                            @if ($attachment->canPreview())
                                                <a href="{{ route('tickets.attachments.show', $attachment) }}" target="_blank" class="block">
                                                    <img src="{{ route('tickets.attachments.show', $attachment) }}" alt="{{ $attachment->filename }}" class="max-w-xs rounded-lg border border-neutral">
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('tickets.attachments.show', $attachment) }}"
                                                    class="flex items-center gap-3 p-2 rounded-lg bg-background border border-neutral hover:bg-background/70 transition-colors max-w-xs"
                                                >
                                                    <div class="bg-background-secondary p-1.5 rounded border border-neutral text-primary">
                                                        <i class="fa-regular fa-file-lines text-[16px]"></i>
                                                    </div>
                                                    <div class="flex flex-col min-w-0">
                                                        <span class="text-xs font-medium truncate text-base">{{ $attachment->filename }}</span>
                                                        <span class="text-[10px] text-base/60">{{ $attachmentSize }}</span>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <span class="text-[10px] text-base/60 pl-12 opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ $message->user->name }} • {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="shrink-0 p-4 md:p-3 bg-background-secondary border-t border-neutral rounded-b-2xl" x-data="{
            selectedFiles: [],
            previews: [],
            isImage(file) {
                return file.type.startsWith('image/');
            },
            handleFiles(event) {
                const files = Array.from(event.target.files);
                this.selectedFiles = files;
                this.previews = [];
                files.forEach((file, index) => {
                    if (this.isImage(file)) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews[index] = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        this.previews[index] = null;
                    }
                });
            },
            removeFile(index) {
                this.selectedFiles.splice(index, 1);
                this.previews.splice(index, 1);
                const dt = new DataTransfer();
                this.selectedFiles.forEach(f => dt.items.add(f));
                this.$refs.fileInput.files = dt.files;
                this.$wire.set('attachments', null);
                if (this.selectedFiles.length > 0) {
                    this.$refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            },
            clearPreviews() {
                this.selectedFiles = [];
                this.previews = [];
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            },
            init() {
                Livewire.on('saved', () => {
                    this.clearPreviews();
                });
            }
        }">
            <form wire:submit.prevent="save">
                <div class="flex flex-col gap-2">
                    <template x-if="selectedFiles.length > 0">
                        <div class="flex flex-wrap gap-2 p-2 bg-background rounded-lg border border-neutral mb-2">
                            <template x-for="(file, index) in selectedFiles" :key="index">
                                <div class="relative group">
                                    <template x-if="previews[index]">
                                        <img :src="previews[index]" class="h-16 w-16 object-cover rounded-lg border border-neutral">
                                    </template>
                                    <template x-if="!previews[index]">
                                        <div class="h-16 w-16 flex flex-col items-center justify-center rounded-lg border border-neutral bg-background-secondary text-base/60">
                                            <i class="fa-regular fa-file text-[18px]"></i>
                                            <span class="text-[8px] mt-1 px-1 truncate max-w-full" x-text="file.name.split('.').pop()"></span>
                                        </div>
                                    </template>
                                    <button type="button" @click="removeFile(index)" class="absolute -top-1.5 -right-1.5 size-5 flex items-center justify-center bg-error text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                    </button>
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[8px] px-1 py-0.5 rounded-b-lg truncate" x-text="file.name"></div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <div class="flex items-end gap-3 bg-background-secondary relative">
                        <div class="absolute left-2 top-12 flex items-center pb-1 pl-1 w-10 z-10">
                            <label for="attachments" class="cursor-pointer p-2 text-base/60 hover:text-primary rounded-xl">
                                <i class="fa-solid fa-circle-plus text-[20px]"></i>
                            </label>
                        </div>

                        <div class="w-full px-1" wire:ignore>
                            <textarea id="editor"></textarea>
                        </div>

                            <button type="submit" class="flex items-center justify-center absolute right-4 top-13 w-8 h-8 p-2 bg-primary text-white rounded-xl">
                              <i class="rotate-45 fa-solid fa-location-arrow"></i>
                            </button>
                    </div>

                    <input id="attachments" type="file" multiple class="sr-only" wire:model.live="attachments" x-ref="fileInput" x-on:change="handleFiles($event)">
                </div>
            </form>

            <x-easymde-editor />
        </div>
    </div>
</div>
