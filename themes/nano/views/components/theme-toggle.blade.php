@if(theme('force_theme_mode', 'none') === 'none')
<button @click="darkMode = !darkMode" type="button" {{ $attributes->merge(['class' => 'flex size-9 items-center cursor-pointer justify-center rounded-xl text-muted hover:bg-background-secondary transition-colors']) }}>
    <template x-if="!darkMode">
        <x-ri-sun-fill class="size-5" />
    </template>
    <template x-if="darkMode">
        <x-ri-moon-fill class="size-5" />
    </template>
</button>
@endif

