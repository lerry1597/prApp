<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/pr-flow-history.css') }}">
    

    <div class="pfh-root">

        {{-- Stats --}}
        <div class="pfh-stats">
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-blue"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg></div>
                <div>
                    <div class="pfh-stat-value">{{ $stats['total'] }}</div>
                    <div class="pfh-stat-label">Total Selesai</div>
                </div>
            </div>
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-green"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg></div>
                <div>
                    <div class="pfh-stat-value">{{ $stats['converted'] }}</div>
                    <div class="pfh-stat-label">ke PO</div>
                </div>
            </div>
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-red"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg></div>
                <div>
                    <div class="pfh-stat-value">{{ $stats['rejected'] }}</div>
                    <div class="pfh-stat-label">Ditolak</div>
                </div>
            </div>
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-gray"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg></div>
                <div>
                    <div class="pfh-stat-value">{{ $stats['closed'] }}</div>
                    <div class="pfh-stat-label">Ditutup</div>
                </div>
            </div>
            <div class="pfh-stat-card">
                <div class="pfh-stat-icon fsi-amber"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg></div>
                <div>
                    <div class="pfh-stat-value">{{ $stats['this_month'] }}</div>
                    <div class="pfh-stat-label">Bulan Ini</div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="pfh-toolbar">
            <div class="pfh-search-wrap">
                <svg class="pfh-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                </svg>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari nomor PR, PO, kapal, keperluan..." class="pfh-search-input">
            </div>
            {{--
            <select wire:model.live="statusFilter" class="pfh-filter-select">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
            </select>
            --}}
            <button type="button" wire:click="resetFilters" class="pfh-reset-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Reset
            </button>
        </div>

        {{-- Table --}}
        <div class="pfh-table-card">
            @if($prList->isEmpty())
            <div class="pfh-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
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
                        <!-- <th>Pengaju</th> -->
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
                        </td>
                        <td><span class="pfh-vessel-name">{{ $pr->detail?->vessel?->name ?? '—' }}</span></td>
                        <td><span class="pfh-meta">{{ $pr->detail?->needs ?? '—' }}</span></td>
                        <!-- <td><span class="pfh-meta">{{ $pr->requester?->name ?? '—' }}</span></td> -->
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
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
                str_contains($action, 'REJECT') => 'tli-reject',
                str_contains($action, 'SUBMIT') => 'tli-submit',
                default => 'tli-default',
                };
                $logColor = \App\Constants\PrStatusConstant::getColor($log->pr_status ?? '');
                $logLabel = \App\Constants\PrStatusConstant::getStatuses()[$log->pr_status] ?? $log->pr_status ?? '—';
                @endphp
                <div class="pfh-tl-item">
                    <div class="pfh-tl-icon-wrap">
                        <div class="pfh-tl-icon {{ $iconClass }}">
                            @if(str_contains($action, 'CONVERT'))
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            @elseif(str_contains($action, 'APPROVE'))
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @elseif(str_contains($action, 'REJECT'))
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @elseif(str_contains($action, 'SUBMIT'))
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
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
                                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.8rem;height:.8rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                    <svg x-cloak x-show="open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="display:none;width:.8rem;height:.8rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                    </svg>
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:.9rem;height:.9rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tutup
            </button>
        </div>
    </div>
    @endif

</x-filament-panels::page>
