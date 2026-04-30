<x-filament-panels::page>
<style>
    /* ===== DOCUMENT WRAPPER ===== */
    .pr-document-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* ===== DOCUMENT CARD ===== */
    .pr-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 24px 0 rgba(15, 23, 42, 0.07);
        overflow: hidden;
    }
    .dark .pr-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
    }

    /* ===== DOCUMENT HEADER ===== */
    .pr-doc-header {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: stretch;
        border-bottom: 2px solid #e2e8f0;
    }
    .dark .pr-doc-header {
        border-bottom-color: #334155;
    }

    .pr-doc-logo-cell {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #e2e8f0;
        min-width: 120px;
    }
    .dark .pr-doc-logo-cell {
        border-right-color: #334155;
    }
    .pr-doc-logo-text {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.05em;
    }
    .dark .pr-doc-logo-text {
        color: #f1f5f9;
    }

    .pr-doc-title-cell {
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .pr-doc-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: 0.025em;
        text-transform: uppercase;
    }
    .dark .pr-doc-title {
        color: #f1f5f9;
    }

    .pr-doc-meta-cell {
        border-left: 1px solid #e2e8f0;
        min-width: 200px;
    }
    .dark .pr-doc-meta-cell {
        border-left-color: #334155;
    }
    .pr-doc-meta-row {
        display: flex;
        align-items: flex-start;
        padding: 0.45rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.75rem;
        gap: 0.5rem;
    }
    .pr-doc-meta-row:last-child {
        border-bottom: none;
    }
    .dark .pr-doc-meta-row {
        border-bottom-color: #334155;
    }
    .pr-doc-meta-label {
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
        min-width: 80px;
    }
    .dark .pr-doc-meta-label {
        color: #94a3b8;
    }
    .pr-doc-meta-value {
        color: #0f172a;
        font-weight: 600;
        word-break: break-word;
        line-height: 1.25;
        max-width: 180px;
        display: block;
    }
    .dark .pr-doc-meta-value {
        color: #f1f5f9;
    }

    /* ===== INFO SECTION ===== */
    .pr-info-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        border-bottom: 2px solid #e2e8f0;
    }
    .dark .pr-info-section {
        border-bottom-color: #334155;
    }
    .pr-info-group {
        padding: 1rem 1.5rem;
    }
    .pr-info-group:first-child {
        border-right: 1px solid #e2e8f0;
    }
    .dark .pr-info-group:first-child {
        border-right-color: #334155;
    }
    .pr-info-row {
        display: flex;
        align-items: center;
        margin-bottom: 0.625rem;
        gap: 0.75rem;
    }
    .pr-info-row:last-child {
        margin-bottom: 0;
    }
    .pr-info-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        min-width: 130px;
        white-space: nowrap;
    }
    .dark .pr-info-label {
        color: #94a3b8;
    }
    .pr-info-colon {
        color: #94a3b8;
        font-weight: 600;
    }
    .pr-info-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
    }
    .dark .pr-info-value {
        color: #f1f5f9;
    }

    /* ===== NEEDS RADIO ===== */
    .pr-needs-group {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .pr-radio-label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
    }
    .dark .pr-radio-label {
        color: #cbd5e1;
    }
    .pr-radio-input {
        width: 1rem;
        height: 1rem;
        accent-color: #3b82f6;
        cursor: pointer;
    }

    /* ===== TABLE SECTION ===== */
    .pr-table-section {
        padding: 0;
    }
    .pr-table-label {
        padding: 0.875rem 1.5rem 0.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .dark .pr-table-label {
        color: #94a3b8;
        border-bottom-color: #334155;
        background: #0f172a;
    }

    .pr-table-scroll {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 294px; /* ≈ 6 baris */
    }
    .pr-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8125rem;
    }
    .pr-table thead th {
        background: #f1f5f9;
        color: #374151;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.75rem 0.875rem;
        text-align: left;
        border-bottom: 2px solid #cbd5e1;
        white-space: nowrap;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .dark .pr-table thead th {
        background: #1e293b;
        color: #94a3b8;
        border-bottom-color: #475569;
    }
    .pr-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.15s;
    }
    .pr-table tbody tr:last-child {
        border-bottom: none;
    }
    .dark .pr-table tbody tr {
        border-bottom-color: #334155;
    }
    .pr-table tbody tr:hover {
        background: #f8fafc;
    }
    .dark .pr-table tbody tr:hover {
        background: #0f172a;
    }
    .pr-table td {
        padding: 0.75rem 0.875rem;
        vertical-align: top;
    }
    .pr-table .col-no    { width: 4%; text-align: center; color: #94a3b8; font-weight: 700; }
    .pr-table .col-cat   { width: 12%; }
    .pr-table .col-type  { width: 38%; }
    .pr-table .col-size  { width: 15%; }
    .pr-table .col-qty   { width: 6%; }
    .pr-table .col-unit  { width: 7%; }
    .pr-table .col-rem   { width: 8%; }
    .pr-table .col-act   { width: 10%; text-align: center; }

    /* ===== FORM INPUTS IN TABLE ===== */
    .pr-field {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        padding: 0.4rem 0.6rem;
        font-size: 0.8125rem;
        color: #0f172a;
        background: #ffffff;
        transition: border-color 0.15s, box-shadow 0.15s;
        line-height: 1.5;
    }
    .pr-field:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .dark .pr-field {
        background: #0f172a;
        border-color: #475569;
        color: #f1f5f9;
    }
    .dark .pr-field:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2);
    }
    .pr-field-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.4rem center;
        background-size: 1.25em;
        padding-right: 2rem !important;
        cursor: pointer;
    }
    .pr-field-error {
        font-size: 0.7rem;
        color: #ef4444;
        margin-top: 0.2rem;
        display: block;
        font-weight: 600;
    }
    .pr-field-invalid {
        border: 2px solid #dc2626 !important;
        /* Reverted background-color as requested */
    }
    .dark .pr-field-invalid {
        border-color: #f87171 !important;
    }
    .pr-row-invalid {
        border-left: 4px solid #dc2626 !important;
    }
    .dark .pr-row-invalid {
        background-color: rgba(239, 68, 68, 0.05) !important;
    }

    /* ===== CUSTOM NOTIFICATION STYLE ===== */

    /* ===== DELETE BUTTON ===== */
    .pr-btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.75rem;
        gap: 0.4rem;
        border-radius: 0.375rem;
        border: 1px solid #fca5a5;
        background: #fff1f2;
        color: #ef4444;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        cursor: pointer;
        transition: all 0.15s;
    }
    .pr-btn-delete:hover {
        background: #fee2e2;
        border-color: #ef4444;
    }
    .dark .pr-btn-delete {
        background: rgba(239,68,68,0.1);
        border-color: rgba(239,68,68,0.3);
        color: #f87171;
    }
    .dark .pr-btn-delete:hover {
        background: rgba(239,68,68,0.2);
    }

    /* ===== FOOTER ACTIONS ===== */
    .pr-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 0 0 1rem 1rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .dark .pr-footer {
        border-top-color: #334155;
        background: #0f172a;
    }

    .pr-item-count {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }
    .dark .pr-item-count {
        color: #94a3b8;
    }

    .pr-footer-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* ===== EMPTY STATE ===== */
    .pr-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .pr-doc-header {
            grid-template-columns: 1fr;
        }
        .pr-doc-logo-cell {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }
        .pr-doc-meta-cell {
            border-left: none;
            border-top: 1px solid #e2e8f0;
            min-width: unset;
        }
        .pr-info-section {
            grid-template-columns: 1fr;
        }
        .pr-info-group:first-child {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }
    }
