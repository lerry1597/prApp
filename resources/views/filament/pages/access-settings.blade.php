<x-filament-panels::page>
    <style>
        .custom-grid-2 {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 2rem;
        }

        @media (min-width: 1024px) {
            .custom-grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        
        .custom-nav {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 640px) {
            .custom-nav {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .custom-nav {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
            }
        }
    </style>

    <div name="access-settings" x-data="{ tab: null }" class="space-y-8">
        <nav class="custom-nav">
            <x-filament::tabs.item
                active="tab === 'roles'"
                icon="heroicon-m-key"
                x-on:click="tab = 'roles'">
                Peran & Izin
            </x-filament::tabs.item>

            <x-filament::tabs.item
                active="tab === 'org'"
                icon="heroicon-m-building-office"
                x-on:click="tab = 'org'">
                Struktur Organisasi
            </x-filament::tabs.item>

            <x-filament::tabs.item
                active="tab === 'audit-mapping'"
                icon="heroicon-m-clipboard-document-check"
                x-on:click="tab = 'audit-mapping'">
                Audit Mapping
            </x-filament::tabs.item>

            <x-filament::tabs.item
                active="tab === 'audit-role'"
                icon="heroicon-m-user-group"
                x-on:click="tab = 'audit-role'">
                Audit Role
            </x-filament::tabs.item>
        </nav>

        <!-- Bagian Settings -->
        <div x-show="tab === 'roles'" x-cloak class="custom-grid-2">
            <div class="overflow-x-auto">
                <!-- <h2 class="text-xl font-bold mb-4">Daftar Peran (Roles)</h2> -->
                @livewire('access-control.role-table')
            </div>
            <div class="overflow-x-auto">
                <!-- <h2 class="text-xl font-bold mb-4">Daftar Izin (Permissions)</h2> -->
                @livewire('access-control.permission-table')
            </div>
        </div>

        <div x-show="tab === 'org'" x-cloak class="custom-grid-2">
            <div class="overflow-x-auto">
                <!-- <h2 class="text-xl font-bold mb-4">Daftar Departemen</h2> -->
                @livewire('access-control.department-table')
            </div>
            <div class="overflow-x-auto">
                <!-- <h2 class="text-xl font-bold mb-4">Daftar Jabatan</h2> -->
                @livewire('access-control.position-table')
            </div>
        </div>

        <div x-show="tab === 'audit-mapping'" x-cloak>
            <!-- <h2 class="text-xl font-bold mb-4">Riwayat Pemetaan Izin</h2> -->
            @livewire('access-control.audit-mapping-table')
        </div>

        <div x-show="tab === 'audit-role'" x-cloak>
            <!-- <h2 class="text-xl font-bold mb-4">Riwayat Penugasan Peran</h2> -->
            @livewire('access-control.audit-assignment-table')
        </div>
    </div>
</x-filament-panels::page>