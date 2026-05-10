<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/manage-pr-headers.css') }}">
    

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
                <input type="text" class="mpr-search-input" wire:model.live.debounce.300ms="search" placeholder="Cari No Dokumen, Nama Kapal, atau Nama Barang...">
            </div>
            <select class="mpr-filter-select" wire:model.live="needsFilter">
                <option value="">Semua Kebutuhan</option>
                <option value="Dek">Dek</option>
                <option value="Mesin">Mesin</option>
            </select>
        </div>

        <!-- List -->
        <div
            x-data="{ loadingMore: false, hasMore: @entangle('hasMoreRows') }"
            x-on:scroll.window.debounce.150ms="
                if (loadingMore || !hasMore) return;
                if ((window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 220)) {
                    loadingMore = true;
                    $wire.loadMore().then(() => loadingMore = false);
                }
            ">
            <div class="mpr-list-table-wrap">
                <table class="mpr-list-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">
                                <span class="mpr-head-action" title="No" aria-label="No">No</span>
                            </th>
                            <th>
                                <button type="button" class="mpr-sort-btn {{ $sortColumn === 'document_no' ? 'active' : '' }}" wire:click="sortBy('document_no')" title="Urutkan No Dokumen" aria-label="Urutkan No Dokumen">
                                    No Dokumen
                                    <span class="mpr-sort-arrow">{{ $sortColumn === 'document_no' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="mpr-sort-btn {{ $sortColumn === 'vessel_name' ? 'active' : '' }}" wire:click="sortBy('vessel_name')" title="Urutkan Nama Kapal" aria-label="Urutkan Nama Kapal">
                                    Nama Kapal
                                    <span class="mpr-sort-arrow">{{ $sortColumn === 'vessel_name' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="mpr-sort-btn {{ $sortColumn === 'request_date' ? 'active' : '' }}" wire:click="sortBy('request_date')" title="Urutkan Tanggal Pengajuan" aria-label="Urutkan Tanggal Pengajuan">
                                    Tanggal Pengajuan
                                    <span class="mpr-sort-arrow">{{ $sortColumn === 'request_date' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="mpr-sort-btn {{ $sortColumn === 'status' ? 'active' : '' }}" wire:click="sortBy('status')" title="Urutkan Status" aria-label="Urutkan Status">
                                    Status
                                    <span class="mpr-sort-arrow">{{ $sortColumn === 'status' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="mpr-sort-btn {{ $sortColumn === 'needs' ? 'active' : '' }}" wire:click="sortBy('needs')" title="Urutkan Kebutuhan" aria-label="Urutkan Kebutuhan">
                                    Kebutuhan
                                    <span class="mpr-sort-arrow">{{ $sortColumn === 'needs' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                                </button>
                            </th>
                            <th>
                                <span class="mpr-head-action" title="Aksi" aria-label="Aksi">
                                    Aksi
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->prList as $row)
                        @php
                        $statusColor = \App\Constants\PrStatusConstant::getColor($row->pr_status);
                        $statusLabel = \App\Constants\PrStatusConstant::getStatuses()[$row->pr_status] ?? $row->pr_status;
                        $hintItems = $this->matchedItemHints[$row->id] ?? [];
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: 600; color: #64748b;">{{ $loop->iteration }}</td>
                            <td class="mpr-doc-no">{{ $row->detail?->document_no ?? '-' }}</td>
                            <td>
                                <div>{{ $row->detail?->vessel?->name ?? '-' }}</div>
                                @if(!empty($hintItems))
                                <div class="mpr-item-hint">Barang cocok: {{ implode(', ', $hintItems) }}</div>
                                @endif
                            </td>
                            <td>{{ $row->detail?->request_date?->format('d M Y') ?? '-' }}</td>
                            <td><span class="mpr-badge mpr-badge-{{ $statusColor }}">{{ $statusLabel }}</span></td>
                            <td>{{ $row->detail?->needs ?? '-' }}</td>
                            <td>
                                <button class="mpr-action-btn" wire:click="openModal({{ $row->id }})">
                                    Proses Pengajuan
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="mpr-empty-state">Belum ada PR yang perlu disetujui.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mpr-load-state" wire:loading.flex wire:target="loadMore">
            <span class="mpr-spinner"></span>
            Memuat 10 data berikutnya...
        </div>
        @if(!$this->hasMoreRows && count($this->prList) > 0)
        <div class="mpr-load-state mpr-load-end">Semua data sudah dimuat.</div>
        @endif

        <!-- Modal -->
        @if($showModal && $selectedPr)
        <div class="mpr-modal-overlay" x-data="{ full: false }" :class="{ 'mpr-modal-overlay-full': full }">
            <div class="mpr-modal" :class="{ 'mpr-modal-fullscreen': full }" role="dialog" aria-modal="true">
                <div class="mpr-modal-header">
                    <div class="mpr-modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3h5.25" />
                        </svg>
                        Proses Pengajuan PR &mdash; {{ $selectedPr->detail?->document_no ?? '-' }}
                    </div>
                    <div class="mpr-modal-header-actions">
                        <button
                            type="button"
                            class="mpr-modal-full-toggle"
                            @click="full = !full"
                            :aria-label="full ? 'Keluar fullscreen' : 'Layar penuh'"
                            :title="full ? 'Keluar fullscreen' : 'Layar penuh'">
                            <template x-if="!full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" style="width:1.15rem;height:1.15rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                            </template>
                            <template x-if="full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" style="width:1.15rem;height:1.15rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 4v5H4m11-5v5h5M9 20v-5H4m11 5v-5h5" />
                                </svg>
                            </template>
                        </button>
                        <button class="mpr-modal-close" wire:click="closeModal" aria-label="Tutup">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mpr-modal-body">
                    <!-- PR Info -->
                    <div class="mpr-info-card">
                        <div class="mpr-info-grid">
                            {{-- 
                            <!-- <div class="mpr-info-item">
                                <span class="mpr-info-label">Pemohon</span>
                                <span class="mpr-info-val"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg> {{ $selectedPr->requester?->name ?? '-' }}</span>
                            </div> -->
                            --}}
                            <div class="mpr-info-item">
                                <span class="mpr-info-label">Tujuan Pengiriman</span>
                                <span class="mpr-info-val"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375M8.25 9.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008z" />
                                    </svg> {{ $selectedPr->detail?->delivery_address ?? '-' }}</span>
                            </div>
                            <div class="mpr-info-item">
                                <span class="mpr-info-label">Kapal</span>
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
                                <span class="mpr-info-label">Departemen</span>
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
                                    <th width="140" style="text-align: center;">Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Sisa</th>
                                    <th>Klasifikasi Urgensi</th>
                                    <th>Keterangan</th>
                                    <th width="90" style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($this->selectedItems as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <select class="mpr-input mpr-select-input" wire:model="itemCategoryIds.{{ $item->id }}">
                                            <option value="">--Pilih--</option>
                                            @foreach($this->itemCategoryOptions as $categoryId => $categoryName)
                                            <option value="{{ $categoryId }}">{{ $categoryName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="mpr-input mpr-name-input" wire:model="itemTypes.{{ $item->id }}">
                                    </td>
                                    <td>
                                        <input type="text" class="mpr-input mpr-size-input-narrow" wire:model="itemSizes.{{ $item->id }}">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" step="0.01" min="0" class="mpr-qty-input" wire:model="itemQuantities.{{ $item->id }}">
                                    </td>
                                    <td>
                                        <input type="text" class="mpr-input mpr-unit-input" wire:model="itemUnits.{{ $item->id }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" class="mpr-qty-input mpr-remaining-input" wire:model="itemRemainings.{{ $item->id }}">
                                    </td>
                                    <td>
                                        <select class="mpr-input mpr-select-input mpr-priority-select-narrow" wire:model="itemPriorities.{{ $item->id }}">
                                            <option value="">--Pilih--</option>
                                            <option value="Kritis">Kritis</option>
                                            <option value="Menengah">Menengah</option>
                                            <option value="Rutin">Rutin</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="mpr-table-description-input" wire:model="itemDescriptions.{{ $item->id }}" placeholder="Keterangan...">
                                    </td>
                                    <td style="text-align: center;">
                                        <button
                                            type="button"
                                            class="mpr-delete-btn"
                                            wire:click="deleteItem({{ $item->id }})"
                                            onclick="return confirm('Hapus item ini dari proses approval?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-10.5 0v11.25A1.5 1.5 0 009 20.25h6a1.5 1.5 0 001.5-1.5V7.5m-6.75 0V5.25A1.5 1.5 0 0111.25 3.75h1.5a1.5 1.5 0 011.5 1.5V7.5" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" style="text-align: center; color: #64748b;">Tidak ada barang</td>
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
