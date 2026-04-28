<?php

namespace App\Filament\Resources\Admin\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $userCodeMaxLength = env('USER_CODE_MAX_LENGTH', 12);

        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->columns(2)
                    ->schema([
                        TextInput::make('user_code')
                            ->label('Kode Pengguna')
                            ->placeholder('Otomatis')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('id_employee')
                            ->label('ID Karyawan')
                            ->placeholder('Masukkan ID Karyawan')
                            ->unique(ignoreRecord: true)
                            ->nullable(),
                        TextInput::make('username')
                            ->label('Nama Pengguna')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->regex('/^\S*$/')
                            ->validationMessages([
                                'regex' => 'Nama Pengguna tidak boleh mengandung spasi.',
                            ]),
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required(),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('status')
                            ->label('Status')
                            ->required()
                            ->default('active')
                            ->disabled(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(),
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state)),
                        Select::make('roles')
                            ->label('Role / Peran')
                            ->relationship(
                                'roles',
                                'title',
                                fn (Builder $query) => $query->where('name', '!=', 'super_admin')
                            )
                            ->pivotData([
                                'assigned_by' => Auth::id(),
                                'assigned_at' => now(),
                            ])
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),
                        Select::make('vessel_id')
                            ->label('Kapal (Vessel)')
                            ->relationship('vessel', 'name')
                            ->placeholder('Pilih Kapal (Opsional)')
                            ->searchable()
                            ->preload()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                    ]),

                Section::make('Detail Pengguna')
                    ->relationship('detailsUser')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone_number')
                            ->label('Nomor Telepon'),
                        DatePicker::make('date_of_birth')
                            ->label('Tanggal Lahir'),
                        Select::make('department_id')
                            ->label('Departemen')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('position_id')
                            ->label('Jabatan')
                            ->relationship('position', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'male' => 'Laki-laki',
                                'female' => 'Perempuan',
                                'other' => 'Lainnya',
                            ]),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->columnSpanFull(),
                        Textarea::make('bio')
                            ->label('Bio')
                            ->columnSpanFull(),
                        FileUpload::make('profile_picture')
                            ->label('Foto Profil')
                            ->image()
                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                            ->directory('media'),
                    ]),
            ]);
    }
}
