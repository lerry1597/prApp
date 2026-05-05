<x-filament-panels::page>
    <style>
        .apr-container {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* ── Header Stats ── */
        .apr-header-card {
            background: linear-gradient(135deg, #059669, #10b981);
            border-radius: 1.25rem;
            padding: 1.5rem 2rem;
            color: white;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.3);
        }
        .apr-header-info h2 { font-size: 1.5rem; font-weight: 800; margin: 0; }
        .apr-header-info p { font-size: 0.9rem; opacity: 0.9; margin-top: 0.25rem; }
        .apr-header-stat { text-align: right; }
        .apr-header-stat-val { font-size: 2.5rem; font-weight: 900; line-height: 1; }
        .apr-header-stat-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.8; }

        /* ── Toolbar ── */
        .apr-toolbar {
            margin-bottom: 1.5rem;
        }
        .apr-search-wrap {
            position: relative;
            max-width: 500px;
        }
        .apr-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.2rem;
            height: 1.2rem;
            color: #94a3b8;
        }
        .apr-search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border-radius: 0.8rem;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            font-size: 0.9rem;
            transition: all .2s;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }
        .apr-search-input:focus { border-color: #10b981; outline: none; box-shadow: 0 0 0 3px rgba(16, 185, 129, .15); }
        .dark .apr-search-input { background: #1e293b; border-color: #334155; color: #f8fafc; }

        /* ── PR List ── */
        .apr-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .apr-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #10b981;
            border-radius: 1rem;
            padding: 1.25rem;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1.5rem;
            align-items: center;
            transition: all .2s;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }
        .apr-row:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            border-color: #10b981;
        }
        .dark .apr-row { background: #1e293b; border-color: #334155; }
        
        .apr-row-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1rem;
            background: #ecfdf5;
            color: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dark .apr-row-icon { background: rgba(16, 185, 129, 0.1); }
        .apr-row-icon svg { width: 1.75rem; height: 1.75rem; }

        .apr-row-body { min-width: 0; }
        .apr-row-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem; }
        .apr-pr-num { font-size: 1.1rem; font-weight: 800; color: #065f46; }
        .dark .apr-pr-num { color: #34d399; }
        .apr-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .apr-meta-grid { display: flex; flex-wrap: wrap; gap: 0.5rem 2rem; }
        .apr-meta-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: #64748b; font-weight: 500; }
        .dark .apr-meta-item { color: #94a3b8; }
        .apr-meta-item svg { width: 1rem; height: 1rem; color: #94a3b8; }
        .apr-meta-val { color: #1e293b; font-weight: 700; }
        .dark .apr-meta-val { color: #f1f5f9; }

        .apr-action-btn {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all .2s;
        }
        .apr-action-btn:hover { background: #e2e8f0; color: #1e293b; }
        .dark .apr-action-btn { background: #334155; color: #cbd5e1; }
        .dark .apr-action-btn:hover { background: #475569; color: #fff; }

        /* ── Empty State ── */
        .apr-empty {
            text-align: center;
            padding: 4rem 2rem;
            background: #fff;
            border-radius: 1.25rem;
            border: 2px dashed #e2e8f0;
            color: #94a3b8;
        }
        .dark .apr-empty { background: #1e293b; border-color: #334155; }
        .apr-empty svg { width: 4rem; height: 4rem; margin-bottom: 1rem; opacity: 0.5; }
        .apr-empty h3 { font-size: 1.25rem; font-weight: 700; color: #475569; margin: 0; }
        .dark .apr-empty h3 { color: #cbd5e1; }

        /* ── Modal ── */
        .apr-modal-overlay {
            position: fixed; inset: 0; background: rgba(2,6,23,0.7); backdrop-filter: blur(6px);
            z-index: 9000; display: flex; align-items: flex-start; justify-content: center;
            padding: 2rem 1rem; overflow-y: auto;
        }
        .apr-modal {
            width: 100%; max-width: 1100px; background: #ffffff; border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);
            animation: aprSlideUp .3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex; flex-direction: column;
        }
        @keyframes aprSlideUp { from { transform: translateY(2rem); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .dark .apr-modal { background: #0f172a; border: 1px solid #334155; }
        
        .apr-modal-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #065f46, #10b981);
            color: white; display: flex; justify-content: space-between; align-items: center;
            border-radius: 1.25rem 1.25rem 0 0;
        }
        .apr-modal-title { font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; }
        .apr-modal-title svg { width: 1.5rem; height: 1.5rem; color: #a7f3d0; }
        .apr-modal-close {
            background: rgba(255,255,255,.2); border: none; color: white;
            width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center; transition: background .2s;
        }
        .apr-modal-close:hover { background: rgba(255,255,255,.3); }

        .apr-modal-body { padding: 1.5rem; background: #f8fafc; }
        .dark .apr-modal-body { background: #020617; }

        /* Info Grid in Modal */
        .apr-info-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.5rem;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }
        .dark .apr-info-card { background: #1e293b; border-color: #334155; }
        .apr-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .apr-info-item { display: flex; flex-direction: column; gap: 0.25rem; }
        .apr-info-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .apr-info-val { font-size: 0.95rem; font-weight: 600; color: #0f172a; display: flex; align-items: center; gap: 0.4rem; }
        .dark .apr-info-label { color: #94a3b8; }
        .dark .apr-info-val { color: #f1f5f9; }

        /* Items Table in Modal */
        .apr-table-wrap {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }
        .dark .apr-table-wrap { background: #1e293b; border-color: #334155; }
        .apr-table { width: 100%; border-collapse: collapse; text-align: left; }
        .apr-table th { background: #f1f5f9; padding: 0.75rem 1rem; font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
        .apr-table td { padding: 0.85rem 1rem; font-size: 0.85rem; color: #1e293b; border-bottom: 1px solid #f1f5f9; font-weight: 500; }
        .dark .apr-table th { background: #0f172a; color: #94a3b8; border-color: #334155; }
        .dark .apr-table td { color: #cbd5e1; border-color: #334155; }

        .apr-modal-footer {
            padding: 1.25rem 1.5rem; background: #fff; border-top: 1px solid #e2e8f0;
            display: flex; justify-content: flex-end; gap: 1rem; border-radius: 0 0 1.25rem 1.25rem;
        }
        .dark .apr-modal-footer { background: #1e293b; border-color: #334155; }
        .apr-btn-cancel {
            padding: 0.65rem 1.25rem; border: 1px solid #cbd5e1; background: #fff; color: #475569;
            border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all .2s;
        }
        .apr-btn-cancel:hover { background: #f1f5f9; }
        .dark .apr-btn-cancel { background: #0f172a; border-color: #475569; color: #cbd5e1; }
        .dark .apr-btn-cancel:hover { background: #334155; }
    </style>

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
