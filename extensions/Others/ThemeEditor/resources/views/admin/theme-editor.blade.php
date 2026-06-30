<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ route('theme-editor.css') }}?v={{ time() }}" />
    <div 
        class="te-root"
        x-data="{ 
            activeTab: @entangle('activeTab'),
            saving: false,
            panelWidth: localStorage.getItem('te-panel-width') ? parseInt(localStorage.getItem('te-panel-width')) : 380,
            isResizing: false,
            startResize(e) {
                this.isResizing = true;
                document.body.style.cursor = 'ew-resize';
                document.body.style.userSelect = 'none';
            },
            doResize(e) {
                if (!this.isResizing) return;
                const sidebar = document.querySelector('.te-icon-sidebar');
                const sidebarWidth = sidebar ? sidebar.offsetWidth + 24 : 88;
                const newWidth = e.clientX - sidebarWidth;
                if (newWidth >= 280 && newWidth <= 600) {
                    this.panelWidth = newWidth;
                }
            },
            stopResize() {
                if (this.isResizing) {
                    this.isResizing = false;
                    document.body.style.cursor = '';
                    document.body.style.userSelect = '';
                    localStorage.setItem('te-panel-width', this.panelWidth);
                }
            }
        }"
        x-init="$watch('activeTab', () => {
            const contentArea = document.querySelector('.te-options-content');
            if (contentArea) {
                contentArea.scrollTop = 0;
            }
        })"
        @mousemove.window="doResize($event)"
        @mouseup.window="stopResize()"
    >
        <div class="te-icon-sidebar">
            <div class="te-icon-sidebar-tabs">
                @foreach($this->getTabs() as $key => $tab)
                    <button 
                        type="button"
                        class="te-icon-btn"
                        :class="{ 'active': activeTab === '{{ $key }}' }"
                        @click="activeTab = '{{ $key }}'; $wire.setActiveTab('{{ $key }}')"
                        title="{{ $tab['label'] }}"
                    >
                        <i class="fas {{ $tab['icon'] }}"></i>
                    </button>
                @endforeach
            </div>
            <div class="te-icon-sidebar-footer">
                <a 
                    href="{{ route('filament.admin.pages.dashboard') }}"
                    class="te-icon-btn te-back-btn"
                    title="Back to Admin"
                >
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <div class="te-options-panel" :style="'width: ' + panelWidth + 'px; min-width: ' + panelWidth + 'px;'">
            <div class="te-options-header">
                <span class="te-options-title">
                    @foreach($this->getTabs() as $key => $tab)
                        <span x-show="activeTab === '{{ $key }}'">{{ $tab['label'] }}</span>
                    @endforeach
                </span>
            </div>

            <div class="te-options-content">
                <form wire:submit="save">
                    {{ $this->form }}
                </form>
            </div>

            <div class="te-options-footer">
                <button 
                    type="button"
                    class="te-save-btn mb-2"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    :disabled="saving"
                >
                    <span wire:loading.remove wire:target="save">
                        <i class="fas fa-check"></i>
                    </span>
                    <span wire:loading wire:target="save">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
                <button 
                    type="button"
                    class="te-reset-btn"
                    wire:click="resetToDefaults"
                    wire:loading.attr="disabled"
                    wire:confirm="Are you sure you want to reset all theme settings to defaults? This cannot be undone."
                >
                    <span wire:loading.remove wire:target="resetToDefaults">
                        <i class="fas fa-undo"></i>
                    </span>
                    <span wire:loading wire:target="resetToDefaults">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span wire:loading.remove wire:target="resetToDefaults">Reset to Defaults</span>
                    <span wire:loading wire:target="resetToDefaults">Resetting...</span>
                </button>
            </div>
        </div>

        <div 
            class="te-resize-handle"
            @mousedown.prevent="startResize($event)"
            :class="{ 'active': isResizing }"
        >
            <div class="te-resize-line"></div>
        </div>

        <div 
            class="te-preview-container"
            x-data="{
                refreshPreview() {
                    const iframe = document.getElementById('preview-iframe');
                    if (iframe) {
                        iframe.src = iframe.src;
                    }
                },
                iframeWidth: localStorage.getItem('te-iframe-width') ? parseInt(localStorage.getItem('te-iframe-width')) : null,
                isResizingIframe: false,
                resizeSide: null,
                startX: 0,
                startWidth: 0,
                startIframeResize(e, side) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.isResizingIframe = true;
                    this.resizeSide = side;
                    this.startX = e.clientX;
                    const wrapper = this.$refs.iframeInner;
                    this.startWidth = this.iframeWidth || wrapper.offsetWidth;
                    document.body.style.cursor = 'ew-resize';
                    document.body.style.userSelect = 'none';
                },
                doIframeResize(e) {
                    if (!this.isResizingIframe) return;
                    const delta = this.resizeSide === 'left' 
                        ? (this.startX - e.clientX) * 2
                        : (e.clientX - this.startX) * 2;
                    const newWidth = this.startWidth + delta;
                    const minWidth = 320;
                    const maxWidth = this.$refs.iframeWrapper.offsetWidth - 40;
                    if (newWidth >= minWidth && newWidth <= maxWidth) {
                        this.iframeWidth = newWidth;
                    }
                },
                stopIframeResize() {
                    if (this.isResizingIframe) {
                        this.isResizingIframe = false;
                        this.resizeSide = null;
                        document.body.style.cursor = '';
                        document.body.style.userSelect = '';
                        if (this.iframeWidth) {
                            localStorage.setItem('te-iframe-width', this.iframeWidth);
                        }
                    }
                },
                resetIframeWidth() {
                    this.iframeWidth = null;
                    localStorage.removeItem('te-iframe-width');
                    this.$nextTick(() => {
                        const wrapper = this.$refs.iframeInner;
                        if (wrapper) {
                            wrapper.style.width = '';
                        }
                    });
                }
            }"
            @mousemove.window="doIframeResize($event)"
            @mouseup.window="stopIframeResize()"
            x-on:theme-settings-saved.window="refreshPreview()"
        >
            <div class="te-preview-header">
                <div class="te-preview-url">
                    <i class="fas fa-globe"></i>
                    <span>{{ $this->getPreviewUrl() }}</span>
                </div>
                <div class="te-preview-actions">
                    <button 
                        type="button" 
                        class="te-preview-action" 
                        @click="resetIframeWidth()" 
                        title="Reset Width"
                        x-show="iframeWidth"
                    >
                        <i class="fas fa-expand"></i>
                    </button>
                    <span 
                        class="te-viewport-size" 
                        x-show="iframeWidth"
                        x-text="iframeWidth + 'px'"
                    ></span>
                    <button type="button" class="te-preview-action" onclick="document.getElementById('preview-iframe').src = document.getElementById('preview-iframe').src" title="Refresh Preview">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <a href="{{ $this->getPreviewUrl() }}" target="_blank" class="te-preview-action" title="Open in New Tab">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="te-iframe-wrapper" x-ref="iframeWrapper">
                <div 
                    class="te-iframe-inner"
                    x-ref="iframeInner"
                    :style="iframeWidth ? 'width: ' + iframeWidth + 'px' : ''"
                    :class="{ 'te-iframe-resized': iframeWidth }"
                >
                    <div 
                        class="te-iframe-handle te-iframe-handle-left"
                        @mousedown.prevent="startIframeResize($event, 'left')"
                        :class="{ 'active': isResizingIframe && resizeSide === 'left' }"
                        x-show="iframeWidth != null"
                        x-cloak
                    >
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    <iframe 
                        id="preview-iframe"
                        src="{{ $this->getPreviewUrl() }}" 
                        class="te-iframe"
                        loading="lazy"
                    ></iframe>
                    <div 
                        class="te-iframe-overlay"
                        x-show="isResizingIframe"
                        style="position: absolute; inset: 0; z-index: 10; cursor: ew-resize;"
                    ></div>
                    <div 
                        class="te-iframe-handle te-iframe-handle-right"
                        @mousedown.prevent="startIframeResize($event, 'right')"
                        :class="{ 'active': isResizingIframe && resizeSide === 'right' }"
                    >
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
