@php
    $fontFamily = theme('font_family', 'Geist Sans');
    $fontCssName = match($fontFamily) {
        'Source Sans Pro' => 'Source Sans 3',
        default => $fontFamily
    };
    $fontFamilyCss = $fontFamily === 'System Default' ? 'ui-sans-serif, system-ui, sans-serif' : '"' . $fontCssName . '", ui-sans-serif, system-ui, sans-serif';
@endphp
<style>
    :root {
        /* Branding Colors (Light) */
        --color-primary: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('primary', '231 58% 55%'))) }};
        --color-secondary: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('primary', '231 58% 55%'))) }};

        /* Neutral Colors - Borders, Accents... (Light) */
        --color-neutral: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('neutral', '0 0% 91%'))) }};

        /* Text Colors (Light) */
        --color-base: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('base', '0 0% 0%'))) }};
        --color-muted: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('muted', '220 28% 25%'))) }};
        --color-inverted: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('inverted', '100 100% 100%'))) }};

        /* State Colors */
        --color-success: 142 71% 45%;
        --color-error: 0 75% 60%;
        --color-warning: 25 95% 53%;
        --color-inactive: 0 0% 63%;
        --color-info: 210 100% 60%;

        /* Background Colors (Light) */
        --color-background: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('background', '210, 40%, 98%'))) }};
        --color-background-secondary: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('background-secondary', '0 0% 100%'))) }};

        /* Theme Styling */
        --card-radius: {{ theme('card-border-radius', '12px') }};
        --card-shadow: {{ theme('card-shadow', '0 1px 3px rgba(0,0,0,0.1)') }};
        --button-radius: {{ theme('button-border-radius', '12px') }};
        --input-radius: {{ theme('input-border-radius', '12px') }};

        /* Layout */
        --container-max-width: {{ theme('container-max-width', '1280px') }};

        /* Typography */
        --font-family: {{ $fontFamilyCss }};

        /* Gradients */
        --hero-gradient-1: {{ theme('hero-gradient-1', '#4A5ECF') }};
        --hero-gradient-2: {{ theme('hero-gradient-2', '#7FA6F9') }};
        --hero-gradient-3: {{ theme('hero-gradient-3', '#3451B2') }};
        --hero-gradient-4: {{ theme('hero-gradient-4', '#7CD2FF') }};
        --paper-gradient-from: {{ theme('paper-gradient-from', '#4169E1') }};
        --paper-gradient-to: {{ theme('paper-gradient-to', '#89CFF0') }};
    }

    .dark {
        /* Branding Colors (Dark) */
        --color-primary: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('dark-primary', '0, 0%, 15%'))) }};

        /* Neutral Colors - Borders, Accents... (Dark) */
        --color-neutral: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('dark-neutral', '0, 0%, 15%'))) }};

        /* Text Colors (Dark) */
        --color-base: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('dark-base', '100 100% 100%'))) }};
        --color-muted: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('dark-muted', '0, 0%, 76%'))) }};
        --color-inverted: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('dark-inverted', '220 14% 60%'))) }};

        /* Background Colors (Dark) */
        --color-background: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('dark-background', '240, 10%, 4%'))) }};
        --color-background-secondary: {{ str_replace(',', '', preg_replace('/^hsl\((.+)\)$/', '$1', theme('dark-background-secondary', '240, 2%, 8%'))) }};
    }

    @media (min-width: {{ theme('container-max-width', '1280px') }}) {
        .layout-wide-content {
            padding-left: 3rem;
            padding-right: 3rem;
        }
    }

    .page-bg-gradient {
        background: linear-gradient(
            to top,
            hsl(var(--color-background) / 1) 0%,
            hsl(var(--color-background) / 0.95) 20%,
            hsl(var(--color-background) / 0.85) 40%,
            hsl(var(--color-background) / var(--bg-top-opacity, 0.8)) 100%
        );
    }

    body {
        font-family: {!! $fontFamilyCss !!} !important;
    }
</style>