<?php

namespace App\Livewire;


use App\Models\Servicio;
use App\Services\ServicioService;
use App\Services\Validators\CheckAvailableDays;
use App\Services\Validators\IsInRange;
use Carbon\Carbon;
use Filament\Forms\Components\Grid as ComponentsGrid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split as ComponentsSplit;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
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
use Masmerise\Toaster\Toaster;
use Filament\Forms\Get;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\DeleteAction;

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
            ->actionsAlignment('justify-center')
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
                                return $record->habilitado ? 'success' : 'warning';
                            })
                            ->tooltip(fn($record) => $record->habilitado ? 'El servicio esta habilitado' : $record->observaciones)
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
            EditAction::make()
                ->extraAttributes(['class' => 'place-self-center'])
                ->form(function ($record) {
                    $canEdit = true;
                    if ($record->reservas->whereIn('estado', ['Pendiente', 'Confirmado'])->count() == 0) {
                        $canEdit = false;
                    }
                    return [
                        ComponentsSplit::make([
                            TextInput::make('nombre')
                                ->required()
                                ->minLength(5)
                                ->maxLength(30)
                                ->label('Nombre'),
                            TextInput::make('precio')
                                ->prefixIcon('heroicon-o-currency-dollar')
                                ->prefixIconColor('success')
                                ->required()
                                ->numeric()
                                ->step(100)
                                ->suffix('.00')
                                ->label('Precio'),
                        ]),
                        Textarea::make('descripcion')
                            ->required()
                            ->minLength(5)
                            ->maxLength(255)
                            ->label('Descripcion'),
                        TimePicker::make('incio_turno')
                            ->disabled($canEdit)
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Solo puede ser editado si no hay reservas pendientes o confirmadas')
                            ->required()
                            ->rules([
                                new IsInRange($record->proveedor->proveedor->horario_inicio, $record->proveedor->proveedor->horario_fin, true),
                            ])
                            ->seconds(false)
                            ->label('Horario Inicio'),
                        TimePicker::make('fin_turno')
                            ->disabled($canEdit)
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Solo puede ser editado si no hay reservas pendientes o confirmadas')
                            ->required()
                            ->after('incio_turno')
                            ->seconds(false)
                            ->label('Horario Fin'),
                        ComponentsGrid::make([
                            'default' => 1,
                            'xs' => 1,
                            'sm' => 2,
                        ])
                            ->schema([
                                TextInput::make('duracion')
                                    ->required()
                                    ->disabled($canEdit)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Solo puede ser editado si no hay reservas pendientes o confirmadas')
                                    ->numeric()
                                    ->suffix('Minutos')
                                    ->minValue(5)
                                    ->maxValue(function (Get $get) {
                                        $inicio = \Carbon\Carbon::createFromFormat('H:i', $get('incio_turno'));
                                        $fin = \Carbon\Carbon::createFromFormat('H:i', $get('fin_turno'));
                                        $differenceInMinutes = $inicio->diffInMinutes($fin);
                                        return $differenceInMinutes;
                                    })
                                    ->label('Duracion')
                            ]),
                        ViewField::make('dias_disponible')
                            ->view('components.daysselected'),
                        Select::make('dias_disponible')
                            ->multiple()
                            ->disabled($canEdit)
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Solo puede ser editado si no hay reservas pendientes o confirmadas')
                            ->options([
                                'Lunes' => 'Lunes',
                                'Martes' => 'Martes',
                                'Miercoles' => 'Miercoles',
                                'Jueves' => 'Jueves',
                                'Viernes' => 'Viernes',
                                'Sabado' => 'Sabado',
                                'Domingo' => 'Domingo',
                            ])
                            ->searchable(true)
                            ->minItems(fn() => !$canEdit ? 1 : 0)
                            ->required(fn($record) => $record->reservas->whereIn('estado', ['Pendiente', 'Confirmado'])->count() < 0)
                            ->rules([
                                !$canEdit ? new CheckAvailableDays($record->proveedor_id, $record->id, true) : '',
                            ])
                            ->label('Dias Disponibles'),
                    ];
                })
                ->modalWidth(MaxWidth::ExtraLarge)
                ->slideOver()
                ->action(function ($record, $data) {
                    if (isset($data['dias_disponible'])) {
                        $data['dias_disponible'] = implode(',', $data['dias_disponible']);
                    }
                    $record->update($data);
                    Toaster::success('Servicio actualizado correctamente');
                })
                ->icon('heroicon-o-pencil-square')
                ->iconSize(IconSize::Large)
                ->iconPosition(IconPosition::After)
                ->button()
                ->label('')
                ->outlined()
                ->tooltip('Editar detalles del servicio')
                ->extraAttributes(['class' => 'pe-3.5'])
                ->color('success'),
            Action::make('Reservas')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->iconSize(IconSize::Large)
                ->iconPosition(IconPosition::After)
                ->button()
                ->label('')
                ->outlined()
                ->tooltip('Ver reservas del servicio')
                ->extraAttributes(['class' => 'pe-3.5'])
                ->badge(fn($record) => $record->reservas->whereIn('estado', ['Pendiente', 'Confirmado'])->count())
                ->url(fn($record) => route('reserva.selected', $record->id))
                ->iconSize(IconSize::Large)
                ->openUrlInNewTab(false),
            DeleteAction::make('Borrar')
                ->icon('heroicon-o-trash')
                ->iconSize(IconSize::Large)
                ->iconPosition(IconPosition::After)
                ->button()
                ->label('')
                ->outlined()
                ->tooltip('Borrar servicio')
                ->extraAttributes(['class' => 'pe-3.5'])
                ->color('danger')
                ->disabled(fn($record) => $record->reservas->whereIn('estado', ['Pendiente', 'Confirmado'])->count() > 0)
                ->action(function ($record) {
                    $record->delete();
                    Toaster::success('Servicio borrado correctamente');
                })
                ->requiresConfirmation(),
            Action::make('Deshabilitar')
                ->icon('heroicon-o-x-circle')
                ->iconSize(IconSize::Large)
                ->iconPosition(IconPosition::After)
                ->button()
                ->label('')
                ->outlined()
                ->tooltip('Deshabilitar el servicio')
                ->extraAttributes(['class' => 'pe-3.5'])
                ->color('warning')
                ->visible(fn($record) => $record->habilitado == true)
                ->disabled(fn($record) => $record->reservas->whereIn('estado', ['Pendiente', 'Confirmado'])->count() > 0)
                ->form(
                    [
                        Section::make('')
                            ->description('Se cancelaran todas las reservas pendientes y confirmadas')
                            ->schema(
                                [
                                    Textarea::make('observaciones')
                                        ->label('Observaciones')
                                        ->required()
                                        ->minLength(5)
                                        ->maxLength(255)
                                        ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Debe ingresar una observación para deshabilitar el servicio'),
                                ]
                            )
                    ]
                )
                ->action(function ($record, $data) {
                    $servicio = new ServicioService();
                    $servicio->disableServicio($record->id, $data['observaciones']);
                    Toaster::success('Servicio deshabilitado exitosamente');
                })
                ->modalHeading('Deshabilitar Servicio')
                ->requiresConfirmation(),
            Action::make('Habilitar')
                ->icon('heroicon-o-check-circle')
                ->iconSize(IconSize::Large)
                ->iconPosition(IconPosition::After)
                ->button()
                ->label('')
                ->outlined()
                ->tooltip('Habilitar el servicio')
                ->extraAttributes(['class' => 'pe-3.5'])
                ->color('secondary')
                ->visible(fn($record) => $record->habilitado == false)
                ->action(function ($record) {
                    $record->update(['habilitado' => true]);
                    Toaster::success('Servicio habilitado correctamente');
                })
                ->requiresConfirmation(),
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
                        fn(Builder $query, $dias) => $query->where(function ($query) use ($dias) {
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
