@if($cartCount > 0)
    <div class="relative">
        <a href="/cart"
            class="relative flex size-9 mr-1 items-center justify-center rounded-[var(--button-radius)] text-muted hover:bg-background-secondary transition-colors focus:outline-none"
            aria-label="{{ __('Cart') }}">
            <x-ri-shopping-cart-2-line class="size-5" />
            <span
                class="absolute text-white -right-0.5 -top-0.5 flex size-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold border border-transparent hover:border-primary/50">{{ $cartCount }}</span>

        </a>
    </div>
@endif