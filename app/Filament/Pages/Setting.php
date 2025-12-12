<?php

namespace App\Filament\Pages;

use App\Models\Store;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class Setting extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog';
    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan';

    protected string $view = 'filament.pages.Setting';

    public ?array $data = [];

    public ?string $successMessage = null;

    public function mount(): void
    {
        $store = Store::first();

        if ($store) {
            $this->form->fill($store->toArray());
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Umum')
                    ->schema([
                        TextInput::make('store_name')
                            ->label('Nama Toko')
                            ->columnSpanFull()
                            ->required(),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->rows(2),

                        TextInput::make('address')
                            ->label('Alamat')
                            ->columnSpanFull(),

                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('logo')
                            ->imageEditor()
                            ->columnSpanFull()
                            ->maxSize(2048),

                    ])
                    ->columns(2)
                    ->columnSpan(2),

                Section::make('Sosial Media & Kontak')
                    ->schema([

                        TextInput::make('whatsapp')
                            ->label('Whatsapp'),

                        TextInput::make('tiktok')
                            ->label('TikTok'),

                        TextInput::make('facebook')
                            ->label('Facebook'),

                        TextInput::make('instagram')
                            ->label('Instragram'),
                    ])
                    ->columns(2),

                Section::make('Marketplace')
                    ->schema([
                        TextInput::make('toko_pedia')
                            ->label('Tokopedia'),

                        TextInput::make('shopee')
                            ->label('Shopee'),
                    ]),

                Section::make('Midtrans Configuration')
                    ->schema([
                        TextInput::make('midtrans_client_key')
                            ->label('Midtrans Client Key')
                            ->required(),

                        TextInput::make('midtrans_server_key')
                            ->label('Midtrans Server Key')
                            ->required(),

                        Toggle::make('is_production')
                            ->label('Is Production')
                            ->helperText('Aktifkan untuk mode produksi, nonaktifkan untuk sandbox'),
                    ]),


            ])->columns(2)
            ->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();

        $store = Store::first() ?? new Store();
        $store->fill($data);
        $store->save();

        $this->successMessage = 'Pengaturan berhasil disimpan!';

        Notification::make()
            ->title('Pengaturan berhasil disimpan!')
            ->success()
            ->duration(3000) // tampil 3 detik (opsional)
            ->send();
    }
}
