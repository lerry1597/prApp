<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/approved-pr-list.css') }}">
    

    <div class="apr-container">
        <!-- Stats Header -->
        <div class="apr-header-card">
            <div class="apr-header-info">
                <h2>Laporan Purchase Requisition</h2>
                <p>Kumpulan seluruh PR yang telah mendapatkan persetujuan akhir.</p>
            </div>
            <div class="apr-header-stat">
                <div class="apr-header-stat-val">{{ $totalApproved }}</div>
                <div class="apr-header-stat-label">Total Disetujui</div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="apr-toolbar">
            <div class="apr-search-wrap">
                <svg class="apr-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" class="apr-search-input" wire:model.live.debounce.300ms="search" placeholder="Cari PR No, Kapal, atau Keperluan...">
            </div>
        </div>

        <!-- List -->
        <div class="apr-list-table-wrap">
            <table class="apr-list-table">
                <thead>
                    <tr>
                        <th>No Dokumen</th>
                        <th>Nama Kapal</th>
                        <th>Disetujui oleh</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Kebutuhan</th>
                        <th class="apr-action-col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->prList as $pr)
                    @php
                    $statusColor = \App\Constants\PrStatusConstant::getColor($pr->pr_status);
                    $statusLabel = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status;
                    @endphp
                    <tr>
                        <td class="apr-doc-no">{{ $pr->detail?->document_no ?? '-' }}</td>
                        <td>{{ $pr->detail?->vessel?->name ?? '-' }}</td>
                        <td>{{ $pr->approver?->name ?? 'System' }}</td>
                        <td>{{ $pr->detail?->request_date?->format('d M Y') ?? '-' }}</td>
                        <td>
                            <span class="apr-status-badge apr-status-{{ $statusColor }}">{{ $statusLabel }}</span>
                        </td>
                        <td>{{ $pr->detail?->needs ?? '-' }}</td>
                        <td class="apr-action-col">
                            <button class="apr-action-btn" wire:click="openDetail({{ $pr->id }})">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="apr-empty-inline">Belum ada PR yang disetujui.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $this->prList->links() }}
        </div>

        <!-- Detail Modal -->
        @if($showDetailModal && $selectedPr)
        <div class="apr-modal-overlay">
            <div class="apr-modal">
                <div class="apr-modal-header">
                    <div class="apr-modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Detail PR Disetujui &mdash; {{ $selectedPr->detail?->document_no ?? '-' }}
                    </div>
                    <button class="apr-modal-close" wire:click="closeDetail">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="apr-modal-body">
                    <div class="apr-info-card">
                        <div class="apr-info-grid">
                            <div class="apr-info-item">
                                <span class="apr-info-label">Tujuan Pengiriman</span>
                                <span class="apr-info-val">{{ $selectedPr->detail?->delivery_address ?? '-' }}</span>
                            </div>
                            <div class="apr-info-item">
                                <span class="apr-info-label">Kapal</span>
                                <span class="apr-info-val">{{ $selectedPr->detail?->vessel?->name ?? '-' }}</span>
                            </div>
                            <div class="apr-info-item">
                                <span class="apr-info-label">Disetujui Oleh</span>
                                <span class="apr-info-val">{{ $selectedPr->approver?->name ?? 'System' }}</span>
                            </div>
                            <div class="apr-info-item">
                                <span class="apr-info-label">Tanggal Pengajuan</span>
                                <span class="apr-info-val">{{ $selectedPr->detail?->request_date ?? 'System' }}</span>
                            </div>
                            <div class="apr-info-item">
                                <span class="apr-info-label">Tanggal Setuju</span>
                                <span class="apr-info-val">{{ $selectedPr->approved_at?->format('d M Y H:i') ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="apr-table-wrap" style="overflow-x:auto;">
                        <table class="apr-table">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kategori</th>
                                    <th>Nama Barang</th>
                                    <th>Ukuran / Spek</th>
                                    <th>Jumlah Permintaan</th>
                                    <th width="140" style="text-align: center;">Jumlah Disetujui</th>
                                    <th>Satuan</th>
                                    <th>Sisa</th>
                                    <th>Klasifikasi Urgensi</th>
                                    <th>Keterangan</th>
                                    <th style="text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedPr->items as $idx => $item)
                                <tr>
                                    <td style="text-align: center; font-weight: 600;">{{ $idx + 1 }}</td>
                                    <td>{{ $item->itemCategory?->name ?? '-' }}</td>
                                    <td style="font-weight: 700;">{{ $item->type }}</td>
                                    <td>{{ $item->size ?? '-' }}</td>
                                    <td style="text-align: center; font-weight: 800;">{{ $item->quantity ?? '-' }}</td>
                                    <td style="text-align: center; font-weight: 800;">{{ $item->quantity_approve ?? '-' }}</td>
                                    <td>{{ $item->unit ?? '-' }}</td>
                                    <td style="text-align: center;">{{ $item->remaining ?? '-' }}</td>
                                    <td>{{ $item->item_priority ?? '-' }}</td>
                                    <td>{{ $item->description ?? '-' }}</td>
                                    <td style="text-align: center;">
                                        <span class="apr-status-badge apr-status-{{ $this->itemStatusColors[$item->id] ?? 'gray' }}">
                                            {{ $this->itemStatuses[$item->id] ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="apr-modal-footer">
                    <button class="apr-btn-cancel" wire:click="closeDetail">Tutup</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-filament-panels::page>

