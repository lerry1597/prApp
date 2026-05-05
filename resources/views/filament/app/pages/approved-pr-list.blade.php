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
        <div class="apr-list">
            @forelse($this->prList as $pr)
            <div class="apr-row">
                <div class="apr-row-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
                <div class="apr-row-body">
                    <div class="apr-row-header">
                        <span class="apr-pr-num">{{ $pr->pr_number }}</span>
                        <span class="apr-badge">Disetujui</span>
                    </div>
                    <div class="apr-meta-grid">
                        <div class="apr-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375M8.25 9.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008z" /></svg>
                            Kapal: <span class="apr-meta-val">{{ $pr->detail?->vessel?->name ?? '-' }}</span>
                        </div>
                        <div class="apr-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            Pemohon: <span class="apr-meta-val">{{ $pr->requester?->name ?? '-' }}</span>
                        </div>
                        <div class="apr-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Disetujui Oleh: <span class="apr-meta-val">{{ $pr->approver?->name ?? 'System' }}</span>
                        </div>
                        <div class="apr-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            Tanggal: <span class="apr-meta-val">{{ $pr->approved_at?->format('d M Y') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="apr-action">
                    <button class="apr-action-btn" wire:click="openDetail({{ $pr->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Detail
                    </button>
                </div>
            </div>
            @empty
            <div class="apr-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                <h3>Belum ada PR yang disetujui</h3>
                <p>Seluruh PR yang telah disetujui akan muncul di sini.</p>
            </div>
            @endforelse
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
                        Detail PR Disetujui &mdash; {{ $selectedPr->pr_number }}
                    </div>
                    <button class="apr-modal-close" wire:click="closeDetail">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="apr-modal-body">
                    <div class="apr-info-card">
                        <div class="apr-info-grid">
                            <div class="apr-info-item">
                                <span class="apr-info-label">Pemohon</span>
                                <span class="apr-info-val">{{ $selectedPr->requester?->name ?? '-' }}</span>
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
                                <span class="apr-info-label">Tanggal Setuju</span>
                                <span class="apr-info-val">{{ $selectedPr->approved_at?->format('d M Y H:i') ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="apr-table-wrap">
                        <table class="apr-table">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kategori</th>
                                    <th>Barang</th>
                                    <th>Spesifikasi</th>
                                    <th style="text-align: center;">Jumlah</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedPr->items as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $item->itemCategory?->name ?? '-' }}</td>
                                    <td style="font-weight: 700;">{{ $item->type }}</td>
                                    <td>{{ $item->size ?? '-' }}</td>
                                    <td style="text-align: center; font-weight: 800;">{{ $item->quantity }}</td>
                                    <td>{{ $item->unit ?? '-' }}</td>
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

