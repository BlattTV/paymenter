@php
    $statePath = $getStatePath();
    $currentValue = $getState() ?? 'default';
@endphp

<style>
    .te-pricing-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        width: 100%;
    }

    .te-pricing-selector>div {
        flex: 0 0 calc(50% - 6px);
    }

    .te-pricing-option {
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        border: 3px solid rgba(128, 128, 128, 0.3);
        transition: border-color 0.2s;
        width: 100%;
        position: relative;
    }

    .te-pricing-option:hover {
        border-color: rgba(99, 102, 241, 0.6);
    }

    .te-pricing-option.te-selected {
        border-color: rgb(99, 102, 241);
    }

    .te-pricing-option img {
        width: 100%;
        height: auto;
        display: block;
    }

    .te-pricing-tooltip {
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

    .te-pricing-option:hover .te-pricing-tooltip {
        opacity: 1;
    }

    .te-pricing-tooltip-title {
        font-weight: 600;
        font-size: 13px;
        color: #fff;
        margin-bottom: 2px;
    }

    .te-pricing-tooltip-desc {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.3;
    }
</style>

<div 
    x-data="{ state: $wire.entangle('{{ $statePath }}').live ?? '{{ $currentValue }}' }"
    x-init="if (!state) state = '{{ $currentValue }}'"
    class="te-pricing-selector"
>
    <div x-on:click="state = 'default'" class="te-pricing-option"
        x-bind:class="{ 'te-selected': state === 'default' }">
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/plan_1.svg?cachebust=1"
            alt="Default Layout" />
        <div class="te-pricing-tooltip">
            <div class="te-pricing-tooltip-title">Default</div>
            <div class="te-pricing-tooltip-desc">Classic card with divider and full-width button</div>
        </div>
    </div>
    <div x-on:click="state = 'compact'" class="te-pricing-option"
        x-bind:class="{ 'te-selected': state === 'compact' }">
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/plan_2.svg?cachebust=1"
            alt="Compact Layout" />
        <div class="te-pricing-tooltip">
            <div class="te-pricing-tooltip-title">Compact</div>
            <div class="te-pricing-tooltip-desc">Price prominent at top, link-style button</div>
        </div>
    </div>
    <div x-on:click="state = 'modern'" class="te-pricing-option" x-bind:class="{ 'te-selected': state === 'modern' }">
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/plan_3.svg?cachebust=1"
            alt="Modern Layout" />
        <div class="te-pricing-tooltip">
            <div class="te-pricing-tooltip-title">Modern</div>
            <div class="te-pricing-tooltip-desc">Accent border on top, centered layout</div>
        </div>
    </div>
    <div x-on:click="state = 'featured'" class="te-pricing-option"
        x-bind:class="{ 'te-selected': state === 'featured' }">
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/plan_4.svg?cachebust=1"
            alt="Featured Layout" />
        <div class="te-pricing-tooltip">
            <div class="te-pricing-tooltip-title">Featured</div>
            <div class="te-pricing-tooltip-desc">Header with price, highlighted background</div>
        </div>
    </div>
    <div x-on:click="state = 'minimal'" class="te-pricing-option"
        x-bind:class="{ 'te-selected': state === 'minimal' }">
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/plan_5.svg?cachebust=1"
            alt="Minimal Layout" />
        <div class="te-pricing-tooltip">
            <div class="te-pricing-tooltip-title">Minimal</div>
            <div class="te-pricing-tooltip-desc">Clean design with subtle hover effects</div>
        </div>
    </div>
</div>