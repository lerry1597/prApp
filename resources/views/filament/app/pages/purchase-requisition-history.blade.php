<x-filament-panels::page>
    <style>
        .prh-container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .prh-container .fi-section,
        .prh-container .fi-section-content,
        .prh-container .fi-section-content-ctn {
            width: 100%;
        }

        .prh-toolbar {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .prh-search-shell {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border: 1px solid #dbe4f0;
            border-radius: 1rem;
            background: #ffffff;
            padding: 0.42rem 0.58rem;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            flex: 1 1 340px;
            min-width: 260px;
        }

        .dark .prh-search-shell {
            background: #0f172a;
            border-color: #334155;
            box-shadow: none;
        }

        .prh-search-icon {
            width: 1.15rem;
            height: 1.15rem;
            color: #64748b;
            flex-shrink: 0;
        }

        .dark .prh-search-icon {
            color: #94a3b8;
        }

        .prh-search {
            width: 100%;
            padding: 0.46rem 0.25rem;
            border-radius: 0;
            border: none;
            background: transparent;
            color: #0f172a;
            font-weight: 600;
            font-size: 0.88rem;
        }

        .prh-search:focus {
            outline: none;
            box-shadow: none;
        }

        .dark .prh-search {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        .prh-search-clear {
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #be123c;
            border-radius: 0.65rem;
            height: 1.85rem;
            min-width: 1.85rem;
            padding: 0 0.55rem;
            font-weight: 800;
            font-size: 0.74rem;
            cursor: pointer;
        }

        .prh-search-clear:hover {
            background: #ffe4e6;
        }

        .dark .prh-search-clear {
            border-color: rgba(251, 113, 133, 0.35);
            background: rgba(190, 24, 93, 0.14);
            color: #fda4af;
        }

        .dark .prh-search-clear:hover {
            background: rgba(190, 24, 93, 0.2);
        }

        .prh-date-shell {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border: 1px solid #dbe4f0;
            border-radius: 0.9rem;
            background: #ffffff;
            padding: 0.35rem 0.45rem;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            flex: 0 1 auto;
        }

        .dark .prh-date-shell {
            background: #0f172a;
            border-color: #334155;
            box-shadow: none;
        }

        .prh-date-input {
            height: 1.9rem;
            padding: 0.2rem 0.55rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.55rem;
            background: #f8fafc;
            color: #0f172a;
            font-size: 0.78rem;
            font-weight: 700;
            min-width: 145px;
            cursor: pointer;
        }

        .dark .prh-date-input {
            background: #111827;
            border-color: #475569;
            color: #f8fafc;
        }

        .prh-date-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.14);
        }

        .prh-date-reset {
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #be123c;
            border-radius: 0.55rem;
            height: 1.9rem;
            padding: 0 0.55rem;
            font-size: 0.72rem;
            font-weight: 800;
            cursor: pointer;
        }

        .prh-date-reset:hover {
            background: #ffe4e6;
        }

        .dark .prh-date-reset {
            border-color: rgba(251, 113, 133, 0.35);
            background: rgba(190, 24, 93, 0.14);
            color: #fda4af;
        }

        .dark .prh-date-reset:hover {
            background: rgba(190, 24, 93, 0.2);
        }

        .prh-date-input::placeholder {
            color: #94a3b8;
        }

        .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid #dbe4f0 !important;
            border-radius: 0.9rem !important;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.14) !important;
            width: 280px !important;
            font-size: 0.82rem !important;
        }

        .dark .flatpickr-calendar {
            background: #0f172a !important;
            border-color: #334155 !important;
            box-shadow: 0 16px 36px rgba(2, 6, 23, 0.5) !important;
        }

        .flatpickr-months {
            padding: 0.35rem 0.35rem 0 !important;
        }

        .flatpickr-months .flatpickr-month {
            height: 38px !important;
        }

        .flatpickr-current-month {
            font-size: 0.83rem !important;
            padding-top: 4px !important;
            display: inline-flex !important;
            align-items: center;
            gap: 0.35rem;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            appearance: none !important;
            -webkit-appearance: none !important;
            height: 1.7rem !important;
            border: 1px solid #bfdbfe !important;
            border-radius: 0.45rem !important;
            padding: 0 1.5rem 0 0.55rem !important;
            font-weight: 800 !important;
            font-size: 0.82rem !important;
            color: #0f172a !important;
            background: #eff6ff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%231e3a8a' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 0.4rem center !important;
            cursor: pointer;
            outline: none !important;
            line-height: 1.7rem !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.18) !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #ffffff !important;
            color: #0f172a !important;
            font-weight: 600 !important;
        }

        .flatpickr-current-month input.cur-year {
            font-weight: 800 !important;
            color: #0f172a !important;
        }

        .flatpickr-current-month .numInputWrapper {
            position: relative;
            width: 4.4rem;
            height: 1.7rem;
            border: 1px solid #bfdbfe;
            border-radius: 0.45rem;
            background: #eff6ff;
            overflow: hidden;
        }

        .flatpickr-current-month input.cur-year {
            height: 100% !important;
            color: #0f172a !important;
            font-weight: 800 !important;
            font-size: 0.78rem !important;
            padding-right: 1.05rem !important;
            padding-left: 0.35rem !important;
        }

        .flatpickr-current-month .numInputWrapper span {
            opacity: 1 !important;
            border: 0 !important;
            width: 1.05rem !important;
            right: 0 !important;
            cursor: pointer;
            border-radius: 0 !important;
            transition: background-color 0.18s ease;
            background: rgba(191, 219, 254, 0.45) !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp {
            top: 0 !important;
            border-left: 1px solid #93c5fd !important;
            border-bottom: 1px solid #93c5fd !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowDown {
            top: 50% !important;
            border-left: 1px solid #93c5fd !important;
        }

        .flatpickr-current-month .numInputWrapper span:hover {
            background: #dbeafe !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-left-width: 3.5px !important;
            border-right-width: 3.5px !important;
            border-bottom-width: 5.5px !important;
            border-bottom-color: #1e3a8a !important;
            margin-top: -1px !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-left-width: 3.5px !important;
            border-right-width: 3.5px !important;
            border-top-width: 5.5px !important;
            border-top-color: #1e3a8a !important;
            margin-top: 1px !important;
        }

        /* Dark mode — month dropdown */
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #f1f5f9 !important;
            background: rgba(30, 41, 59, 0.95) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%2393c5fd' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 0.4rem center !important;
            border-color: rgba(96, 165, 250, 0.42) !important;
        }

        .dark .flatpickr-current-month .flatpickr-monthDropdown-months:focus {
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.18) !important;
        }

        .dark .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #1e293b !important;
            color: #f1f5f9 !important;
            font-weight: 600 !important;
        }

        .dark .flatpickr-current-month input.cur-year {
            color: #f8fafc !important;
        }

        .dark .flatpickr-current-month .numInputWrapper {
            border-color: rgba(96, 165, 250, 0.42);
            background: rgba(59, 130, 246, 0.18);
        }

        .dark .flatpickr-current-month .numInputWrapper span {
            background: rgba(96, 165, 250, 0.22) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowUp {
            border-left-color: rgba(96, 165, 250, 0.4) !important;
            border-bottom-color: rgba(96, 165, 250, 0.4) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowDown {
            border-left-color: rgba(96, 165, 250, 0.4) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span:hover {
            background: rgba(96, 165, 250, 0.35) !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #93c5fd !important;
        }

        .dark .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #93c5fd !important;
        }

        .flatpickr-prev-month,
        .flatpickr-next-month {
            width: 1.25rem !important;
            height: 1.25rem !important;
            border-radius: 0 !important;
            border: 0 !important;
            background: transparent !important;
            color: #1e3a8a !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            top: 5px !important;
            transition: color 0.18s ease;
        }

        .flatpickr-prev-month:hover,
        .flatpickr-next-month:hover {
            background: transparent !important;
            color: #1d4ed8 !important;
        }

        .dark .flatpickr-prev-month,
        .dark .flatpickr-next-month {
            color: #93c5fd !important;
        }

        .dark .flatpickr-prev-month:hover,
        .dark .flatpickr-next-month:hover {
            color: #bfdbfe !important;
        }

        .flatpickr-prev-month svg,
        .flatpickr-next-month svg,
        .flatpickr-prev-month svg *,
        .flatpickr-next-month svg * {
            fill: currentColor !important;
            stroke: currentColor !important;
            stroke-width: 1.4px !important;
        }

        .flatpickr-weekday {
            color: #64748b !important;
            font-size: 0.67rem !important;
            font-weight: 800 !important;
        }

        .dark .flatpickr-weekday {
            color: #94a3b8 !important;
        }

        .flatpickr-day {
            border-radius: 0.55rem !important;
            color: #0f172a !important;
            font-weight: 700 !important;
        }

        .dark .flatpickr-day {
            color: #f8fafc !important;
        }

        .flatpickr-day:hover {
            background: #eff6ff !important;
            border-color: #bfdbfe !important;
        }

        .dark .flatpickr-day:hover {
            background: rgba(59, 130, 246, 0.18) !important;
            border-color: rgba(96, 165, 250, 0.3) !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
        }

        .dark .flatpickr-day.selected,
        .dark .flatpickr-day.startRange,
        .dark .flatpickr-day.endRange {
            background: #60a5fa !important;
            border-color: #60a5fa !important;
            color: #0f172a !important;
        }

        .prh-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            width: 100%;
        }

        .prh-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            align-items: center;
        }

        .dark .prh-row {
            background: #0f172a;
            border-color: #334155;
        }

        .prh-title {
            font-weight: 800;
            color: #0f172a;
            font-size: 1.03rem;
            line-height: 1.3;
        }

        .dark .prh-title {
            color: #f8fafc;
        }

        .prh-sub {
            margin-top: 0.25rem;
            color: #64748b;
            font-size: 0.86rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .dark .prh-sub {
            color: #94a3b8;
        }

        .prh-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.22rem 0.65rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .prh-badge-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .prh-badge-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .prh-badge-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .prh-badge-info {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .prh-badge-gray {
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .prh-detail-btn {
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 0.75rem;
            padding: 0.55rem 0.95rem;
            font-weight: 800;
            font-size: 0.84rem;
            cursor: pointer;
        }

        .prh-detail-btn:hover {
            background: #dbeafe;
        }

        .dark .prh-detail-btn {
            background: rgba(30, 64, 175, 0.18);
            color: #93c5fd;
            border-color: rgba(96, 165, 250, 0.35);
        }

        .prh-empty {
            border: 1px dashed #cbd5e1;
            border-radius: 1rem;
            padding: 2.4rem 1.2rem;
            min-height: clamp(360px, 58vh, 620px);
            width: 100%;
            text-align: center;
            color: #64748b;
            font-weight: 600;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .dark .prh-empty {
            border-color: #475569;
            color: #94a3b8;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
        }

        .prh-empty-icon-wrap {
            width: 5rem;
            height: 5rem;
            border-radius: 999px;
            margin: 0 auto 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #1e40af;
        }

        .dark .prh-empty-icon-wrap {
            background: rgba(59, 130, 246, 0.18);
            color: #93c5fd;
        }

        .prh-empty-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
        }

        .dark .prh-empty-title {
            color: #f8fafc;
        }

        .prh-empty-text {
            margin-top: 0.45rem;
            font-size: 0.95rem;
            max-width: 640px;
            line-height: 1.5;
            color: #64748b;
        }

        .dark .prh-empty-text {
            color: #94a3b8;
        }

        .prh-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.65);
            z-index: 9000;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 1.2rem 0.8rem;
            overflow-y: auto;
        }

        .prh-modal {
            width: 100%;
            max-width: 980px;
            background: #ffffff;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .dark .prh-modal {
            background: #0f172a;
            border-color: #334155;
        }

        .prh-modal-header {
            padding: 1rem 1.2rem;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .prh-modal-title {
            font-size: 1.03rem;
            font-weight: 800;
        }

        .prh-modal-subtitle {
            margin-top: 0.15rem;
            font-size: 0.82rem;
            opacity: 0.85;
        }

        .prh-close {
            border: 1px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            border-radius: 0.6rem;
            width: 2rem;
            height: 2rem;
            cursor: pointer;
            font-weight: 700;
        }

        .prh-modal-body {
            padding: 1rem 1.2rem 1.2rem;
        }

        .prh-step {
            border: 1px solid #e2e8f0;
            border-radius: 0.8rem;
            padding: 0.75rem 0.85rem;
            margin-bottom: 0.65rem;
            background: #f8fafc;
        }

        .dark .prh-step {
            background: #111827;
            border-color: #334155;
        }

        .prh-step-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.45rem;
        }

        .prh-step-title {
            font-size: 0.87rem;
            font-weight: 800;
            color: #0f172a;
        }

        .dark .prh-step-title {
            color: #f8fafc;
        }

        .prh-step-meta {
            color: #64748b;
            font-size: 0.78rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: 0.4rem;
        }

        .dark .prh-step-meta {
            color: #94a3b8;
        }

        .prh-change-list {
            margin: 0.45rem 0 0;
            padding-left: 1rem;
            color: #334155;
            font-size: 0.8rem;
        }

        .dark .prh-change-list {
            color: #cbd5e1;
        }

        .prh-change-list li {
            margin-bottom: 0.2rem;
        }

        .prh-pagination-wrap {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .prh-row {
                grid-template-columns: 1fr;
            }

            .prh-detail-btn {
                width: 100%;
            }

            .prh-date-shell {
                width: 100%;
                flex-wrap: wrap;
            }

            .prh-date-input,
            .prh-date-reset {
                width: 100%;
            }
        }
    </style>

    @assets
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endassets

    <div class="prh-container">
        <x-filament::section>
            <div class="prh-toolbar">
                <div class="prh-search-shell">
                    <svg class="prh-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                    <input
                        type="text"
                        class="prh-search"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Cari riwayat berdasarkan nomor PR, judul, kebutuhan, atau nomor dokumen...">

                    @if($search !== '')
                    <button type="button" class="prh-search-clear" wire:click="$set('search', '')" aria-label="Reset pencarian" title="Reset pencarian">
                        Reset
                    </button>
                    @endif
                </div>

                <div class="prh-date-shell">
                    <input
                        type="text"
                        class="prh-date-input"
                        placeholder="Tanggal awal"
                        readonly
                        value="{{ $startDate }}"
                        wire:ignore
                        x-data="{
                            init() {
                                if ($el._flatpickr) {
                                    $el._flatpickr.destroy();
                                }

                                const picker = flatpickr($el, {
                                    dateFormat: 'Y-m-d',
                                    altInput: true,
                                    altFormat: 'd M Y',
                                    defaultDate: @js($startDate),
                                    disableMobile: true,
                                    clickOpens: true,
                                    onChange: (selectedDates, dateStr) => {
                                        $wire.set('startDate', dateStr || null);
                                    },
                                });

                                window.addEventListener('pr-history-date-filters-reset', () => {
                                    picker.clear();
                                });
                            }
                        }"
                        aria-label="Filter tanggal awal">
                    <input
                        type="text"
                        class="prh-date-input"
                        placeholder="Tanggal akhir"
                        readonly
                        value="{{ $endDate }}"
                        wire:ignore
                        x-data="{
                            init() {
                                if ($el._flatpickr) {
                                    $el._flatpickr.destroy();
                                }

                                const picker = flatpickr($el, {
                                    dateFormat: 'Y-m-d',
                                    altInput: true,
                                    altFormat: 'd M Y',
                                    defaultDate: @js($endDate),
                                    disableMobile: true,
                                    clickOpens: true,
                                    onChange: (selectedDates, dateStr) => {
                                        $wire.set('endDate', dateStr || null);
                                    },
                                });

                                window.addEventListener('pr-history-date-filters-reset', () => {
                                    picker.clear();
                                });
                            }
                        }"
                        aria-label="Filter tanggal akhir">
                    <button type="button" class="prh-date-reset" wire:click="resetDateFilters" title="Reset filter tanggal">Reset</button>
                </div>
            </div>

            <div class="prh-list">
                @forelse($historyList as $row)
                @php
                $statusColor = \App\Constants\PrStatusConstant::getColor($row->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                $statusLabel = $statusLabels[$row->pr_status] ?? ($row->pr_status ?? 'Menunggu');
                @endphp
                <div class="prh-row">
                    <div>
                        <div class="prh-title">{{ $row->pr_number ?? '-' }} - {{ $row->title ?? 'Tanpa Judul' }}</div>
                        <div class="prh-sub">
                            <span>Batch: {{ $row->batch_id }}</span>
                            <span>Dokumen: {{ $row->document_no ?? '-' }}</span>
                            <span>Kebutuhan: {{ $row->needs ?? '-' }}</span>
                            <span>Update: {{ $row->updated_at?->format('d M Y H:i') ?? '-' }}</span>
                            <span class="prh-badge prh-badge-{{ $statusColor }}">{{ $statusLabel }}</span>
                        </div>
                    </div>

                    <button class="prh-detail-btn" wire:click="showFlowDetails('{{ $row->batch_id }}')">
                        Selengkapnya
                    </button>
                </div>
                @empty
                <div class="prh-empty">
                    <div class="prh-empty-icon-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" style="width:2.1rem;height:2.1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="prh-empty-title">Riwayat Pengajuan Belum Tersedia</div>
                    <div class="prh-empty-text">Belum ada flow PR selesai (approved, rejected, atau closed) untuk akun ini.</div>
                </div>
                @endforelse
            </div>

            <div class="prh-pagination-wrap">
                {{ $historyList->links() }}
            </div>
        </x-filament::section>
    </div>

    @if($showFlowModal && $selectedFlowHeader)
    <div class="prh-modal-overlay" wire:click.self="closeFlowDetails">
        <div class="prh-modal">
            <div class="prh-modal-header">
                <div>
                    <div class="prh-modal-title">Flow Approval - {{ $selectedFlowHeader['pr_number'] ?? '-' }}</div>
                    <div class="prh-modal-subtitle">
                        Batch {{ $selectedFlowHeader['batch_id'] ?? '-' }}
                        | Status Akhir: {{ $selectedFlowHeader['status_label'] ?? '-' }}
                    </div>
                </div>
                <button class="prh-close" type="button" wire:click="closeFlowDetails">x</button>
            </div>

            <div class="prh-modal-body">
                @foreach($flowSteps as $index => $step)
                @php
                $color = \App\Constants\PrStatusConstant::getColor($step['status'] ?? \App\Constants\PrStatusConstant::PENDING);
                $fieldChanges = $flowFieldChanges[$index] ?? [];
                $itemChanges = $flowItemChanges[$index] ?? ['added' => [], 'updated' => [], 'removed' => []];
                @endphp
                <div class="prh-step">
                    <div class="prh-step-head">
                        <div class="prh-step-title">Tahap {{ $index + 1 }}</div>
                        <span class="prh-badge prh-badge-{{ $color }}">{{ $step['status_label'] ?? '-' }}</span>
                    </div>

                    <div class="prh-step-meta">
                        <span>Waktu: {{ $step['created_at'] ?? '-' }}</span>
                        <span>Approver: {{ $step['approver'] ?? '-' }}</span>
                        <span>Role: {{ $step['role'] ?? '-' }}</span>
                        <span>Step: {{ $step['step'] ?? '-' }}</span>
                    </div>

                    @if(!empty($fieldChanges))
                    <ul class="prh-change-list">
                        @foreach($fieldChanges as $change)
                        <li>
                            <strong>{{ $change['field'] ?? '-' }}:</strong>
                            {{ $change['from'] ?? '-' }} -> {{ $change['to'] ?? '-' }}
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @if(!empty($itemChanges['added']) || !empty($itemChanges['updated']) || !empty($itemChanges['removed']))
                    <ul class="prh-change-list">
                        @if(!empty($itemChanges['added']))
                        <li>Item ditambahkan: {{ count($itemChanges['added']) }}</li>
                        @endif

                        @if(!empty($itemChanges['removed']))
                        <li>Item dihapus: {{ count($itemChanges['removed']) }}</li>
                        @endif

                        @if(!empty($itemChanges['updated']))
                        <li>
                            Item diubah:
                            {{ collect($itemChanges['updated'])->pluck('item')->filter()->implode(', ') }}
                        </li>
                        @endif
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>
