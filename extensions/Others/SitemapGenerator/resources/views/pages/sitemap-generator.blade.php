<x-filament-panels::page>
    <div class="space-y-6">
          @if(file_exists(public_path('sitemap.xml')))
            <div class="mb-4 p-4 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Sitemap File</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    Your sitemap is available at: 
                    <a href="{{ url('sitemap.xml') }}" target="_blank" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                        {{ url('sitemap.xml') }}
                    </a>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-500">
                    Last generated: {{ \Carbon\Carbon::createFromTimestamp(filemtime(public_path('sitemap.xml')))->diffForHumans() }}
                </p>
            </div>
        @endif
        @if(count($this->urls) > 0)
            {{ $this->table }}
        @else
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <p class="text-gray-500 dark:text-gray-400">No URLs found. Click "Update Sitemap" to generate the sitemap.</p>
            </div>
        @endif
        
    </div>
</x-filament-panels::page>

