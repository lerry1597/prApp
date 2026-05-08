<?php

namespace App\Filament\Resources\Admin\Users\Schemas;

use App\Constants\RoleConstant;
use App\Models\Company;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GeneralUserForm
{
    public static function configure(Schema $schema): Schema
    {
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
                            ->unique(table: User::class, ignoreRecord: true)
                            ->nullable(),

                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('username')
                            ->label('Nama Pengguna')
                            ->required()
                            ->unique(table: User::class, ignoreRecord: true)
                            ->regex('/^\S*$/')
                            ->validationMessages([
                                'regex' => 'Nama Pengguna tidak boleh mengandung spasi.',
                            ])
                            ->suffixAction(
                                Action::make('generateUsername')
                                    ->icon('heroicon-o-arrow-path')
                                    ->tooltip('Generate otomatis dari Nama Lengkap')
                                    ->action(function (Set $set, Get $get) {
                                        $name = $get('name');
                                        if ($name) {
                                            $base = Str::lower(preg_replace('/\s+/', '.', trim($name)));
                                            $candidate = $base;
                                            $i = 1;
                                            while (User::where('username', $candidate)->exists()) {
                                                $candidate = $base . $i;
                                                $i++;
                                            }
                                            $set('username', $candidate);
                                        }
                                    })
                            ),

                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->unique(table: User::class, ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('status')
                            ->label('Status')
                            ->default('active')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->helperText('Akan dikirim otomatis ke email dan WhatsApp pengguna.')
                            ->dehydrated(fn ($state) => filled($state)),

                        Select::make('roles')
                            ->label('Role / Peran')
                            ->relationship(
                                'roles',
                                'title',
                                fn (Builder $query) => $query
                                    ->where('name', '!=', RoleConstant::SUPER_ADMIN)
                                    ->where('name', '!=', RoleConstant::VESSEL_CREW_REQUESTER)
                            )
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->pivotData([
                                'assigned_by' => Auth::id(),
                                'assigned_at' => now(),
                            ])
                            ->required()
                            ->preload()
                            ->searchable(),

                        Select::make('company_id')
                            ->label('Perusahaan')
                            ->options(
                                fn () => Company::where('status', 'active')
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->required()
                            ->searchable()
                            ->placeholder('Pilih Perusahaan'),
                    ]),

                Section::make('Detail Pengguna')
                    ->relationship('detailsUser')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(20),

                        Select::make('department_id')
                            ->label('Departemen')
                            ->relationship('department', 'name')
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('position_id')
                            ->label('Jabatan')
                            ->relationship('position', 'name')
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('date_of_birth')
                            ->label('Tanggal Lahir')
                            ->native(false)
                            ->maxDate(now()),

                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'male'   => 'Laki-laki',
                                'female' => 'Perempuan',
                            ]),

                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('bio')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        FileUpload::make('profile_picture')
                            ->label('Foto Profil')
                            ->image()
                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                            ->directory('media')
                            ->maxSize(2048),
                    ]),
            ]);
    }
}
