<?php

namespace App\Filament\Resources\Admin\Companies\Schemas;

use App\Models\Company;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class CompanyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Utama')
                ->columns(3)
                ->schema([
                    TextEntry::make('name')
                        ->label('Nama Perusahaan')
                        ->weight('bold')
                        ->size('lg')
                        ->columnSpan(2)
                        ->wrap(),
                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn(string $state): string => match ($state) {
                            'active' => 'success',
                            'inactive' => 'danger',
                            default => 'gray',
                        })
                        ->formatStateUsing(fn(string $state): string => match ($state) {
                            'active' => 'Aktif',
                            'inactive' => 'Tidak Aktif',
                            default => $state,
                        }),

                    TextEntry::make('code')
                        ->label('Kode Perusahaan')
                        ->copyable()
                        ->wrap(),
                    TextEntry::make('type')
                        ->label('Tipe Perusahaan')
                        ->badge()
                        ->color(fn(string $state): string => match ($state) {
                            'internal' => 'primary',
                            'vendor' => 'warning',
                            'client' => 'success',
                            default => 'gray',
                        })
                        ->formatStateUsing(fn(string $state): string => match ($state) {
                            'internal' => 'Internal',
                            'vendor' => 'Vendor / Supplier',
                            'client' => 'Klien / Customer',
                            default => $state,
                        }),
                    TextEntry::make('tax_id')
                        ->label('NPWP / Tax ID')
                        ->placeholder('-')
                        ->wrap(),
                ]),

            Section::make('Kontak & Alamat')
                ->columns(3)
                ->schema([
                    TextEntry::make('email')
                        ->label('Email Address')
                        ->icon('heroicon-m-envelope')
                        ->copyable()
                        ->wrap(),
                    TextEntry::make('phone')
                        ->label('Nomor Telepon')
                        ->icon('heroicon-m-phone')
                        ->wrap(),
                    TextEntry::make('website')
                        ->label('Website')
                        ->icon('heroicon-m-globe-alt')
                        ->url(fn($state) => $state ? (str_starts_with($state, 'http') ? $state : "https://{$state}") : null)
                        ->openUrlInNewTab()
                        ->wrap(),
                    TextEntry::make('address')
                        ->label('Alamat Lengkap')
                        ->columnSpanFull()
                        ->placeholder('-')
                        ->wrap(),
                ]),

            Section::make('Metadata')
                ->columns(3)
                ->schema([
                    TextEntry::make('created_at')
                        ->label('Dibuat Pada')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->label('Terakhir Diperbarui')
                        ->dateTime(),
                ]),
        ]);
    }
}
