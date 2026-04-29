<x-filament-panels::page>
    <style>
        /* Target the very first item in the infolist grid */
        /* .fi-in-section .grid > div:first-child {
            grid-column: 1 / -1 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            text-align: center !important;
            width: 100% !important;
        }

        .fi-in-section .grid > div:first-child * {
            text-align: center !important;
            justify-content: center !important;
            font-size: 1.75rem !important;
            font-weight: 800 !important;
            display: flex !important;
            margin: 0 auto !important;
        }

        /* Hide the label of the title entry */
        /* .fi-in-section .grid>div:first-child .fi-in-entry-label,
        .fi-in-section .grid>div:first-child dt {
            display: none !important;
        }
        */

        trix-editor {
            min-height: 600px !important;
            padding-bottom: 400px !important;
            height: auto !important;
        }
    </style>

    <div class="mb-6">
        {{ $this->getSchema('infolist') }}
    </div>

    <form wire:submit="submit">
        {{ $this->getSchema('form') }}

        <div class="mt-6 pt-3 flex justify-end">
            <x-filament::button type="submit" size="lg">
                Kirim Pengajuan PR
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>