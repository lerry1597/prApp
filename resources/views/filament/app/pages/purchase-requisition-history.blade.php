<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/purchase-requisition-history.css') }}">


    <div class="prh-container">
        <x-filament::section>
            <div class="prh-toolbar" wire:key="history-toolbar">
                <div class="prh-search-shell">
                    <svg class="prh-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                    <input
                        type="text"
                        class="prh-search"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Cari berdasarkan Nomor PR, Departemen, nama barang / jenis barang..">

                    {{-- Untuk sementara Reset tidak di gunakan dalam search --}}
                    {{--
                    @if($search !== '')
                    <button type="button" class="prh-search-clear" wire:click="$set('search', '')" aria-label="Reset pencarian" title="Reset pencarian">
                        Reset
                    </button>
                    @endif
                    --}}
                </div>

                <x-app.date-picker
                    wire-model="requestDate"
                    placeholder="Tanggal pengajuan"
                    reset-event="pr-history-date-filters-reset"
                    :value="$requestDate"
                    :show-icon="false" />


                <button type="button" class="prh-date-reset" wire:click="resetDateFilters" title="Reset filter tanggal">
                    <span wire:loading wire:target="resetDateFilters" class="prh-spinner-sm"></span>
                    <span wire:loading.remove wire:target="resetDateFilters">Reset</span>
                    <span wire:loading wire:target="resetDateFilters">Mereset...</span>
                </button>

            </div>

            <div class="prh-list-table-wrap">
                <table class="prh-list-table">
                    <thead>
                        <tr>
                            <th>Nomor PR</th>
                            <th>Kebutuhan Mesin/Dek</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status PR</th>
                            <th class="prh-action-col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyList as $row)
                        @php
                        $hintItems = $matchedItemHints[$row->pr_header_id] ?? [];
                        $statusCode = $row->pr_status ?? \App\Constants\PrStatusConstant::UNKNOWN;
                        $statusColor = \App\Constants\PrStatusConstant::getColor($statusCode);
                        $badgeColor = in_array($statusColor, ['warning', 'danger', 'success', 'info', 'gray'], true) ? $statusColor : 'gray';
                        $statusLabel = $statusLabels[$statusCode] ?? $statusCode;
                        @endphp
                        <tr>
                            <td>
                                <div>{{ $row->document_no ?: '-' }}</div>
                                @if(!empty($hintItems))
                                <div class="prh-doc-item-hint">Barang cocok: {{ implode(', ', $hintItems) }}</div>
                                @endif
                            </td>
                            <td>{{ $row->needs ?: '-' }}</td>
                            <td>{{ $row->request_date ? \Illuminate\Support\Carbon::parse($row->request_date)->format('d M Y') : '-' }}</td>
                            <td>
                                <span class="prh-badge prh-badge-{{ $badgeColor }}">
                                    <span class="prh-badge-dot"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="prh-action-col">
                                <button class="prh-detail-btn" wire:click="showFlowDetails('{{ $row->batch_id }}')">
                                    <span wire:loading wire:target="showFlowDetails('{{ $row->batch_id }}')" class="prh-spinner-sm"></span>
                                    <span wire:loading.remove wire:target="showFlowDetails('{{ $row->batch_id }}')">Selengkapnya</span>
                                    <span wire:loading wire:target="showFlowDetails('{{ $row->batch_id }}')">Memuat...</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="prh-empty-inline">Belum ada data riwayat pengajuan barang.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="prh-list-loading" wire:loading.flex wire:target="search,requestDate,loadMore">
                    <span class="prh-spinner" aria-hidden="true"></span>
                    <span>Memuat data pengajuan...</span>
                </div>

                @if($hasMoreRows)
                <div class="prh-load-sentinel" wire:intersect="loadMore" aria-hidden="true"></div>
                @endif
            </div>

        </x-filament::section>
    </div>

    @if($showFlowModal && $selectedFlowHeader)
    <div class="prh-modal-overlay" :class="{ 'prh-modal-overlay-full': full }" wire:click.self="closeFlowDetails" x-data="{ full: false }">
        <div class="prh-modal" :class="{ 'prh-modal-fullscreen': full }" role="dialog" aria-modal="true" aria-labelledby="prh-modal-title">
            <div class="prh-modal-header">
                <div>
                    <div class="prh-modal-kicker">Nomor PR</div>
                    <div class="prh-modal-title" id="prh-modal-title">{{ $selectedFlowHeader['document_no'] ?? '-' }}</div>
                    <div class="prh-modal-subtitle">Status terakhir: {{ $selectedFlowHeader['status_label'] ?? '-' }}</div>
                </div>
                <div class="prh-modal-header-actions">
                    <button class="prh-modal-full-toggle" type="button" @click="full = !full" :aria-label="full ? 'Keluar fullscreen' : 'Layar Penuh'" :title="full ? 'Keluar fullscreen' : 'Layar Penuh'">
                        <template x-if="!full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                        </template>
                        <template x-if="full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 4v5H4m11-5v5h5M9 20v-5H4m11 5v-5h5" />
                            </svg>
                        </template>
                    </button>
                    <button class="prh-close" type="button" wire:click="closeFlowDetails" aria-label="Tutup">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="prh-modal-body">
                <div class="prh-info-head">Informasi Pengajuan</div>

                <div class="prh-info-sections">
                    <section class="prh-info-card">
                        <div class="prh-info-title">IDENTITAS PR</div>
                        <div class="prh-info-grid">
                            <div class="prh-info-item">
                                <span class="prh-info-label">Judul Title</span>
                                <span class="prh-info-value">{{ $selectedFlowHeader['title'] ?? '-' }}</span>
                            </div>
                            <div class="prh-info-item">
                                <span class="prh-info-label">Nomor Dokumen</span>
                                <span class="prh-info-value">{{ $selectedFlowHeader['document_no'] ?? '-' }}</span>
                            </div>
                            <div class="prh-info-item">
                                <span class="prh-info-label">Tanggal Pengajuan</span>
                                <span class="prh-info-value">{{ $selectedFlowHeader['request_date'] ?? '-' }}</span>
                            </div>
                        </div>
                    </section>

                    <section class="prh-info-card">
                        <div class="prh-info-title">KAPAL</div>
                        <div class="prh-info-grid">
                            <div class="prh-info-item">
                                <span class="prh-info-label">Nama Kapal</span>
                                <span class="prh-info-value">{{ $selectedFlowHeader['vessel_name'] ?? '-' }}</span>
                            </div>
                            <div class="prh-info-item">
                                <span class="prh-info-label">Departemen (Mesin/Dek)</span>
                                <span class="prh-info-value">{{ $selectedFlowHeader['needs'] ?? '-' }}</span>
                            </div>
                            <div class="prh-info-item">
                                <span class="prh-info-label">Tanggal Pengajuan (Client)</span>
                                <span class="prh-info-value">{{ $selectedFlowHeader['client_request_date'] ?? '-' }}</span>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="prh-items-head">
                    <div>
                        <div class="prh-items-title">Daftar Barang</div>
                        <div class="prh-items-count">Jumlah Barang: {{ count($latestItems) }}</div>
                    </div>
                    <button type="button" class="prh-change-toggle" wire:click="openItemChangesModal">
                        <span wire:loading wire:target="openItemChangesModal" class="prh-spinner-sm"></span>
                        <span wire:loading.remove wire:target="openItemChangesModal">Lihat Perubahan Data</span>
                        <span wire:loading wire:target="openItemChangesModal">Memuat...</span>
                    </button>
                </div>

                <div class="prh-item-table-wrap">
                    <table class="prh-item-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Nama / Jenis Barang</th>
                                <th>Ukuran / Spesifikasi</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Sisa</th>
                                <th>Klasifikasi Urgensi</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestItems as $item)
                            <tr>
                                <td>{{ $item['category'] ?? '-' }}</td>
                                <td>{{ $item['item'] ?? '-' }}</td>
                                <td>{{ $item['size'] ?? '-' }}</td>
                                <td>{{ $item['quantity'] ?? '-' }}</td>
                                <td>{{ $item['unit'] ?? '-' }}</td>
                                <td>{{ $item['remaining'] ?? '-' }}</td>
                                <td>{{ $item['item_priority'] ?? '-' }}</td>
                                <td>{{ $item['description'] ?? '-' }}</td>
                                <td>
                                    @php
                                    $itemStatusColor = in_array(($item['status_color'] ?? 'gray'), ['warning', 'danger', 'success', 'info', 'gray'], true) ? ($item['status_color'] ?? 'gray') : 'gray';
                                    @endphp
                                    <span class="prh-badge prh-badge-{{ $itemStatusColor }}">
                                        <span class="prh-badge-dot"></span>
                                        {{ $item['status_label'] ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="prh-item-empty">Data barang tidak tersedia pada payload terakhir.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="prh-modal-footer">
                <button type="button" class="prh-modal-footer-close" wire:click="closeFlowDetails">Tutup</button>
            </div>
        </div>
    </div>
    @endif

    @if($showFlowModal && $showItemChangesModal)
    <div class="prh-modal-overlay prh-modal-overlay-top" :class="{ 'prh-modal-overlay-full': full }" wire:click.self="closeItemChangesModal" x-data="{ full: false }">
        <div class="prh-modal prh-change-modal" :class="{ 'prh-modal-fullscreen': full }" role="dialog" aria-modal="true" aria-labelledby="prh-change-modal-title">
            <div class="prh-modal-header prh-modal-header-change">
                <div>
                    <div class="prh-modal-kicker">Perubahan Data</div>
                    <div class="prh-modal-title" id="prh-change-modal-title">Riwayat Perubahan Barang</div>
                    <div class="prh-modal-subtitle"></div>
                </div>
                <div class="prh-modal-header-actions">
                    <button class="prh-modal-full-toggle" type="button" @click="full = !full" :aria-label="full ? 'Keluar fullscreen' : 'Layar Penuh'" :title="full ? 'Keluar fullscreen' : 'Layar Penuh'">
                        <template x-if="!full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                        </template>
                        <template x-if="full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 4v5H4m11-5v5h5M9 20v-5H4m11 5v-5h5" />
                            </svg>
                        </template>
                    </button>
                    <button class="prh-close" type="button" wire:click="closeItemChangesModal" aria-label="Tutup modal perubahan">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="prh-modal-body">
                <div class="prh-change-steps-scroll">
                    @forelse($itemChangeSteps as $step)
                    <section class="prh-change-step-block">
                        <div class="prh-change-step-head-modern">
                            <!-- <div class="prh-change-step-title">Snapshot Tahap {{ $step['step_no'] ?? '-' }}</div> -->
                            <div class="prh-change-step-title">{{ $step['status_label'] ?? '-' }}</div>
                            <div class="prh-change-step-meta-modern">{{ $step['changed_at'] ?? '-' }}</div>
                        </div>

                        <div class="prh-change-modern-table-wrap">
                            <table class="prh-change-modern-table">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Nama / Jenis</th>
                                        <th>Ukuran / Spesifikasi</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Sisa</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($step['rows'] ?? []) as $row)
                                    @php
                                    $changeType = $row['change'] ?? '-';
                                    $before = $row['before'] ?? [];
                                    $after = $row['after'] ?? [];
                                    $changedFields = $row['changed_fields'] ?? [];
                                    $isDeleted = !empty($row['is_deleted_current']);
                                    $isEdited = $changeType === 'Diubah';
                                    $isAdded = $changeType === 'Ditambahkan';

                                    $oldCategory = $before['category'] ?? '-';
                                    $newCategory = $after['category'] ?? '-';
                                    $oldItem = $before['item'] ?? '-';
                                    $newItem = $after['item'] ?? '-';
                                    $oldSize = $before['size'] ?? '-';
                                    $newSize = $after['size'] ?? '-';
                                    $oldQty = $before['quantity'] ?? '-';
                                    $newQty = $after['quantity'] ?? '-';
                                    $oldUnit = $before['unit'] ?? '-';
                                    $newUnit = $after['unit'] ?? '-';
                                    $oldRemaining = $before['remaining'] ?? '-';
                                    $newRemaining = $after['remaining'] ?? '-';
                                    $oldNotes = $before['notes'] ?? '-';
                                    $newNotes = $after['notes'] ?? '-';

                                    $hasBefore = !empty($before);
                                    $warnCategory = $hasBefore && in_array('category', $changedFields, true);
                                    $warnItem = $hasBefore && in_array('item', $changedFields, true);
                                    $warnSize = $hasBefore && in_array('size', $changedFields, true);
                                    $warnQty = $hasBefore && in_array('quantity', $changedFields, true);
                                    $warnUnit = $hasBefore && in_array('unit', $changedFields, true);
                                    $warnRemaining = $hasBefore && in_array('remaining', $changedFields, true);
                                    @endphp
                                    <tr class="{{ $isDeleted ? 'prh-change-row-deleted' : '' }}">
                                        <td class="{{ $warnCategory ? 'prh-cell-warning' : '' }}">
                                            <div class="prh-cell-title">{{ $newCategory !== '-' ? $newCategory : $oldCategory }}</div>
                                        </td>
                                        <td class="{{ $warnItem ? 'prh-cell-warning' : '' }}">
                                            @if($isDeleted)
                                            <div class="prh-cell-old">{{ $oldItem }}</div>
                                            @endif
                                            <div class="prh-cell-new">{{ $newItem !== '-' ? $newItem : ($isDeleted ? '-' : $oldItem) }}</div>
                                        </td>
                                        <td class="{{ $warnSize ? 'prh-cell-warning' : '' }}">
                                            @if($isDeleted)
                                            <div class="prh-cell-old">{{ $oldSize }}</div>
                                            @endif
                                            <div class="prh-cell-new">{{ $newSize !== '-' ? $newSize : ($isDeleted ? '-' : $oldSize) }}</div>
                                        </td>
                                        <td class="{{ $warnQty ? 'prh-cell-warning' : '' }}">
                                            @if($isDeleted)
                                            <div class="prh-cell-old">{{ $oldQty }}</div>
                                            @endif
                                            <div class="prh-cell-new">{{ $newQty !== '-' ? $newQty : ($isDeleted ? '-' : $oldQty) }}</div>
                                        </td>
                                        <td class="{{ $warnUnit ? 'prh-cell-warning' : '' }}">
                                            @if($isDeleted)
                                            <div class="prh-cell-old">{{ $oldUnit }}</div>
                                            @endif
                                            <div class="prh-cell-new">{{ $newUnit !== '-' ? $newUnit : ($isDeleted ? '-' : $oldUnit) }}</div>
                                        </td>
                                        <td class="{{ $warnRemaining ? 'prh-cell-warning' : '' }}">
                                            @if($isDeleted)
                                            <div class="prh-cell-old">{{ $oldRemaining }}</div>
                                            @endif
                                            <div class="prh-cell-new">{{ $newRemaining !== '-' ? $newRemaining : ($isDeleted ? '-' : $oldRemaining) }}</div>
                                        </td>
                                        <td>
                                            <span class="prh-change-type-badge {{ $isDeleted ? 'is-deleted' : ($isAdded ? 'is-added' : 'is-edited') }}">{{ $isDeleted ? 'Dihapus' : $changeType }}:</span>
                                            <span>{{ $newNotes !== '-' ? $newNotes : ($oldNotes !== '-' ? $oldNotes : '-') }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="prh-item-empty">Tidak ada perubahan item pada snapshot ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                    @empty
                    <div class="prh-no-change">Tidak ada riwayat perubahan data barang.</div>
                    @endforelse
                </div>
            </div>

            <div class="prh-modal-footer">
                <button type="button" class="prh-modal-footer-close" wire:click="closeItemChangesModal">Tutup</button>
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>