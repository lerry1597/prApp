<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/vessel-pr-overview.css') }}">
    

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

