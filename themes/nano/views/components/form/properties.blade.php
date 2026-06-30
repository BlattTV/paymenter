@props(['properties', 'custom_properties' => [], 'hideLabels' => false, 'withSecondaryBg' => false])

@php
    $gridFields = ['company_name', 'phone', 'city', 'country', 'state', 'zip'];
    $groupedProperties = [];
    $regularProperties = [];
    
    foreach ($custom_properties as $property) {
        if (in_array($property->key, $gridFields)) {
            $groupedProperties[$property->key] = $property;
        } else {
            $regularProperties[] = $property;
        }
    }
@endphp

@if(isset($groupedProperties['company_name']))
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    @if(isset($groupedProperties['company_name']))
        @php $property = $groupedProperties['company_name']; @endphp
        <x-form.input :withSecondaryBg="$withSecondaryBg" :type="$property->type ?? 'text'" name="properties.{{ $property->key }}" :label="$property->name" :required="$property->required"
            wire:model="properties.{{ $property->key }}" :value="$properties[$property->key] ?? ''" labelSize="sm" />
    @endif
</div>
@endif

@if(isset($groupedProperties['state']) || isset($groupedProperties['zip']))
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    @if(isset($groupedProperties['state']))
        @php $property = $groupedProperties['state']; @endphp
        @if(($property->type ?? 'text') === 'select')
            <x-form.select :withSecondaryBg="$withSecondaryBg" name="properties.{{ $property->key }}" :label="$property->name"
                wire:model="properties.{{ $property->key }}" :required="$property->required" :options="$property->allowed_values" :selected="$properties[$property->key] ?? ''" labelSize="sm" />
        @else
            <x-form.input :withSecondaryBg="$withSecondaryBg" :type="$property->type ?? 'text'" name="properties.{{ $property->key }}" :label="$property->name" :required="$property->required"
                wire:model="properties.{{ $property->key }}" :value="$properties[$property->key] ?? ''" labelSize="sm" />
        @endif
    @endif
    @if(isset($groupedProperties['zip']))
        @php $property = $groupedProperties['zip']; @endphp
        @if(($property->type ?? 'text') === 'select')
            <x-form.select :withSecondaryBg="$withSecondaryBg" name="properties.{{ $property->key }}" :label="$property->name"
                wire:model="properties.{{ $property->key }}" :required="$property->required" :options="$property->allowed_values" :selected="$properties[$property->key] ?? ''" labelSize="sm" />
        @else
            <x-form.input :withSecondaryBg="$withSecondaryBg" :type="$property->type ?? 'text'" name="properties.{{ $property->key }}" :label="$property->name" :required="$property->required"
                wire:model="properties.{{ $property->key }}" :value="$properties[$property->key] ?? ''" labelSize="sm" />
        @endif
    @endif
</div>
@endif

@if(isset($groupedProperties['city']) || isset($groupedProperties['country']))
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    @if(isset($groupedProperties['city']))
        @php $property = $groupedProperties['city']; @endphp
        @if(($property->type ?? 'text') === 'select')
            <x-form.select :withSecondaryBg="$withSecondaryBg" name="properties.{{ $property->key }}" :label="$property->name"
                wire:model="properties.{{ $property->key }}" :required="$property->required" :options="$property->allowed_values" :selected="$properties[$property->key] ?? ''" labelSize="sm" />
        @else
            <x-form.input :withSecondaryBg="$withSecondaryBg" :type="$property->type ?? 'text'" name="properties.{{ $property->key }}" :label="$property->name" :required="$property->required"
                wire:model="properties.{{ $property->key }}" :value="$properties[$property->key] ?? ''" labelSize="sm" />
        @endif
    @endif
    @if(isset($groupedProperties['country']))
        @php $property = $groupedProperties['country']; @endphp
        @if(($property->type ?? 'text') === 'select')
            <x-form.select :withSecondaryBg="$withSecondaryBg" name="properties.{{ $property->key }}" :label="$property->name"
                wire:model="properties.{{ $property->key }}" :required="$property->required" :options="$property->allowed_values" :selected="$properties[$property->key] ?? ''" labelSize="sm" />
        @else
            <x-form.input :withSecondaryBg="$withSecondaryBg" :type="$property->type ?? 'text'" name="properties.{{ $property->key }}" :label="$property->name" :required="$property->required"
                wire:model="properties.{{ $property->key }}" :value="$properties[$property->key] ?? ''" labelSize="sm" />
        @endif
    @endif
