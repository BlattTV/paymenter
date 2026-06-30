<div class="flex flex-col gap-1">
    @switch($config->type)
        @case('select')
            <x-form.select name="{{ $name }}" :label="__($config->label ?? $config->name)" :required="$config->required ?? false"
                :selected="config('configs.' . $config->name)" :multiple="$config->multiple ?? false"
                wire:model.live="{{ $name }}" :placeholder="$config->placeholder ?? ''">
                {{ $slot }}
            </x-form.select>
        @break

        @case('slider')
            <div x-data="{
                options: @js($config->children->map(fn($child) => ['option' => $child->name, 'value' => $child->id, 'price' => ($showPriceTag && $child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? (string)$child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : ''])),
                showPriceTag: @js($showPriceTag),
                selectedOption: 0,
                backendOption: $wire.entangle('{{ $name }}').live,
                progressOption: '0%',
                segmentsWidthOption: '0%',

                init() {
                    const initialValue = this.$wire.get('{{ $name }}');
                    const foundIndex = this.options.findIndex(plan => plan.value == initialValue);
                    if (foundIndex !== -1) {
                        this.selectedOption = foundIndex;
                    }
                    this.updateSliderVisuals();
                    $watch('selectedOption', Alpine.debounce(() => this.backendOption = this.options[this.selectedOption].value, 300))
                },

                updateSliderVisuals() {
                    this.progressOption = `${(this.selectedOption / (this.options.length - 1)) * 100}%`;
                    this.segmentsWidthOption = `${100 / (this.options.length - 1)}%`;
                },

                setOptionValue(index) {
                    this.selectedOption = parseInt(index);
                    this.updateSliderVisuals();
                }
            }" class="flex flex-col gap-1 relative">
                <label for="{{ $name }}"
                    class="text-lg font-semibold text-base mb-4">
                    {{ $config->label ?? $config->name }}
                </label>
                <div class="ml-2 relative flex items-center" :style="`--progress:${progressOption};--segments-width:${segmentsWidthOption}`" wire:ignore>
                    <div class="
                        absolute left-2.5 right-2.5 h-2 bg-background-secondary rounded-full transition-all duration-300 ease-out
                        before:absolute before:inset-0 before:bg-primary before:rounded-full
                        before:[mask-image:_linear-gradient(to_right,theme(colors.white),theme(colors.white)_var(--progress),transparent_var(--progress))]
                        [&[x-cloak]]:hidden" aria-hidden="true" x-cloak></div>
                    <input class="
                        relative appearance-none cursor-pointer w-full bg-transparent focus:outline-none transition-all duration-300 ease-out
                        [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:w-5
                        [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-4 [&::-webkit-slider-thumb]:border-primary
                        [&::-webkit-slider-thumb]:focus:ring-0 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:w-5
                        [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:border-none
                        [&::-moz-range-thumb]:border-4 [&::-moz-range-thumb]:border-primary [&::-moz-range-thumb]:focus:ring-0
                    " type="range" min="0" :max="options.length - 1" x-model="selectedOption" @input="setOptionValue(selectedOption)" aria-label="{{ __('form.option_slider') }}" name="{{ $name }}" id="{{ $name }}" />
                </div>
                <ul class="ml-2 flex justify-between text-xs font-medium text-light px-2.5">
                    @foreach($config->children as $child)
                        <li class="relative @if($showPriceTag) pb-7 @else pb-2 @endif">
                            <button @click="setOptionValue({{ $loop->index }})" class="absolute flex flex-col items-center -translate-x-1/2">
                                <span class="text-sm font-semibold">
                                    {{ $child->name }}
                                </span>
                                @if($showPriceTag)
                                    <span class="text-sm font-semibold hidden lg:inline">
                                        {{ ($showPriceTag && $child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? $child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '' }}
                                    </span>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        @break

        @case('text')
        @case('password')

        @case('email')
            <x-form.input :withSecondaryBg="true" name="{{ $name }}" :type="$config->type" :label="__($config->label ?? $config->name)"
                :placeholder="$config->default ?? ''" :required="$config->required ?? false" wire:model.live="{{ $name }}" :placeholder="$config->placeholder ?? ''" />
        @break

        @case('number')
            <div x-data="{
                value: $wire.entangle('{{ $name }}').live,
                min: {{ $config->min ?? 0 }},
                max: {{ $config->max ?? 'null' }},
                step: {{ $config->step ?? 1 }},
                
                increment() {
                    const currentValue = parseFloat(this.value) || 0;
                    const newValue = currentValue + this.step;
                    if (this.max === null || newValue <= this.max) {
                        this.value = newValue;
                    } else if (this.max !== null) {
                        this.value = this.max;
                    }
                },
                
                decrement() {
                    const currentValue = parseFloat(this.value) || 0;
                    const newValue = currentValue - this.step;
                    if (this.min === null || newValue >= this.min) {
                        this.value = newValue;
                    } else if (this.min !== null) {
                        this.value = this.min;
                    }
                },
                
                handleInput(event) {
                    const inputValue = parseFloat(event.target.value);
                    if (!isNaN(inputValue)) {
                        if (this.min !== null && inputValue < this.min) {
                            this.value = this.min;
                        } else if (this.max !== null && inputValue > this.max) {
                            this.value = this.max;
                        } else {
                            this.value = inputValue;
                        }
                    } else if (event.target.value === '' || event.target.value === '-') {
                        this.value = this.min !== null ? this.min : 0;
                    }
                }
            }" class="flex flex-col gap-1">
                <div class="flex items-center justify-between">
                    <label for="{{ $name }}" class="text-lg font-medium text-base/90 whitespace-nowrap">
                        {{ __($config->label ?? $config->name) }}
                        @if($config->required ?? false)
                            <span class="text-error">*</span>
                        @endif
                    </label>
                    <div class="flex items-center bg-background-secondary rounded-[var(--input-radius)] border border-neutral overflow-hidden">
                        <button type="button" @click="decrement()" class="flex items-center font-bold justify-center w-10 h-10 text-white text-inverted bg-primary hover:opacity-90 transition-opacity duration-200 disabled:opacity-50 disabled:cursor-not-allowed" :disabled="(parseFloat(value) || 0) <= min" aria-label="{{ __('form.decrease') }}">
                            <span>−</span>
                        </button>
                        <input type="number" 
                            id="{{ $name }}" 
                            name="{{ $name }}"
                            x-model="value"
                            @input="handleInput($event)"
                            min="{{ $config->min ?? 0 }}"
                            :max="$config->max ?? null"
                            :step="$config->step ?? 1"
                            :required="$config->required ?? false"
                            style="border: none; outline: none; box-shadow: none;"
                            class="w-16 text-center text-base bg-transparent focus:ring-0 px-2 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        <button type="button" @click="increment()" class="flex items-center font-bold justify-center w-10 h-10 text-white text-inverted bg-primary hover:opacity-90 transition-opacity duration-200 disabled:opacity-50 disabled:cursor-not-allowed" :disabled="max !== null && (parseFloat(value) || 0) >= max" aria-label="{{ __('form.increase') }}">
                            <span>+</span>
                        </button>
                    </div>
                </div>
                @error($name)
                    <p class="text-error text-xs mt-1.5 ml-0.5">{{ $message }}</p>
                @enderror
            </div>
        @break

        @case('color')
        @case('file')
            <x-form.input :withSecondaryBg="true" name="{{ $name }}" :type="$config->type" :label="__($config->label ?? $config->name)"
                :placeholder="$config->default ?? ''" :required="$config->required ?? false" wire:model.live="{{ $name }}" :placeholder="$config->placeholder ?? ''" />
        @break

        @case('checkbox')
            <x-form.checkbox name="{{ $name }}" type="checkbox" :label="__($config->label ?? $config->name) . (($showPriceTag && $config->children->first()->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? ' - ' . $config->children->first()->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '')"
                :required="$config->required ?? false" wire:model.live="{{ $name }}" />
        @break

        @case('radio')
            <x-form.radio name="{{ $name }}" :label="__($config->label ?? $config->name)" :required="$config->required ?? false" wire:model.live="{{ $name }}">
                {{  $slot }}
            </x-form.radio>
        @break

        @default
    @endswitch
    @isset($config->description)
        @isset($config->link)
            <a href="{{ $config->link }}" class="text-xs text-muted hover:text-primary group transition-colors duration-200">
                {{ $config->description }}
                <x-ri-arrow-right-long-line class="ml-1 size-3 inline-block -rotate-45 group-hover:rotate-0 transition-transform duration-200" />
            </a>
        @else
            <p class="text-xs text-muted">{{ $config->description }}</p>
        @endisset
    @endisset
</div>
