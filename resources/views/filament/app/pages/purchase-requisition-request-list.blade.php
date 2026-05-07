<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/purchase-requisition-request-list.css') }}">

    <div class="prq-container">
        <x-filament::section>
            <div class="prq-toolbar" wire:key="request-toolbar">
                <div class="prq-search-shell">
                    <svg class="prq-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                    <input
                        type="text"
                        class="prq-search"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Cari berdasarkan No PR, Kapal, atau nama barang..">
                </div>

                <x-app.date-picker
                    wire-model="requestDate"
                    placeholder="Tanggal pengajuan"
                    reset-event="pr-request-list-date-filters-reset"
                    :value="$requestDate"
                    :show-icon="false" />

                <button type="button" class="prq-date-reset" wire:click="resetDateFilters" title="Reset filter">
                    <span wire:loading wire:target="resetDateFilters" class="prq-spinner-sm"></span>
                    <span wire:loading.remove wire:target="resetDateFilters">Reset</span>
                    <span wire:loading wire:target="resetDateFilters">Mereset...</span>
                </button>
            </div>

            <div class="prq-list-table-wrap">
                <table class="prq-list-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor PR</th>
                            <th>Nama Kapal</th>
                            <th>Departemen</th>
                            <th>Status PR</th>
                            <th>Tgl Pengajuan</th>
                            <th class="prq-action-col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requestList as $row)
                        @php
                        $hintItems = $matchedItemHints[$row->id] ?? [];
                        $statusColor = \App\Constants\PrStatusConstant::getColor($row->pr_status);
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: 600;">{{ $loop->iteration }}</td>
                            <td>
                                <div class="prq-doc-no">{{ $row->detail?->document_no ?: '-' }}</div>
                                @if(!empty($hintItems))
                                <div class="prq-doc-item-hint">Barang cocok: {{ implode(', ', $hintItems) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="prq-vessel-name">{{ $row->detail?->vessel?->name ?: '-' }}</div>
                            </td>
                            <td>{{ $row->detail?->needs ?: '-' }}</td>
                            <td>
                                <span class="prq-status-text {{ $statusColor }}">
                                    {{ $statusMap[$row->pr_status] ?? $row->pr_status }}
                                </span>
                            </td>
                            <td>{{ $row->created_at?->format('d M Y') }}</td>
                            <td class="prq-action-col">
                                <button class="prq-detail-btn" wire:click="showFlowDetails({{ $row->id }})">
                                    <span wire:loading wire:target="showFlowDetails({{ $row->id }})" class="prq-spinner-sm"></span>
                                    <span wire:loading.remove wire:target="showFlowDetails({{ $row->id }})">Selengkapnya</span>
                                    <span wire:loading wire:target="showFlowDetails({{ $row->id }})">Memuat...</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="prq-empty-inline">Tidak ada permintaan barang aktif yang ditemukan.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="prq-list-loading" wire:loading.flex wire:target="search,requestDate">
                    <span class="prq-spinner" aria-hidden="true"></span>
                    <span>Memuat data...</span>
                </div>

                <div class="prq-list-loading" wire:loading.flex wire:target="loadMore">
                    <span class="prq-spinner" aria-hidden="true"></span>
                    <span>Memuat data tambahan...</span>
                </div>

                @if($hasMoreRows)
                <div class="prq-load-sentinel" wire:intersect="loadMore" aria-hidden="true"></div>
                @endif
            </div>
        </x-filament::section>
    </div>

    @if($showFlowModal && $selectedFlowHeader)
    <div class="prq-modal-overlay"
        :class="{ 'prq-modal-overlay-full': full }"
        wire:click.self="closeFlowDetails"
        x-data="{ full: false }"
        x-init="document.body.style.overflow = 'hidden'; $nextTick(() => $el.focus());"
        x-on:destroy="document.body.style.overflow = ''">
        <div class="prq-modal" :class="{ 'prq-modal-fullscreen': full }" role="dialog" aria-modal="true">
            <div class="prq-modal-header">
                <div>
                    <div class="prq-modal-kicker">NOMOR PR</div>
                    <div class="prq-modal-title">{{ $selectedFlowHeader['document_no'] ?? '-' }}</div>
                    <div class="prq-modal-status-last">Status terakhir: {{ $selectedFlowHeader['status_label'] }}</div>
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

            <div class="prq-modal-body">
                <div class="prq-section-title" style="border:none; margin-bottom: 1.5rem; font-size: 0.9rem;">INFORMASI PENGAJUAN</div>
                <div class="prq-info-sections">
                    <section class="prq-info-group">
                        <div class="prq-info-group-title">IDENTITAS PR</div>
                        <div class="prq-info-grid-simple">
                            <div class="prq-info-item">
                                <span class="prq-info-label">Judul Title</span>
                                <span class="prq-info-value">{{ $selectedFlowHeader['title'] ?? '-' }}</span>
                            </div>
                            <div class="prq-info-item">
                                <span class="prq-info-label">Nomor Dokumen</span>
                                <span class="prq-info-value">{{ $selectedFlowHeader['document_no'] ?? '-' }}</span>
                            </div>
                            <div class="prq-info-item">
                                <span class="prq-info-label">Tanggal Pengajuan</span>
                                <span class="prq-info-value">{{ $selectedFlowHeader['request_date'] ?? '-' }}</span>
                            </div>
                        </div>
                    </section>

                    <section class="prq-info-group">
                        <div class="prq-info-group-title">KAPAL</div>
                        <div class="prq-info-grid-simple">
                            <div class="prq-info-item">
                                <span class="prq-info-label">Nama Kapal</span>
                                <span class="prq-info-value">{{ $selectedFlowHeader['vessel_name'] }}</span>
                            </div>
                            <div class="prq-info-item">
                                <span class="prq-info-label">Departemen (Mesin/Dek)</span>
                                <span class="prq-info-value">{{ $selectedFlowHeader['needs'] }}</span>
                            </div>
                            <div class="prq-info-item">
                                <span class="prq-info-label">Tujuan Pengiriman</span>
                                <span class="prq-info-value">{{ $selectedFlowHeader['delivery_address'] ?? '-' }}</span>
                            </div>
                            <div class="prq-info-item">
                                <span class="prq-info-label">Tanggal Pengajuan (Kru)</span>
                                <span class="prq-info-value">{{ $selectedFlowHeader['client_request_date'] ?? '-' }}</span>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="prq-table-section">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <div>
                            <div class="prq-section-title" style="border:none; margin-bottom: 0.2rem; font-size: 0.9rem;">Daftar Barang</div>
                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Jumlah Barang: {{ count($latestItems) }}</div>
                        </div>
                        <x-filament::button color="info" size="sm" icon="heroicon-m-document-text" class="rounded-xl" wire:click="showItemChanges">
                            Lihat Perubahan Data
                        </x-filament::button>
                    </div>

                    <div class="prq-table-wrap-modal">
                        <table class="prq-items-table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Nama / Jenis Barang</th>
                                    <th>Ukuran / Spesifikasi</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestItems as $item)
                                <tr>
                                    <td>{{ $item['category'] }}</td>
                                    <td class="prq-item-name">{{ $item['item'] }}</td>
                                    <td>{{ $item['size'] }}</td>
                                    <td style="font-weight: 700;">{{ $item['quantity'] }}</td>
                                    <td>{{ $item['unit'] }}</td>
                                    <td>{{ $item['remaining'] ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="prq-modal-footer">
                <button type="button" wire:click="closeFlowDetails" class="prq-modal-footer-close">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($showFlowModal && $showItemChangesModal)
    <div class="prh-modal-overlay prh-modal-overlay-top" :class="{ 'prh-modal-overlay-full': full }" wire:click.self="closeItemChanges" x-data="{ full: false }">
        <div class="prh-modal prh-change-modal" :class="{ 'prh-modal-fullscreen': full }" role="dialog" aria-modal="true">
            <div class="prh-modal-header prh-modal-header-change">
                <div>
                    <div class="prh-modal-kicker">Perubahan Data</div>
                    <div class="prh-modal-title">Riwayat Perubahan Barang</div>
                </div>
                <div class="prh-modal-header-actions">
                    <button class="prh-modal-full-toggle" type="button" @click="full = !full" :title="full ? 'Kecilkan' : 'Layar Penuh'">
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
                    <button class="prh-close" type="button" wire:click="closeItemChanges">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="prh-modal-body">
                <div class="prh-change-legend-fixed">
                    <div class="prh-legend-item-v2">
                        <span class="prh-legend-dot-v2 bg-orange-vibrant"></span>
                        <div class="prh-legend-content">
                            <div class="prh-legend-title-v2">Data telah diubah</div>
                            <div class="prh-legend-sub">Kolom berwarna oranye menandakan bahwa data telah diubah</div>
                        </div>
                    </div>
                    <div class="prh-legend-item-v2">
                        <span class="prh-legend-dot-v2 bg-red-vibrant"></span>
                        <div class="prh-legend-content">
                            <div class="prh-legend-title-v2">Data telah dihapus</div>
                            <div class="prh-legend-sub">Baris berwarna merah menandakan bahwa data telah dihapus</div>
                        </div>
                    </div>
                </div>

                <div class="prh-change-steps-scroll">
                    @forelse($itemChangeSteps as $step)
                    <section class="prh-change-step-block">
                        <div class="prh-change-step-head-modern">
                            <div class="prh-change-step-title">{{ $step['status_label'] ?? '-' }}</div>
                            <div class="prh-change-step-meta-modern">{{ $step['changed_at'] ?? '-' }}</div>
                        </div>



                        <div class="prh-change-modern-table-wrap">
                            <table class="prh-change-modern-table">
                                <thead>
                                    <tr>
                                        <th style="width: 140px;">Kategori</th>
                                        <th>Nama / Jenis</th>
                                        <th>Ukuran / Spesifikasi</th>
                                        <th style="width: 80px;">Jumlah</th>
                                        <th style="width: 80px;">Satuan</th>
                                        <th style="width: 80px;">Sisa</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($step['rows'] as $row)
                                    @php
                                    $changeType = $row['change'];
                                    $before = $row['before'] ?? [];
                                    $after = $row['after'] ?? [];
                                    $changedFields = $row['changed_fields'] ?? [];
                                    $isDeleted = !empty($row['is_deleted_current']);
                                    $isEdited = $changeType === 'Diubah';
                                    $isAdded = $changeType === 'Ditambahkan';
                                    @endphp
                                    <tr class="{{ $isDeleted ? 'prh-change-row-deleted' : '' }}">
                                        <td>{{ $after['category'] ?? '-' }}</td>
                                        <td>
                                            <div class="prh-cell-title">{{ $after['item'] ?? '-' }}</div>
                                        </td>
                                        <td>{{ $after['size'] ?? '-' }}</td>
                                        <td>
                                            @if($isEdited && in_array('quantity', $changedFields))
                                            <div class="prh-cell-old">{{ $before['quantity'] ?? '-' }}</div>
                                            <div class="prh-cell-new">{{ $after['quantity'] ?? '-' }}</div>
                                            @else
                                            {{ $after['quantity'] ?? '-' }}
                                            @endif
                                        </td>
                                        <td>{{ $after['unit'] ?? '-' }}</td>
                                        <td>{{ $after['remaining'] ?? 0 }}</td>
                                        <td>
                                            @if($isDeleted)
                                            <span class="prh-change-type-badge is-deleted">Dihapus</span>
                                            @elseif($isAdded)
                                            <span class="prh-change-type-badge is-added">Ditambahkan</span>
                                            @elseif($isEdited)
                                            <span class="prh-change-type-badge is-edited">Diubah</span>
                                            @else
                                            <span style="color: #94a3b8; font-size: 0.75rem;">Awal: -</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                    @empty
                    <div class="prh-no-change" style="width: 100%;">Belum ada riwayat perubahan data barang.</div>
                    @endforelse
                </div>
            </div>

            <div class="prh-modal-footer">
                <button type="button" wire:click="closeItemChanges" class="prq-modal-footer-close">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>