</div>
@endif

@foreach ($regularProperties as $property)
    @switch($property->type)
        @case('date')
        @case('string')

        @case('number')
            @if($hideLabels)
            <div>
                <label class="text-xs text-base/60 ml-1 mt-4 block">{{ $property->name }}</label>
                <x-form.input :withSecondaryBg="$withSecondaryBg" :type="$property->type" name="properties.{{ $property->key }}" :placeholder="$property->name" :required="$property->required"
                    wire:model="properties.{{ $property->key }}" :value="$properties[$property->key] ?? ''" label="" />
            </div>
            @else
            <x-form.input :withSecondaryBg="$withSecondaryBg" :type="$property->type" name="properties.{{ $property->key }}" :label="$property->name" :required="$property->required"
                wire:model="properties.{{ $property->key }}" :value="$properties[$property->key] ?? ''" labelSize="sm" />
            @endif
        @break

        @case('checkbox')
            <x-form.checkbox name="properties.{{ $property->key }}" :label="$property->name" :required="$property->required"
                wire:model="properties.{{ $property->key }}" :checked="$properties[$property->key] ?? false" />
        @break

        @case('radio')
            <div class="text-sm font-medium text-base/90 ml-0.5 mb-3">{{ $property->name }}</div>
            @foreach ($property->allowed_values as $value)
                <div class="flex items-center gap-3 mb-2">
                    <input type="radio" value="{{ $value }}" name="properties.{{ $property->key }}" type="radio"
                        @checked($properties[$property->key] === $value ?? false) label="{{ $value }}" @required($property->required)
                        wire:model="properties.{{ $property->key }}"
                        class="form-radio size-4 text-primary rounded-full focus:ring-primary focus:ring-2 focus:ring-offset-0 border-neutral transition-all duration-200 ease-out cursor-pointer" />
                    <label class="text-sm text-base/90 cursor-pointer"
                        for="properties.{{ $property->key }}">{{ $value }}</label>
                </div>
            @endforeach
        @break

        @case('select')
            @if($hideLabels)
            <div>
                <label class="text-xs text-base/60 ml-1 mt-4 block">{{ $property->name }}</label>
                <x-form.select :withSecondaryBg="$withSecondaryBg" name="properties.{{ $property->key }}" label=""
                    wire:model="properties.{{ $property->key }}" :required="$property->required" :options="$property->allowed_values" :selected="$properties[$property->key] ?? ''" />
            </div>
            @else
            <x-form.select :withSecondaryBg="$withSecondaryBg" name="properties.{{ $property->key }}" :label="$property->name"
                wire:model="properties.{{ $property->key }}" :required="$property->required" :options="$property->allowed_values" :selected="$properties[$property->key] ?? ''" />
            @endif
        @break

        @case('text')
            @if($hideLabels)
            <div>
                <label class="text-xs text-base/60 ml-1 mt-2 block">{{ $property->name }}</label>
                <x-form.textarea :withSecondaryBg="$withSecondaryBg" :type="$property->type" name="properties.{{ $property->key }}" :placeholder="$property->name" :required="$property->required"
                    wire:model="properties.{{ $property->key }}" label="">{{ $properties[$property->key] ?? '' }}</x-form.textarea>
            </div>
            @else
            <x-form.textarea :withSecondaryBg="$withSecondaryBg" :type="$property->type" name="properties.{{ $property->key }}" :label="$property->name" :required="$property->required"
                wire:model="properties.{{ $property->key }}">{{ $properties[$property->key] ?? '' }}</x-form.textarea>
            @endif
        @break

        @default
    @endswitch
@endforeach
