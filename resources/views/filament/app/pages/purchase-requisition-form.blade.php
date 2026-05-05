<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/purchase-requisition-form.css') }}">
    

    <div class="pr-document-wrapper">
        <form wire:submit.prevent="previewSubmit">
            <div class="pr-card">

                {{-- ===== DOCUMENT HEADER ===== --}}
                <div class="pr-doc-header">
                    {{-- Logo --}}
                    <div class="pr-doc-logo-cell">
                        <span class="pr-doc-logo-text">PATIN</span>
                    </div>

                    {{-- Judul --}}
                    <div class="pr-doc-title-cell">
                        <span class="pr-doc-title">Formulir Permintaan Barang</span>
                        <span style="font-size:0.7rem;color:#94a3b8;margin-top:0.2rem;font-weight:500;">Purchase Requisition Form</span>
                    </div>

                    {{-- Meta dokumen --}}
                    <div class="pr-doc-meta-cell">
                        <div class="pr-doc-meta-row">
                            <span class="pr-doc-meta-label">No. Dokumen</span>
                            <span style="color:#94a3b8;">:</span>
                            <span class="pr-doc-meta-value">{{ $documentNo }}</span>
                        </div>
                        <div class="pr-doc-meta-row">
                            <span class="pr-doc-meta-label">Tanggal Terbit</span>
                            <span style="color:#94a3b8;">:</span>
                            <span class="pr-doc-meta-value">{{ app(\App\Service\DateService::class)->getIssueDate() }}</span>
                        </div>


                    </div>
                </div>

                {{-- ===== INFO SECTION ===== --}}
                <div class="pr-info-section">
                    {{-- Kolom kiri --}}
                    <div class="pr-info-group">
                        <div class="pr-info-row">
                            <span class="pr-info-label">Nama Kapal</span>
                            <span class="pr-info-colon">:</span>
                            <span class="pr-info-value">{{ $vesselName }}</span>
                        </div>

                        <div class="pr-info-row">
                            <span class="pr-info-label">Kebutuhan</span>
                            <span class="pr-info-colon">:</span>
                            <div class="pr-needs-group">
                                <label class="pr-radio-label">
                                    <input type="radio" class="pr-radio-input" wire:model="needs" value="Mesin">
                                    Mesin
                                </label>
                                <label class="pr-radio-label">
                                    <input type="radio" class="pr-radio-input" wire:model="needs" value="Dek">
                                    Dek
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom kanan --}}
                    <div class="pr-info-group">

                        <div class="pr-info-row"
                            x-data="{ 
                            clientTime: @entangle('clientDateTime'),
                            update() {
                                const now = new Date();
                                const options = { day: '2-digit', month: 'long', year: 'numeric' };
                                const offset = -now.getTimezoneOffset();
                                let tzName = '';
                                if (offset === 420) tzName = 'WIB';
                                else if (offset === 480) tzName = 'WITA';
                                else if (offset === 540) tzName = 'WIT';
                                else {
                                    const sign = offset >= 0 ? '+' : '-';
                                    const hours = Math.floor(Math.abs(offset) / 60);
                                    const minutes = Math.abs(offset) % 60;
                                    tzName = `GMT${sign}${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                                }
                                this.clientTime = now.toLocaleString('id-ID', options) + ', ' + 
                                                  now.getHours().toString().padStart(2, '0') + ':' + 
                                                  now.getMinutes().toString().padStart(2, '0') + ' ' + tzName;
                            }
                         }"
                            x-init="update(); setInterval(() => update(), 30000)">
                            <span class="pr-info-label">Waktu Pengajuan</span>
                            <span class="pr-info-colon">:</span>
                            <span class="pr-info-value" x-text="clientTime || '{{ $clientDateTime }}'"></span>
                        </div>

                    </div>
                </div>

                {{-- ===== TABLE SECTION ===== --}}
                <div class="pr-table-section">
                    <div class="pr-table-label">Harap dibelikan barang sbb:</div>
                    <div class="pr-table-scroll">
                        <table class="pr-table">
                            <thead>
                                <tr>
                                    <th class="col-no">#</th>
                                    <th class="col-cat">Kategori Item</th>
                                    <th class="col-type">Jenis / Nama Barang</th>
                                    <th class="col-size">Ukuran / Spesifikasi</th>
                                    <th class="col-qty">Jumlah</th>
                                    <th class="col-unit">Satuan</th>
                                    <th class="col-rem">Sisa</th>
                                    <th class="col-act">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $index => $item)
                                @php
                                $hasRowError = $errors->has("items.{$index}.*");
                                @endphp
                                <tr wire:key="pr-item-{{ $index }}" @class(['pr-row-invalid'=> $hasRowError])>
                                    {{-- No --}}
                                    <td class="col-no">{{ $index + 1 }}</td>

                                    {{-- Kategori --}}
                                    <td class="col-cat">
                                        <select wire:model.blur="items.{{ $index }}.item_category_id"
                                            @class(['pr-field', 'pr-field-select' , 'pr-field-invalid'=> $errors->has("items.{$index}.item_category_id")])>
                                            <option value="">— Pilih —</option>
                                            @foreach($itemCategories as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error("items.{$index}.item_category_id")
                                        <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Jenis --}}
                                    <td class="col-type">
                                        <input type="text"
                                            wire:model.blur="items.{{ $index }}.type"
                                            placeholder="Nama barang..."
                                            @class(['pr-field', 'pr-field-invalid'=> $errors->has("items.{$index}.type")])>
                                        @error("items.{$index}.type")
                                        <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Ukuran --}}
                                    <td class="col-size">
                                        <input type="text"
                                            wire:model.blur="items.{{ $index }}.size"
                                            placeholder="mis. 10mm, 1/2 inch"
                                            @class(['pr-field', 'pr-field-invalid'=> $errors->has("items.{$index}.size")])>
                                        @error("items.{$index}.size")
                                        <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Jumlah --}}
                                    <td class="col-qty">
                                        <input type="number"
                                            wire:model.blur="items.{{ $index }}.quantity"
                                            placeholder="0"
                                            min="1"
                                            @class(['pr-field', 'pr-field-invalid'=> $errors->has("items.{$index}.quantity")])
                                        style="text-align:right;">
                                        @error("items.{$index}.quantity")
                                        <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Satuan --}}
                                    <td class="col-unit">
                                        <input type="text"
                                            wire:model.blur="items.{{ $index }}.unit"
                                            placeholder="Pcs, Ltr, Box..."
                                            @class(['pr-field', 'pr-field-invalid'=> $errors->has("items.{$index}.unit")])>
                                        @error("items.{$index}.unit")
                                        <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Sisa --}}
                                    <td class="col-rem">
                                        <input type="number"
                                            wire:model.blur="items.{{ $index }}.remaining"
                                            placeholder="0"
                                            step="0.01"
                                            @class(['pr-field', 'pr-field-invalid'=> $errors->has("items.{$index}.remaining")])
                                        style="text-align:right;">
                                        @error("items.{$index}.remaining")
                                        <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="col-act">
                                        <button type="button"
                                            wire:click="removeItem({{ $index }})"
                                            class="pr-btn-delete"
                                            title="Hapus baris">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" style="width:0.9rem;height:0.9rem;">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="pr-empty">
                                        Belum ada item. Klik <strong>+ Tambah Item</strong> untuk mulai menambahkan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===== FOOTER ACTIONS ===== --}}
                <div class="pr-footer">
                    <div class="pr-footer-actions">
                        <x-filament::button
                            type="button"
                            wire:click="addItem"
                            icon="heroicon-m-plus"
                            color="gray"
                            size="sm">
                            Tambah Item
                        </x-filament::button>
                        <span class="pr-item-count">
                            {{ count($items) }} item{{ count($items) !== 1 ? '' : '' }} dalam daftar
                        </span>
                    </div>

                    <x-filament::button
                        type="button"
                        wire:click="previewSubmit"
                        icon="heroicon-m-eye"
                        size="md"
                        wire:target="previewSubmit">
                        Periksa & Kirim
                    </x-filament::button>
                </div>

            </div>{{-- end pr-card --}}
        </form>
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.hook('morph.updated', ({
                    el,
                    component
                }) => {
                    const firstError = document.querySelector('.pr-field-invalid, .pr-field-error');
                    if (firstError && !firstError.dataset.scrolled) {
                        setTimeout(() => {
                            firstError.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center',
                                inline: 'nearest'
                            });
                            const input = firstError.tagName === 'INPUT' || firstError.tagName === 'SELECT' ?
                                firstError : firstError.closest('td')?.querySelector('input, select');
                            if (input) input.focus();
                            firstError.dataset.scrolled = "true";
                        }, 150);
                    }
                });
                Livewire.hook('request', () => {
                    document.querySelectorAll('[data-scrolled]').forEach(el => delete el.dataset.scrolled);
                });
            });
        </script>
        

        {{-- ===== PREVIEW MODAL ===== --}}
        @if($showPreviewModal)
        <div class="pr-modal-overlay" wire:click.self="$set('showPreviewModal', false)">
            <div class="pr-modal-container">

                {{-- MODAL HEADER --}}
                <div class="pr-modal-header">
                    <div class="pr-modal-header-title">
                        <div class="pr-modal-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width:1.25rem;height:1.25rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="pr-modal-title">Preview Pengajuan PR</div>
                            <div class="pr-modal-subtitle">Periksa kembali data sebelum dikirim</div>
                        </div>
                    </div>
                    <button type="button" class="pr-modal-close" wire:click="closePreview" wire:target="closePreview" title="Tutup">
                        <svg wire:loading.remove wire:target="closePreview" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <x-filament::loading-indicator wire:loading wire:target="closePreview" class="h-4 w-4" />
                    </button>
                </div>

                {{-- MODAL BODY --}}
                <div class="pr-modal-body">

                    {{-- INFO SUMMARY --}}
                    <div class="pr-preview-info-grid">
                        {{-- Kiri --}}
                        <div class="pr-preview-info-card">
                            <div class="pr-preview-info-item">
                                <span class="pr-preview-info-label">No. Dokumen</span>
                                <span class="pr-preview-info-value">{{ $documentNo }}</span>
                            </div>
                            <div class="pr-preview-info-item">
                                <span class="pr-preview-info-label">Nama Kapal</span>
                                <span class="pr-preview-info-value">{{ $vesselName }}</span>
                            </div>
                            <div class="pr-preview-info-item">
                                <span class="pr-preview-info-label">Tanggal Terbit</span>
                                <span class="pr-preview-info-value">{{ app(\App\Service\DateService::class)->getIssueDate() }}</span>
                            </div>
                        </div>
                        {{-- Kanan --}}
                        <div class="pr-preview-info-card">
                            <div class="pr-preview-info-item">
                                <span class="pr-preview-info-label">Kebutuhan</span>
                                <span class="pr-preview-badge">{{ $needs }}</span>
                            </div>
                            <div class="pr-preview-info-item">
                                <span class="pr-preview-info-label">Waktu Pengajuan</span>
                                <span class="pr-preview-info-value" style="font-size:0.8rem;">{{ $clientDateTime }}</span>
                            </div>
                            <div class="pr-preview-info-item">
                                <span class="pr-preview-info-label">Total Item</span>
                                <span class="pr-preview-info-value">{{ count($items) }} item</span>
                            </div>
                        </div>
                    </div>

                    {{-- ITEMS TABLE --}}
                    <div>
                        <div class="pr-preview-section-label">
                            <span class="pr-preview-section-title">Daftar Barang yang Diminta</span>
                            <span class="pr-preview-total-badge">{{ count($items) }} item</span>
                        </div>

                        <div class="pr-preview-table-wrap">
                            <div class="pr-preview-table-scroll">
                                <table class="pr-preview-table">
                                    <thead>
                                        <tr>
                                            <th class="col-no">#</th>
                                            <th>Kategori Item</th>
                                            <th>Jenis / Nama Barang</th>
                                            <th>Ukuran / Spesifikasi</th>
                                            <th class="col-qty">Jumlah</th>
                                            <th class="col-unit">Satuan</th>
                                            <th class="col-rem">Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $currentCat = null;
                                        $rowNo = 0;
                                        @endphp
                                        @foreach($items as $item)
                                        @php
                                        $catName = $itemCategories[$item['item_category_id']] ?? '—';
                                        $rowNo++;
                                        @endphp
                                        @if($catName !== $currentCat)
                                        @php $currentCat = $catName; @endphp
                                        <tr class="pr-cat-divider">
                                            <td colspan="7">
                                                <span class="pr-cat-divider-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:0.75rem;height:0.75rem;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                                    </svg>
                                                    {{ $catName }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="col-no">{{ $rowNo }}</td>
                                            <td>{{ $catName }}</td>
                                            <td>{{ $item['type'] ?: '—' }}</td>
                                            <td>{{ $item['size'] ?: '—' }}</td>
                                            <td class="col-qty">{{ $item['quantity'] ?: '—' }}</td>
                                            <td class="col-unit">{{ $item['unit'] ?: '—' }}</td>
                                            <td class="col-rem">{{ $item['remaining'] !== '' ? $item['remaining'] : '—' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>{{-- end modal-body --}}

                {{-- MODAL FOOTER --}}
                <div class="pr-modal-footer">
                    <span class="pr-modal-footer-info">
                        ✦ Pastikan semua data sudah benar sebelum mengirim.
                    </span>
                    <div class="pr-modal-footer-actions">
                        <x-filament::button
                            type="button"
                            color="gray"
                            icon="heroicon-m-arrow-left"
                            wire:click="closePreview"
                            wire:target="closePreview">
                            Kembali Edit
                        </x-filament::button>

                        <x-filament::button
                            type="button"
                            icon="heroicon-m-check"
                            wire:click="confirmSubmit"
                            wire:target="confirmSubmit">
                            Konfirmasi &amp; Kirim
                        </x-filament::button>
                    </div>
                </div>

            </div>
        </div>
        @endif

</x-filament-panels::page>
