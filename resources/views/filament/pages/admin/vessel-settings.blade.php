<x-filament-panels::page>
    <div x-data="{ activeTab: 'vessels' }" class="space-y-6">
        <!-- Tab Navigation -->
        <div class="flex items-center justify-start pb-4">
            <!-- Menggunakan inline CSS untuk gap agar lebih stabil (override Tailwind) dan responsive -->
            <div class="flex flex-wrap w-full sm:w-auto p-2 bg-gray-100 dark:bg-gray-800 rounded-2xl" style="gap: clamp(0.5rem, 2vw, 1rem);">
                <button
                    @click="activeTab = 'vessels'"
                    x-bind:class="{ 
                        'bg-white dark:bg-gray-700 shadow text-primary-600': activeTab === 'vessels', 
                        'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200': activeTab !== 'vessels' 
                    }"
                    class="px-6 sm:px-12 py-3 text-sm font-bold transition-all duration-200 rounded-xl focus:outline-none flex-1 sm:flex-none text-center whitespace-nowrap">
                    Daftar Kapal
                </button>

                <button
                    @click="activeTab = 'categories'"
                    x-bind:class="{ 
                        'bg-white dark:bg-gray-700 shadow text-primary-600': activeTab === 'categories', 
                        'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200': activeTab !== 'categories' 
                    }"
                    class="px-6 sm:px-12 py-3 text-sm font-bold transition-all duration-200 rounded-xl focus:outline-none flex-1 sm:flex-none text-center whitespace-nowrap">
                    Kategori Vessel
                </button>
            </div>
        </div>

        <!-- Vessels Grid View -->
        <div x-show="activeTab === 'vessels'" x-transition>
            @if(count($vessels) > 0)
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($vessels as $vessel)
                <div class="relative flex flex-col bg-white border border-gray-200 rounded-xl dark:bg-gray-900 dark:border-gray-800 shadow-sm transition-shadow hover:shadow-md">
                    <div class="p-6 flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-50 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400">
                                <x-heroicon-o-sparkles class="w-6 h-6" />
                            </div>

                            <div class="flex space-x-2">
                                <button
                                    wire:click="mountAction('editVessel', { record: {{ $vessel->id }} })"
                                    class="p-2 text-gray-500 hover:text-primary-600 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                    <x-heroicon-m-pencil-square class="w-5 h-5" />
                                </button>
                                <button
                                    wire:click="mountAction('deleteVessel', { record: {{ $vessel->id }} })"
                                    class="p-2 text-gray-500 hover:text-danger-600 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                    <x-heroicon-m-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                            {{ $vessel->name }}
                        </h3>
                        <p class="text-sm font-medium text-primary-600 dark:text-primary-400 mb-4">
                            {{ $vessel->vesselCategory->name ?? 'Tanpa Kategori' }}
                        </p>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Kode/IMO</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $vessel->code ?? '-' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Bendera</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $vessel->flag ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 rounded-b-xl">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <x-heroicon-m-building-office-2 class="w-5 h-5 mr-2 text-gray-400" />
                            <span>{{ $vessel->company->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center p-12 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900">
                <x-lucide-ship class="w-12 h-12 text-gray-400 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada data kapal</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Silakan tambahkan kapal baru menggunakan tombol di atas.</p>
            </div>
            @endif
        </div>

        <!-- Categories List View -->
        <div x-show="activeTab === 'categories'" x-transition>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($categories as $category)
                <div class="p-5 bg-white border border-gray-200 rounded-xl dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-50 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400">
                            <x-heroicon-o-tag class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $category->vessels_count }} Kapal terdaftar</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            wire:click="mountAction('editCategory', { record: {{ $category->id }} })"
                            class="p-2 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                            <x-heroicon-m-pencil-square class="w-5 h-5" />
                        </button>
                        <button
                            wire:click="mountAction('deleteCategory', { record: {{ $category->id }} })"
                            class="p-2 text-gray-400 hover:text-danger-600 transition-colors rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                            <x-heroicon-m-trash class="w-5 h-5" />
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Livewire logic for handling Edit Modal correctly in Custom Page -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Custom JS if needed
        });
    </script>
</x-filament-panels::page>