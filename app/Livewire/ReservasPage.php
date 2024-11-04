<?php

namespace App\Livewire;

use App\Models\Reserva;
use App\Models\Servicio;
use App\Services\ServicioService;
use Carbon\Carbon;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table as TablesTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;

class ReservasPage extends Component implements HasTable, HasForms
{

    use InteractsWithTable, InteractsWithForms;

    public $idServicio;

    public function mount($idServicio = null)
    {
        $this->idServicio = $idServicio;
    }

    public function table(TablesTable $table): TablesTable
    {
        $query = Reserva::query();

        if (auth()->user()->hasRole('proveedor')) {
            $query->whereHas('servicio', function ($q) {
                $q->where('proveedor_id', auth()->user()->id);
            });
        }

        if ($this->idServicio) {
            $query->where('servicio_id', $this->idServicio);
        }
        return $table
            ->selectable()
            ->bulkActions(static::getBulkActions())
            ->filters(static::getTableFilters())
            ->filtersLayout(static::getTableFiltersLayout())
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('user.name')->label('Cliente')->sortable()->searchable()->toggleable(),
                TextColumn::make('servicio.nombre')->label('Servicio')->sortable()->searchable()->toggleable()->weight(FontWeight::Bold),
                TextColumn::make('servicio.proveedor.name')->label('Proveedor')->sortable()->searchable()->toggleable()->width('5%'),
                TextColumn::make('hora_inicio')
                    ->label('Horario')
                    ->getStateUsing(fn($record) =>
                    Carbon::parse($record->hora_inicio)->format('H:i') .
                        " a " .
                        Carbon::parse($record->hora_fin)->format('H:i'))->sortable()->toggleable(),
                TextColumn::make('estado')->label('Estado')
                    ->badge()
                    ->color(fn($record) => match ($record->estado) {
                        'Pendiente' => 'secondary',
                        'Confirmado' => 'info',
                        'Cancelado' => 'danger',
                        'Completado' => 'success',
                        default => 'success',
                    })
                    ->sortable()->searchable()->toggleable(),
                TextColumn::make('fecha_reserva')->label('Fecha de reserva')->date()->sortable()->toggleable()->extraAttributes(['class' => 'text-green-500']),
            ])
            ->actions(static::getActions())->actionsColumnLabel("Acciones")
            ->query($query);
    }

    public function render()
    {
        return view('livewire.reservas-page');
    }

    protected static function ConfirmReject($reserva, $estado)
    {
        $servicioService = app(ServicioService::class);

        $servicioService->UpdateReserva($reserva, $estado);
    }

    protected static function CompleteReservas($reserva)
    {
        $reserva->update([
            'estado' => 'Completado'
        ]);
    }

    protected static function DeleteCompletados()
    {
        try {
            $reservas = Reserva::where('estado', 'Completado')->where('fecha_reserva', '<=', now())->get();
            if ($reservas->count() <= 0) {
                Toaster::warning('No hay elementos a eliminar');
                return;
            }
            $reservas->each(fn($record) => $record->delete());
            Toaster::info('Reservas completadas eliminadas correctamente');
        } catch (Exception $e) {
            Toaster::error('Error al eliminar reservas' . $e->getMessage());
        }
    }

    protected static function DeleteRechazados()
    {
        try {
            $reservas = Reserva::where('estado', 'Cancelado')->get();
            if ($reservas->count() <= 0) {
                Toaster::warning('No hay elementos a eliminar');
                return;
            }
            $reservas->each(fn($record) => $record->delete());
            Toaster::info('Se eliminaron ' . $reservas->count() . ' reservas');
        } catch (Exception $e) {
            Toaster::error('Error al eliminar reservas' . $e->getMessage());
        }
    }

    protected static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                BulkAction::make('Confirmar')
                    ->action(fn(EloquentCollection $records) => $records->each(fn($record) => static::ConfirmReservas($record)))
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('Cancelar')
                    ->action(fn(EloquentCollection $records) => $records->each(fn($record) => static::RejectReservas($record)))
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('Completada')
                    ->action(fn(EloquentCollection $records) => $records->each(fn($record) => ($record->fecha_reserva >= now() && in_array($record->estado, ['Confirmado'])) ? $record->delete() : null))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
                BulkAction::make('Borrar')
                    ->action(fn(EloquentCollection $records) => $records->each(
                        fn($record) => ($record->fecha_reserva <= now() && in_array($record->estado, ['Rechazado', 'Confirmado'])) ? $record->delete() : null
                    ))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),
            ])
                ->label('Seleccion')
                ->color('secondary'),
            ActionGroup::make([
                Action::make('Borrar completados')
                    ->action(fn() => static::DeleteCompletados())
                    ->icon('heroicon-o-trash')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Borrar reservas completadas')
                    ->modalDescription('Esta seguro que quiere borrar todas las reservas completadas?
                         Esta accion no se puede deshacer')
                    ->modalSubmitActionLabel('Borrar'),
                Action::make('Borrar cancelados')
                    ->action(fn() => static::DeleteRechazados())
                    ->icon('heroicon-o-trash')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Borrar reservas canceladas')
                    ->modalDescription('Esta seguro que quiere borrar todas las reservas canceladas?
                         Esta accion no se puede deshacer')
                    ->modalSubmitActionLabel('Borrar'),
            ])->color('primary')
                ->button()
        ];
    }

    protected static function getTableFilters(): array
    {
        return [
            Filter::make('estado')
                ->form([
                    Toggle::make('pendiente')
                        ->label('Pendiente'),
                    Toggle::make('confirmado')
                        ->label('Confirmado'),
                    Toggle::make('rechazado')
                        ->label('Cancelado'),
                    Toggle::make('completado')
                        ->label('Completado'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['pendiente'] ?? false,
                            fn(Builder $query): Builder => $query->where('estado', 'Pendiente')
                        )
                        ->when(
                            $data['confirmado'] ?? false,
                            fn(Builder $query): Builder => $query->where('estado', 'Confirmado')
                        )
                        ->when(
                            $data['rechazado'] ?? false,
                            fn(Builder $query): Builder => $query->where('estado', 'Rechazado')
                        )->when(
                            $data['completado'] ?? false,
                            fn(Builder $query): Builder => $query->where('estado', 'Completado')
                        );
                })
                ->indicateUsing(function (array $data): ?string {
                    $states = collect([
                        'pendiente' => 'Pendiente',
                        'confirmado' => 'Confirmado',
                        'rechazado' => 'Rechazado',
                        'completado' => 'Completado'
                    ]);

                    return $states->first(fn($label, $key) => $data[$key] ?? false);
                }),

            Filter::make('fecha_reserva')
                ->form([
                    DatePicker::make('fecha_exacta')
                        ->label('Fecha Exacta'),
                    DatePicker::make('fecha_inicio')
                        ->label('Desde'),
                    DatePicker::make('fecha_fin')
                        ->label('Hasta'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['fecha_exacta'] ?? false,
                            fn(Builder $query) => $query->whereDate('fecha_reserva', $data['fecha_exacta'])
                        )
                        ->when(
                            $data['fecha_inicio'] ?? false,
                            fn(Builder $query) => $query->whereDate('fecha_reserva', '>=', $data['fecha_inicio'])
                        )
                        ->when(
                            $data['fecha_fin'] ?? false,
                            fn(Builder $query) => $query->whereDate('fecha_reserva', '<=', $data['fecha_fin'])
                        );
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['fecha_exacta'] ?? false) {
                        return 'Fecha: ' . $data['fecha_exacta'];
                    }

                    $parts = [];
                    if ($data['fecha_inicio'] ?? false) {
                        $parts[] = 'Desde ' . $data['fecha_inicio'];
                    }
                    if ($data['fecha_fin'] ?? false) {
                        $parts[] = 'Hasta ' . $data['fecha_fin'];
                    }

                    return empty($parts) ? null : implode(' ', $parts);
                }),
        ];
    }

    public static function getTableFiltersLayout(): FiltersLayout
    {
        return FiltersLayout::Modal;
    }

    public static function getHeading($idServicio): string
    {
        if ($idServicio) {
            $servicio = Servicio::find($idServicio);
            return 'Reservas del servicio ' . $servicio->nombre;
        } else {
            return 'Mostrando todas las reservas';
        }
    }

    public static function getActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('Confirmar')
                    ->visible(fn($record) => $record->estado == 'Pendiente')
                    ->action(fn($record) => static::ConfirmReservas($record))
                    ->icon('heroicon-o-check')
                    ->label('Confirmar')
                    ->color('info'),
                Action::make('Completada')
                    ->visible(fn($record) => $record->estado == 'Confirmado' && Carbon::Parse($record->fecha_reserva)->lte(Carbon::now()))
                    ->action(fn($record) => static::CompleteReservas($record))
                    ->icon('heroicon-o-check-circle')
                    ->label('Completada')
                    ->color('success'),
                Action::make('Cancelar')
                    ->visible(fn($record) => $record->estado == 'Pendiente')
                    ->action(fn($record) => static::RejectReservas($record))
                    ->icon('heroicon-o-x-mark')
                    ->label('Cancelar')
                    ->color('warning'),
                Action::make('Borrar')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->estado == 'Completado' || $record->estado == 'Rechazado')
                    ->action(fn($record) => $record->delete())
                    ->icon('heroicon-o-trash')
                    ->label('Borrar')
                    ->color('danger'),
            ])->button()
                ->label('Editar')
                ->icon('heroicon-m-pencil-square')
                ->iconPosition(IconPosition::After)
        ];
    }
}
