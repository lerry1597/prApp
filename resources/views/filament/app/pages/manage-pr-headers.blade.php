<x-filament-panels::page>
    <style>
        /* Base Container */
        .mpr-container {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* ── Stats ── */
        .mpr-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .mpr-stat-card {
            background: #ffffff;
            border-radius: 1rem;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        }

        .dark .mpr-stat-card {
            background: #1e293b;
            border-color: #334155;
        }

        .mpr-stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mpr-stat-icon.total {
            background: #eff6ff;
            color: #3b82f6;
        }

        .mpr-stat-icon.waiting {
            background: #fffbeb;
            color: #d97706;
        }

        .mpr-stat-icon.done {
            background: #ecfdf5;
            color: #10b981;
        }

        .dark .mpr-stat-icon.total {
            background: rgba(59, 130, 246, .15);
            color: #60a5fa;
        }

        .dark .mpr-stat-icon.waiting {
            background: rgba(245, 158, 11, .15);
            color: #fbbf24;
        }

        .dark .mpr-stat-icon.done {
            background: rgba(16, 185, 129, .15);
            color: #34d399;
        }

        .mpr-stat-icon svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        .mpr-stat-info {
            display: flex;
            flex-direction: column;
        }

        .mpr-stat-val {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .dark .mpr-stat-val {
            color: #f8fafc;
        }

        .mpr-stat-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark .mpr-stat-label {
            color: #94a3b8;
        }

        /* ── Toolbar ── */
        .mpr-toolbar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }

        .mpr-search-wrap {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .mpr-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.2rem;
            height: 1.2rem;
            color: #94a3b8;
        }

        .mpr-search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border-radius: 0.8rem;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            font-size: 0.9rem;
            transition: all .2s;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, .02);
        }

        .mpr-search-input:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .15);
        }

        .dark .mpr-search-input {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        .mpr-filter-select {
            padding: 0.75rem 1rem;
            border-radius: 0.8rem;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            font-size: 0.9rem;
            min-width: 200px;
        }

        .dark .mpr-filter-select {
            background: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        /* ── PR List Cards ── */
        .mpr-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .mpr-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #3b82f6;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1.25rem;
            align-items: center;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .04);
            transition: box-shadow .2s, transform .2s;
        }

        .mpr-row:hover {
            box-shadow: 0 8px 24px rgba(59, 130, 246, .12);
            transform: translateY(-2px);
        }

        .dark .mpr-row {
            background: #1e293b;
            border-color: #334155;
            border-left-color: #60a5fa;
        }

        .mpr-row-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark .mpr-row-icon {
            background: rgba(59, 130, 246, .2);
            color: #93c5fd;
        }

        .mpr-row-icon svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        .mpr-row-body {
            min-width: 0;
        }

        .mpr-row-top {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 0.4rem;
        }

        .mpr-pr-num {
            font-size: 1rem;
            font-weight: 800;
            color: #1e40af;
        }

        .dark .mpr-pr-num {
            color: #60a5fa;
        }

        .mpr-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .mpr-badge-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .mpr-badge-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .dark .mpr-badge-warning {
            background: rgba(245, 158, 11, .15);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, .3);
        }

        .dark .mpr-badge-success {
            background: rgba(16, 185, 129, .15);
            color: #34d399;
            border-color: rgba(16, 185, 129, .3);
        }

        .mpr-meta-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1.5rem;
        }

        .mpr-meta-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.8rem;
            color: #475569;
            font-weight: 600;
        }

        .dark .mpr-meta-item {
            color: #cbd5e1;
        }

        .mpr-meta-item svg {
            width: 0.9rem;
            height: 0.9rem;
            color: #94a3b8;
        }

        .mpr-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
            border-radius: 0.75rem;
            padding: 0.65rem 1.25rem;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(245, 158, 11, .2);
        }

        .mpr-action-btn:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, .3);
        }

        .mpr-action-btn svg {
            width: 1rem;
            height: 1rem;
        }

        /* ── Modal ── */
        .mpr-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.7);
            backdrop-filter: blur(6px);
            z-index: 9000;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1rem;
            overflow-y: auto;
        }

        .mpr-modal {
            width: 100%;
            max-width: 1100px;
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            animation: mprSlideUp .3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
        }

        @keyframes mprSlideUp {
            from {
                transform: translateY(2rem);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .dark .mpr-modal {
            background: #0f172a;
            border: 1px solid #334155;
        }

        .mpr-modal-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .mpr-modal-title {
            font-size: 1.15rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mpr-modal-title svg {
            width: 1.5rem;
            height: 1.5rem;
            color: #93c5fd;
        }

        .mpr-modal-close {
            background: rgba(255, 255, 255, .2);
            border: none;
            color: white;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .2s;
        }

        .mpr-modal-close:hover {
            background: rgba(255, 255, 255, .3);
        }

        .mpr-modal-body {
            padding: 1.5rem;
            background: #f8fafc;
        }

        .dark .mpr-modal-body {
            background: #020617;
        }

        /* Info Grid in Modal */
        .mpr-info-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .dark .mpr-info-card {
            background: #1e293b;
            border-color: #334155;
        }

        .mpr-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .mpr-info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .mpr-info-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .mpr-info-val {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .dark .mpr-info-label {
            color: #94a3b8;
        }

        .dark .mpr-info-val {
            color: #f1f5f9;
        }

        .mpr-info-val svg {
            width: 1rem;
            height: 1rem;
            color: #3b82f6;
        }

        /* Items Table in Modal */
        .mpr-table-wrap {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .dark .mpr-table-wrap {
            background: #1e293b;
            border-color: #334155;
        }

        .mpr-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .mpr-table th {
            background: #f1f5f9;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }

        .mpr-table td {
            padding: 0.85rem 1rem;
            font-size: 0.85rem;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 500;
        }

        .dark .mpr-table th {
            background: #0f172a;
            color: #94a3b8;
            border-color: #334155;
        }

        .dark .mpr-table td {
            color: #cbd5e1;
            border-color: #334155;
        }

        .mpr-qty-input {
            width: 80px;
            padding: 0.4rem 0.5rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            text-align: center;
            font-weight: 700;
            color: #0f172a;
            background: #f8fafc;
        }

        .mpr-qty-input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, .2);
        }

        .dark .mpr-qty-input {
            background: #0f172a;
            border-color: #475569;
            color: #f1f5f9;
        }

        .mpr-modal-footer {
            padding: 1.25rem 1.5rem;
            background: #fff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            border-radius: 0 0 1.25rem 1.25rem;
        }

        .dark .mpr-modal-footer {
            background: #1e293b;
            border-color: #334155;
        }

        .mpr-btn-cancel {
            padding: 0.65rem 1.25rem;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #475569;
            border-radius: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
        }

        .mpr-btn-cancel:hover {
            background: #f1f5f9;
        }

        .dark .mpr-btn-cancel {
            background: #0f172a;
            border-color: #475569;
            color: #cbd5e1;
        }

        .dark .mpr-btn-cancel:hover {
            background: #334155;
        }

        .mpr-btn-submit {
            padding: 0.65rem 1.5rem;
            border: none;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border-radius: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, .25);
        }

        .mpr-btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, .35);
        }

        .mpr-btn-submit svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        /* ── Empty State ── */
        .mpr-empty-state {
            text-align: center;
            padding: 3rem;
            background: #ffffff;
            border-radius: 1rem;
            border: 1px dashed #cbd5e1;
            color: #64748b;
        }

        .dark .mpr-empty-state {
            background: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }
    </style>

    <div class="mpr-container">
        <!-- Stats -->
        <div class="mpr-stats-grid">
            <div class="mpr-stat-card">
                <div class="mpr-stat-icon total">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="mpr-stat-info">
                    <div class="mpr-stat-val">{{ $this->stats['total'] }}</div>
                    <div class="mpr-stat-label">Total Pengajuan PR</div>
                </div>
            </div>
            <!-- <div class="mpr-stat-card">
                <div class="mpr-stat-icon waiting">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="mpr-stat-info">
                    <div class="mpr-stat-val">{{ $this->stats['waiting'] }}</div>
                    <div class="mpr-stat-label">Menunggu Persetujuan</div>
                </div>
            </div> -->
            <!-- <div class="mpr-stat-card">
                <div class="mpr-stat-icon done">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="mpr-stat-info">
                    <div class="mpr-stat-val">{{ $this->stats['done'] }}</div>
                    <div class="mpr-stat-label">Disetujui</div>
                </div>
            </div> -->
        </div>

        <!-- Toolbar -->
        <div class="mpr-toolbar">
            <div class="mpr-search-wrap">
                <svg class="mpr-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" class="mpr-search-input" wire:model.live.debounce.300ms="search" placeholder="Cari No PR, Pemohon, Keperluan, Kapal...">
            </div>
            <!-- <select class="mpr-filter-select" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="{{ \App\Constants\PrStatusConstant::WAITING_APPROVAL }}">Menunggu Persetujuan</option>
                <option value="{{ \App\Constants\PrStatusConstant::APPROVED }}">Disetujui</option>
            </select> -->
        </div>

        <!-- List -->
        <div class="mpr-list">
            @forelse($this->prList as $row)
            @php
            $statusColor = \App\Constants\PrStatusConstant::getColor($row->pr_status);
            $statusLabel = \App\Constants\PrStatusConstant::getStatuses()[$row->pr_status] ?? $row->pr_status;
            @endphp
            <div class="mpr-row">
                <div class="mpr-row-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="mpr-row-body">
                    <div class="mpr-row-top">
                        <span class="mpr-pr-num">{{ $row->pr_number }}</span>
                        <span class="mpr-badge mpr-badge-{{ $statusColor }}">&bull; {{ $statusLabel }}</span>
                    </div>
                    <div class="mpr-meta-grid">
                        <span class="mpr-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Pemohon: {{ $row->requester?->name ?? '-' }}
                        </span>
                        <span class="mpr-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375M8.25 9.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008z" />
                            </svg>
                            Kapal: {{ $row->detail?->vessel?->name ?? '-' }}
                        </span>
                        <span class="mpr-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            Tgl: {{ $row->detail?->request_date?->format('d M Y') ?? '-' }}
                        </span>
                    </div>
                </div>
                <button class="mpr-action-btn" wire:click="openModal({{ $row->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Tinjau
                </button>
            </div>
            @empty
            <div class="mpr-empty-state">
                Belum ada PR yang perlu disetujui.
            </div>
            @endforelse
        </div>
        <div style="margin-top: 1rem;">
            {{ $this->prList->links() }}
        </div>

        <!-- Modal -->
        @if($showModal && $selectedPr)
        <div class="mpr-modal-overlay">
            <div class="mpr-modal" role="dialog" aria-modal="true">
                <div class="mpr-modal-header">
                    <div class="mpr-modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3h5.25" />
                        </svg>
                        Tinjau & Proses Pengajuan PR &mdash; {{ $selectedPr->pr_number }}
                    </div>
                    <button class="mpr-modal-close" wire:click="closeModal" aria-label="Tutup">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mpr-modal-body">
                    <!-- PR Info -->
                    <div class="mpr-info-card">
                        <div class="mpr-info-grid">
                            <div class="mpr-info-item">
                                <span class="mpr-info-label">Pemohon</span>
                                <span class="mpr-info-val"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg> {{ $selectedPr->requester?->name ?? '-' }}</span>
                            </div>
                            <div class="mpr-info-item">
                                <span class="mpr-info-label">Kapal / Tujuan</span>
                                <span class="mpr-info-val"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375M8.25 9.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008z" />
                                    </svg> {{ $selectedPr->detail?->vessel?->name ?? '-' }}</span>
                            </div>
                            <div class="mpr-info-item">
                                <span class="mpr-info-label">Tgl Pengajuan</span>
                                <span class="mpr-info-val"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg> {{ $selectedPr->detail?->request_date?->format('d M Y') ?? '-' }}</span>
                            </div>
                            <div class="mpr-info-item">
                                <span class="mpr-info-label">Keperluan</span>
                                <span class="mpr-info-val"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                                    </svg> {{ $selectedPr->detail?->needs ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    @if($approvalError)
                    <div style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-weight: 600;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.2rem; height: 1.2rem; display: inline; margin-right: 0.5rem; vertical-align: text-bottom;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $approvalError }}
                    </div>
                    @endif

                    <!-- Items Table -->
                    <div class="mpr-table-wrap" style="overflow-x:auto;">
                        <table class="mpr-table">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kategori</th>
                                    <th>Nama Barang</th>
                                    <th>Ukuran / Spek</th>
                                    <th width="120" style="text-align: center;">Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($this->selectedItems as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $item->itemCategory?->name ?? '-' }}</td>
                                    <td style="font-weight: 700; color: #3b82f6;">{{ $item->type }}</td>
                                    <td>{{ $item->size ?? '-' }}</td>
                                    <td style="text-align: center;">
                                        <input type="number" step="0.01" min="0" class="mpr-qty-input" wire:model="itemQuantities.{{ $item->id }}">
                                    </td>
                                    <td>{{ $item->unit ?? '-' }}</td>
                                    <td>{{ $item->remaining ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #64748b;">Tidak ada barang</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mpr-modal-footer">
                    <button type="button" class="mpr-btn-cancel" wire:click="closeModal">Batal</button>
                    <button type="button" class="mpr-btn-submit" wire:click="submitApproval" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Setujui Pengajuan
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-filament-panels::page>