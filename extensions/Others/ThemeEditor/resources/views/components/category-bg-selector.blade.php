@php
    $statePath = $getStatePath();
    $currentValue = $getState() ?? false;
@endphp

<style>
    .te-category-bg-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        width: 100%;
    }

    .te-category-bg-selector > div {
        flex: 0 0 calc(50% - 6px);
    }

    .te-category-bg-option {
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        border: 3px solid rgba(128, 128, 128, 0.3);
        transition: border-color 0.2s;
        width: 100%;
        position: relative;
    }

    .te-category-bg-option:hover {
        border-color: rgba(99, 102, 241, 0.6);
    }

    .te-category-bg-option.te-selected {
        border-color: rgb(99, 102, 241);
    }

    .te-category-bg-option img {
        width: 100%;
        height: auto;
        display: block;
    }

    .te-category-bg-tooltip {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0));
        padding: 32px 12px 10px 12px;
        opacity: 0;
        transition: opacity 0.2s;
        pointer-events: none;
    }

    .te-category-bg-option:hover .te-category-bg-tooltip {
        opacity: 1;
    }

    .te-category-bg-tooltip-title {
        font-weight: 600;
        font-size: 13px;
        color: #fff;
        margin-bottom: 2px;
    }

    .te-category-bg-tooltip-desc {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.3;
    }
</style>

<div 
    x-data="{ state: $wire.entangle('{{ $statePath }}').live ?? {{ $currentValue ? 'true' : 'false' }} }"
    x-init="if (state === null || state === undefined) state = false"
    class="te-category-bg-selector"
>
    <div x-on:click="state = false" class="te-category-bg-option"
        x-bind:class="{ 'te-selected': state === false || state === '0' || state === '' }">
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/without_image.svg"
            alt="Without Category Image" />
        <div class="te-category-bg-tooltip">
            <div class="te-category-bg-tooltip-title">Without Image</div>
            <div class="te-category-bg-tooltip-desc">Standard card without category background</div>
        </div>
    </div>
    <div x-on:click="state = true" class="te-category-bg-option"
        x-bind:class="{ 'te-selected': state === true || state === '1' }">
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/with_image.svg"
            alt="With Category Image" />
        <div class="te-category-bg-tooltip">
            <div class="te-category-bg-tooltip-title">With Image</div>
            <div class="te-category-bg-tooltip-desc">Shows category image at top with gradient overlay</div>
        </div>
    </div>
</div>
