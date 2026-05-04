<x-filament-panels::page>
    <style>
        /* ── Root ── */
        .ppo-root { display:flex; flex-direction:column; gap:1.25rem; width:100%; }

        /* ── Stats ── */
        .ppo-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:.75rem; }
        .ppo-stat-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:1rem;
            padding:1rem 1.25rem; display:flex; align-items:center; gap:.9rem;
            box-shadow:0 1px 4px rgba(15,23,42,.05); transition:box-shadow .18s;
        }
        .ppo-stat-card:hover { box-shadow:0 4px 14px rgba(15,23,42,.09); }
        .dark .ppo-stat-card { background:#1e293b; border-color:#334155; box-shadow:none; }
        .ppo-stat-icon {
            width:2.6rem; height:2.6rem; border-radius:.75rem;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .ppo-stat-icon svg { width:1.2rem; height:1.2rem; }
        .ppo-icon-violet  { background:#ede9fe; color:#6d28d9; }
        .ppo-icon-green   { background:#d1fae5; color:#065f46; }
        .ppo-icon-sky     { background:#e0f2fe; color:#0369a1; }
        .dark .ppo-icon-violet { background:rgba(139,92,246,.15); color:#a78bfa; }
        .dark .ppo-icon-green  { background:rgba(16,185,129,.15);  color:#34d399; }
        .dark .ppo-icon-sky    { background:rgba(3,105,161,.15);   color:#38bdf8; }
        .ppo-stat-value { font-size:1.45rem; font-weight:800; color:#0f172a; line-height:1; }
        .dark .ppo-stat-value { color:#f8fafc; }
        .ppo-stat-label { font-size:.72rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.04em; margin-top:.15rem; }

        /* ── Toolbar ── */
        .ppo-toolbar {
            background:#fff; border:1px solid #e2e8f0; border-radius:1rem;
            padding:1rem 1.25rem; display:flex; flex-wrap:wrap; align-items:center; gap:.75rem;
        }
        .dark .ppo-toolbar { background:#1e293b; border-color:#334155; }
        .ppo-search-wrap { position:relative; flex:1 1 220px; min-width:0; }
        .ppo-search-icon { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:#94a3b8; pointer-events:none; }
        .ppo-search-input {
            width:100%; padding:.65rem .9rem .65rem 2.4rem; border-radius:.75rem;
            border:1px solid #cbd5e1; background:#f8fafc; color:#0f172a; font-size:.875rem; font-weight:600;
        }
        .ppo-search-input:focus { outline:none; border-color:#059669; background:#fff; box-shadow:0 0 0 3px rgba(5,150,105,.12); }
        .dark .ppo-search-input { background:#0f172a; border-color:#334155; color:#f8fafc; }
        .ppo-search-input::placeholder { color:#94a3b8; }
        .ppo-reset-btn {
            display:inline-flex; align-items:center; gap:.4rem; padding:.65rem 1rem;
            border-radius:.75rem; border:1px solid #e2e8f0; background:#f8fafc; color:#64748b;
            font-size:.8rem; font-weight:700; cursor:pointer;
        }
        .ppo-reset-btn:hover { background:#fee2e2; border-color:#fecaca; color:#be123c; }
        .dark .ppo-reset-btn { background:#1e293b; border-color:#334155; color:#94a3b8; }
        .ppo-reset-btn svg { width:.85rem; height:.85rem; }

        /* ── Table card ── */
        .ppo-table-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:1.1rem;
            box-shadow:0 1px 4px rgba(15,23,42,.04); overflow:hidden;
        }
        .dark .ppo-table-card { background:#1e293b; border-color:#334155; }
        .ppo-table { width:100%; border-collapse:collapse; font-size:.875rem; }
        .ppo-table thead th {
            background:#f8fafc; padding:.85rem 1rem; text-align:left;
            font-size:.7rem; font-weight:800; color:#64748b; text-transform:uppercase;
            letter-spacing:.07em; border-bottom:1px solid #e2e8f0; white-space:nowrap;
        }
        .dark .ppo-table thead th { background:#0f172a; color:#64748b; border-bottom-color:#334155; }
        .ppo-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .14s; }
        .ppo-table tbody tr:last-child { border-bottom:none; }
        .ppo-table tbody tr:hover { background:#f8fafc; }
        .dark .ppo-table tbody tr { border-bottom-color:#1e293b; }
        .dark .ppo-table tbody tr:hover { background:#0f172a; }
        .ppo-table td { padding:.9rem 1rem; color:#0f172a; vertical-align:middle; }
        .dark .ppo-table td { color:#e2e8f0; }

        .ppo-pr-number  { font-weight:800; color:#4f46e5; font-size:.9rem; }
        .dark .ppo-pr-number { color:#818cf8; }
        .ppo-po-badge {
            display:inline-flex; align-items:center; gap:.35rem; padding:.3rem .85rem;
            background:#d1fae5; color:#065f46; border-radius:9999px;
            font-size:.8rem; font-weight:800;
        }
        .dark .ppo-po-badge { background:rgba(16,185,129,.2); color:#34d399; }
        .ppo-vessel-name { font-weight:700; color:#0f172a; }
        .dark .ppo-vessel-name { color:#f1f5f9; }
        .ppo-meta-text  { color:#475569; font-weight:500; font-size:.85rem; }
        .dark .ppo-meta-text { color:#94a3b8; }
        .ppo-date-text  { color:#64748b; font-size:.82rem; white-space:nowrap; }
        .dark .ppo-date-text { color:#64748b; }

        .ppo-view-btn {
            display:inline-flex; align-items:center; gap:.45rem; padding:.5rem 1.1rem;
            border-radius:.75rem; background:linear-gradient(135deg,#059669,#0d9488);
            color:#fff; font-size:.8rem; font-weight:700; border:none; cursor:pointer; transition:all .18s;
        }
        .ppo-view-btn:hover {
            background:linear-gradient(135deg,#047857,#0f766e);
            transform:translateY(-1px); box-shadow:0 4px 12px rgba(5,150,105,.35);
        }
        .ppo-view-btn svg { width:.8rem; height:.8rem; }

        /* ── Empty ── */
        .ppo-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:5rem 2rem; gap:1rem; }
        .ppo-empty svg { width:4rem; height:4rem; color:#cbd5e1; }
        .ppo-empty-title { font-size:1.1rem; font-weight:700; color:#475569; }
        .ppo-empty-sub   { font-size:.875rem; color:#94a3b8; }
        .dark .ppo-empty-title { color:#94a3b8; }

        /* ── Pagination ── */
        .ppo-pagination { display:flex; align-items:center; justify-content:space-between; padding:.85rem 1rem; border-top:1px solid #f1f5f9; gap:.5rem; flex-wrap:wrap; }
        .dark .ppo-pagination { border-top-color:#1e293b; }
        .ppo-pagination-info { font-size:.8rem; color:#64748b; font-weight:600; }
        .ppo-pagination-btns { display:flex; align-items:center; gap:.35rem; }
        .ppo-page-btn { min-width:2rem; height:2rem; padding:0 .65rem; border-radius:.5rem; border:1px solid #e2e8f0; background:#f8fafc; color:#374151; font-size:.8rem; font-weight:700; cursor:pointer; transition:all .15s; }
        .ppo-page-btn:hover, .ppo-page-btn.active { background:#059669; border-color:#059669; color:#fff; }
        .ppo-page-btn:disabled { opacity:.4; cursor:not-allowed; }
        .ppo-page-btn:disabled:hover { background:#f8fafc; border-color:#e2e8f0; color:#374151; }
        .dark .ppo-page-btn { background:#1e293b; border-color:#334155; color:#cbd5e1; }
        .dark .ppo-page-btn:hover, .dark .ppo-page-btn.active { background:#059669; border-color:#059669; color:#fff; }
        .dark .ppo-page-btn:disabled:hover { background:#1e293b; border-color:#334155; color:#cbd5e1; }

        /* ── Panel ── */
        .ppo-panel-backdrop { position:fixed; inset:0; z-index:8000; background:rgba(2,6,23,.5); backdrop-filter:blur(3px); animation:ppoFade .2s ease; }
        @keyframes ppoFade   { from{opacity:0} to{opacity:1} }
        @keyframes ppoSlide  { from{transform:translateX(100%)} to{transform:translateX(0)} }
        .ppo-panel {
            position:fixed; top:0; right:0; bottom:0; width:min(680px,100vw);
            background:#fff; box-shadow:-8px 0 40px rgba(2,6,23,.18);
            z-index:8001; display:flex; flex-direction:column;
            animation:ppoSlide .25s cubic-bezier(.22,1,.36,1); overflow:hidden;
        }
        .dark .ppo-panel { background:#0f172a; box-shadow:-8px 0 40px rgba(0,0,0,.55); }
        .ppo-panel-header {
            display:flex; align-items:flex-start; justify-content:space-between;
            padding:1.5rem 1.75rem 1.25rem; border-bottom:1px solid #e2e8f0; flex-shrink:0;
            background:linear-gradient(135deg,#059669 0%,#0d9488 100%);
        }
        .dark .ppo-panel-header { border-bottom-color:#1e293b; }
        .ppo-panel-kicker  { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:rgba(255,255,255,.7); }
        .ppo-panel-title   { font-size:1.5rem; font-weight:900; color:#fff; letter-spacing:.01em; }
        .ppo-panel-po      { display:inline-flex; align-items:center; gap:.4rem; margin-top:.4rem; padding:.3rem .85rem; background:rgba(255,255,255,.2); border-radius:9999px; color:#fff; font-size:.85rem; font-weight:700; }
        .ppo-panel-close {
            width:2.25rem; height:2.25rem; border-radius:.6rem;
            border:1.5px solid rgba(255,255,255,.3); background:rgba(255,255,255,.15);
            color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;
        }
        .ppo-panel-close:hover { background:rgba(255,255,255,.28); }
        .ppo-panel-close svg { width:1.1rem; height:1.1rem; }
        .ppo-panel-body { flex:1; overflow-y:auto; padding:1.5rem 1.75rem; display:flex; flex-direction:column; gap:1.5rem; }
        .ppo-info-grid { display:grid; grid-template-columns:1fr 1fr; gap:.85rem; }
        @media(max-width:500px){ .ppo-info-grid{ grid-template-columns:1fr; } }
        .ppo-info-card { background:#f8fafc; border:1px solid #e2e8f0; border-radius:.875rem; padding:1rem 1.1rem; }
        .dark .ppo-info-card { background:#1e293b; border-color:#334155; }
        .ppo-info-card-full { grid-column:1/-1; }
        .ppo-info-row { display:flex; align-items:flex-start; gap:.5rem; padding:.45rem 0; border-bottom:1px solid #f1f5f9; }
        .dark .ppo-info-row { border-bottom-color:#334155; }
        .ppo-info-row:last-child { border-bottom:none; padding-bottom:0; }
        .ppo-info-row:first-child { padding-top:0; }
        .ppo-row-icon { width:1.5rem; height:1.5rem; border-radius:.4rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:.05rem; }
        .ppo-row-icon svg { width:.8rem; height:.8rem; }
        .ri-indigo { background:#e0e7ff; color:#4338ca; } .dark .ri-indigo { background:rgba(67,56,202,.2); color:#a5b4fc; }
        .ri-sky    { background:#e0f2fe; color:#0369a1; } .dark .ri-sky    { background:rgba(3,105,161,.2);  color:#38bdf8; }
        .ri-green  { background:#d1fae5; color:#065f46; } .dark .ri-green  { background:rgba(6,95,70,.2);   color:#6ee7b7; }
        .ri-amber  { background:#fef9c3; color:#a16207; } .dark .ri-amber  { background:rgba(161,98,7,.2);  color:#fcd34d; }
        .ri-violet { background:#ede9fe; color:#6d28d9; } .dark .ri-violet { background:rgba(109,40,217,.2);color:#c4b5fd; }
        .ri-teal   { background:#ccfbf1; color:#0f766e; } .dark .ri-teal   { background:rgba(15,118,110,.2);color:#2dd4bf; }
        .ppo-info-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:.2rem; }
        .ppo-info-value { font-size:.9rem; font-weight:700; color:#0f172a; }
        .dark .ppo-info-value { color:#f1f5f9; }
        .ppo-section-heading { display:flex; align-items:center; gap:.5rem; margin-bottom:.75rem; }
        .ppo-section-line  { flex:1; height:1px; background:#e2e8f0; }
        .dark .ppo-section-line { background:#334155; }
        .ppo-section-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.09em; color:#94a3b8; white-space:nowrap; }
        .ppo-items-wrap  { border:1px solid #e2e8f0; border-radius:.875rem; overflow:hidden; }
        .dark .ppo-items-wrap { border-color:#334155; }
        .ppo-items-table { width:100%; border-collapse:collapse; font-size:.82rem; }
        .ppo-items-table thead th { background:#f1f5f9; padding:.65rem .85rem; text-align:left; font-size:.68rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:.07em; border-bottom:1px solid #e2e8f0; }
        .dark .ppo-items-table thead th { background:#1e293b; color:#64748b; border-bottom-color:#334155; }
        .ppo-items-table tbody tr { border-bottom:1px solid #f8fafc; }
        .ppo-items-table tbody tr:last-child { border-bottom:none; }
        .ppo-items-table tbody tr:hover { background:#f8fafc; }
        .dark .ppo-items-table tbody tr { border-bottom-color:#1e293b; }
        .dark .ppo-items-table tbody tr:hover { background:#1e293b; }
        .ppo-items-table td { padding:.7rem .85rem; color:#374151; vertical-align:middle; }
        .dark .ppo-items-table td { color:#cbd5e1; }
        .ppo-item-no { width:2rem; height:2rem; border-radius:.5rem; background:#ede9fe; color:#6d28d9; font-size:.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; }
        .dark .ppo-item-no { background:rgba(139,92,246,.2); color:#c4b5fd; }
        .ppo-item-cat { display:inline-block; padding:.15rem .6rem; border-radius:9999px; background:#d1fae5; color:#065f46; font-size:.68rem; font-weight:700; }
        .dark .ppo-item-cat { background:rgba(6,95,70,.2); color:#6ee7b7; }
        .ppo-item-qty { font-weight:800; color:#059669; }
        .dark .ppo-item-qty { color:#34d399; }
        .ppo-panel-footer { flex-shrink:0; padding:1rem 1.75rem; border-top:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:flex-end; }
        .dark .ppo-panel-footer { border-top-color:#1e293b; background:#0f172a; }
        .ppo-close-btn {
            display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.4rem;
            border-radius:.75rem; border:1.5px solid #e2e8f0; background:#fff; color:#374151; font-size:.875rem; font-weight:700; cursor:pointer;
        }
        .ppo-close-btn:hover { background:#f1f5f9; }
        .dark .ppo-close-btn { background:#1e293b; border-color:#334155; color:#cbd5e1; }
        .dark .ppo-close-btn:hover { background:#334155; }
        @media(max-width:640px){ .ppo-panel{width:100vw;} .ppo-panel-body,.ppo-panel-header{padding:1.1rem;} }
    </style>

    <div class="ppo-root">

        {{-- Stats --}}
        <div class="ppo-stats">
            <div class="ppo-stat-card">
                <div class="ppo-stat-icon ppo-icon-violet">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                </div>
                <div>
                    <div class="ppo-stat-value">{{ $stats['total'] }}</div>
                    <div class="ppo-stat-label">Total PO</div>
                </div>
            </div>
            <div class="ppo-stat-card">
                <div class="ppo-stat-icon ppo-icon-green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <div>
                    <div class="ppo-stat-value">{{ $stats['this_month'] }}</div>
                    <div class="ppo-stat-label">Bulan Ini</div>
                </div>
            </div>
            <div class="ppo-stat-card">
                <div class="ppo-stat-icon ppo-icon-sky">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                <svg class="ppo-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari nomor PR, nomor PO, kapal, keperluan, pengaju..." class="ppo-search-input">
            </div>
            <button type="button" wire:click="resetFilters" class="ppo-reset-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                Reset
            </button>
        </div>

        {{-- Table --}}
        <div class="ppo-table-card">
            @if($prList->isEmpty())
                <div class="ppo-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    <div class="ppo-empty-title">Belum ada PR yang dikonversi ke PO</div>
                    <div class="ppo-empty-sub">Data akan muncul setelah ada PR yang berhasil dikonversi.</div>
                </div>
            @else
                <table class="ppo-table">
                    <thead>
                        <tr>
                            <th>Nomor PR</th>
                            <th>Nomor PO</th>
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
                                <td>
                                    <span class="ppo-po-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.8rem;height:.8rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $pr->po_number ?? '—' }}
                                    </span>
                                </td>
                                <td><span class="ppo-vessel-name">{{ $pr->detail?->vessel?->name ?? '—' }}</span></td>
                                <td><span class="ppo-meta-text">{{ $pr->detail?->needs ?? '—' }}</span></td>
                                <td><span class="ppo-meta-text">{{ $pr->requester?->name ?? '—' }}</span></td>
                                <td>
                                    <div class="ppo-date-text">{{ $pr->approved_at?->timezone('Asia/Jakarta')->format('d M Y') ?? '—' }}</div>
                                    <div class="ppo-date-text" style="margin-top:.1rem;">{{ $pr->approved_at?->timezone('Asia/Jakarta')->format('H:i') ?? '' }}</div>
                                </td>
                                <td>
                                    <button type="button" class="ppo-view-btn" wire:click="openDetail({{ $pr->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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
            $pr  = $selectedPr;
            $det = $pr->detail;
            $items = $det?->items ?? collect();
        @endphp
        <div class="ppo-panel-backdrop" wire:click="closeDetail"></div>
        <div class="ppo-panel" role="dialog" aria-modal="true">
            <div class="ppo-panel-header">
                <div>
                    <div class="ppo-panel-kicker">Detail Purchase Order</div>
                    <div class="ppo-panel-title">{{ $pr->pr_number }}</div>
                    @if($pr->po_number)
                        <div class="ppo-panel-po">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.8rem;height:.8rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            PO: {{ $pr->po_number }}
                        </div>
                    @endif
                </div>
                <button type="button" class="ppo-panel-close" wire:click="closeDetail">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="ppo-panel-body">
                {{-- Conversion date banner --}}
                @if($pr->approved_at)
                    <div style="display:flex;align-items:center;gap:.75rem;padding:.85rem 1.1rem;border-radius:.875rem;background:#ecfdf5;border:1px solid #a7f3d0;">
                        <div style="width:2.2rem;height:2.2rem;border-radius:.6rem;background:#d1fae5;color:#065f46;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                                <div class="ppo-row-icon ri-indigo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5"/></svg></div>
                                <div><div class="ppo-info-label">Nomor PR</div><div class="ppo-info-value">{{ $pr->pr_number }}</div></div>
                            </div>
                            <div class="ppo-info-row">
                                <div class="ppo-row-icon ri-green"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg></div>
                                <div><div class="ppo-info-label">Nomor PO</div><div class="ppo-info-value" style="color:#059669;">{{ $pr->po_number ?? '—' }}</div></div>
                            </div>
                            <div class="ppo-info-row">
                                <div class="ppo-row-icon ri-sky"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></div>
                                <div><div class="ppo-info-label">Pengaju</div><div class="ppo-info-value">{{ $pr->requester?->name ?? '—' }}</div></div>
                            </div>
                        </div>
                        <div class="ppo-info-card">
                            <div class="ppo-info-row">
                                <div class="ppo-row-icon ri-violet"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg></div>
                                <div><div class="ppo-info-label">Nama Kapal</div><div class="ppo-info-value">{{ $det?->vessel?->name ?? '—' }}</div></div>
                            </div>
                            <div class="ppo-info-row">
                                <div class="ppo-row-icon ri-amber"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z"/></svg></div>
                                <div><div class="ppo-info-label">Keperluan</div><div class="ppo-info-value">{{ $det?->needs ?? '—' }}</div></div>
                            </div>
                            <div class="ppo-info-row">
                                <div class="ppo-row-icon ri-teal"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg></div>
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
                                <thead><tr><th style="width:2.5rem;">#</th><th>Kategori</th><th>Jenis / Ukuran</th><th>Keterangan</th><th>Qty</th></tr></thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td><div class="ppo-item-no">{{ $item->no ?? $loop->iteration }}</div></td>
                                            <td><span class="ppo-item-cat">{{ $item->itemCategory?->name ?? '—' }}</span></td>
                                            <td>
                                                <div style="font-weight:700;color:#0f172a;" class="dark:text-slate-100">{{ $item->type ?? '—' }}</div>
                                                @if($item->size)<div style="font-size:.75rem;color:#64748b;">{{ $item->size }}</div>@endif
                                            </td>
                                            <td style="max-width:180px;font-size:.8rem;color:#475569;">{{ $item->description ?? '—' }}</td>
                                            <td><span class="ppo-item-qty">{{ $item->quantity }}</span> <span style="font-size:.72rem;color:#94a3b8;">{{ $item->unit }}</span></td>
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.9rem;height:.9rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tutup
                </button>
            </div>
        </div>
    @endif

</x-filament-panels::page>
