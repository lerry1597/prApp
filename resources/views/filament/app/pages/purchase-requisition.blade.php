<x-filament-panels::page>
    <style>
        trix-editor {
            min-height: 600px !important;
            padding-bottom: 400px !important;
            height: auto !important;
        }

        /* Custom Table styling */
        .custom-table-container {
            max-height: 450px;
            overflow-y: auto;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            background-color: #ffffff;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }
        
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        
        .custom-table th {
            background-color: #f8fafc;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid #cbd5e1;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .custom-table td {
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        
        .custom-table tr:hover {
            background-color: #f8fafc;
        }
        
        /* Form inputs */
        .custom-input {
            width: 100%;
            border: 1px solid #94a3b8 !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            background-color: #ffffff !important;
            color: #0f172a !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease-in-out;
        }
        
        .custom-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
            outline: none !important;
        }

        /* Dark Mode support */
        .dark .custom-table-container {
            border-color: #334155;
            background-color: #0f172a;
        }
        
        .dark .custom-table th {
            background-color: #1e293b;
            color: #f1f5f9;
            border-bottom-color: #475569;
        }
        
        .dark .custom-table td {
            border-bottom-color: #334155;
        }
        
        .dark .custom-table tr:hover {
            background-color: #1e293b;
        }
        
        .dark .custom-input {
            border-color: #475569 !important;
            background-color: #1e293b !important;
            color: #f8fafc !important;
        }
        
        .dark .custom-input:focus {
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.3) !important;
        }
    </style>

    <div class="mb-6">
        {{ $this->getSchema('infolist') }}
    </div>

    <form wire:submit.prevent="submit">
        <div class="custom-table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Kategori Item</th>
                        <th style="width: 25%;">Jenis</th>
                        <th style="width: 20%;">Ukuran</th>
                        <th style="width: 12%;">Jumlah</th>
                        <th style="width: 12%;">Satuan</th>
                        <th style="width: 6%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                        <tr wire:key="item-row-{{ $index }}">
                            <!-- Kategori Item -->
                            <td>
                                <select wire:model="items.{{ $index }}.item_category_id" class="custom-input">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($itemCategories as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error("items.{$index}.item_category_id")
                                    <span class="text-xs text-danger-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </td>

                            <!-- Jenis -->
                            <td>
                                <input type="text" wire:model="items.{{ $index }}.type" class="custom-input">
                                @error("items.{$index}.type")
                                    <span class="text-xs text-danger-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </td>

                            <!-- Ukuran -->
                            <td>
                                <input type="text" wire:model="items.{{ $index }}.size" class="custom-input">
                                @error("items.{$index}.size")
                                    <span class="text-xs text-danger-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </td>

                            <!-- Jumlah -->
                            <td>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" wire:model="items.{{ $index }}.quantity" class="custom-input">
                                @error("items.{$index}.quantity")
                                    <span class="text-xs text-danger-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </td>

                            <!-- Satuan -->
                            <td>
                                <input type="text" wire:model="items.{{ $index }}.unit" placeholder="Pcs, Box, dll" class="custom-input">
                                @error("items.{$index}.unit")
                                    <span class="text-xs text-danger-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </td>

                            <!-- Aksi -->
                            <td style="text-align: center;">
                                <button type="button" wire:click="removeItem({{ $index }})" 
                                        class="text-danger-600 hover:text-danger-700 dark:text-danger-400 dark:hover:text-danger-300 font-semibold text-sm transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tombol Tambah & Submit -->
        <div class="mt-4 flex justify-between items-center">
            <x-filament::button type="button" wire:click="addItem" icon="heroicon-m-plus" color="gray" size="sm">
                Tambah Item
            </x-filament::button>

            <x-filament::button type="submit" size="lg">
                Kirim Pengajuan PR
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>