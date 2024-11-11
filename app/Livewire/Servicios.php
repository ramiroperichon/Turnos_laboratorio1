<?php

namespace App\Livewire;

use App\Models\Servicio;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table as TablesTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;

class Servicios extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function render()
    {
        return view('livewire.servicios');
    }

    public function table(TablesTable $table): TablesTable
    {
        $query = Servicio::query();
        if (auth()->user()->hasRole('proveedor')) {
            $query->whereHas('proveedor', function ($q) {
                $q->where('id', auth()->user()->id);
            });
        };

        return $table
            ->filters(static::getFilters())->filtersFormColumns(3)
            ->query($query)
            ->actions(static::getActions())
            ->columns([
                Split::make([
                    Grid::make()
                        ->columns(1)
                        ->schema([
                            IconColumn::make('id')
                                ->icon('heroicon-s-briefcase')
                                ->size('2xl')
                                ->alignCenter()
                        ])->grow(false),
                    Stack::make([
                        TextColumn::make('nombre')
                            ->label('Nombre')
                            ->sortable()
                            ->searchable()
                            ->weight('bold'),
                        TextColumn::make('proveedor.name')
                            ->label('Proveedor')
                            ->sortable()
                            ->extraAttributes(['class' => 'text-center'])
                            ->limit(10)
                            ->tooltip(function (TextColumn $column): ?string {
                                $state = $column->getState();
                                if (strlen($state) <= 10) {
                                    return null;
                                }
                                return $state;
                            })
                            ->searchable()
                            ->weight('bold')
                            ->color(Color::hex('#6c7293')),
                        TextColumn::make('descripcion')
                            ->label('Descripción')
                            ->color(Color::hex('#6c7293'))
                            ->limit(30)
                            ->tooltip(function (TextColumn $column): ?string {
                                $state = $column->getState();
                                if (strlen($state) <= 30) {
                                    return null;
                                }
                                return $state;
                            }),
                    ])->space(2),
                    Stack::make([
                        TextColumn::make('precio')
                            ->label('Precio')
                            ->sortable()
                            ->prefix('$')
                            ->money('ars')
                            ->color(Color::hex('#77ed28')),
                        TextColumn::make('incio_turno')
                            ->label('Horario')
                            ->icon('heroicon-s-bookmark-square')
                                ->getStateUsing(fn($record) => ('' .
                                    Carbon::parse($record->incio_turno)->format('H:i') .
                                    " a " .
                                    Carbon::parse($record->fin_turno)->format('H:i')))
                                ->sortable(),
                        TextColumn::make('duracion')
                            ->label('Duración')
                            ->tooltip('Duración del turno')
                            ->color(Color::hex('#6c7293'))
                            ->icon('heroicon-o-clock')
                            ->iconColor(Color::Neutral)
                            ->sortable()
                            ->suffix('Min'),
                        TextColumn::make('habilitado')
                            ->icon(function ($record) {
                                return $record->habilitado ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                            })
                            ->getStateUsing(fn($record) => $record->habilitado ? 'Habilitado' : 'Deshabilitado')
                            ->color(function ($record) {
                                return $record->habilitado ? 'success' : 'danger';
                            })
                            ->size('xl')
                    ])->space(1)->grow(false),
                ])->from('sm'),
                Panel::make([
                    ViewColumn::make('status')->view('components.dayscolumn'),
                ])->collapsible()
                    ->collapsed(true)
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ]);
    }

    public static function getActions(): array
    {
        return [
            Action::make('Crear')
                ->visible(fn($record) => auth()->user()->hasRole('proveedor') || auth()->user()->hasRole('administrador'))
                ->icon('heroicon-o-plus-circle')
                ->color('info')
                ->view('components.servicioEditModal')
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkAction::make('Desabilitar')
                ->action(fn(EloquentCollection $records) => $records->each(fn($record) => $record->update(['habilitado' => false])))
                ->icon('heroicon-o-x-circle')
                ->button()
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Deshabilitar Servicios')
                ->modalDescription('Esta seguro que quiere deshabilitar los servicios seleccionados?
                Esta accion no se puede deshacer')
                ->modalSubmitActionLabel('Deshabilitar'),
        ];
    }

    public static function getFilters(): array
    {
        return [
            Filter::make('habilitado')
                ->form([
                    Select::make('habilitado')
                        ->label('Habilitado')
                        ->options([
                            true => 'Habilitado',
                            false => 'Deshabilitado',
                        ]),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        isset($data['habilitado']),
                        fn(Builder $query): Builder => $query->where('habilitado', (bool) $data['habilitado'])
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    $states = [
                        true => 'Habilitado',
                        false => 'Deshabilitado',
                    ];
                    return $states[$data['habilitado']] ?? null;
                }),
            Filter::make('dias_disponible')
                ->form([
                    Select::make('dias_disponible')
                        ->multiple()
                        ->label('Dias Disponibles')
                        ->options([
                            'Lunes' => 'Lunes',
                            'Martes' => 'Martes',
                            'Miercoles' => 'Miercoles',
                            'Jueves' => 'Jueves',
                            'Viernes' => 'Viernes',
                            'Sabado' => 'Sabado',
                            'Domingo' => 'Domingo',
                        ]),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['dias_disponible'] ?? null,
                        fn (Builder $query, $dias) => $query->where(function ($query) use ($dias) {
                            foreach ($dias as $dia) {
                                $query->where('dias_disponible', 'LIKE', "%$dia%");
                            }
                        })
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    $dias = $data['dias_disponible'] ?? null;
                    if (is_array($dias)) {
                        return implode(', ', $dias);
                    }
                    return $dias;
                }),
        ];
    }
}
