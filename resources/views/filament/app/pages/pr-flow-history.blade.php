<x-filament-panels::page>
    <style>
        /* ── Root ── */
        .pfh-root { display:flex; flex-direction:column; gap:1.25rem; width:100%; }

        /* ── Stats ── */
        .pfh-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:.75rem; }
        .pfh-stat-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:1rem;
            padding:1rem 1.25rem; display:flex; align-items:center; gap:.9rem;
            box-shadow:0 1px 4px rgba(15,23,42,.05);
        }
        .dark .pfh-stat-card { background:#1e293b; border-color:#334155; box-shadow:none; }
        .pfh-stat-icon { width:2.6rem; height:2.6rem; border-radius:.75rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .pfh-stat-icon svg { width:1.2rem; height:1.2rem; }
        .fsi-blue   { background:#dbeafe; color:#1d4ed8; } .dark .fsi-blue   { background:rgba(59,130,246,.15);  color:#93c5fd; }
        .fsi-green  { background:#d1fae5; color:#065f46; } .dark .fsi-green  { background:rgba(16,185,129,.15);  color:#34d399; }
        .fsi-red    { background:#fee2e2; color:#be123c; } .dark .fsi-red    { background:rgba(239,68,68,.15);   color:#f87171; }
        .fsi-gray   { background:#f1f5f9; color:#475569; } .dark .fsi-gray   { background:rgba(71,85,105,.2);   color:#94a3b8; }
        .fsi-amber  { background:#fef3c7; color:#b45309; } .dark .fsi-amber  { background:rgba(245,158,11,.15);  color:#fbbf24; }
        .pfh-stat-value { font-size:1.45rem; font-weight:800; color:#0f172a; line-height:1; }
        .dark .pfh-stat-value { color:#f8fafc; }
        .pfh-stat-label { font-size:.72rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.04em; margin-top:.15rem; }

        /* ── Toolbar ── */
        .pfh-toolbar {
            background:#fff; border:1px solid #e2e8f0; border-radius:1rem;
            padding:1rem 1.25rem; display:flex; flex-wrap:wrap; align-items:center; gap:.75rem;
        }
        .dark .pfh-toolbar { background:#1e293b; border-color:#334155; }
        .pfh-search-wrap { position:relative; flex:1 1 220px; min-width:0; }
        .pfh-search-icon { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:#94a3b8; pointer-events:none; }
        .pfh-search-input {
            width:100%; padding:.65rem .9rem .65rem 2.4rem; border-radius:.75rem;
            border:1px solid #cbd5e1; background:#f8fafc; color:#0f172a; font-size:.875rem; font-weight:600;
        }
        .pfh-search-input:focus { outline:none; border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .dark .pfh-search-input { background:#0f172a; border-color:#334155; color:#f8fafc; }
        .pfh-search-input::placeholder { color:#94a3b8; }
        .pfh-filter-select {
            padding:.65rem .9rem; border-radius:.75rem; border:1px solid #cbd5e1;
            background:#f8fafc; color:#0f172a; font-size:.875rem; font-weight:600;
            cursor:pointer; min-width:170px;
        }
        .pfh-filter-select:focus { outline:none; border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .dark .pfh-filter-select { background:#0f172a; border-color:#334155; color:#f8fafc; }
        .pfh-reset-btn {
            display:inline-flex; align-items:center; gap:.4rem; padding:.65rem 1rem;
            border-radius:.75rem; border:1px solid #e2e8f0; background:#f8fafc; color:#64748b;
            font-size:.8rem; font-weight:700; cursor:pointer;
        }
        .pfh-reset-btn:hover { background:#fee2e2; border-color:#fecaca; color:#be123c; }
        .dark .pfh-reset-btn { background:#1e293b; border-color:#334155; color:#94a3b8; }
        .pfh-reset-btn svg { width:.85rem; height:.85rem; }

        /* ── Table card ── */
        .pfh-table-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:1.1rem;
            box-shadow:0 1px 4px rgba(15,23,42,.04); overflow:hidden;
        }
        .dark .pfh-table-card { background:#1e293b; border-color:#334155; }
        .pfh-table { width:100%; border-collapse:collapse; font-size:.875rem; }
        .pfh-table thead th { background:#f8fafc; padding:.85rem 1rem; text-align:left; font-size:.7rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:.07em; border-bottom:1px solid #e2e8f0; white-space:nowrap; }
        .dark .pfh-table thead th { background:#0f172a; color:#64748b; border-bottom-color:#334155; }
        .pfh-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .14s; }
        .pfh-table tbody tr:last-child { border-bottom:none; }
        .pfh-table tbody tr:hover { background:#f8fafc; }
        .dark .pfh-table tbody tr { border-bottom-color:#1e293b; }
        .dark .pfh-table tbody tr:hover { background:#0f172a; }
        .pfh-table td { padding:.9rem 1rem; color:#0f172a; vertical-align:middle; }
        .dark .pfh-table td { color:#e2e8f0; }
        .pfh-pr-number { font-weight:800; color:#4f46e5; font-size:.9rem; }
        .dark .pfh-pr-number { color:#818cf8; }
        .pfh-vessel-name { font-weight:700; color:#0f172a; }
        .dark .pfh-vessel-name { color:#f1f5f9; }
        .pfh-meta { color:#475569; font-size:.85rem; font-weight:500; }
        .dark .pfh-meta { color:#94a3b8; }
        .pfh-date { color:#64748b; font-size:.82rem; white-space:nowrap; }
        .pfh-status-pill { display:inline-flex; align-items:center; gap:.35rem; padding:.28rem .75rem; border-radius:9999px; font-size:.72rem; font-weight:800; white-space:nowrap; }
        .pfh-dot { width:.45rem; height:.45rem; border-radius:50%; flex-shrink:0; }
        .fp-warning{ background:#fffbeb; color:#92400e; } .fp-dot-warning{ background:#f59e0b; }
        .fp-danger { background:#fef2f2; color:#991b1b; } .fp-dot-danger { background:#ef4444; }
        .fp-info   { background:#eff6ff; color:#1e40af; } .fp-dot-info   { background:#3b82f6; }
        .fp-success{ background:#ecfdf5; color:#065f46; } .fp-dot-success{ background:#10b981; }
        .fp-gray   { background:#f9fafb; color:#4b5563; } .fp-dot-gray   { background:#9ca3af; }
        .fp-violet { background:#ede9fe; color:#5b21b6; } .fp-dot-violet { background:#8b5cf6; }
        .dark .fp-warning{ background:rgba(245,158,11,.12); color:#fbbf24; }
        .dark .fp-danger { background:rgba(239,68,68,.12);  color:#f87171; }
        .dark .fp-info   { background:rgba(59,130,246,.12); color:#60a5fa; }
        .dark .fp-success{ background:rgba(16,185,129,.12); color:#34d399; }
        .dark .fp-gray   { background:rgba(107,114,128,.1); color:#d1d5db; }
        .dark .fp-violet { background:rgba(139,92,246,.12); color:#a78bfa; }

        .pfh-timeline-btn {
            display:inline-flex; align-items:center; gap:.45rem; padding:.5rem 1.1rem;
            border-radius:.75rem; background:linear-gradient(135deg,#4f46e5,#7c3aed);
            color:#fff; font-size:.8rem; font-weight:700; border:none; cursor:pointer; transition:all .18s;
        }
        .pfh-timeline-btn:hover { background:linear-gradient(135deg,#4338ca,#6d28d9); transform:translateY(-1px); box-shadow:0 4px 12px rgba(79,70,229,.35); }
        .pfh-timeline-btn svg { width:.8rem; height:.8rem; }

        /* ── Empty ── */
        .pfh-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:5rem 2rem; gap:1rem; }
        .pfh-empty svg { width:4rem; height:4rem; color:#cbd5e1; }
        .pfh-empty-title { font-size:1.1rem; font-weight:700; color:#475569; }
        .pfh-empty-sub   { font-size:.875rem; color:#94a3b8; }
        .dark .pfh-empty-title { color:#94a3b8; }

        /* ── Pagination ── */
        .pfh-pagination { display:flex; align-items:center; justify-content:space-between; padding:.85rem 1rem; border-top:1px solid #f1f5f9; gap:.5rem; flex-wrap:wrap; }
        .dark .pfh-pagination { border-top-color:#1e293b; }
        .pfh-pagination-info { font-size:.8rem; color:#64748b; font-weight:600; }
        .pfh-pagination-btns { display:flex; align-items:center; gap:.35rem; }
        .pfh-page-btn { min-width:2rem; height:2rem; padding:0 .65rem; border-radius:.5rem; border:1px solid #e2e8f0; background:#f8fafc; color:#374151; font-size:.8rem; font-weight:700; cursor:pointer; transition:all .15s; }
        .pfh-page-btn:hover, .pfh-page-btn.active { background:#4f46e5; border-color:#4f46e5; color:#fff; }
        .pfh-page-btn:disabled { opacity:.4; cursor:not-allowed; }
        .pfh-page-btn:disabled:hover { background:#f8fafc; border-color:#e2e8f0; color:#374151; }
        .dark .pfh-page-btn { background:#1e293b; border-color:#334155; color:#cbd5e1; }
        .dark .pfh-page-btn:hover, .dark .pfh-page-btn.active { background:#4f46e5; border-color:#4f46e5; color:#fff; }
        .dark .pfh-page-btn:disabled:hover { background:#1e293b; border-color:#334155; color:#cbd5e1; }

        /* ── Timeline Panel ── */
        .pfh-panel-backdrop { position:fixed; inset:0; z-index:8000; background:rgba(2,6,23,.5); backdrop-filter:blur(3px); animation:pfhFade .2s ease; }
        @keyframes pfhFade  { from{opacity:0} to{opacity:1} }
        @keyframes pfhSlide { from{transform:translateX(100%)} to{transform:translateX(0)} }
        .pfh-panel {
            position:fixed; top:0; right:0; bottom:0; width:min(680px,100vw);
            background:#fff; box-shadow:-8px 0 40px rgba(2,6,23,.18);
            z-index:8001; display:flex; flex-direction:column;
            animation:pfhSlide .25s cubic-bezier(.22,1,.36,1); overflow:hidden;
        }
        .dark .pfh-panel { background:#0f172a; }
        .pfh-panel-header {
            padding:1.5rem 1.75rem 1.25rem; border-bottom:1px solid #e2e8f0; flex-shrink:0;
            background:linear-gradient(135deg,#1e293b 0%,#334155 100%);
            display:flex; align-items:flex-start; justify-content:space-between;
        }
        .dark .pfh-panel-header { background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%); border-bottom-color:#0f172a; }
        .pfh-ph-kicker { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:rgba(255,255,255,.55); }
        .pfh-ph-title  { font-size:1.4rem; font-weight:900; color:#fff; }
        .pfh-ph-close  { width:2.25rem; height:2.25rem; border-radius:.6rem; border:1.5px solid rgba(255,255,255,.2); background:rgba(255,255,255,.1); color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; }
        .pfh-ph-close:hover { background:rgba(255,255,255,.2); }
        .pfh-ph-close svg { width:1.1rem; height:1.1rem; }
        .pfh-panel-body { flex:1; overflow-y:auto; padding:1.5rem 1.75rem; }

        /* ── Timeline ── */
        .pfh-timeline { display:flex; flex-direction:column; gap:0; }
        .pfh-tl-item { display:flex; gap:1rem; position:relative; }
        .pfh-tl-item:not(:last-child)::before {
            content:''; position:absolute; left:1.1rem; top:2.8rem; bottom:0;
            width:2px; background:#e2e8f0; z-index:0;
        }
        .dark .pfh-tl-item:not(:last-child)::before { background:#334155; }
        .pfh-tl-icon-wrap { flex-shrink:0; display:flex; flex-direction:column; align-items:center; z-index:1; }
        .pfh-tl-icon {
            width:2.2rem; height:2.2rem; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            border:2px solid #e2e8f0; background:#fff;
        }
        .dark .pfh-tl-icon { background:#1e293b; border-color:#334155; }
        .pfh-tl-icon svg { width:.9rem; height:.9rem; }
        .tli-approve  { background:#ecfdf5; border-color:#a7f3d0; color:#059669; } .dark .tli-approve  { background:rgba(16,185,129,.15); border-color:rgba(16,185,129,.3); color:#34d399; }
        .tli-convert  { background:#d1fae5; border-color:#6ee7b7; color:#047857; } .dark .tli-convert  { background:rgba(5,150,105,.2);   border-color:rgba(5,150,105,.4);  color:#6ee7b7; }
        .tli-reject   { background:#fef2f2; border-color:#fecaca; color:#be123c; } .dark .tli-reject   { background:rgba(239,68,68,.15);   border-color:rgba(239,68,68,.3);   color:#f87171; }
        .tli-submit   { background:#eff6ff; border-color:#bfdbfe; color:#1d4ed8; } .dark .tli-submit   { background:rgba(59,130,246,.15);  border-color:rgba(59,130,246,.3);  color:#60a5fa; }
        .tli-default  { background:#f8fafc; border-color:#e2e8f0; color:#64748b; } .dark .tli-default  { background:#1e293b; border-color:#334155; color:#94a3b8; }
        .pfh-tl-content { flex:1; padding-bottom:1.5rem; }
        .pfh-tl-header { display:flex; align-items:flex-start; justify-content:space-between; gap:.5rem; margin-bottom:.45rem; }
        .pfh-tl-action { font-size:.85rem; font-weight:800; color:#0f172a; }
        .dark .pfh-tl-action { color:#f1f5f9; }
        .pfh-tl-time { font-size:.72rem; color:#94a3b8; font-weight:600; white-space:nowrap; }
        .pfh-tl-status-badge { display:inline-flex; align-items:center; gap:.3rem; padding:.18rem .6rem; border-radius:9999px; font-size:.68rem; font-weight:700; }
        .pfh-tl-body {
            background:#f8fafc; border:1px solid #e2e8f0; border-radius:.75rem;
            padding:.75rem 1rem; display:flex; flex-direction:column; gap:.4rem;
        }
        .dark .pfh-tl-body { background:#1e293b; border-color:#334155; }
        .pfh-tl-row { display:flex; align-items:baseline; gap:.5rem; font-size:.82rem; }
        .pfh-tl-row-label { color:#94a3b8; font-weight:600; white-space:nowrap; min-width:5rem; }
        .pfh-tl-row-val { color:#0f172a; font-weight:600; }
        .dark .pfh-tl-row-val { color:#e2e8f0; }
        .pfh-tl-notes { font-size:.82rem; color:#475569; font-style:italic; margin-top:.25rem; }
        .dark .pfh-tl-notes { color:#94a3b8; }
        .pfh-tl-empty { text-align:center; padding:4rem 2rem; color:#94a3b8; font-size:.875rem; }

        /* ── Diff Items ── */
        .pfh-diff-wrap { margin-top:.75rem; border-top:1px dashed #e2e8f0; padding-top:.75rem; }
        .dark .pfh-diff-wrap { border-top-color:#334155; }
        .pfh-diff-toggle { display:inline-flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:700; color:#4f46e5; background:none; border:none; cursor:pointer; padding:0; }
        .pfh-diff-toggle:hover { color:#4338ca; text-decoration:underline; }
        .dark .pfh-diff-toggle { color:#818cf8; }
        .dark .pfh-diff-toggle:hover { color:#a5b4fc; }
        .pfh-diff-list { display:flex; flex-direction:column; gap:.5rem; margin-top:.5rem; padding:.65rem; background:#fff; border-radius:.5rem; border:1px solid #e2e8f0; }
        .dark .pfh-diff-list { background:#0f172a; border-color:#334155; }
        .pfh-diff-item { font-size:.75rem; padding:.4rem .5rem; border-radius:.3rem; background:#f8fafc; border:1px solid #f1f5f9; display:flex; flex-direction:column; gap:.2rem; }
        .dark .pfh-diff-item { background:#1e293b; border-color:#1e293b; }
        .pfh-diff-title { font-weight:700; color:#0f172a; display:flex; align-items:center; gap:.3rem; }
        .dark .pfh-diff-title { color:#e2e8f0; }
        .pfh-diff-added { color:#059669; } .dark .pfh-diff-added { color:#34d399; }
        .pfh-diff-removed { color:#be123c; text-decoration:line-through; } .dark .pfh-diff-removed { color:#f87171; }
        .pfh-diff-changed { color:#d97706; } .dark .pfh-diff-changed { color:#fbbf24; }
        .pfh-diff-detail { color:#475569; font-weight:600; font-size:.7rem; padding-left:1.1rem; }
        .dark .pfh-diff-detail { color:#94a3b8; }

        .pfh-panel-footer { flex-shrink:0; padding:1rem 1.75rem; border-top:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:flex-end; }
        .dark .pfh-panel-footer { border-top-color:#1e293b; background:#0f172a; }
        .pfh-close-btn { display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.4rem; border-radius:.75rem; border:1.5px solid #e2e8f0; background:#fff; color:#374151; font-size:.875rem; font-weight:700; cursor:pointer; }
        .pfh-close-btn:hover { background:#f1f5f9; }
        .dark .pfh-close-btn { background:#1e293b; border-color:#334155; color:#cbd5e1; }
        .dark .pfh-close-btn:hover { background:#334155; }

        @media(max-width:640px){ .pfh-panel{width:100vw;} .pfh-panel-body,.pfh-panel-header{padding:1.1rem;} .pfh-table thead{display:none;} }
    </style>

    <div class="pfh-root">

        {{-- Stats --}}
        <div class="pfh-stats">
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-blue"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div><div class="pfh-stat-value">{{ $stats['total'] }}</div><div class="pfh-stat-label">Total Selesai</div></div>
            </div>
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-green"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg></div>
                <div><div class="pfh-stat-value">{{ $stats['converted'] }}</div><div class="pfh-stat-label">ke PO</div></div>
            </div>
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-red"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div><div class="pfh-stat-value">{{ $stats['rejected'] }}</div><div class="pfh-stat-label">Ditolak</div></div>
            </div>
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-gray"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg></div>
                <div><div class="pfh-stat-value">{{ $stats['closed'] }}</div><div class="pfh-stat-label">Ditutup</div></div>
            </div>
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-amber"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg></div>
                <div><div class="pfh-stat-value">{{ $stats['this_month'] }}</div><div class="pfh-stat-label">Bulan Ini</div></div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="pfh-toolbar">
            <div class="pfh-search-wrap">
                <svg class="pfh-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari nomor PR, PO, kapal, keperluan..." class="pfh-search-input">
            </div>
            <select wire:model.live="statusFilter" class="pfh-filter-select">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <button type="button" wire:click="resetFilters" class="pfh-reset-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                Reset
            </button>
        </div>

        {{-- Table --}}
        <div class="pfh-table-card">
            @if($prList->isEmpty())
                <div class="pfh-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="pfh-empty-title">Belum ada riwayat PR</div>
                    <div class="pfh-empty-sub">Data akan muncul setelah PR mencapai status akhir.</div>
                </div>
            @else
                <table class="pfh-table">
                    <thead>
                        <tr>
                            <th>Nomor PR</th>
                            <th>Nama Kapal</th>
                            <th>Keperluan</th>
                            <th>Pengaju</th>
                            <th>Status Akhir</th>
                            <th>Tgl Selesai</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prList as $pr)
                            @php
                                $color = \App\Constants\PrStatusConstant::getColor($pr->pr_status ?? '');
                                $label = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? '—';
                                $finishedAt = $pr->approved_at ?? $pr->updated_at;
                            @endphp
                            <tr>
                                <td>
                                    <span class="pfh-pr-number">{{ $pr->pr_number }}</span>
                                    @php
                                        $uniquePos = $pr->items->pluck('po_number')->filter()->unique();
                                    @endphp
                                    @if($uniquePos->count() > 0)
                                        <div style="display:flex; flex-wrap:wrap; gap:.25rem; margin-top:.15rem;">
                                            @foreach($uniquePos as $uPo)
                                                <div style="font-size:.72rem; font-weight:800; color:#059669; background:#ecfdf5; padding:.1rem .4rem; border-radius:.4rem; border:1px solid #a7f3d0;">
                                                    PO: {{ $uPo }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($pr->po_number)
                                        <div style="font-size:.72rem; font-weight:800; color:#059669; margin-top:.15rem;">PO: {{ $pr->po_number }}</div>
                                    @endif
                                </td>
                                <td><span class="pfh-vessel-name">{{ $pr->detail?->vessel?->name ?? '—' }}</span></td>
                                <td><span class="pfh-meta">{{ $pr->detail?->needs ?? '—' }}</span></td>
                                <td><span class="pfh-meta">{{ $pr->requester?->name ?? '—' }}</span></td>
                                <td>
                                    <span class="pfh-status-pill fp-{{ $color }}">
                                        <span class="pfh-dot fp-dot-{{ $color }}"></span>
                                        {{ $label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="pfh-date">{{ $finishedAt?->timezone('Asia/Jakarta')->format('d M Y') ?? '—' }}</div>
                                    <div class="pfh-date">{{ $finishedAt?->timezone('Asia/Jakarta')->format('H:i') ?? '' }}</div>
                                </td>
                                <td>
                                    <button type="button" class="pfh-timeline-btn" wire:click="openTimeline({{ $pr->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Alur
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($prList->hasPages())
                    <div class="pfh-pagination">
                        <div class="pfh-pagination-info">Menampilkan {{ $prList->firstItem() }}–{{ $prList->lastItem() }} dari {{ $prList->total() }} data</div>
                        <div class="pfh-pagination-btns">
                            <button class="pfh-page-btn" {{ $prList->onFirstPage() ? 'disabled' : '' }} wire:click="previousPage">‹</button>
                            @foreach($prList->getUrlRange(max(1,$prList->currentPage()-2),min($prList->lastPage(),$prList->currentPage()+2)) as $page => $url)
                                <button class="pfh-page-btn {{ $page == $prList->currentPage() ? 'active' : '' }}" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                            @endforeach
                            <button class="pfh-page-btn" {{ !$prList->hasMorePages() ? 'disabled' : '' }} wire:click="nextPage">›</button>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Timeline Panel --}}
    @if($showTimelinePanel && $selectedPr)
        @php $pr = $selectedPr; @endphp
        <div class="pfh-panel-backdrop" wire:click="closeTimeline"></div>
        <div class="pfh-panel" role="dialog" aria-modal="true">
            <div class="pfh-panel-header">
                <div>
                    <div class="pfh-ph-kicker">Alur Proses PR</div>
                    <div class="pfh-ph-title">{{ $pr->pr_number }}</div>
                    <div style="font-size:.82rem;color:rgba(255,255,255,.6);margin-top:.2rem;">
                        {{ $pr->detail?->vessel?->name ?? '—' }}
                        &middot; {{ $pr->detail?->needs ?? '—' }}
                    </div>
                </div>
                <button type="button" class="pfh-ph-close" wire:click="closeTimeline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="pfh-panel-body">
                @if($timeline->isEmpty())
                    <div class="pfh-tl-empty">
                        Tidak ada riwayat aktivitas yang tercatat untuk PR ini.
                    </div>
                @else
                    <div class="pfh-timeline">
                        @foreach($timeline as $log)
                            @php
                                $action = strtoupper($log->action ?? '');
                                $iconClass = match(true) {
                                    str_contains($action, 'CONVERT') => 'tli-convert',
                                    str_contains($action, 'APPROVE') => 'tli-approve',
                                    str_contains($action, 'REJECT')  => 'tli-reject',
                                    str_contains($action, 'SUBMIT')  => 'tli-submit',
                                    default                          => 'tli-default',
                                };
                                $logColor = \App\Constants\PrStatusConstant::getColor($log->pr_status ?? '');
                                $logLabel = \App\Constants\PrStatusConstant::getStatuses()[$log->pr_status] ?? $log->pr_status ?? '—';
                            @endphp
                            <div class="pfh-tl-item">
                                <div class="pfh-tl-icon-wrap">
                                    <div class="pfh-tl-icon {{ $iconClass }}">
                                        @if(str_contains($action, 'CONVERT'))
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                                        @elseif(str_contains($action, 'APPROVE'))
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif(str_contains($action, 'REJECT'))
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif(str_contains($action, 'SUBMIT'))
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="pfh-tl-content">
                                    <div class="pfh-tl-header">
                                        <div>
                                            <div class="pfh-tl-action">{{ $log->action ?? 'Aktivitas' }}</div>
                                            @if($log->status)
                                                <div style="font-size:.72rem;font-weight:600;color:#94a3b8;margin-top:.1rem;">{{ $log->status }}</div>
                                            @endif
                                        </div>
                                        <div class="pfh-tl-time">{{ $log->created_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</div>
                                    </div>
                                    <div class="pfh-tl-body">
                                        @if($log->user)
                                            <div class="pfh-tl-row">
                                                <span class="pfh-tl-row-label">Oleh</span>
                                                <span class="pfh-tl-row-val">{{ $log->user->name }}</span>
                                            </div>
                                        @endif
                                        @if($log->role)
                                            <div class="pfh-tl-row">
                                                <span class="pfh-tl-row-label">Role</span>
                                                <span class="pfh-tl-row-val">{{ $log->role->name }}</span>
                                            </div>
                                        @endif
                                        <div class="pfh-tl-row">
                                            <span class="pfh-tl-row-label">Status</span>
                                            <span class="pfh-status-pill fp-{{ $logColor }}" style="font-size:.68rem;padding:.18rem .6rem;">
                                                <span class="pfh-dot fp-dot-{{ $logColor }}"></span>
                                                {{ $logLabel }}
                                            </span>
                                        </div>
                                        @php
                                            $poInPayload = $log->payload['po_number'] ?? null;
                                            $poNumbersArr = $log->payload['po_numbers'] ?? null;
                                        @endphp
                                        @if($poNumbersArr && is_array($poNumbersArr))
                                            @php 
                                                $uniqueNewPos = collect($poNumbersArr)->filter()->unique();
                                            @endphp
                                            @if($uniqueNewPos->count() > 0)
                                                <div class="pfh-tl-row">
                                                    <span class="pfh-tl-row-label">Nomor PO</span>
                                                    <div style="display:flex; flex-wrap:wrap; gap:.3rem;">
                                                        @foreach($uniqueNewPos as $pNum)
                                                            <span class="pfh-tl-row-val" style="color:#059669; background:#ecfdf5; padding:0 .4rem; border-radius:.3rem;">{{ $pNum }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @elseif($poInPayload)
                                            <div class="pfh-tl-row">
                                                <span class="pfh-tl-row-label">Nomor PO</span>
                                                <span class="pfh-tl-row-val" style="color:#059669;">{{ $poInPayload }}</span>
                                            </div>
                                        @endif
                                        @if($log->notes)
                                            <div class="pfh-tl-notes">"{{ $log->notes }}"</div>
                                        @endif
                                        
                                        @if(!empty($log->item_diffs))
                                            <div class="pfh-diff-wrap" x-data="{ open: false }">
                                                <button type="button" class="pfh-diff-toggle" @click="open = !open">
                                                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.8rem;height:.8rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                                    <svg x-cloak x-show="open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="display:none;width:.8rem;height:.8rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                                                    Lihat {{ count($log->item_diffs) }} Perubahan Item
                                                </button>
                                                
                                                <div class="pfh-diff-list" x-show="open" x-collapse x-cloak>
                                                    @foreach($log->item_diffs as $diff)
                                                        <div class="pfh-diff-item">
                                                            <div class="pfh-diff-title">
                                                                @if($diff['status'] === 'added')
                                                                    <span class="pfh-diff-added">+</span>
                                                                    <span>{{ $diff['item_name'] }}</span>
                                                                @elseif($diff['status'] === 'removed')
                                                                    <span class="pfh-diff-removed">-</span>
                                                                    <span class="pfh-diff-removed">{{ $diff['item_name'] }}</span>
                                                                @else
                                                                    <span class="pfh-diff-changed">&bull;</span>
                                                                    <span>{{ $diff['item_name'] }}</span>
                                                                @endif
                                                            </div>
                                                            @foreach($diff['changes'] as $change)
                                                                <div class="pfh-diff-detail">{!! $change !!}</div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pfh-panel-footer">
                <button type="button" class="pfh-close-btn" wire:click="closeTimeline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.9rem;height:.9rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tutup
                </button>
            </div>
        </div>
    @endif

</x-filament-panels::page>
