<x-filament-panels::page>
    <style>
        /* ── Root ── */
        .vpo-root { display:flex; flex-direction:column; gap:1.25rem; width:100%; }

        /* ── Stats ── */
        .vpo-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:.75rem; }
        .vpo-stat-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:1rem;
            padding:1rem 1.25rem; display:flex; align-items:center; gap:.9rem;
            box-shadow:0 1px 4px rgba(15,23,42,.05);
        }
        .dark .vpo-stat-card { background:#1e293b; border-color:#334155; box-shadow:none; }
        .vpo-stat-icon { width:2.6rem; height:2.6rem; border-radius:.75rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .vpo-stat-icon svg { width:1.2rem; height:1.2rem; }
        .vsi-blue   { background:#dbeafe; color:#1d4ed8; } .dark .vsi-blue   { background:rgba(59,130,246,.15);  color:#93c5fd; }
        .vsi-violet { background:#ede9fe; color:#6d28d9; } .dark .vsi-violet { background:rgba(139,92,246,.15); color:#a78bfa; }
        .vsi-amber  { background:#fef3c7; color:#b45309; } .dark .vsi-amber  { background:rgba(245,158,11,.15);  color:#fbbf24; }
        .vsi-green  { background:#d1fae5; color:#065f46; } .dark .vsi-green  { background:rgba(16,185,129,.15);  color:#34d399; }
        .vpo-stat-value { font-size:1.45rem; font-weight:800; color:#0f172a; line-height:1; }
        .dark .vpo-stat-value { color:#f8fafc; }
        .vpo-stat-label { font-size:.72rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.04em; margin-top:.15rem; }

        /* ── Toolbar ── */
        .vpo-toolbar {
            background:#fff; border:1px solid #e2e8f0; border-radius:1rem;
            padding:1rem 1.25rem; display:flex; flex-wrap:wrap; align-items:center; gap:.75rem;
        }
        .dark .vpo-toolbar { background:#1e293b; border-color:#334155; }
        .vpo-search-wrap { position:relative; flex:1 1 200px; min-width:0; }
        .vpo-search-icon { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:#94a3b8; pointer-events:none; }
        .vpo-search-input {
            width:100%; padding:.65rem .9rem .65rem 2.4rem; border-radius:.75rem;
            border:1px solid #cbd5e1; background:#f8fafc; color:#0f172a; font-size:.875rem; font-weight:600;
        }
        .vpo-search-input:focus { outline:none; border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .dark .vpo-search-input { background:#0f172a; border-color:#334155; color:#f8fafc; }
        .vpo-search-input::placeholder { color:#94a3b8; }
        .vpo-hint { font-size:.78rem; color:#94a3b8; font-weight:600; margin-left:auto; white-space:nowrap; }
        .dark .vpo-hint { color:#64748b; }

        /* ── Vessel grid ── */
        .vpo-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1rem; }
        .vpo-vessel-card {
            background:#fff; border:1.5px solid #e2e8f0; border-radius:1.25rem;
            padding:1.25rem; cursor:pointer; transition:all .18s;
            box-shadow:0 1px 4px rgba(15,23,42,.05);
        }
        .vpo-vessel-card:hover { border-color:#6366f1; box-shadow:0 6px 20px rgba(99,102,241,.12); transform:translateY(-2px); }
        .vpo-vessel-card.active { border-color:#4f46e5; background:#f0f0ff; box-shadow:0 6px 20px rgba(79,70,229,.15); }
        .dark .vpo-vessel-card { background:#1e293b; border-color:#334155; }
        .dark .vpo-vessel-card:hover { border-color:#6366f1; box-shadow:0 6px 20px rgba(99,102,241,.15); }
        .dark .vpo-vessel-card.active { border-color:#4f46e5; background:#1e1e40; }

        .vpo-vessel-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1rem; }
        .vpo-vessel-icon {
            width:3rem; height:3rem; border-radius:.875rem;
            background:linear-gradient(135deg,#4f46e5,#7c3aed);
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .vpo-vessel-icon svg { width:1.4rem; height:1.4rem; color:#fff; }
        .vpo-vessel-total { text-align:right; }
        .vpo-vessel-total-num { font-size:1.6rem; font-weight:900; color:#4f46e5; line-height:1; }
        .dark .vpo-vessel-total-num { color:#818cf8; }
        .vpo-vessel-total-lbl { font-size:.68rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
        .vpo-vessel-name { font-size:1rem; font-weight:800; color:#0f172a; margin-bottom:.2rem; }
        .dark .vpo-vessel-name { color:#f1f5f9; }
        .vpo-vessel-code { font-size:.78rem; color:#94a3b8; font-weight:600; }
        .vpo-vessel-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:.5rem; margin-top:1rem; }
        .vpo-vs-item { text-align:center; background:#f8fafc; border-radius:.6rem; padding:.4rem; }
        .dark .vpo-vs-item { background:#0f172a; }
        .vpo-vs-num { font-size:.9rem; font-weight:800; }
        .vpo-vs-lbl { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#94a3b8; }
        .vpo-vs-inprogress { color:#f59e0b; } .vpo-vs-converted { color:#10b981; } .vpo-vs-other { color:#ef4444; }

        .vpo-chevron { width:1.2rem; height:1.2rem; color:#94a3b8; transition:transform .2s; }
        .vpo-vessel-card.active .vpo-chevron { transform:rotate(90deg); color:#4f46e5; }

        /* ── Empty ── */
        .vpo-empty { text-align:center; padding:5rem 2rem; }
        .vpo-empty svg { width:4rem; height:4rem; color:#cbd5e1; margin:0 auto; }
        .vpo-empty-title { font-size:1.1rem; font-weight:700; color:#475569; margin-top:1rem; }
        .vpo-empty-sub   { font-size:.875rem; color:#94a3b8; margin-top:.25rem; }
        .dark .vpo-empty-title { color:#94a3b8; }

        /* ── Panel ── */
        .vpo-panel-backdrop { position:fixed; inset:0; z-index:8000; background:rgba(2,6,23,.5); backdrop-filter:blur(3px); animation:vpoFade .2s ease; }
        @keyframes vpoFade  { from{opacity:0} to{opacity:1} }
        @keyframes vpoSlide { from{transform:translateX(100%)} to{transform:translateX(0)} }
        .vpo-panel {
            position:fixed; top:0; right:0; bottom:0; width:min(700px,100vw);
            background:#fff; box-shadow:-8px 0 40px rgba(2,6,23,.18);
            z-index:8001; display:flex; flex-direction:column;
            animation:vpoSlide .25s cubic-bezier(.22,1,.36,1); overflow:hidden;
        }
        .dark .vpo-panel { background:#0f172a; }
        .vpo-panel-header {
            padding:1.5rem 1.75rem 1.25rem; border-bottom:1px solid #e2e8f0; flex-shrink:0;
            background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);
            display:flex; align-items:flex-start; justify-content:space-between;
        }
        .dark .vpo-panel-header { border-bottom-color:#1e293b; }
        .vpo-ph-kicker { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:rgba(255,255,255,.7); }
        .vpo-ph-title  { font-size:1.4rem; font-weight:900; color:#fff; }
        .vpo-ph-close  { width:2.25rem; height:2.25rem; border-radius:.6rem; border:1.5px solid rgba(255,255,255,.3); background:rgba(255,255,255,.15); color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; }
        .vpo-ph-close:hover { background:rgba(255,255,255,.28); }
        .vpo-ph-close svg { width:1.1rem; height:1.1rem; }
        .vpo-panel-body { flex:1; overflow-y:auto; padding:1.5rem 1.75rem; display:flex; flex-direction:column; gap:1rem; }

        /* PR list in panel */
        .vpo-pr-item {
            background:#f8fafc; border:1px solid #e2e8f0; border-radius:.875rem;
            padding:1rem 1.1rem; display:flex; align-items:center; gap:1rem;
        }
        .dark .vpo-pr-item { background:#1e293b; border-color:#334155; }
        .vpo-pr-item-left { flex:1; min-width:0; }
        .vpo-pr-num { font-weight:800; color:#4f46e5; font-size:.9rem; }
        .dark .vpo-pr-num { color:#818cf8; }
        .vpo-pr-needs { font-size:.82rem; color:#64748b; margin-top:.15rem; font-weight:500; }
        .dark .vpo-pr-needs { color:#94a3b8; }
        .vpo-pr-meta { font-size:.75rem; color:#94a3b8; margin-top:.1rem; }
        .vpo-pr-right { display:flex; flex-direction:column; align-items:flex-end; gap:.35rem; flex-shrink:0; }
        .vpo-pr-status { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .7rem; border-radius:9999px; font-size:.7rem; font-weight:800; white-space:nowrap; }
        .vpo-dot { width:.45rem; height:.45rem; border-radius:50%; }
        .vps-warning { background:#fffbeb; color:#92400e; } .vps-dot-warning { background:#f59e0b; }
        .vps-danger  { background:#fef2f2; color:#991b1b; } .vps-dot-danger  { background:#ef4444; }
        .vps-info    { background:#eff6ff; color:#1e40af; } .vps-dot-info    { background:#3b82f6; }
        .vps-success { background:#ecfdf5; color:#065f46; } .vps-dot-success { background:#10b981; }
        .vps-gray    { background:#f9fafb; color:#4b5563; } .vps-dot-gray    { background:#9ca3af; }
        .vps-violet  { background:#ede9fe; color:#5b21b6; } .vps-dot-violet  { background:#8b5cf6; }
        .dark .vps-warning { background:rgba(245,158,11,.12); color:#fbbf24; }
        .dark .vps-danger  { background:rgba(239,68,68,.12);  color:#f87171; }
        .dark .vps-info    { background:rgba(59,130,246,.12); color:#60a5fa; }
        .dark .vps-success { background:rgba(16,185,129,.12); color:#34d399; }
        .dark .vps-gray    { background:rgba(107,114,128,.1); color:#d1d5db; }
        .dark .vps-violet  { background:rgba(139,92,246,.12); color:#a78bfa; }
        .vpo-pr-date { font-size:.72rem; color:#94a3b8; }
        .vpo-panel-footer { flex-shrink:0; padding:1rem 1.75rem; border-top:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:flex-end; }
        .dark .vpo-panel-footer { border-top-color:#1e293b; background:#0f172a; }
        .vpo-close-btn { display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.4rem; border-radius:.75rem; border:1.5px solid #e2e8f0; background:#fff; color:#374151; font-size:.875rem; font-weight:700; cursor:pointer; }
        .vpo-close-btn:hover { background:#f1f5f9; }
        .dark .vpo-close-btn { background:#1e293b; border-color:#334155; color:#cbd5e1; }
        .dark .vpo-close-btn:hover { background:#334155; }
        .vpo-item-remaining { font-weight:800; color:#0f172a; }
        .dark .vpo-item-remaining { color:#ffffff; }

        /* ── Table ── */
        .vpo-table-wrap { overflow-x:auto; border-radius:.75rem; border:1px solid #e2e8f0; }
        .dark .vpo-table-wrap { border-color:#334155; }
        .vpo-table { width:100%; border-collapse:collapse; text-align:left; }
        .vpo-table th { background:#f8fafc; padding:.75rem 1rem; font-size:.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em; border-bottom:1px solid #e2e8f0; white-space:nowrap; }
        .dark .vpo-table th { background:#1e293b; color:#94a3b8; border-bottom-color:#334155; }
        .vpo-table td { padding:.875rem 1rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; font-size:.85rem; color:#1e293b; }
        .dark .vpo-table td { border-bottom-color:#1e293b; color:#f1f5f9; }
        .vpo-table tr:last-child td { border-bottom:none; }
        .vpo-table tr:hover td { background:#f8fafc; }
        .dark .vpo-table tr:hover td { background:#1e293b; }
        .vpo-btn-action { display:inline-flex; align-items:center; gap:.4rem; padding:.4rem .8rem; border-radius:.5rem; background:#eff6ff; color:#1d4ed8; font-size:.75rem; font-weight:700; cursor:pointer; transition:all .2s; border:none; }
        .vpo-btn-action:hover { background:#dbeafe; }
        .dark .vpo-btn-action { background:rgba(59,130,246,.2); color:#93c5fd; }
        .dark .vpo-btn-action:hover { background:rgba(59,130,246,.3); }

        /* ── Items Modal ── */
        .vpo-modal-backdrop { position:fixed; inset:0; z-index:9000; background:rgba(15,23,42,.6); backdrop-filter:blur(4px); animation:vpoFade .2s ease; display:flex; align-items:center; justify-content:center; padding:1.5rem; }
        .vpo-modal { background:#fff; border-radius:1.25rem; width:min(900px,100%); max-height:90vh; display:flex; flex-direction:column; box-shadow:0 20px 25px -5px rgba(0,0,0,.1),0 8px 10px -6px rgba(0,0,0,.1); animation:vpoPop .25s cubic-bezier(.175,.885,.32,1.275); }
        .dark .vpo-modal { background:#0f172a; border:1px solid #334155; }
        @keyframes vpoPop { from{transform:scale(.95);opacity:0} to{transform:scale(1);opacity:1} }
        .vpo-modal-header { padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; }
        .dark .vpo-modal-header { border-bottom-color:#1e293b; }
        .vpo-modal-title { font-size:1.15rem; font-weight:800; color:#0f172a; }
        .dark .vpo-modal-title { color:#f1f5f9; }
        .vpo-modal-close { width:2rem; height:2rem; border-radius:.5rem; display:flex; align-items:center; justify-content:center; background:#f1f5f9; color:#64748b; cursor:pointer; border:none; transition:all .2s; }
        .vpo-modal-close:hover { background:#e2e8f0; color:#0f172a; }
        .dark .vpo-modal-close { background:#1e293b; color:#94a3b8; }
        .dark .vpo-modal-close:hover { background:#334155; color:#f1f5f9; }
        .vpo-modal-body { padding:1.5rem; overflow-y:auto; flex:1; }

        @media(max-width:640px){ .vpo-panel{width:100vw;} .vpo-panel-body,.vpo-panel-header{padding:1.1rem;} .vpo-grid{grid-template-columns:1fr;} }
    </style>

    <div class="vpo-root">

        {{-- Stats --}}
        <div class="vpo-stats">
            <div class="vpo-stat-card">
                <div class="vpo-stat-icon vsi-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                </div>
                <div><div class="vpo-stat-value">{{ $stats['total_vessels'] }}</div><div class="vpo-stat-label">Kapal Aktif</div></div>
            </div>
            <div class="vpo-stat-card">
                <div class="vpo-stat-icon vsi-violet">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                </div>
                <div><div class="vpo-stat-value">{{ $stats['total_pr'] }}</div><div class="vpo-stat-label">Total PR</div></div>
            </div>
            <div class="vpo-stat-card">
                <div class="vpo-stat-icon vsi-amber">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><div class="vpo-stat-value">{{ $stats['in_progress'] }}</div><div class="vpo-stat-label">Sedang Berjalan</div></div>
            </div>
            <div class="vpo-stat-card">
                <div class="vpo-stat-icon vsi-green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><div class="vpo-stat-value">{{ $stats['converted'] }}</div><div class="vpo-stat-label">Sudah ke PO</div></div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="vpo-toolbar">
            <div class="vpo-search-wrap">
                <svg class="vpo-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari nama kapal atau kode..." class="vpo-search-input">
            </div>
            <span class="vpo-hint">Klik kartu kapal untuk melihat detail PR</span>
        </div>

        {{-- Vessel Grid --}}
        @if($vessels->isEmpty())
            <div class="vpo-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg>
                <div class="vpo-empty-title">Belum ada data kapal</div>
                <div class="vpo-empty-sub">Kapal akan muncul di sini setelah ada pengajuan PR yang terkait.</div>
            </div>
        @else
            <div class="vpo-grid">
                @foreach($vessels as $vessel)
                    <div
                        class="vpo-vessel-card {{ $selectedVesselId === $vessel->id ? 'active' : '' }}"
                        wire:click="selectVessel({{ $vessel->id }})">
                        <div class="vpo-vessel-top">
                            <div class="vpo-vessel-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                            </div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <div class="vpo-vessel-total">
                                    <div class="vpo-vessel-total-num">{{ $vessel->total_pr }}</div>
                                    <div class="vpo-vessel-total-lbl">Total PR</div>
                                </div>
                                <svg class="vpo-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </div>
                        </div>
                        <div class="vpo-vessel-name">{{ $vessel->name }}</div>
                        @if($vessel->code)
                            <div class="vpo-vessel-code">Kode: {{ $vessel->code }}</div>
                        @endif
                        <div class="vpo-vessel-stats">
                            <div class="vpo-vs-item">
                                <div class="vpo-vs-num vpo-vs-inprogress">{{ $vessel->in_progress_pr }}</div>
                                <div class="vpo-vs-lbl">Berjalan</div>
                            </div>
                            <div class="vpo-vs-item">
                                <div class="vpo-vs-num vpo-vs-converted">{{ $vessel->converted_pr }}</div>
                                <div class="vpo-vs-lbl">ke PO</div>
                            </div>
                            <div class="vpo-vs-item">
                                <div class="vpo-vs-num vpo-vs-other">{{ $vessel->rejected_pr }}</div>
                                <div class="vpo-vs-lbl">Ditolak</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Vessel PR Panel --}}
    @if($selectedVesselId && $selectedVessel)
        <div class="vpo-panel-backdrop" wire:click="closeVesselPanel"></div>
        <div class="vpo-panel" role="dialog" aria-modal="true">
            <div class="vpo-panel-header">
                <div>
                    <div class="vpo-ph-kicker">Detail Pengajuan PR</div>
                    <div class="vpo-ph-title">{{ $selectedVessel->name }}</div>
                    @if($selectedVessel->code)
                        <div style="font-size:.8rem;color:rgba(255,255,255,.65);margin-top:.2rem;">Kode: {{ $selectedVessel->code }}</div>
                    @endif
                </div>
                <button type="button" class="vpo-ph-close" wire:click="closeVesselPanel">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="vpo-panel-body">
                @if($vesselPrs->isEmpty())
                    <div style="text-align:center;padding:4rem 2rem;">
                        <div style="font-size:1rem;font-weight:700;color:#94a3b8;">Tidak ada PR untuk kapal ini.</div>
                    </div>
                @else
                    <div style="font-size:.78rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.25rem;">
                        {{ $vesselPrs->count() }} Pengajuan PR
                    </div>
                    <div class="vpo-table-wrap">
                        <table class="vpo-table">
                            <thead>
                                <tr>
                                    <th>No. PR</th>
                                    <th>Kebutuhan</th>
                                    <th>Pengaju</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vesselPrs as $pr)
                                    @php
                                        $color = \App\Constants\PrStatusConstant::getColor($pr->pr_status ?? \App\Constants\PrStatusConstant::PENDING);
                                        $label = \App\Constants\PrStatusConstant::getStatuses()[$pr->pr_status] ?? $pr->pr_status ?? '—';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="vpo-pr-num">{{ $pr->pr_number }}</div>
                                            @if($pr->po_number)
                                                <div style="font-size:.7rem;font-weight:700;color:#059669;margin-top:.2rem;">PO: {{ $pr->po_number }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="font-weight:600;max-width:150px;white-space:normal;">{{ $pr->detail?->needs ?? '—' }}</div>
                                        </td>
                                        <td>{{ $pr->requester?->name ?? '—' }}</td>
                                        <td style="color:#64748b;font-size:.8rem;">{{ $pr->created_at?->timezone('Asia/Jakarta')->format('d M Y') ?? '—' }}</td>
                                        <td>
                                            <span class="vpo-pr-status vps-{{ $color }}">
                                                <span class="vpo-dot vps-dot-{{ $color }}"></span>
                                                {{ $label }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="vpo-btn-action" wire:click="openPrItems({{ $pr->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                                Lihat Barang
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="vpo-panel-footer">
                <button type="button" class="vpo-close-btn" wire:click="closeVesselPanel">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.9rem;height:.9rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tutup
                </button>
            </div>
        </div>
    @endif

    {{-- Items Modal --}}
    @if($selectedPrId && $selectedPr)
        <div class="vpo-modal-backdrop" wire:click.self="closePrItems">
            <div class="vpo-modal" role="dialog" aria-modal="true">
                <div class="vpo-modal-header">
                    <div>
                        <div class="vpo-modal-title">Daftar Barang PR</div>
                        <div style="font-size:.8rem;color:#64748b;font-weight:600;margin-top:.1rem;">
                            {{ $selectedPr->pr_number }} &bull; {{ $selectedPr->detail?->vessel?->name ?? 'Kapal' }}
                        </div>
                    </div>
                    <button type="button" class="vpo-modal-close" wire:click="closePrItems">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.2rem;height:1.2rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="vpo-modal-body">
                    @php
                        $items = collect($selectedPr->detail?->items ?? []);
                    @endphp

                    @if($items->isEmpty())
                        <div style="text-align:center;padding:3rem 1rem;">
                            <div style="font-size:1rem;font-weight:700;color:#94a3b8;">Tidak ada barang pada PR ini.</div>
                        </div>
                    @else
                        <div class="vpo-table-wrap">
                            <table class="vpo-table">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Kategori</th>
                                        <th>Jenis</th>
                                        <th>Ukuran / Spesifikasi</th>
                                        <th>Keterangan</th>
                                        <th>Qty</th>
                                        <th>Satuan</th>
                                        <th>Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>
                                                <div style="width:1.8rem;height:1.8rem;background:#f1f5f9;color:#64748b;display:flex;align-items:center;justify-content:center;border-radius:.4rem;font-weight:800;font-size:.75rem;">
                                                    {{ $item->no ?? $loop->iteration }}
                                                </div>
                                            </td>
                                            <td>
                                                <span style="display:inline-block;padding:.2rem .5rem;border-radius:9999px;background:#e0f2fe;color:#0369a1;font-size:.7rem;font-weight:700;">
                                                    {{ $item->itemCategory?->name ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div style="font-weight:700;">{{ $item->type ?? '—' }}</div>
                                            </td>
                                            <td>
                                                <div style="font-size:.85rem;font-weight:600;">{{ $item->size ?? '—' }}</div>
                                            </td>
                                            <td style="max-width:180px;white-space:normal;">
                                                <span style="font-size:.85rem;line-height:1.4;font-weight:500;">{{ $item->description ?? '—' }}</span>
                                            </td>
                                            <td>
                                                <span style="font-weight:800;color:#4f46e5;">{{ $item->quantity }}</span>
                                            </td>
                                            <td>
                                                <span style="font-size:.75rem;font-weight:700;color:#64748b;background:#f1f5f9;padding:.2rem .5rem;border-radius:.4rem;">
                                                    {{ $item->unit ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($item->remaining !== null)
                                                    <span class="vpo-item-remaining">{{ $item->remaining }}</span>
                                                @else
                                                    <span style="color:#94a3b8;">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</x-filament-panels::page>
