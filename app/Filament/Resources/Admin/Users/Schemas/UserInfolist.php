<?php

namespace App\Filament\Resources\Admin\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make()->columns([
                'lg' => 1
            ])->schema([
                //Account Information
                Section::make('Account Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user_code'),
                        TextEntry::make('username'),
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->label('Email address'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('email_verified_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('vessel.name')
                            ->label('Kapal (Vessel)')
                            ->placeholder('Tidak ada kapal'),
                    ]),
                //Meta data    
                Section::make('Metadata')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn(User $record): bool => $record->trashed()),
                    ]),

            ]),

            Grid::make()->columns([
                'lg' => 1,
            ])->schema([
                //User Details    
                Section::make('User Details')
                    ->relationship('detailsUser')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('phone_number'),
                        TextEntry::make('date_of_birth')
                            ->date(),
                        TextEntry::make('gender'),
                        TextEntry::make('address')
                            ->columnSpanFull(),
                        TextEntry::make('bio')
                            ->columnSpanFull(),
                    ]),
            ])
        ]);
    }
}
