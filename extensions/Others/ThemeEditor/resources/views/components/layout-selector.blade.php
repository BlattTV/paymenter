@php
    $statePath = $getStatePath();
    $currentValue = $getState() ?? 'default';
@endphp

<style>
    .te-layout-selector {
        display: flex;
        flex-direction: column;
        gap: 16px;
        width: 100%;
    }
    .te-layout-option {
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        border: 3px solid rgba(128,128,128,0.3);
        transition: border-color 0.2s;
        width: 100%;
        position: relative;
    }
    .te-layout-option:hover {
        border-color: rgba(99, 102, 241, 0.6);
    }
    .te-layout-option.te-selected {
        border-color: rgb(99, 102, 241);
    }
    .te-layout-option img {
        width: 100%;
        height: auto;
        display: block;
    }
    .te-layout-tooltip {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85), rgba(0,0,0,0));
        padding: 32px 16px 12px 16px;
        opacity: 0;
        transition: opacity 0.2s;
        pointer-events: none;
    }
    .te-layout-option:hover .te-layout-tooltip {
        opacity: 1;
    }
    .te-layout-tooltip-title {
        font-weight: 600;
        font-size: 14px;
        color: #fff;
        margin-bottom: 4px;
    }
    .te-layout-tooltip-desc {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
        line-height: 1.4;
    }
</style>

<div 
    x-data="{ state: $wire.entangle('{{ $statePath }}').live ?? '{{ $currentValue }}' }"
    x-init="if (!state) state = '{{ $currentValue }}'"
    class="te-layout-selector"
>
    <div 
        x-on:click="state = 'default'" 
        class="te-layout-option"
        x-bind:class="{ 'te-selected': state === 'default' }"
    >
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/1.svg" alt="Default Layout" />
        <div class="te-layout-tooltip">
            <div class="te-layout-tooltip-title">Default</div>
            <div class="te-layout-tooltip-desc">Standard layout with a sidebar on the left side within a contained width.</div>
        </div>
    </div>
    <div 
        x-on:click="state = 'wide'" 
        class="te-layout-option"
        x-bind:class="{ 'te-selected': state === 'wide' }"
    >
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/2.svg" alt="Wide Layout" />
        <div class="te-layout-tooltip">
            <div class="te-layout-tooltip-title">Wide</div>
            <div class="te-layout-tooltip-desc">Maximises screen space with a fixed sidebar and wider content area.</div>
        </div>
    </div>
    <div 
        x-on:click="state = 'header'" 
        class="te-layout-option"
        x-bind:class="{ 'te-selected': state === 'header' }"
    >
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/3.svg" alt="Header Layout" />
        <div class="te-layout-tooltip">
            <div class="te-layout-tooltip-title">Header</div>
            <div class="te-layout-tooltip-desc">Modern horizontal navigation bar replacing the sidebar.</div>
        </div>
    </div>
</div>