</style>

<div class="pr-document-wrapper">
    <form wire:submit.prevent="submit">
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
                                <tr wire:key="pr-item-{{ $index }}" @class(['pr-row-invalid' => $hasRowError])>
                                    {{-- No --}}
                                    <td class="col-no">{{ $index + 1 }}</td>

                                    {{-- Kategori --}}
                                    <td class="col-cat">
                                        <select wire:model="items.{{ $index }}.item_category_id"
                                                @class(['pr-field', 'pr-field-select', 'pr-field-invalid' => $errors->has("items.{$index}.item_category_id")])>
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
                                               wire:model="items.{{ $index }}.type"
                                               placeholder="Nama barang..."
                                               @class(['pr-field', 'pr-field-invalid' => $errors->has("items.{$index}.type")])>
                                        @error("items.{$index}.type")
                                            <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Ukuran --}}
                                    <td class="col-size">
                                        <input type="text"
                                               wire:model="items.{{ $index }}.size"
                                               placeholder="mis. 10mm, 1/2 inch"
                                               @class(['pr-field', 'pr-field-invalid' => $errors->has("items.{$index}.size")])>
                                        @error("items.{$index}.size")
                                            <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Jumlah --}}
                                    <td class="col-qty">
                                        <input type="number"
                                               wire:model="items.{{ $index }}.quantity"
                                               placeholder="0"
                                               min="1"
                                               @class(['pr-field', 'pr-field-invalid' => $errors->has("items.{$index}.quantity")])
                                               style="text-align:right;">
                                        @error("items.{$index}.quantity")
                                            <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Satuan --}}
                                    <td class="col-unit">
                                        <input type="text"
                                               wire:model="items.{{ $index }}.unit"
                                               placeholder="Pcs, Ltr, Box..."
                                               @class(['pr-field', 'pr-field-invalid' => $errors->has("items.{$index}.unit")])>
                                        @error("items.{$index}.unit")
                                            <span class="pr-field-error">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Sisa --}}
                                    <td class="col-rem">
                                        <input type="number"
                                               wire:model="items.{{ $index }}.remaining"
                                               placeholder="0"
                                               step="0.01"
                                               @class(['pr-field', 'pr-field-invalid' => $errors->has("items.{$index}.remaining")])
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
                                                      d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
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
                    type="submit"
                    icon="heroicon-m-paper-airplane"
                    size="md"
                    wire:loading.attr="disabled"
                    wire:target="submit">
                    <span wire:loading.remove wire:target="submit">Kirim Pengajuan PR</span>
                    <span wire:loading wire:target="submit">Mengirim...</span>
                </x-filament::button>
            </div>

        </div>{{-- end pr-card --}}
    </form>
    <script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('morph.updated', ({ el, component }) => {
            // Cari elemen error (pesan error atau border merah)
            const firstError = document.querySelector('.pr-field-invalid, .pr-field-error');
            
            if (firstError && !firstError.dataset.scrolled) {
                setTimeout(() => {
                    // Scroll ke elemen error tersebut
                    firstError.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center',
                        inline: 'nearest'
                    });

                    // Coba fokuskan ke input terkait
                    const input = firstError.tagName === 'INPUT' || firstError.tagName === 'SELECT' 
                        ? firstError 
                        : firstError.closest('td')?.querySelector('input, select');
                    
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

</x-filament-panels::page>