<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\Widget;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;

class IncomeFiltersWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static bool $isDiscovered = false;
    protected string $view = 'filament.widgets.pendapatan-filter-widget';

    protected int|string|array $columnSpan = 'full';
    public string $businessCustomer = '';
    public string $startDate = '';
    public string $endDate = '';
    public string $month = '';
    public string $year = '';


    public function mount(): void
    {
        $filter = session('pendapatan_filter', []);

        $this->form->fill([
            'startDate' => $filter['startDate'] ?? '',
            'endDate' => $filter['endDate'] ?? now()->format('Y-m-d'),
            'month' => $filter['month'] ?? '',
            'year' => $filter['year'] ?? now()->year,
        ]);
    }
    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()
                ->schema([
                    Grid::make(4)->schema([

                        DatePicker::make('startDate')
                            ->label('Start date')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->live()
                            ->afterStateUpdated(fn() => $this->syncFilter()),

                        DatePicker::make('endDate')
                            ->label('End date')
                            ->displayFormat('d/m/Y')
                            ->minDate(fn(Get $get) => $get('startDate'))
                            ->maxDate(now())
                            ->live()
                            ->afterStateUpdated(fn() => $this->syncFilter()),
                        Select::make('month')
                            ->label('Month')
                            ->placeholder('Semua')
                            ->options([
                                '01' => 'Jan',
                                '02' => 'Feb',
                                '03' => 'Mar',
                                '04' => 'Apr',
                                '05' => 'May',
                                '06' => 'Jun',
                                '07' => 'Jul',
                                '08' => 'Aug',
                                '09' => 'Sep',
                                '10' => 'Oct',
                                '11' => 'Nov',
                                '12' => 'Dec',
                            ])
                            ->live()
                            ->afterStateUpdated(fn() => $this->syncFilter()),

                        Select::make('year')
                            ->label('Year')
                            ->placeholder('Semua')
                            ->options(function () {
                                $firstYear = Transaction::min(
                                    DB::raw('YEAR(created_at)')
                                ) ?? now()->year;

                                return collect(range(now()->year, $firstYear))
                                    ->mapWithKeys(fn($y) => [$y => $y]);
                            })
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                // JIKA YEAR = SEMUA → RESET BULAN
                                if (empty($state)) {
                                    $this->month = '';
                                    $this->form->fill([
                                        'month' => '',
                                    ]);
                                }

                                $this->syncFilter();
                            }),

                    ]),
                ])
                ->compact(),
        ]);
    }

    protected function syncFilter(): void
    {
        $isEmpty =
            empty($this->startDate) &&
            empty($this->endDate);

        if ($isEmpty) {
            session()->forget('pendapatan_filter');
        } else {
            session()->put('pendapatan_filter', [
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'month' => $this->month,
                'year' => $this->year,
            ]);
        }

        $this->dispatch('pendapatan-filter-updated');
    }
}
