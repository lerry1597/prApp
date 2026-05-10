<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/procurement-officer-pr-list.css') }}">
    

    <div class="poc-root">

        {{-- ── Stats Strip ── --}}
        <div class="poc-stats-strip">
            <div class="poc-stat-card">
                <div class="poc-stat-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </div>
                <div class="poc-stat-body">
                    <div class="poc-stat-value">{{ $stats['total'] }}</div>
                    <div class="poc-stat-label">Total PR</div>
                </div>
            </div>

            <!-- <div class="poc-stat-card">
                <div class="poc-stat-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="poc-stat-body">
                    <div class="poc-stat-value">{{ $stats['approved'] }}</div>
                    <div class="poc-stat-label">Disetujui</div>
                </div>
            </div> -->

            <!-- <div class="poc-stat-card">
                <div class="poc-stat-icon violet">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
                <div class="poc-stat-body">
                    <div class="poc-stat-value">{{ $stats['converted'] }}</div>
                    <div class="poc-stat-label">Ke PO</div>
                </div>
            </div> -->
        </div>

        {{-- ── Toolbar ── --}}
        <div class="poc-toolbar">
            {{-- Search --}}
            <div class="poc-search-wrap">
                <svg class="poc-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Cari nomor PR, kapal, keperluan, pengaju..."
                    class="poc-search-input">
            </div>

            <div class="poc-date-range-group">
                <x-app.date-picker
                    wire-model="startDate"
                    placeholder="Tanggal mulai"
                    reset-event="po-start-date-reset"
                    :value="$startDate"
                    :show-icon="false"
                    :with-time="true"
                    :submit-with-time="true" />

                <x-app.date-picker
                    wire-model="endDate"
                    placeholder="Tanggal selesai"
                    reset-event="po-end-date-reset"
                    :value="$endDate"
                    :show-icon="false"
                    :with-time="true"
                    :submit-with-time="true" />
            </div>

            {{-- Status filter --}}
            {{-- select wire:model.live="statusFilter" class="poc-filter-select">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
            </select> --}}


            {{-- Reset --}}
            <button type="button" wire:click="resetFilters" class="poc-reset-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Reset
            </button>
        </div>

        {{-- ── Table ── --}}
        <div class="poc-table-card">
            @if($prList->isEmpty())
            <div class="poc-empty">
                <svg class="poc-empty-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <div class="poc-empty-title">Tidak ada pengajuan PR</div>
                <div class="poc-empty-sub">Belum ada data yang sesuai dengan filter yang dipilih.</div>
            </div>
            @else
            <table class="poc-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Nomor PR</th>
                        <th>Nama Kapal</th>
                        <th>Keperluan</th>
                        <th>Disetujui Oleh</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prList as $pr)
                    @php
                    $color = \App\Constants\PrStatusConstant::getColor($pr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                    $label = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? '—';
                    @endphp
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #666;">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <span class="poc-pr-number">{{ $pr->detail?->document_no ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="poc-vessel-name">{{ $pr->detail?->vessel?->name ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="poc-needs-text">{{ $pr->detail?->needs ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="poc-approver-text">{{ $pr->approver?->name ?? '—' }}</span>
                        </td>
                        <td>
                            <div class="poc-date-text">
                                {{ $pr->created_at?->timezone('Asia/Jakarta')->format('d M Y') ?? '—' }}
                            </div>
                            <div class="poc-date-text" style="margin-top:.1rem;">
                                {{ $pr->created_at?->timezone('Asia/Jakarta')->format('H:i') ?? '' }}
                            </div>
                        </td>
                        <td>
                            <span class="poc-status-pill poc-pill-{{ $color }}">
                                <span class="poc-dot poc-dot-{{ $color }}"></span>
                                {{ $label }}
                            </span>
                        </td>
                        <td>
                            <button
                                type="button"
                                class="poc-review-btn"
                                wire:click="openDetail({{ $pr->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Proses
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($prList->hasPages())
            <div class="poc-pagination">
                <div class="poc-pagination-info">
                    Menampilkan {{ $prList->firstItem() }}–{{ $prList->lastItem() }} dari {{ $prList->total() }} data
                </div>
                <div class="poc-pagination-btns">
                    @if($prList->onFirstPage())
                    <button class="poc-page-btn" disabled>‹</button>
                    @else
                    <button class="poc-page-btn" wire:click="previousPage">‹</button>
                    @endif

                    @foreach($prList->getUrlRange(max(1, $prList->currentPage() - 2), min($prList->lastPage(), $prList->currentPage() + 2)) as $page => $url)
                    <button
                        class="poc-page-btn {{ $page == $prList->currentPage() ? 'active' : '' }}"
                        wire:click="gotoPage({{ $page }})">
                        {{ $page }}
                    </button>
                    @endforeach

                    @if($prList->hasMorePages())
                    <button class="poc-page-btn" wire:click="nextPage">›</button>
                    @else
                    <button class="poc-page-btn" disabled>›</button>
                    @endif
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         DETAIL REVIEW MODAL
         ══════════════════════════════════════════ --}}
    @if($showDetailPanel && $selectedPr)
    @php
    $pr = $selectedPr;
    $det = $pr->detail;
    $color = \App\Constants\PrStatusConstant::getColor($pr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
    $label = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? '—';
    $items = $det?->items ?? collect();
    @endphp

    <div class="poc-modal-backdrop poc-modal-backdrop-fullscreen" wire:click.self="closeDetail">
        <div class="poc-detail-modal" role="dialog" aria-modal="true" aria-labelledby="poc-detail-title">

            {{-- Modal Header --}}
            <div class="poc-detail-modal-header">
                <div class="poc-detail-modal-header-left">
                    <div>
                        <div class="poc-panel-kicker">Detail Pengajuan PR</div>
                        <div class="poc-panel-pr-number" id="poc-detail-title">{{ $pr->detail?->document_no ?? '—' }}</div>
                    </div>
                    <span class="poc-status-pill poc-pill-{{ $color }}" style="font-size:.73rem;">
                        <span class="poc-dot poc-dot-{{ $color }}"></span>
                        {{ $label }}
                    </span>
                    @if($pr->currentRole)
                    <span style="font-size:.75rem;font-weight:600;color:rgba(255,255,255,.7);">
                        &mdash; ditangani: <strong style="color:#fff;">{{ $pr->currentRole->name }}</strong>
                    </span>
                    @endif
                </div>
                <button type="button" class="poc-panel-close-btn" wire:click="closeDetail" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="poc-detail-modal-body">

                {{-- Info Grid --}}
                <div>
                    <div class="poc-section-heading" style="margin-bottom:.6rem;">
                        <span class="poc-section-label">Informasi Pengajuan</span>
                        <span class="poc-section-line"></span>
                    </div>
                    <div class="poc-detail-info-grid">

                        {{-- Card: Identitas PR --}}
                        <div class="poc-info-card">
                            <div class="poc-info-card-title">Identitas PR</div>
                             @if($det?->document_no)
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-teal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Nomor PR</div>
                                    <div class="poc-info-value">{{ $det->document_no }}</div>
                                </div>
                            </div>
                            @endif
                            
                           
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-sky">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Tgl Pengajuan</div>
                                    <div class="poc-info-value" style="font-size:.85rem;">
                                        {{ $pr->created_at ? $pr->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') : '—' }}
                                    </div>
                                </div>
                            </div>
                           
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-rose">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Tgl Disetujui</div>
                                    <div class="poc-info-value" style="font-size:.85rem;">
                                        {{ $pr->approved_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        {{-- Card: Kapal & Keperluan --}}
                        <div class="poc-info-card">
                            <div class="poc-info-card-title">Kapal &amp; Keperluan</div>
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-violet">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Nama Kapal</div>
                                    <div class="poc-info-value">{{ $det?->vessel?->name ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-amber">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Departemen</div>
                                    <div class="poc-info-value">{{ $det?->needs ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="poc-info-row">
                                <div class="poc-info-row-icon poc-row-icon-green">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="green">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="poc-info-label">Disetujui Oleh</div>
                                    <div class="poc-info-value" style="font-size:.85rem;">{{ $pr->approver?->name ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Items Section --}}
                <div>
                    <div class="poc-section-heading" style="margin-bottom:.6rem;">
                        <span class="poc-section-label">Daftar Barang</span>
                        <span class="poc-section-line"></span>
                        @if($items->count())
                        <span style="font-size:.72rem;font-weight:700;color:#6d28d9;background:#ede9fe;padding:.2rem .6rem;border-radius:9999px;flex-shrink:0;">
                            {{ $items->count() }} item
                        </span>
                        @endif
                    </div>

                    @if(isset($itemDiffs) && count($itemDiffs) > 0)
                    <div style="margin-bottom: 1.2rem;">
                        <button type="button" wire:click="openDiffModal" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 0.85rem; font-size:0.75rem; font-weight:600; color:#6366f1; background:#eef2ff; border:1px solid #c7d2fe; border-radius:0.5rem; cursor:pointer; transition:all 0.2s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                            Lihat {{ count($itemDiffs) }} Perubahan oleh Approver
                        </button>
                    </div>
                    @endif

                    <div class="poc-items-wrap">
                        @if($items->isEmpty())
                        <div class="poc-no-items">Belum ada barang dalam pengajuan ini.</div>
                        @else
                        <table class="poc-items-table">
                            <thead>
                                <tr>
                                    <th style="width:2.5rem;">#</th>
                                    <th>Kategori</th>
                                    <th>Nama Barang</th>
                                    <th>Ukuran / Spek</th>
                                    <th>Jumlah Permintaan</th>
                                    <th>Jumlah Disetujui</th>
                                    <th>Satuan</th>
                                    <th>Sisa</th>
                                    <th>Klasifikasi Urgensi</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                @php
                                $itemStatus = $item->status ?? \App\Constants\PrStatusConstant::UNKNOWN;
                                $itemStatusColor = \App\Constants\PrStatusConstant::getColor($itemStatus);
                                $itemStatusLabel = \App\Constants\PrStatusConstant::getStatuses()[$itemStatus] ?? $itemStatus;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="poc-item-no">{{ $item->no ?? $loop->iteration }}</div>
                                    </td>
                                    <td>
                                        <span class="poc-item-cat-badge">{{ $item->itemCategory?->name ?? '—' }}</span>
                                    </td>
                                    <td>
                                        <div class="poc-item-type">{{ $item->type ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="poc-item-size">{{ $item->size ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <span class="poc-item-qty">{{ $item->quantity ?? '—' }}</span>
                                    </td>
                                    <td>
                                        <span class="poc-item-qty">{{ $item->quantity_approve ?? '—' }}</span>
                                    </td>
                                    <td>
                                        <span class="poc-item-unit">{{ $item->unit ?? '—' }}</span>
                                    </td>
                                    <td>
                                        @if($item->remaining !== null)
                                        <span class="poc-item-remaining">{{ $item->remaining }}</span>
                                        @else
                                        <span style="color:#94a3b8;font-size:.8rem;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="poc-item-size">{{ $item->item_priority ?? '—' }}</div>
                                    </td>
                                    <td style="max-width:180px;">
                                        <div class="poc-item-desc">{{ $item->description ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <span class="poc-status-pill poc-pill-{{ $itemStatusColor }}" style="font-size:.7rem;">
                                            <span class="poc-dot poc-dot-{{ $itemStatusColor }}"></span>
                                            {{ $itemStatusLabel }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>

            </div>{{-- /modal-body --}}

            {{-- Modal Footer --}}
            <div class="poc-detail-modal-footer">
                <button type="button" class="poc-close-action-btn" wire:click="closeDetail">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.9rem;height:.9rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tutup
                </button>
                <button type="button" class="poc-convert-btn" wire:click="openApproveModal({{ $pr->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                   Proses ke PO
                </button>
            </div>

        </div>{{-- /detail-modal --}}
    </div>{{-- /backdrop --}}
    @endif

    {{-- ══════════════════════════════════════════
         PO NUMBER MODAL (Per-Item)
         ══════════════════════════════════════════ --}}
    @if($showApproveModal)
    <div class="poc-modal-backdrop">
        <div style="width:100%; max-width:56rem; margin:auto; border-radius:1rem; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.35); display:flex; flex-direction:column; max-height:90vh; background:#fff;" role="dialog" aria-modal="true">
            

            {{-- Header --}}
            <div class="pom-header">
                <div class="pom-header-left">
                    <div class="pom-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="pom-header-title">Konversi ke Purchase Order</div>
                        <div class="pom-header-sub">Isi nomor PO untuk setiap item. Bisa sebagian atau semua sekaligus.</div>
                    </div>
                </div>
                <button type="button" class="pom-close" wire:click="closeApproveModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="pom-body">

                @if($approveError)
                <div class="pom-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ $approveError }}
                </div>
                @endif

                <!-- {{-- Bulk fill --}}
                <div class="pom-bulk-bar">
                    <div style="flex:1;">
                        <div class="pom-bulk-label">Isi semua item yang belum punya PO</div>
                        <input type="text" wire:model="bulkPoNumber" class="pom-bulk-input" placeholder="Contoh: PO-2026-0001" autocomplete="off">
                    </div>
                    <button type="button" class="pom-bulk-btn" wire:click="applyBulkPo">
                        Terapkan
                    </button>
                </div> -->

                {{-- Items table --}}
                <div class="pom-table-wrap">
                    <table class="pom-table">
                        <thead>
                            <tr>
                                <th style="width:2rem;">#</th>
                                <th>Kategori</th>
                                <th>Nama Barang</th>
                                <th>Ukuran</th>
                                <th style="width:3.5rem;">Qty</th>
                                <th style="width:3.5rem;">Satuan</th>
                                <th style="min-width:10rem;">Nomor PO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvalItems as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $item['category'] }}</td>
                                <td class="font-extrabold text-slate-900 dark:text-white">{{ $item['type'] }}</td>
                                <td class="text-slate-600 dark:text-slate-400">{{ $item['size'] }}</td>
                                <td style="text-align:center;">{{ $item['qty'] }}</td>
                                <td>{{ $item['unit'] }}</td>
                                <td>
                                    @if(!empty($item['po_number']))
                                    <span class="pom-po-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.7rem;height:.7rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $item['po_number'] }}
                                    </span>
                                    @endif
                                    <input
                                        type="text"
                                        wire:model="poNumbers.{{ $item['id'] }}"
                                        class="pom-po-input {{ !empty($poNumbers[$item['id']] ?? '') ? 'pom-has-po' : '' }}"
                                        placeholder="{{ !empty($item['po_number']) ? 'Ubah PO...' : 'Isi nomor PO' }}"
                                        autocomplete="off">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="pom-footer">
                @php
                $filledCount = collect($poNumbers)->filter(fn($po) => is_string($po) && trim($po) !== '')->count();
                $totalCount = count($approvalItems);
                @endphp
                <div class="pom-footer-info">
                    {{ $filledCount }} / {{ $totalCount }} item sudah diisi PO
                </div>
                <div class="pom-footer-actions">
                    <button type="button" class="pom-cancel-btn" wire:click="closeApproveModal">
                        Batal
                    </button>
                    <button type="button" class="pom-confirm-btn" wire:click="confirmApprove">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Simpan & Konversi
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════
         APPROVER DIFF MODAL (Comparison Table)
         ══════════════════════════════════════════ --}}
    @if($showDiffModal && !empty($diffRows))
    <div class="poc-modal-backdrop" style="z-index: 9999;">
        <div style="width:100%; max-width:52rem; margin:auto; border-radius:1rem; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.4); display:flex; flex-direction:column; max-height:90vh;" role="dialog" aria-modal="true">
            

            {{-- Header --}}
            <div class="rdm-header">
                <div class="rdm-header-left">
                    <div class="rdm-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="rdm-header-title">Riwayat Perubahan Item</div>
                        <div class="rdm-header-sub">{{ $pr->pr_number ?? '' }} — sebelum & sesudah approval</div>
                    </div>
                </div>
                <button type="button" class="rdm-close" wire:click="closeDiffModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="rdm-body">
                @if(!empty($diffSteps))
                <div class="rdm-timeline">
                    @foreach($diffSteps as $idx => $step)
                    <div class="rdm-step rdm-step-{{ $idx + 1 }}">
                        <div class="rdm-step-num">{{ $idx + 1 }}</div>
                        <div class="rdm-step-label">{{ $step['label'] }}</div>
                        <div class="rdm-step-time">{{ $step['time'] }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="rdm-table-wrap">
                    <table class="rdm-table">
                        <thead>
                            <tr>
                                <th class="rdm-th-base" style="width:2rem;">#</th>
                                <th class="rdm-th-base">Kategori</th>
                                <th class="rdm-th-base">Nama Barang</th>
                                <th class="rdm-th-base">Ukuran</th>
                                <th class="rdm-th-base">Satuan</th>
                                <th class="rdm-th-init">{{ $diffSteps[0]['label'] ?? 'Awal' }}<span class="rdm-th-sub">Jumlah</span></th>
                                <th class="rdm-th-latest">{{ $diffSteps[1]['label'] ?? 'Terbaru' }}<span class="rdm-th-sub">Jumlah</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($diffRows as $row)
                            <tr>
                                <td>{{ $row['no'] }}</td>
                                <td style="font-weight:700;">{{ $row['category'] }}</td>
                                <td style="font-weight:800;">{{ $row['type'] }}</td>
                                <td>{{ $row['size'] }}</td>
                                <td>{{ $row['unit'] }}</td>
                                <td class="rdm-td-center rdm-td-init">
                                    @if($row['init_qty'] !== null)
                                    <span style="font-weight:800;">{{ $row['init_qty'] }}</span>
                                    @else
                                    <span style="color:#475569;">—</span>
                                    @endif
                                </td>
                                <td class="rdm-td-center rdm-td-latest">
                                    @if($row['latest_qty'] !== null)
                                    <span class="rdm-qty-{{ $row['qty_status'] }}" style="font-weight:800;">
                                        {{ $row['latest_qty'] }}
                                        @if($row['qty_status'] === 'up')
                                        <span class="rdm-arrow rdm-arrow-up">▲</span>
                                        @elseif($row['qty_status'] === 'down')
                                        <span class="rdm-arrow rdm-arrow-down">▼</span>
                                        @endif
                                    </span>
                                    @else
                                    <span style="color:#475569;">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="rdm-legend">
                    <div class="rdm-legend-item">
                        <span class="rdm-legend-icon rdm-legend-up">▲</span>
                        Jumlah bertambah
                    </div>
                    <div class="rdm-legend-item">
                        <span class="rdm-legend-icon rdm-legend-down">▼</span>
                        Jumlah berkurang
                    </div>
                    <div class="rdm-legend-item">
                        <span class="rdm-legend-icon rdm-legend-same">—</span>
                        Tidak ada perubahan
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="rdm-footer">
                <button type="button" class="rdm-close-btn" wire:click="closeDiffModal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>

<script>
(() => {
    if (window.__pocModalScrollLockBound) {
        return;
    }

    window.__pocModalScrollLockBound = true;

    const setScrollLock = (locked) => {
        const body = document.body;
        const html = document.documentElement;

        if (!body || !html) {
            return;
        }

        if (locked) {
            if (!body.dataset.pocPrevOverflow) {
                body.dataset.pocPrevOverflow = body.style.overflow || '';
            }

            if (!html.dataset.pocPrevOverflow) {
                html.dataset.pocPrevOverflow = html.style.overflow || '';
            }

            body.style.overflow = 'hidden';
            html.style.overflow = 'hidden';
            return;
        }

        body.style.overflow = body.dataset.pocPrevOverflow ?? '';
        html.style.overflow = html.dataset.pocPrevOverflow ?? '';
        delete body.dataset.pocPrevOverflow;
        delete html.dataset.pocPrevOverflow;
    };

    window.addEventListener('poc-modal-scroll-lock', (event) => {
        setScrollLock(Boolean(event.detail?.locked));
    });
})();
</script>
