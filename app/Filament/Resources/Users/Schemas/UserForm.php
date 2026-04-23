<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('user_code')
                            ->required()
                            ->maxLength(12),
                        TextInput::make('username')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('status')
                            ->required()
                            ->default('active'),
                        TextInput::make('password')
                            ->password()
                            ->required(fn($record) => $record === null)
                            ->dehydrated(fn($password) => filled($password)),
                    ]),

                Section::make('User Details')
                    ->relationship('detailsUser')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone_number'),
                        DatePicker::make('date_of_birth'),
                        Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ]),
                        Textarea::make('address')
                            ->columnSpanFull(),
                        Textarea::make('bio')
                            ->columnSpanFull(),
                        TextInput::make('profile_picture'),
                    ]),
            ]);
    }
}
