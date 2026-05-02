<x-filament-panels::page>
    <style>
        .pr-history-container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* ===== ELDERLY FRIENDLY TYPOGRAPHY ===== */
        .pr-list-card {
            background: #ffffff;
            border-bottom: 1px solid #94a3b8; /* Darker, more proper separator */
            transition: background-color 0.2s;
            display: flex;
            align-items: stretch;
        }

        .pr-list-card:last-child {
            border-bottom: none;
        }

        .dark .pr-list-card {
            background: #1e293b;
            border-color: #475569;
        }

        .pr-list-card:hover {
            background: #f8fafc;
        }
        .dark .pr-list-card:hover {
            background: #334155;
        }

        /* Status Stripe */
        .pr-status-stripe {
            width: 12px;
            flex-shrink: 0;
        }
        .status-pending, .status-waiting_approval { background: #f59e0b; }
        .status-approved { background: #10b981; }
        .status-rejected { background: #ef4444; }
        .status-draft { background: #64748b; }
        .status-submitted { background: #3b82f6; }
        .status-converted_to_po { background: #6366f1; }
        .status-closed { background: #1f2937; }

        .pr-card-content {
            flex: 1;
            padding: 1.5rem 2rem;
            display: grid;
            grid-template-columns: 1fr auto auto;
            align-items: center;
            gap: 2rem;
        }

        /* Main Info Section */
        .pr-main-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .pr-number-label {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .dark .pr-number-label { color: #60a5fa; }

        .pr-title-label {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }
        .dark .pr-title-label { color: #f1f5f9; }

        .pr-document-no {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.02em;
        }
        .dark .pr-document-no { color: #94a3b8; }

        .pr-meta-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: 0.5rem;
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }
        .dark .pr-meta-info { color: #94a3b8; }

        .pr-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Status Badge - Adjusted for visibility but smaller */
        .pr-status-badge {
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
            min-width: 140px;
        }

        .badge-pending, .badge-waiting_approval { background: #fffbeb; color: #92400e; border: 2px solid #fde68a; }
        .badge-approved { background: #ecfdf5; color: #065f46; border: 2px solid #a7f3d0; }
        .badge-rejected { background: #fef2f2; color: #991b1b; border: 2px solid #fecaca; }
        .badge-draft { background: #f8fafc; color: #334155; border: 2px solid #e2e8f0; }
        .badge-submitted { background: #eff6ff; color: #1e40af; border: 2px solid #bfdbfe; }
        .badge-converted_to_po { background: #eef2ff; color: #3730a3; border: 2px solid #c7d2fe; }
        .badge-closed { background: #f3f4f6; color: #111827; border: 2px solid #d1d5db; }

        .dark .badge-pending, .dark .badge-waiting_approval { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border-color: rgba(245, 158, 11, 0.3); }
        .dark .badge-approved { background: rgba(16, 185, 129, 0.1); color: #34d399; border-color: rgba(16, 185, 129, 0.3); }
        .dark .badge-rejected { background: rgba(239, 68, 68, 0.1); color: #f87171; border-color: rgba(239, 68, 68, 0.3); }
        .dark .badge-submitted { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border-color: rgba(59, 130, 246, 0.3); }
        .dark .badge-converted_to_po { background: rgba(99, 102, 241, 0.1); color: #818cf8; border-color: rgba(99, 102, 241, 0.3); }
        .dark .badge-closed { background: rgba(31, 41, 55, 0.1); color: #9ca3af; border-color: rgba(31, 41, 55, 0.3); }

        /* Action Button - Updated with Text */
        .pr-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.75rem 1.75rem;
            border-radius: 1rem;
            background: #f1f5f9;
            color: #1e40af;
            font-size: 1.1rem;
            font-weight: 800;
            transition: all 0.2s;
            cursor: pointer;
            border: 2px solid transparent;
            white-space: nowrap;
        }
        .dark .pr-action-btn { background: #334155; color: #60a5fa; }

        .pr-action-btn:hover {
            background: #1e40af;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }
        .dark .pr-action-btn:hover { background: #60a5fa; color: #0f172a; box-shadow: 0 4px 12px rgba(96, 165, 250, 0.3); }

        /* Empty State */
        .pr-empty-state {
            padding: 8rem 2rem;
            text-align: center;
            background: #ffffff;
            border: none;
            border-radius: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .dark .pr-empty-state { background: #1e293b; }

        .pr-empty-icon {
            width: 5rem;
            height: 5rem;
            margin: 0 auto 1.5rem;
            color: #94a3b8;
        }

        .pr-empty-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #475569;
        }
        .dark .pr-empty-text { color: #94a3b8; }

        @media (max-width: 768px) {
            .pr-card-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                text-align: center;
            }
            .pr-main-info { align-items: center; }
            .pr-meta-info { justify-content: center; flex-wrap: wrap; }
            .pr-action-btn { width: 100%; height: auto; padding: 1rem; }
        }

        /* Search Section */
        .pr-search-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .pr-search-input-wrapper {
            position: relative;
            width: 100%;
            max-width: 450px;
        }

        .pr-search-input {
            width: 100%;
            padding: 1rem 1.5rem 1rem 3.5rem;
            border-radius: 1.25rem;
            border: 3px solid #cbd5e1;
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            background: #ffffff;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .dark .pr-search-input {
            background: #1e293b;
            border-color: #475569;
            color: #f1f5f9;
        }

        .pr-search-input:focus {
            outline: none;
            border-color: #1e40af;
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
        }

        .dark .pr-search-input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
        }

        .pr-search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            width: 1.75rem;
            height: 1.75rem;
        }
        .dark .pr-search-icon { color: #94a3b8; }

        /* Internal Header Styles */
        /* Unified Toolbar Styles */
        .pr-toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f1f5f9;
            width: 100%;
        }
        .dark .pr-toolbar { border-bottom-color: #334155; }

        .pr-toolbar-container {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 0.5rem;
            gap: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        .dark .pr-toolbar-container {
            background: #0f172a;
            border-color: #334155;
        }

        .pr-toolbar-container:focus-within {
            border-color: #1e40af;
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
        }
        .dark .pr-toolbar-container:focus-within {
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
        }

        .pr-toolbar-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.25rem 0.75rem;
        }

        .pr-toolbar-divider {
            width: 2px;
            height: 2rem;
            background: #e2e8f0;
            margin: 0 0.5rem;
        }
        .dark .pr-toolbar-divider { background: #334155; }

        .pr-toolbar-icon {
            width: 1.5rem;
            height: 1.5rem;
            color: #64748b;
        }
        .dark .pr-toolbar-icon { color: #94a3b8; }

        .pr-toolbar-input {
            border: none !important;
            background: transparent !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            box-shadow: none !important;
            outline: none !important;
            width: auto;
        }
        .dark .pr-toolbar-input { color: #f1f5f9 !important; }

        .pr-toolbar-input::placeholder { color: #94a3b8; }

        .pr-date-input-unified {
            width: 160px;
            cursor: pointer;
            background: #eff6ff !important;
            border: 2px solid #bfdbfe !important;
            border-radius: 0.8rem;
            padding: 0.4rem 0.75rem !important;
            font-weight: 800 !important;
            color: #1e40af !important;
            accent-color: #1e40af; /* Matches calendar UI to button color */
            transition: all 0.2s;
            outline: none !important;
        }

        .pr-date-input-unified:hover {
            background: #dbeafe !important;
            border-color: #1e40af !important;
        }

        .dark .pr-date-input-unified {
            background: rgba(30, 64, 175, 0.2) !important;
            border-color: rgba(96, 165, 250, 0.3) !important;
            color: #60a5fa !important;
            accent-color: #60a5fa;
        }

        .pr-toolbar-dash {
            font-weight: 800;
            color: #94a3b8;
            margin: 0 0.25rem;
        }

        @media (max-width: 768px) {
            .pr-toolbar { justify-content: center; }
            .pr-toolbar-container {
                flex-direction: column;
                width: 100%;
                gap: 1rem;
                padding: 1rem;
            }
            .pr-toolbar-divider { display: none; }
            .pr-toolbar-section { width: 100%; justify-content: center; }
        }

        /* Flatpickr Custom Premium Theme */
        .flatpickr-calendar {
            background: #ffffff !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 20px 25px -5px rgba(30, 64, 175, 0.15), 0 10px 10px -5px rgba(30, 64, 175, 0.1) !important;
            border: 2px solid #e2e8f0 !important;
            font-family: inherit !important;
            width: 315px !important; /* Ukuran pas agar Sabtu tidak terpotong dan tidak terlalu lebar */
        }
        .flatpickr-innerContainer {
            padding: 0.5rem !important;
        }
        .dark .flatpickr-calendar {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        .flatpickr-day {
            color: #0f172a !important; /* Teks tanggal lebih gelap dan tajam */
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
        }
        .flatpickr-day.selected {
            background: #1e40af !important;
            border-color: #1e40af !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(30, 64, 175, 0.4) !important;
        }
        .dark .flatpickr-day {
            color: #f1f5f9 !important;
        }
        .dark .flatpickr-day.selected {
            background: #60a5fa !important;
            border-color: #60a5fa !important;
            color: #0f172a !important;
        }
        .flatpickr-months .flatpickr-month {
            color: #0f172a !important;
            fill: #0f172a !important;
        }
        .dark .flatpickr-months .flatpickr-month {
            color: #f1f5f9 !important;
            fill: #f1f5f9 !important;
        }
        .flatpickr-prev-month svg, .flatpickr-next-month svg {
            fill: #1e40af !important; /* Warna panah navigasi biru tegas */
            width: 14px !important;
            height: 14px !important;
        }
        .dark .flatpickr-prev-month svg, .dark .flatpickr-next-month svg {
            fill: #60a5fa !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 800 !important;
            color: #0f172a !important;
            background: transparent !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #ffffff !important;
            color: #0f172a !important;
        }
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #f1f5f9 !important;
        }
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #1e293b !important;
            color: #f1f5f9 !important;
        }
        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #1e40af !important;
        }
        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #1e40af !important;
        }
        .flatpickr-weekday {
            color: #475569 !important;
            font-weight: 800 !important;
        }
        .dark .flatpickr-weekday {
            color: #94a3b8 !important;
        }
    </style>

    {{-- Flatpickr Assets - Moved to @assets for SPA persistence --}}
    @assets
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endassets

    <div class="pr-history-container">
        <x-filament::section>
            <div class="pr-toolbar">
                <div class="pr-toolbar-container">
                    <!-- Date Section -->
                    <div class="pr-toolbar-section">
                        <svg class="pr-toolbar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <input 
                            type="text" 
                            wire:model.live="startDate" 
                            id="startDate"
                            class="pr-toolbar-input pr-date-input-unified" 
                            placeholder="yyyy-mm-dd"
                            readonly
                            x-data="{
                                init() {
                                    flatpickr($el, {
                                        dateFormat: 'Y-m-d',
                                        disableMobile: true,
                                        onChange: (selectedDates, dateStr) => {
                                            $wire.set('startDate', dateStr);
                                        }
                                    });
                                }
                            }"
                        >
                        <span class="pr-toolbar-dash">—</span>
                        <input 
                            type="text" 
                            wire:model.live="endDate" 
                            id="endDate"
                            class="pr-toolbar-input pr-date-input-unified" 
                            placeholder="yyyy-mm-dd"
                            readonly
                            x-data="{
                                init() {
                                    flatpickr($el, {
                                        dateFormat: 'Y-m-d',
                                        disableMobile: true,
                                        onChange: (selectedDates, dateStr) => {
                                            $wire.set('endDate', dateStr);
                                        }
                                    });
                                }
                            }"
                        >
                    </div>

                    <div class="pr-toolbar-divider"></div>

                    <!-- Search Section -->
                    <div class="pr-toolbar-section">
                        <svg class="pr-toolbar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <input 
                            type="text" 
                            wire:model.live.debounce.500ms="search" 
                            placeholder="Cari PR..." 
                            class="pr-toolbar-input"
                            style="width: 250px;"
                        >
                    </div>
                </div>
            </div>
            <div class="mt-0 overflow-hidden border border-slate-300 dark:border-slate-600 rounded-xl">
                @forelse($prList as $pr)
                    <div class="pr-list-card">
                        <div class="pr-status-stripe status-{{ strtolower($pr->pr_status ?? 'pending') }}"></div>
                        
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
                                </div>
                            </div>

                            <div class="pr-status-container">
                                @php
                                    $statusLabel = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? 'Menunggu';
                                @endphp
                                <div class="pr-status-badge badge-{{ strtolower($pr->pr_status ?? 'pending') }}">
                                    {{ $statusLabel }}
                                </div>
                            </div>

                            <button class="pr-action-btn">
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

            <style>
                .pr-pagination-wrapper {
                    display: flex;
                    justify-content: center;
                    width: 100%;
                    border-top: 2px solid #f1f5f9;
                    padding-top: 2.5rem;
                    margin-top: 2.5rem;
                }
                .dark .pr-pagination-wrapper { border-color: #334155; }

                .pr-pagination-nav {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.75rem;
                    width: 100%;
                    flex-wrap: nowrap;
                    white-space: nowrap;
                }

                .pr-page-btn {
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.75rem;
                    font-size: 1.1rem;
                    font-weight: 700;
                    transition: all 0.2s;
                    border: 2px solid transparent;
                }

                .pr-page-active-btn {
                    background: #f1f5f9;
                    color: #1e40af;
                    cursor: pointer;
                }
                .dark .pr-page-active-btn { background: #334155; color: #60a5fa; }

                .pr-page-active-btn:hover {
                    background: #1e40af;
                    color: #ffffff;
                }
                .dark .pr-page-active-btn:hover { background: #60a5fa; color: #0f172a; }

                .pr-page-disabled {
                    color: #94a3b8;
                    cursor: not-allowed;
                    opacity: 0.5;
                }

                .pr-page-num {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 3.5rem;
                    height: 3.5rem;
                    border-radius: 50%;
                    font-size: 1.2rem;
                    font-weight: 800;
                    transition: all 0.2s;
                }

                .pr-page-num-btn {
                    color: #475569;
                    background: transparent;
                    cursor: pointer;
                }
                .dark .pr-page-num-btn { color: #94a3b8; }

                .pr-page-num-btn:hover {
                    background: #f1f5f9;
                    color: #1e40af;
                }
                .dark .pr-page-num-btn:hover { background: #334155; color: #60a5fa; }

                .pr-page-num-current {
                    background: #1e40af;
                    color: #ffffff;
                    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
                }
                .dark .pr-page-num-current { background: #60a5fa; color: #0f172a; }
            </style>
        </x-filament::section>
    </div>

    {{-- Script manual dihapus karena sudah ditangani oleh Alpine x-init di atas --}}
</x-filament-panels::page>
