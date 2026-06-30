<div class="flex flex-col max-h-48 overflow-y-auto px-2 gap-2">
    @foreach($locales as $code => $name)
        @php
            $flagMap = [
                'en' => 'GB',
                'fr' => 'FR',
                'de' => 'DE',
                'es' => 'ES',
                'nl' => 'NL',
                'it' => 'IT',
                'pt' => 'PT',
                'pl' => 'PL',
                'ru' => 'RU',
                'tr' => 'TR',
                'ar' => 'SA',
                'zh' => 'CN',
                'ja' => 'JP',
                'ko' => 'KR',
                'sr' => 'SR',
                'ua' => 'UA',
                'uk' => 'UK',
            ];
            $countryCode = $flagMap[$code] ?? strtoupper($code);
        @endphp
        <button
            wire:click="$set('currentLocale', '{{ $code }}')"
            class="w-full cursor-pointer text-left px-4 py-2 text-sm rounded-[var(--button-radius)] hover:bg-primary/10 transition flex items-center gap-2 {{ $currentLocale === $code ? 'bg-primary/20 font-semibold' : '' }}"
            type="button">
            <img src="https://flagsapi.com/{{ $countryCode }}/flat/64.png" alt="{{ $code }}" class="size-5 rounded object-cover" />
            {{ $name }}
        </button>
    @endforeach
</div>