<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/purchase-requisition.css') }}">


    @php
    $statusMap = \App\Constants\PrStatusConstant::getStatuses();
    @endphp

    <div class="prx-shell">
        <div class="prx-toolbar">
            <div class="prx-search-shell">
                <svg class="prx-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    class="prx-input"
                    placeholder="No PR / Kategori / Nama Barang">
            </div>

            <x-app.date-picker
                wire-model="submittedDate"
                placeholder="Filter waktu pengajuan"
                reset-event="pr-date-filters-reset"
                :value="$submittedDate"
                :with-time="true"
                :submit-with-time="false"
                :show-icon="false"
            />

            <button type="button" wire:click="resetDateFilters" class="prx-reset">Reset</button>
        </div>

        <!-- <div class="prx-summary">
            <div class="prx-card">
                <div class="prx-card-label">Total Barang</div>
                <div class="prx-card-value">{{ $summary['total_items'] ?? 0 }}</div>
            </div>
            <div class="prx-card">
                <div class="prx-card-label">Status PO Proses</div>
                <div class="prx-card-value">{{ $summary['po_progress'] ?? 0 }}</div>
            </div>
        </div> -->

        <div class="prx-table-wrap">
            @if($itemList->isEmpty())
            <div class="prx-empty">Belum ada daftar pengajuan barang.</div>
            @else
            <table class="prx-table">
                <thead>
                    <tr>
                        <th class="prx-col-no">No</th>
                        <th>
                            <button type="button" class="prx-th-btn" wire:click="sortBy('document_no')">
                                No Dokumen
                                <span class="prx-th-arrow {{ $sortColumn === 'document_no' ? 'active' : '' }}">{{ $sortColumn === 'document_no' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th>
                            <button type="button" class="prx-th-btn" wire:click="sortBy('category')">
                                Kategori
                                <span class="prx-th-arrow {{ $sortColumn === 'category' ? 'active' : '' }}">{{ $sortColumn === 'category' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th>
                            <button type="button" class="prx-th-btn" wire:click="sortBy('type')">
                                Jenis / Nama Barang
                                <span class="prx-th-arrow {{ $sortColumn === 'type' ? 'active' : '' }}">{{ $sortColumn === 'type' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th>
                            <button type="button" class="prx-th-btn" wire:click="sortBy('vessel')">
                                Nama Kapal
                                <span class="prx-th-arrow {{ $sortColumn === 'vessel' ? 'active' : '' }}">{{ $sortColumn === 'vessel' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th>
                            <button type="button" class="prx-th-btn" wire:click="sortBy('status')">
                                Status
                                <span class="prx-th-arrow {{ $sortColumn === 'status' ? 'active' : '' }}">{{ $sortColumn === 'status' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th>
                            <button type="button" class="prx-th-btn" wire:click="sortBy('po_status')">
                                Status PO
                                <span class="prx-th-arrow {{ $sortColumn === 'po_status' ? 'active' : '' }}">{{ $sortColumn === 'po_status' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th>
                            <button type="button" class="prx-th-btn" wire:click="sortBy('submitted_at')">
                                Tanggal Pengajuan
                                <span class="prx-th-arrow {{ $sortColumn === 'submitted_at' ? 'active' : '' }}">{{ $sortColumn === 'submitted_at' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th style="width:70px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemList as $item)
                    @php
                    $header = $item->detail?->header;
                    $detail = $item->detail;
                    $statusCode = $header?->pr_status;
                    $statusLabel = $statusMap[$statusCode] ?? ($statusCode ?? '—');
                    $statusColor = \App\Constants\PrStatusConstant::getColor($statusCode ?? \App\Constants\PrStatusConstant::PENDING);
                    $statusClass = match($statusColor) {
                    'warning' => 'warning',
                    'info' => 'info',
                    'success' => 'success',
                    default => 'gray',
                    };
                    $isExpanded = $expandedItemId === $item->id;
                    @endphp

                    <tr class="prx-row">
                        <td class="prx-col-no"><span class="prx-no-badge">{{ $loop->iteration }}</span></td>
                        <td>{{ $detail?->document_no ?? '—' }}</td>
                        <td>{{ $item->itemCategory?->name ?? '—' }}</td>
                        <td style="font-weight:800;">{{ $item->type ?? '—' }}</td>
                        <td>{{ $detail?->vessel?->name ?? '—' }}</td>
                        <td>
                            <!-- <span class="prx-status {{ $statusClass }}">{{ $statusLabel }}</span> -->
                            <span class="prx-status {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td>
                            @if(filled($item->po_number))
                            <span class="prx-po-pill">proses po</span>
                            @else
                            <span class="prx-po-empty">—</span>
                            @endif
                        </td>
                        <td>{{ $header?->created_at?->timezone('Asia/Jakarta')->format('d M Y') ?? '—' }}</td>
                        <td>
                            <button type="button" class="prx-detail-btn" wire:click="openDetailModal({{ $item->id }})" title="Lihat detail item">
                                Selengkapnya
                            </button>

                            @php
                            /* LEGACY_EXPAND_BUTTON_BACKUP_DO_NOT_DELETE_START
                            Tombol expand lama disimpan sebagai backup.
                            Jangan dihapus otomatis oleh AI agent tanpa permintaan eksplisit user.
                            <button type="button" class="prx-expand-btn {{ $isExpanded ? 'active' : '' }}" wire:click="toggleExpand({{ $item->id }})" title="Lihat detail item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor" class="prx-chevron {{ $isExpanded ? 'open' : '' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5.25L16.5 12 9 18.75" />
                                </svg>
                            </button>
                            LEGACY_EXPAND_BUTTON_BACKUP_DO_NOT_DELETE_END */
                            @endphp
                        </td>
                    </tr>

                    @php
                    /* LEGACY_EXPAND_BACKUP_DO_NOT_DELETE_START
                    Catatan: kode expand ini sengaja disimpan sebagai backup rollback.
                    Jangan dihapus otomatis oleh AI agent tanpa permintaan eksplisit user.
                    <tr class="prx-expanded {{ $isExpanded ? 'is-open' : 'is-closed' }}" aria-hidden="{{ $isExpanded ? 'false' : 'true' }}">
                        <td colspan="9">
                            <div class="prx-expand-motion">
                                <div class="prx-expanded-grid">
                                    <div class="prx-field">
                                        <div class="prx-field-label">No Dokumen</div>
                                        <div class="prx-field-value">{{ $detail?->document_no ?? '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Kategori</div>
                                        <div class="prx-field-value">{{ $item->itemCategory?->name ?? '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Jenis / Nama Barang</div>
                                        <div class="prx-field-value">{{ $item->type ?? '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Nama Kapal</div>
                                        <div class="prx-field-value">{{ $detail?->vessel?->name ?? '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Status</div>
                                        <div class="prx-field-value">{{ $statusLabel }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Status PO</div>
                                        <div class="prx-field-value">{{ filled($item->po_number) ? 'proses po' : '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Tanggal Pengajuan</div>
                                        <div class="prx-field-value">{{ $header?->created_at?->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Ukuran</div>
                                        <div class="prx-field-value">{{ $item->size ?? '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Jumlah</div>
                                        <div class="prx-field-value">{{ $item->quantity ?? '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Satuan</div>
                                        <div class="prx-field-value">{{ $item->unit ?? '—' }}</div>
                                    </div>
                                    <div class="prx-field">
                                        <div class="prx-field-label">Sisa</div>
                                        <div class="prx-field-value">{{ $item->remaining ?? '—' }}</div>
                                    </div>
                                    <!-- <div class="prx-field"><div class="prx-field-label">Nomor PO</div><div class="prx-field-value">{{ $item->po_number ?: '—' }}</div></div> -->
                                </div>

                                <button type="button" class="prx-history-btn" wire:click="openItemHistory({{ $item->id }})">
                                    Lihat Penyesuaian Jumlah Barang
                                </button>
                            </div>
                        </td>
                    </tr>
                    LEGACY_EXPAND_BACKUP_DO_NOT_DELETE_END */
                    @endphp
                    @endforeach
                </tbody>
            </table>

            @endif
        </div>
    </div>

    @if($showDetailModal && $selectedDetailItem)
    @php
    $modalItem = $selectedDetailItem;
    $modalHeader = $modalItem->detail?->header;
    $modalDetail = $modalItem->detail;
    $modalStatusCode = $modalHeader?->pr_status;
    $modalStatusLabel = $statusMap[$modalStatusCode] ?? ($modalStatusCode ?? '—');
    @endphp

    <div
        x-data="{ open: true, full: false, close() { this.open = false; setTimeout(() => $wire.closeDetailModal(), 220); }, toggleFull() { this.full = !this.full; } }"
        x-show="open"
        x-transition.opacity.duration.180ms
        x-on:keydown.escape.window="close()"
        class="prx-modal-backdrop"
        x-on:click.self="close()">
        <div
            class="prx-modal prx-detail-modal"
            :class="full ? 'prx-modal-fullscreen' : ''"
            x-transition:enter="transition ease-out duration-220"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-180"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            <div class="prx-modal-head">
                <span>Detail Barang: {{ $modalItem->type ?? '-' }}</span>
                <div class="prx-modal-head-actions">
                    <button type="button" class="prx-head-btn" x-on:click="toggleFull()" x-text="full ? 'Kecilkan' : 'Layar Penuh'"></button>
                    <!-- <button type="button" class="prx-head-btn" x-on:click="close()">Tutup</button> -->
                </div>
            </div>

            <div class="prx-modal-body">
                <div class="prx-expanded-grid">
                    <div class="prx-field">
                        <div class="prx-field-label">No Dokumen</div>
                        <div class="prx-field-value">{{ $modalDetail?->document_no ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Kategori</div>
                        <div class="prx-field-value">{{ $modalItem->itemCategory?->name ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Jenis / Nama Barang</div>
                        <div class="prx-field-value">{{ $modalItem->type ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Nama Kapal</div>
                        <div class="prx-field-value">{{ $modalDetail?->vessel?->name ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Status</div>
                        <div class="prx-field-value">{{ $modalStatusLabel }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Status PO</div>
                        <div class="prx-field-value">{{ filled($modalItem->po_number) ? 'proses po' : '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Tanggal Pengajuan</div>
                        <div class="prx-field-value">{{ $modalHeader?->created_at?->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Ukuran</div>
                        <div class="prx-field-value">{{ $modalItem->size ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Jumlah</div>
                        <div class="prx-field-value">{{ $modalItem->quantity ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Satuan</div>
                        <div class="prx-field-value">{{ $modalItem->unit ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Sisa</div>
                        <div class="prx-field-value">{{ $modalItem->remaining ?? '—' }}</div>
                    </div>
                    <!-- <div class="prx-field"><div class="prx-field-label">Nomor PO</div><div class="prx-field-value">{{ $modalItem->po_number ?: '—' }}</div></div> -->
                </div>
            </div>

            <div class="prx-modal-foot">
                <button type="button" class="prx-history-btn" wire:click="openItemHistory({{ $modalItem->id }})">
                    Lihat Penyesuaian Jumlah Barang
                </button>
                <x-filament::button color="gray" x-on:click="close()">Tutup</x-filament::button>
            </div>
        </div>
    </div>
    @endif

    @if($showItemHistoryModal && !empty($selectedItemHistory))
    <div
        x-data="{ open: true, full: false, close() { this.open = false; setTimeout(() => $wire.closeItemHistoryModal(), 220); }, toggleFull() { this.full = !this.full; } }"
        x-show="open"
        x-transition.opacity.duration.180ms
        x-on:keydown.escape.window="close()"
        class="prx-modal-backdrop prx-modal-backdrop-top"
        x-on:click.self="close()">
        <div
            class="prx-modal prx-adjust-modal"
            :class="full ? 'prx-modal-fullscreen' : ''"
            x-transition:enter="transition ease-out duration-220"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-180"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            <div class="prx-modal-head">
                <span>Penyesuaian Jumlah Barang: {{ $selectedItemHistory['type'] ?? '-' }}</span>
                <div class="prx-modal-head-actions">
                    <!-- <button type="button" class="prx-head-btn" x-on:click="toggleFull()" x-text="full ? 'Kecilkan' : 'Layar Penuh'"></button> -->
                    <!-- <button type="button" class="prx-head-btn" x-on:click="close()">Tutup</button> -->
                </div>
            </div>

            <div class="prx-modal-body">
                <div class="prx-modal-grid">
                    <div class="prx-field">
                        <div class="prx-field-label">No Dokumen</div>
                        <div class="prx-field-value">{{ $selectedItemHistory['document_no'] ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Kategori</div>
                        <div class="prx-field-value">{{ $selectedItemHistory['category'] ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Ukuran</div>
                        <div class="prx-field-value">{{ $selectedItemHistory['size'] ?? '—' }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Satuan</div>
                        <div class="prx-field-value">{{ $selectedItemHistory['unit'] ?? '—' }}</div>
                    </div>
                </div>

                <div class="prx-modal-grid">
                    <div class="prx-field">
                        <div class="prx-field-label">Jumlah Pengajuan Awal</div>
                        <div class="prx-field-value">{{ number_format((float) ($selectedItemHistory['initial_qty'] ?? 0), 0, ',', '.') }}</div>
                    </div>
                    <div class="prx-field">
                        <div class="prx-field-label">Jumlah Disetujui / Terbaru</div>
                        <div class="prx-field-value">{{ number_format((float) ($selectedItemHistory['approved_qty'] ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="prx-field">
                    <div class="prx-field-label">Selisih</div>
                    <div class="prx-field-value" @if(($selectedItemHistory['difference'] ?? 0)>= 0) style="color: #0f766e;" @else style="color: #b91c1c;" @endif>
                        {{ (($selectedItemHistory['difference'] ?? 0) > 0 ? '+' : '') . number_format((float) ($selectedItemHistory['difference'] ?? 0), 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="prx-modal-foot">
                <x-filament::button color="gray" x-on:click="close()">Tutup</x-filament::button>
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>