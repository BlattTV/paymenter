@php
    $statePath = $getStatePath();
    $currentValue = $getState() ?? 'style1';
@endphp

<style>
    .te-broadcast-selector {
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: 100%;
    }
    .te-broadcast-option {
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        border: 3px solid rgba(128,128,128,0.3);
        transition: border-color 0.2s;
        width: 100%;
        position: relative;
    }
    .te-broadcast-option:hover {
        border-color: rgba(99, 102, 241, 0.6);
    }
    .te-broadcast-option.te-selected {
        border-color: rgb(99, 102, 241);
    }
    .te-broadcast-option img {
        width: 100%;
        height: auto;
        display: block;
    }
    .te-broadcast-tooltip {
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
    .te-broadcast-option:hover .te-broadcast-tooltip {
        opacity: 1;
    }
    .te-broadcast-tooltip-title {
        font-weight: 600;
        font-size: 14px;
        color: #fff;
        margin-bottom: 4px;
    }
    .te-broadcast-tooltip-desc {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
        line-height: 1.4;
    }
</style>

<div 
    x-data="{ 
        state: '{{ $currentValue }}',
        setState(value) {
            this.state = value;
            $wire.set('{{ $statePath }}', value);
        }
    }"
    class="te-broadcast-selector"
>
    <div 
        x-on:click="setState('style1')" 
        class="te-broadcast-option"
        x-bind:class="{ 'te-selected': state === 'style1' }"
    >
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/alert1.svg" alt="Style 1" />
        <div class="te-broadcast-tooltip">
            <div class="te-broadcast-tooltip-title">Bordered Card</div>
            <div class="te-broadcast-tooltip-desc">Light primary background with 1px border all around.</div>
        </div>
    </div>
    <div 
        x-on:click="setState('style2')" 
        class="te-broadcast-option"
        x-bind:class="{ 'te-selected': state === 'style2' }"
    >
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/alert2.svg" alt="Style 2" />
        <div class="te-broadcast-tooltip">
            <div class="te-broadcast-tooltip-title">Left Accent</div>
            <div class="te-broadcast-tooltip-desc">Light primary background with thick left border accent.</div>
        </div>
    </div>
    <div 
        x-on:click="setState('style3')" 
        class="te-broadcast-option"
        x-bind:class="{ 'te-selected': state === 'style3' }"
    >
        <img src="https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/alert3.svg" alt="Style 3" />
        <div class="te-broadcast-tooltip">
            <div class="te-broadcast-tooltip-title">Solid Fill</div>
            <div class="te-broadcast-tooltip-desc">Full solid primary background with inverted button.</div>
        </div>
    </div>
</div>
