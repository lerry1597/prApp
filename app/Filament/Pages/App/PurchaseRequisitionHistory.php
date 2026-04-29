<?php

namespace App\Filament\Pages\App;

use App\Models\PrHeader;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Pages\Page;

class PurchaseRequisitionHistory extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static ?string $navigationLabel = 'Daftar Pengajuan PR';
    protected static ?string $title = 'Riwayat Purchase Requisition';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected string $view = 'filament.app.pages.purchase-requisition-history';

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('PR Tabs')
                    ->label('')
                    ->tabs([
                        Tab::make('Daftar Pengajuan')
                            ->icon('heroicon-o-list-bullet')
                            ->schema([
                                Grid::make(['default' => 1, 'md' => 2])
                                    ->schema($this->getPrListSchema()),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getPrListSchema(): array
    {
        $user = auth()->user();
        return PrHeader::with('detail')
            ->where('requester_id', $user?->id)
            ->latest()
            ->get()
            ->map(function (PrHeader $pr) {
                return Section::make($pr->pr_number)
                    ->icon('heroicon-o-document')
                    ->schema([
                        Group::make([
                            Text::make('Judul: ' . ($pr->detail->title ?? '-'))
                                ->color('gray')
                                ->size('xs'),
                            Text::make('Status: ' . strtoupper($pr->pr_status ?? 'PENDING'))
                                ->badge()
                                ->color($pr->pr_status === 'approved' ? 'success' : 'warning'),
                            Text::make('Tanggal: ' . ($pr->created_at?->format('d M Y') ?? '-'))
                                ->color('gray')
                                ->size('xs'),
                        ])->columns(3)->gap(),
                    ])
                    ->columnSpan(1);
            })->toArray();
    }
}
