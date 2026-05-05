<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/processed-po-list.css') }}">
    

    <div class="ppo-root">

        {{-- Stats --}}
        <div class="ppo-stats">
            <div class="ppo-stat-card">
                <div class="ppo-stat-icon ppo-icon-violet">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
                <div>
                    <div class="ppo-stat-value">{{ $stats['total'] }}</div>
                    <div class="ppo-stat-label">Total PO</div>
                </div>
            </div>
            <div class="ppo-stat-card">
                <div class="ppo-stat-icon ppo-icon-green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <div class="ppo-stat-value">{{ $stats['this_month'] }}</div>
                    <div class="ppo-stat-label">Bulan Ini</div>
                </div>
            </div>
            <div class="ppo-stat-card">
                <div class="ppo-stat-icon ppo-icon-sky">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="ppo-stat-value">{{ $stats['this_week'] }}</div>
                    <div class="ppo-stat-label">Minggu Ini</div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="ppo-toolbar">
            <div class="ppo-search-wrap">
                <svg class="ppo-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                </svg>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari nomor PR, nomor PO, kapal, keperluan, pengaju..." class="ppo-search-input">
            </div>
            <button type="button" wire:click="resetFilters" class="ppo-reset-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Reset
            </button>
        </div>

        {{-- Table --}}
        <div class="ppo-table-card">
            @if($prList->isEmpty())
            <div class="ppo-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <div class="ppo-empty-title">Belum ada PR yang dikonversi ke PO</div>
                <div class="ppo-empty-sub">Data akan muncul setelah ada PR yang berhasil dikonversi.</div>
            </div>
            @else
            <table class="ppo-table">
                <thead>
                    <tr>
                        <th>Nomor PR</th>
                        <th>Nama Kapal</th>
                        <th>Keperluan</th>
                        <th>Pengaju</th>
                        <th>Tgl Konversi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prList as $pr)
                    <tr>
                        <td><span class="ppo-pr-number">{{ $pr->pr_number }}</span></td>
                        <td><span class="ppo-vessel-name">{{ $pr->detail?->vessel?->name ?? '—' }}</span></td>
                        <td><span class="ppo-meta-text">{{ $pr->detail?->needs ?? '—' }}</span></td>
                        <td><span class="ppo-meta-text">{{ $pr->requester?->name ?? '—' }}</span></td>
                        <td>
                            <div class="ppo-date-text">{{ $pr->approved_at?->timezone('Asia/Jakarta')->format('d M Y') ?? '—' }}</div>
                            <div class="ppo-date-text" style="margin-top:.1rem;">{{ $pr->approved_at?->timezone('Asia/Jakarta')->format('H:i') ?? '' }}</div>
                        </td>
                        <td>
                            <button type="button" class="ppo-view-btn" wire:click="openDetail({{ $pr->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($prList->hasPages())
            <div class="ppo-pagination">
                <div class="ppo-pagination-info">Menampilkan {{ $prList->firstItem() }}–{{ $prList->lastItem() }} dari {{ $prList->total() }} data</div>
                <div class="ppo-pagination-btns">
                    <button class="ppo-page-btn" {{ $prList->onFirstPage() ? 'disabled' : '' }} wire:click="previousPage">‹</button>
                    @foreach($prList->getUrlRange(max(1,$prList->currentPage()-2),min($prList->lastPage(),$prList->currentPage()+2)) as $page => $url)
                    <button class="ppo-page-btn {{ $page == $prList->currentPage() ? 'active' : '' }}" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                    @endforeach
                    <button class="ppo-page-btn" {{ !$prList->hasMorePages() ? 'disabled' : '' }} wire:click="nextPage">›</button>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

    {{-- Detail Panel --}}
    @if($showDetailPanel && $selectedPr)
    @php
    $pr = $selectedPr;
    $det = $pr->detail;
    $items = $det?->items ?? collect();
    @endphp
    <div class="ppo-panel-backdrop" wire:click="closeDetail"></div>
    <div class="ppo-panel" role="dialog" aria-modal="true">
        <div class="ppo-panel-header">
            <div>
                <div class="ppo-panel-kicker">Detail Purchase Order</div>
                <div class="ppo-panel-title">{{ $pr->pr_number }}</div>
            </div>
            <button type="button" class="ppo-panel-close" wire:click="closeDetail">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="ppo-panel-body">
            {{-- Conversion date banner --}}
            @if($pr->approved_at)
            <div style="display:flex;align-items:center;gap:.75rem;padding:.85rem 1.1rem;border-radius:.875rem;background:#ecfdf5;border:1px solid #a7f3d0;">
                <div style="width:2.2rem;height:2.2rem;border-radius:.6rem;background:#d1fae5;color:#065f46;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#059669;">Dikonversi ke PO</div>
                    <div style="font-size:.9rem;font-weight:700;color:#065f46;">{{ $pr->approved_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</div>
                </div>
                @if($pr->approver)
                <div style="margin-left:auto;text-align:right;">
                    <div style="font-size:.7rem;color:#94a3b8;font-weight:600;">Oleh</div>
                    <div style="font-size:.85rem;font-weight:700;color:#065f46;">{{ $pr->approver->name }}</div>
                </div>
                @endif
            </div>
            @endif

            {{-- Info Grid --}}
            <div>
                <div class="ppo-section-heading"><span class="ppo-section-label">Informasi Pengajuan</span><span class="ppo-section-line"></span></div>
                <div class="ppo-info-grid">
                    <div class="ppo-info-card">
                        <div class="ppo-info-row">
                            <div class="ppo-row-icon ri-indigo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                                </svg></div>
                            <div>
                                <div class="ppo-info-label">Nomor PR</div>
                                <div class="ppo-info-value">{{ $pr->pr_number }}</div>
                            </div>
                        </div>
                        <div class="ppo-info-row">
                            <div class="ppo-row-icon ri-sky"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg></div>
                            <div>
                                <div class="ppo-info-label">Pengaju</div>
                                <div class="ppo-info-value">{{ $pr->requester?->name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="ppo-info-card">
                        <div class="ppo-info-row">
                            <div class="ppo-row-icon ri-violet"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg></div>
                            <div>
                                <div class="ppo-info-label">Nama Kapal</div>
                                <div class="ppo-info-value">{{ $det?->vessel?->name ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="ppo-info-row">
                            <div class="ppo-row-icon ri-amber"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z" />
                                </svg></div>
                            <div>
                                <div class="ppo-info-label">Keperluan</div>
                                <div class="ppo-info-value">{{ $det?->needs ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="ppo-info-row">
                            <div class="ppo-row-icon ri-teal"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg></div>
                            <div>
                                <div class="ppo-info-label">Tgl Konversi</div>
                                <div class="ppo-info-value" style="font-size:.85rem;">{{ $pr->approved_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div>
                <div class="ppo-section-heading">
                    <span class="ppo-section-label">Daftar Barang</span>
                    <span class="ppo-section-line"></span>
                    @if($items->count())
                    <span style="font-size:.72rem;font-weight:700;color:#065f46;background:#d1fae5;padding:.2rem .6rem;border-radius:9999px;flex-shrink:0;">{{ $items->count() }} item</span>
                    @endif
                </div>
                <div class="ppo-items-wrap">
                    @if($items->isEmpty())
                    <div style="text-align:center;padding:2rem;color:#94a3b8;font-size:.875rem;">Tidak ada item.</div>
                    @else
                    <table class="ppo-items-table">
                        <thead>
                            <tr>
                                <th style="width:2.5rem;">#</th>
                                <th>Kategori</th>
                                <th>Jenis Barang</th>
                                <th>Ukuran / Spek</th>
                                <th>Keterangan</th>
                                <th style="min-width:7rem;">Nomor PO</th>
                                <th>Satuan</th>
                                <th style="text-align:right;">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    <div class="ppo-item-no">{{ $item->no ?? $loop->iteration }}</div>
                                </td>
                                <!-- <td><span class="ppo-item-cat">{{ $item->itemCategory?->name ?? '—' }}</span></td> -->
                                <td><span>{{ $item->itemCategory?->name ?? '—' }}</span></td>
                                <td class="font-extrabold text-slate-900 dark:text-white">{{ $item->type ?? '—' }}</td>
                                <td class="text-slate-600 dark:text-slate-400">{{ $item->size ?? '—' }}</td>
                                <td style="max-width:180px;" class="text-sm text-slate-600 dark:text-slate-400">{{ $item->description ?? '—' }}</td>
                                <td>
                                    @if($item->po_number)
                                    <span class="ppo-item-cat bg-sky-50 text-sky-700 border border-sky-200">{{ $item->po_number }}</span>
                                    @else
                                    <span style="color:#94a3b8;">—</span>
                                    @endif
                                </td>
                                <td class="text-slate-500 dark:text-slate-400 font-semibold">{{ $item->unit }}</td>
                                <td style="text-align:right;" class="font-extrabold text-emerald-600 dark:text-emerald-400">{{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="ppo-panel-footer">
            <button type="button" class="ppo-close-btn" wire:click="closeDetail">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.9rem;height:.9rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tutup
            </button>
        </div>
    </div>
    @endif

</x-filament-panels::page>
