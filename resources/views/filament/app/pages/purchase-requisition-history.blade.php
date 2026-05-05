<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/purchase-requisition-history.css') }}">
    

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
                    {{-- Icon --}}
                    <div class="prh-row-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                    </div>

                    {{-- Body --}}
                    <div class="prh-row-body">
                        <div class="prh-row-top">
                            <span class="prh-title">{{ $row->pr_number ?? '-' }}</span>
                            @if($row->title)
                            <span class="prh-title-sub">&mdash; {{ $row->title }}</span>
                            @endif
                            <span class="prh-badge prh-badge-{{ $statusColor }}">
                                <span class="prh-badge-dot"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="prh-meta-grid">
                            @if($row->document_no)
                            <span class="prh-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                {{ $row->document_no }}
                            </span>
                            @endif
                            @if($row->needs)
                            <span class="prh-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z" /></svg>
                                {{ $row->needs }}
                            </span>
                            @endif
                            <span class="prh-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $row->updated_at?->format('d M Y, H:i') ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Action button --}}
                    <button class="prh-detail-btn" wire:click="showFlowDetails('{{ $row->batch_id }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
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
        <div class="prh-modal" role="dialog" aria-modal="true" aria-labelledby="prh-modal-title">

            {{-- Header --}}
            <div class="prh-modal-header">
                <div class="prh-modal-header-left">
                    <div class="prh-modal-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color:#c7d2fe;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <div class="prh-modal-title" id="prh-modal-title">Alur Persetujuan &mdash; {{ $selectedFlowHeader['pr_number'] ?? '-' }}</div>
                        <div class="prh-modal-subtitle">Nomor Dokumen {{ $selectedFlowHeader['document_no'] ?? '-' }} &nbsp;&bull;&nbsp; Status akhir: {{ $selectedFlowHeader['status_label'] ?? '-' }}</div>
                    </div>
                </div>
                <button class="prh-close" type="button" wire:click="closeFlowDetails" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="prh-modal-body">
                <div class="prh-steps-list">
                    @foreach($flowSteps as $index => $step)
                    @php
                    $color = \App\Constants\PrStatusConstant::getColor($step['status'] ?? \App\Constants\PrStatusConstant::PENDING);
                    $fieldChanges = $flowFieldChanges[$index] ?? [];
                    $itemChanges = $flowItemChanges[$index] ?? ['added' => [], 'updated' => [], 'removed' => []];
                    $hasChanges = !empty($fieldChanges) || !empty($itemChanges['added']) || !empty($itemChanges['updated']) || !empty($itemChanges['removed']);
                    @endphp
                    <div class="prh-step">
                        <div class="prh-step-num prh-step-num-{{ $color }}">{{ $index + 1 }}</div>
                        <div class="prh-step-card">
                            <div class="prh-step-card-top">
                                <span class="prh-step-label">Tahap {{ $index + 1 }}: {{ $step['role'] ?? '-' }}</span>
                                <span class="prh-badge prh-badge-{{ $color }}">
                                    <span class="prh-badge-dot"></span>
                                    {{ $step['status_label'] ?? '-' }}
                                </span>
                            </div>
                            <div class="prh-step-meta-grid">
                                <span class="prh-step-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    {{ $step['approver'] ?? '-' }}
                                </span>
                                <span class="prh-step-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $step['created_at'] ?? '-' }}
                                </span>
                                @if($step['step'] ?? null)
                                <span class="prh-step-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" /></svg>
                                    Step {{ $step['step'] }}
                                </span>
                                @endif
                            </div>

                            @if($hasChanges)
                            <div class="prh-changes-block">
                                @if(!empty($fieldChanges))
                                <div class="prh-changes-title">Perubahan Field</div>
                                @foreach($fieldChanges as $change)
                                <div class="prh-change-row">
                                    <span class="prh-change-field">{{ $change['field'] ?? '-' }}</span>
                                    <span class="prh-change-from">{{ $change['from'] ?? '-' }}</span>
                                    <span class="prh-change-arrow">&rarr;</span>
                                    <span class="prh-change-to">{{ $change['to'] ?? '-' }}</span>
                                </div>
                                @endforeach
                                @endif

                                @if(!empty($itemChanges['added']) || !empty($itemChanges['updated']) || !empty($itemChanges['removed']))
                                <div class="prh-changes-title" style="margin-top:{{ !empty($fieldChanges) ? '0.6rem' : '0' }};">Perubahan Item</div>
                                <div class="prh-item-changes">
                                    @if(!empty($itemChanges['added']))
                                    <span class="prh-item-pill prh-item-pill-added">+ {{ count($itemChanges['added']) }} ditambahkan</span>
                                    @endif
                                    @if(!empty($itemChanges['removed']))
                                    <span class="prh-item-pill prh-item-pill-removed">&minus; {{ count($itemChanges['removed']) }} dihapus</span>
                                    @endif
                                    @if(!empty($itemChanges['updated']))
                                    <span class="prh-item-pill prh-item-pill-updated">&#9998; {{ count($itemChanges['updated']) }} diubah</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    @endif
</x-filament-panels::page>

