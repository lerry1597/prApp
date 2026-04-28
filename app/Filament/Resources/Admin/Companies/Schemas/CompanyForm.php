<?php

namespace App\Filament\Resources\Admin\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Perusahaan')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label('Kode Perusahaan')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Tipe Perusahaan')
                            ->options([
                                'internal' => 'Internal',
                                'vendor' => 'Vendor / Supplier',
                                'client' => 'Klien / Customer',
                            ])
                            ->required()
                            ->default('internal'),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                            ])
                            ->required()
                            ->default('active'),
                    ])->columns(2),

                Section::make('Informasi Kontak & Alamat')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('tax_id')
                            ->label('NPWP / Tax ID')
                            ->maxLength(255),
                        Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
