<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/purchase-requisition.css') }}">

    {{-- Flatpickr Assets - Moved to @assets for SPA persistence --}}
    @assets
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endassets

    <div class="pr-history-container">
        <x-filament::section>
            <div class="pr-toolbar">
                <div class="pr-toolbar-grid">
                    <section class="pr-toolbar-panel">
                        <div class="pr-toolbar-panel-header">
                            <span class="pr-toolbar-icon-shell">
                                <svg class="pr-toolbar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                                </svg>
                            </span>
                            <div>
                                <div class="pr-toolbar-heading">Pencarian PR</div>
                                <div class="pr-toolbar-subheading">Cari berdasarkan nomor PR, judul, kebutuhan, atau nomor dokumen.</div>
                            </div>
                        </div>

                        <div class="pr-search-input-wrapper">
                            <svg class="pr-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                            </svg>
                            <input
                                type="text"
                                wire:model.live.debounce.500ms="search"
                                placeholder="Cari PR..."
                                class="pr-search-input">
                        </div>
                    </section>

                    <section class="pr-toolbar-panel">
                        <div class="pr-toolbar-panel-header">
                            <span class="pr-toolbar-icon-shell">
                                <svg class="pr-toolbar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M6 21h12a2.25 2.25 0 002.25-2.25V7.5A2.25 2.25 0 0018 5.25H6A2.25 2.25 0 003.75 7.5v11.25A2.25 2.25 0 006 21z" />
                                </svg>
                            </span>
                            <div>
                                <div class="pr-toolbar-heading">Filter Waktu Pengajuan</div>
                                <div class="pr-toolbar-subheading">Cari berdasarkan tanggal pengajuan</div>
                            </div>
                        </div>

                        <div class="pr-filter-layout">
                            <div class="pr-date-field" wire:ignore>
                                <label for="startDate" class="pr-filter-caption">Tanggal Mulai</label>
                                <input
                                    type="text"
                                    id="startDate"
                                    class="pr-toolbar-input pr-date-input-unified"
                                    placeholder="Pilih tanggal dan waktu awal"
                                    readonly
                                    value="{{ $startDate }}"
                                    x-data="{
                                        init() {
                                            if ($el._flatpickr) {
                                                $el._flatpickr.destroy();
                                            }

                                            const picker = flatpickr($el, {
                                                dateFormat: 'Y-m-d H:i',
                                                altInput: true,
                                                altFormat: 'd M Y, H:i',
                                                enableTime: true,
                                                time_24hr: true,
                                                minuteIncrement: 5,
                                                disableMobile: true,
                                                defaultDate: @js($startDate),
                                                onReady: (selectedDates, dateStr, instance) => {
                                                    if (instance.calendarContainer.querySelector('.pr-flatpickr-actions')) {
                                                        return;
                                                    }

                                                    const actions = document.createElement('div');
                                                    actions.className = 'pr-flatpickr-actions';

                                                    const clearBtn = document.createElement('button');
                                                    clearBtn.type = 'button';
                                                    clearBtn.className = 'pr-flatpickr-btn';
                                                    clearBtn.textContent = 'Bersihkan';
                                                    clearBtn.addEventListener('click', () => {
                                                        instance.clear();
                                                        $wire.set('startDate', null);
                                                    });

                                                    const applyBtn = document.createElement('button');
                                                    applyBtn.type = 'button';
                                                    applyBtn.className = 'pr-flatpickr-btn pr-flatpickr-btn-apply';
                                                    applyBtn.textContent = 'Pilih';
                                                    applyBtn.addEventListener('click', () => {
                                                        instance.close();
                                                    });

                                                    actions.appendChild(clearBtn);
                                                    actions.appendChild(applyBtn);
                                                    instance.calendarContainer.appendChild(actions);
                                                },
                                                onChange: (selectedDates, dateStr) => {
                                                    $wire.set('startDate', dateStr);
                                                },
                                                onClose: (selectedDates, dateStr) => {
                                                    if (!dateStr) {
                                                        $wire.set('startDate', null);
                                                    }
                                                },
                                            });

                                            window.addEventListener('pr-date-filters-reset', () => {
                                                picker.clear();
                                            });
                                        }
                                    }">
                            </div>

                            <div class="pr-date-field" wire:ignore>
                                <label for="endDate" class="pr-filter-caption">Tanggal Akhir</label>
                                <input
                                    type="text"
                                    id="endDate"
                                    class="pr-toolbar-input pr-date-input-unified"
                                    placeholder="Pilih tanggal dan waktu akhir"
                                    readonly
                                    value="{{ $endDate }}"
                                    x-data="{
                                        init() {
                                            if ($el._flatpickr) {
                                                $el._flatpickr.destroy();
                                            }

                                            const picker = flatpickr($el, {
                                                dateFormat: 'Y-m-d H:i',
                                                altInput: true,
                                                altFormat: 'd M Y, H:i',
                                                enableTime: true,
                                                time_24hr: true,
                                                minuteIncrement: 5,
                                                disableMobile: true,
                                                defaultDate: @js($endDate),
                                                onReady: (selectedDates, dateStr, instance) => {
                                                    if (instance.calendarContainer.querySelector('.pr-flatpickr-actions')) {
                                                        return;
                                                    }

                                                    const actions = document.createElement('div');
                                                    actions.className = 'pr-flatpickr-actions';

                                                    const clearBtn = document.createElement('button');
                                                    clearBtn.type = 'button';
                                                    clearBtn.className = 'pr-flatpickr-btn';
                                                    clearBtn.textContent = 'Bersihkan';
                                                    clearBtn.addEventListener('click', () => {
                                                        instance.clear();
                                                        $wire.set('endDate', null);
                                                    });

                                                    const applyBtn = document.createElement('button');
                                                    applyBtn.type = 'button';
                                                    applyBtn.className = 'pr-flatpickr-btn pr-flatpickr-btn-apply';
                                                    applyBtn.textContent = 'Pilih';
                                                    applyBtn.addEventListener('click', () => {
                                                        instance.close();
                                                    });

                                                    actions.appendChild(clearBtn);
                                                    actions.appendChild(applyBtn);
                                                    instance.calendarContainer.appendChild(actions);
                                                },
                                                onChange: (selectedDates, dateStr) => {
                                                    $wire.set('endDate', dateStr);
                                                },
                                                onClose: (selectedDates, dateStr) => {
                                                    if (!dateStr) {
                                                        $wire.set('endDate', null);
                                                    }
                                                },
                                            });

                                            window.addEventListener('pr-date-filters-reset', () => {
                                                picker.clear();
                                            });
                                        }
                                    }">
                            </div>

                            <div class="pr-filter-actions">
                                <button
                                    type="button"
                                    class="pr-filter-clear-btn"
                                    wire:click="resetDateFilters"
                                    title="Reset rentang tanggal"
                                    aria-label="Reset rentang tanggal">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            @php
            $summary = $prSummary ?? [
            'total' => $prList->total(),
            'waiting' => 0,
            'submitted' => 0,
            'approved' => 0,
            ];

            $activeFilterCount = collect([
            filled($search),
            filled($startDate),
            filled($endDate),
            ])->filter()->count();
            @endphp

            <!-- <section class="pr-overview-band" aria-label="Ringkasan daftar pengajuan Barang">
                <div class="pr-overview-head">
                    <div>
                        <h3 class="pr-overview-title">Ringkasan Pengajuan PR</h3>
                        <div class="pr-overview-subtitle">Menampilkan {{ $prList->count() }} dari {{ $prList->total() }} data berdasarkan filter aktif.</div>
                    </div>
                    <div class="pr-overview-filter-chip">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:0.9rem;height:0.9rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M6.75 12h10.5m-7.5 7.5h4.5" />
                        </svg>
                        {{ $activeFilterCount }} filter aktif
                    </div>
                </div>

                <div class="pr-overview-metrics">
                    <article class="pr-overview-card">
                        <span class="pr-overview-label">Total Pengajuan</span>
                        <span class="pr-overview-value">{{ $summary['total'] ?? 0 }}</span>
                        <span class="pr-overview-note">Dalam rentang filter saat ini</span>
                    </article>

                    <article class="pr-overview-card">
                        <span class="pr-overview-label">Menunggu Aksi</span>
                        <span class="pr-overview-value">{{ $summary['waiting'] ?? 0 }}</span>
                        <span class="pr-overview-note">Status menunggu / waiting approval</span>
                    </article>

                    <article class="pr-overview-card">
                        <span class="pr-overview-label">Sudah Diajukan</span>
                        <span class="pr-overview-value">{{ $summary['submitted'] ?? 0 }}</span>
                        <span class="pr-overview-note">Sedang diproses sistem approval</span>
                    </article>

                    <article class="pr-overview-card">
                        <span class="pr-overview-label">Disetujui</span>
                        <span class="pr-overview-value">{{ $summary['approved'] ?? 0 }}</span>
                        <span class="pr-overview-note">Siap lanjut ke tahapan berikutnya</span>
                    </article>
                </div>
            </section> -->

            <div class="pr-list-surface">
                @forelse($prList as $pr)
                @php
                $statusColor = \App\Constants\PrStatusConstant::getColor($pr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                @endphp
                <div class="pr-list-card">
                    <div class="pr-status-stripe status-{{ $statusColor }}"></div>

                    <div class="pr-card-content">
                        <div class="pr-main-info">
                            <span class="pr-number-label">{{ $pr->detail->title ?? 'Tanpa Judul' }}</span>
                            <h2 class="pr-title-label">
                                {{ $pr->pr_number }}
                                @if($pr->detail->document_no)
                                <span class="pr-document-no">{{ $pr->detail->document_no }}</span>
                                @endif
                            </h2>

                            <div class="pr-meta-info">
                                <div class="pr-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <span>{{ $pr->created_at?->format('d F Y') ?? '-' }}</span>
                                </div>

                                <div class="pr-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 14h2l-2 5H4l-2-5h2v-3l4-2h8l4 2v3z M8 11V7h3v4M13 11V5h3v6" />
                                    </svg>
                                    <span>{{ $pr->detail->vessel->name ?? 'Semua Kapal' }}</span>
                                </div>

                                <div class="pr-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                    </svg>
                                    <span>{{ $pr->detail->needs ?? '-' }}</span>
                                </div>

                                @php
                                $uniquePos = $pr->items->pluck('po_number')->filter()->unique();
                                @endphp
                                @if($uniquePos->count() > 0)
                                <div class="pr-meta-item" style="color:#059669;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span style="font-weight:800;">PO: {{ $uniquePos->implode(', ') }}</span>
                                </div>
                                @elseif($pr->po_number)
                                <div class="pr-meta-item" style="color:#059669;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span style="font-weight:800;">PO: {{ $pr->po_number }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="pr-status-container">
                            @php
                            $statusLabel = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? 'Menunggu';
                            @endphp
                            <div class="pr-status-badge badge-{{ $statusColor }}">
                                {{ $statusLabel }}
                            </div>
                        </div>

                        <button class="pr-action-btn" wire:click="showDetail({{ $pr->id }})">
                            <span>Selengkapnya</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="pr-empty-state">
                    <div class="pr-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <p class="pr-empty-text">Belum ada daftar pengajuan barang.</p>
                    <p style="color: #94a3b8; margin-top: 0.5rem; font-size: 1.1rem;">Semua pengajuan yang Anda buat akan muncul di sini.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-10 pr-pagination-wrapper">
                @if ($prList->hasPages())
                <nav role="navigation" aria-label="Pagination Navigation" class="pr-pagination-nav">
                    {{-- Previous Page Link --}}
                    @if ($prList->onFirstPage())
                    <span class="pr-page-btn pr-page-disabled">
                        Sebelumnya
                    </span>
                    @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="pr-page-btn pr-page-active-btn">
                        Sebelumnya
                    </button>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($prList->getUrlRange(1, $prList->lastPage()) as $page => $url)
                    @if ($page == $prList->currentPage())
                    <span class="pr-page-num pr-page-num-current">{{ $page }}</span>
                    @else
                    <button wire:click="gotoPage({{ $page }})" class="pr-page-num pr-page-num-btn">
                        {{ $page }}
                    </button>
                    @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($prList->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="pr-page-btn pr-page-active-btn">
                        Berikutnya
                    </button>
                    @else
                    <span class="pr-page-btn pr-page-disabled">
                        Berikutnya
                    </span>
                    @endif
                </nav>
                @endif
            </div>

            <link rel="stylesheet" href="{{ asset('css/purchase-requisition.css') }}">
        </x-filament::section>
    </div>

    {{-- ===== DETAIL MODAL ===== --}}
    @if($showDetailModal && $selectedPr)
    <div class="pr-modal-overlay" wire:click.self="closeDetail">
        <div class="pr-modal-container">
            {{-- MODAL HEADER --}}
            <div class="pr-modal-header">
                <div class="pr-modal-header-title">
                    <div class="pr-modal-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width:1.25rem;height:1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <div class="pr-modal-title">Detail Pengajuan PR</div>
                        <div class="pr-modal-subtitle">Nomor PR: {{ $selectedPr->pr_number }}</div>
                    </div>
                </div>
                <button type="button" class="pr-modal-close" wire:click="closeDetail" title="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- MODAL BODY --}}
            <div class="pr-modal-body">
                {{-- INFO SUMMARY --}}
                <div class="pr-preview-info-grid">
                    <div class="pr-preview-info-card">
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">No. Dokumen</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->detail->document_no }}</span>
                        </div>
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Nama Kapal</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->detail->vessel->name ?? '-' }}</span>
                        </div>
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Tanggal Pengajuan</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->created_at->format('d F Y') }}</span>
                        </div>
                    </div>
                    <div class="pr-preview-info-card">
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Status</span>
                            @php
                            $selectedStatusColor = \App\Constants\PrStatusConstant::getColor($selectedPr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                            @endphp
                            <span class="pr-status-badge badge-{{ $selectedStatusColor }}" style="padding: 0.2rem 1rem; font-size: 0.75rem; min-width: auto;">
                                {{ \App\Constants\PrStatusConstant::getStatuses()[$selectedPr->pr_status] ?? $selectedPr->pr_status }}
                            </span>
                        </div>
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Kebutuhan</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->detail->needs }}</span>
                        </div>
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Total Item</span>
                            <span class="pr-preview-info-value">{{ $selectedPr->detail->items->count() }} item</span>
                        </div>
                        @php
                        $uniqueDetailPos = $selectedPr->items->pluck('po_number')->filter()->unique();
                        @endphp
                        @if($uniqueDetailPos->count() > 0)
                        <div class="pr-preview-info-item">
                            <span class="pr-preview-info-label">Nomor PO</span>
                            <span class="pr-preview-info-value" style="color:#059669; font-weight:800;">{{ $uniqueDetailPos->implode(', ') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ITEMS TABLE --}}
                <div>
                    <span class="pr-preview-section-title">Daftar Barang</span>
                    <div class="pr-preview-table-wrap">
                        <div class="pr-preview-table-scroll">
                            <table class="pr-preview-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px; text-align: center;">#</th>
                                        <th>Kategori Item</th>
                                        <th>Jenis / Nama Barang</th>
                                        <th>Ukuran / Spesifikasi</th>
                                        <th style="min-width:120px;">Nomor PO</th>
                                        <th style="text-align: right;">Jumlah</th>
                                        <th>Satuan</th>
                                        <th style="text-align: right;">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedPr->detail->items as $index => $item)
                                    <tr>
                                        <td style="text-align: center; color: #94a3b8; font-weight: 700;">{{ $index + 1 }}</td>
                                        <td class="pr-col-category">{{ $item->itemCategory->name ?? '—' }}</td>
                                        <td class="font-extrabold text-slate-900 dark:text-white">{{ $item->type }}</td>
                                        <td class="text-slate-600 dark:text-slate-400">{{ $item->size }}</td>
                                        <td>
                                            @if($item->po_number)
                                            <span style="background:#ecfdf5; color:#059669; padding:.1rem .5rem; border-radius:.4rem; border:1px solid #a7f3d0; font-size:.75rem; font-weight:800;">{{ $item->po_number }}</span>
                                            @else
                                            <span style="color:#94a3b8;">—</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; font-weight: 700;">{{ $item->quantity }}</td>
                                        <td style="color: #64748b;">{{ $item->unit }}</td>
                                        <td style="text-align: right; font-weight: 700;">{{ $item->remaining }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL FOOTER --}}
            <div class="pr-modal-footer">
                <x-filament::button color="gray" wire:click="closeDetail">
                    Tutup
                </x-filament::button>
                <x-filament::button
                    color="secondary"
                    wire:click="showItemHistory"
                    style="margin-left: 0.75rem; background: #ede9fe; color: #5b21b6; border: 2px solid #ddd6fe;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;display:inline;margin-right:0.3rem;vertical-align:-2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Perubahan Item
                </x-filament::button>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== HISTORY MODAL ===== --}}
    @if($showHistoryModal && $selectedPr)
    <div class="pr-history-modal-overlay" wire:click.self="closeItemHistory">
        <div class="pr-history-modal-container">

            {{-- HEADER --}}
            <div class="pr-history-modal-header">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <div style="width:2.5rem;height:2.5rem;background:rgba(255,255,255,0.2);border-radius:0.625rem;display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width:1.25rem;height:1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:1.125rem;font-weight:700;color:#ffffff;">Riwayat Perubahan Item</div>
                        <div style="font-size:0.75rem;color:rgba(255,255,255,0.75);">PR {{ $selectedPr->pr_number }} — sebelum &amp; sesudah approval</div>
                    </div>
                </div>
                <button type="button" class="pr-modal-close" wire:click="closeItemHistory" title="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- BODY --}}
            <div class="pr-history-modal-body">

                @if(empty($itemSnapshots))
                <div style="text-align:center; padding:4rem 2rem; color:#94a3b8;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:3rem;height:3rem;margin:0 auto 1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p style="font-size:1.1rem;font-weight:700;">Belum ada riwayat perubahan item.</p>
                </div>
                @else
                {{-- TIMELINE --}}
                <div class="pr-snapshot-timeline">
                    @foreach($itemSnapshots as $sIdx => $snapshot)
                    <div class="pr-snapshot-node">
                        <div class="pr-snapshot-dot {{ $sIdx === 0 ? 'first' : '' }}">{{ $sIdx + 1 }}</div>
                        <span class="pr-snapshot-label {{ $sIdx === 0 ? 'first' : '' }}">{{ $snapshot['label'] }}</span>
                        <span style="font-size:0.6rem;color:#94a3b8;text-align:center;">
                            {{ \Carbon\Carbon::parse($snapshot['created_at'])->format('d/m/y H:i') }}
                        </span>
                    </div>
                    @if(! $loop->last)
                    <div class="pr-snapshot-line"></div>
                    @endif
                    @endforeach
                </div>

                {{-- DIFF TABLE --}}
                @php
                // Collect all unique item keys across all snapshots
                $allKeys = collect($itemSnapshots)->flatMap(fn($s) => array_keys($s['items']))->unique()->values();
                @endphp
                <div style="overflow-x:auto;">
                    <table class="pr-diff-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>Kategori</th>
                                <th>Nama Barang</th>
                                <th>Ukuran</th>
                                <th>Satuan</th>
                                @foreach($itemSnapshots as $sIdx => $snapshot)
                                <th class="snapshot-header {{ $sIdx === 0 ? 'first' : '' }}">
                                    {{ $snapshot['label'] }}<br>
                                    <span style="font-weight:500;font-size:0.65rem;">Jumlah</span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allKeys as $rowIdx => $key)
                            @php
                            // Ambil referensi item dari snapshot pertama yang memiliki key ini
                            $baseItem = null;
                            foreach ($itemSnapshots as $s) {
                            if (isset($s['items'][$key])) { $baseItem = $s['items'][$key]; break; }
                            }
                            @endphp
                            <tr>
                                <td style="text-align:center;color:#94a3b8;font-weight:700;">{{ $rowIdx + 1 }}</td>
                                <td class="pr-col-category">{{ $baseItem['category'] ?? '—' }}</td>
                                <td style="font-weight:700;">{{ $baseItem['type'] ?? '—' }}</td>
                                <td>{{ $baseItem['size'] ?? '—' }}</td>
                                <td style="color:#64748b;">{{ $baseItem['unit'] ?? '—' }}</td>
                                @foreach($itemSnapshots as $sIdx => $snapshot)
                                @php
                                $cell = $snapshot['items'][$key] ?? null;
                                $prevQty = null;
                                $dir = null; // 'up' | 'down' | 'same' | null (first)
                                if ($sIdx > 0) {
                                $prevSnap = $itemSnapshots[$sIdx - 1];
                                $prevQty = isset($prevSnap['items'][$key]) ? (float)$prevSnap['items'][$key]['quantity'] : null;
                                if ($cell !== null && $prevQty !== null) {
                                $curQty = (float)$cell['quantity'];
                                $dir = $curQty > $prevQty ? 'up' : ($curQty < $prevQty ? 'down' : 'same' );
                                    }
                                    }
                                    $changed=$dir==='up' || $dir==='down' ;
                                    @endphp
                                    <td class="snapshot-cell {{ $changed ? 'pr-diff-changed' : '' }}">
                                    @if($cell)
                                    {{ number_format((float)$cell['quantity'], 0, ',', '.') }}
                                    @if($dir === 'up')
                                    <span class="pr-diff-badge-up">▲</span>
                                    @elseif($dir === 'down')
                                    <span class="pr-diff-badge-down">▼</span>
                                    @elseif($dir === 'same')
                                    <span class="pr-diff-badge-same">＝</span>
                                    @endif
                                    @else
                                    <span class="pr-diff-no-change">—</span>
                                    @endif
                                    </td>
                                    @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- LEGEND --}}
                <div style="margin-top:1rem;display:flex;align-items:center;gap:1.25rem;font-size:0.78rem;color:#64748b;flex-wrap:wrap;">
                    <span style="display:flex;align-items:center;gap:0.35rem;">
                        <span class="pr-diff-badge-up">▲</span> Jumlah bertambah
                    </span>
                    <span style="display:flex;align-items:center;gap:0.35rem;">
                        <span class="pr-diff-badge-down">▼</span> Jumlah berkurang
                    </span>
                    <span style="display:flex;align-items:center;gap:0.35rem;">
                        <span class="pr-diff-badge-same">＝</span> Tidak ada perubahan
                    </span>
                </div>
                @endif

            </div>

            {{-- FOOTER --}}
            <div class="pr-history-modal-footer">
                <x-filament::button color="gray" wire:click="closeItemHistory">
                    Tutup
                </x-filament::button>
            </div>

        </div>
    </div>
    @endif

</x-filament-panels::page>