<?php

namespace App\Livewire;

use App\Models\Reserva;
use App\Models\Servicio;
use App\Services\ServicioService;
use Carbon\Carbon;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table as TablesTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Grouping\Group;
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
        if (auth()->user()->hasRole('cliente')) {
            $query->where('cliente_id', auth()->user()->id);
        }

        if ($this->idServicio) {
            $query->where('servicio_id', $this->idServicio);
        }
        return $table
            ->query($query)
            ->selectable((auth()->user()->hasRole('cliente')) ? false : true)
            ->queryStringIdentifier('reservas')
            ->extremePaginationLinks(true)
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->extraHeaderAttributes(['class' => 'dark:text-blue-500']),
                TextColumn::make('user.name')
                    ->label('Cliente')
                    ->getStateUsing(fn($record) => $record->user->name . ' ' . $record->user->last_name)
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('servicio.nombre')
                    ->label('Servicio')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('servicio.proveedor.name')
                    ->label('Proveedor')
                    ->getStateUsing(fn($record) => $record->servicio->proveedor->name . ' ' . $record->servicio->proveedor->last_name)
                    ->sortable()
                    ->searchable()
                    ->visible(auth()->user()->hasRole('administrador'))
                    ->toggleable()
                    ->width('5%')
                    ->color(Color::hex('#6c7293')),
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
                TextColumn::make('fecha_reserva')
                    ->label('Fecha de reserva')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->extraAttributes(['class' => 'text-green-500'])
                    ->grow(false),
            ])
            ->groups(
                static::getGrouping()
            )
            ->groupingSettingsInDropdownOnDesktop(true)
            ->actions((auth()->user()->hasRole('cliente')) ? static::getActionsCliente() : static::getActions())->actionsColumnLabel("Acciones")
            ->bulkActions((auth()->user()->hasRole('cliente')) ? [] : static::getBulkActions())
            ->headerActions((auth()->user()->hasRole('cliente')) ? [] : static::getHeaderActions())
            ->filters(static::getTableFilters())
            ->filtersLayout(static::getTableFiltersLayout());
    }

    public function render()
    {
        return view('livewire.reservas-page');
    }

    protected static function ConfirmReject($reserva, $estado)
    {
        $servicioService = app(ServicioService::class);

        $servicioService->UpdateReserva($reserva, $estado);
        Toaster::success('Reserva actualizada correctamente');
    }

    protected static function DeleteCompletados()
    {
        try {
            $reservas = Reserva::where('estado', 'Completado')->get();
            if (auth()->user()->hasRole('proveedor')) {
                $reservas = $reservas->where('servicio.proveedor_id', auth()->user()->id);
            }
            if ($reservas->count() <= 0) {
                Toaster::warning('No hay elementos a eliminar');
            } else {
                $reservas->each(fn($record) => $record->delete());
                Toaster::info('Se eliminaron ' . $reservas->count() . ' reservas');
            }
        } catch (Exception $e) {
            Toaster::error('Error al eliminar reservas' . $e->getMessage());
        }
    }

    protected static function DeleteRechazados()
    {
        try {
            $reservas = Reserva::where('estado', 'Cancelado')->get();
            if (auth()->user()->hasRole('proveedor')) {
                $reservas = $reservas->where('servicio.proveedor_id', auth()->user()->id);
            }
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
                    ->action(
                        fn(EloquentCollection $records) =>
                        $records->each(
                            fn($record) =>
                            $record->estado === 'Pendiente' ? static::ConfirmReject($record, 'Confirmado') : null
                        )
                    )
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Se confirmaran las reservas pendientes')
                    ->modalDescription('Esta seguro que quiere proceder?
                    Esta accion no se puede deshacer')
                    ->modalSubmitActionLabel('Confirmar')
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('Cancelar')
                    ->action(
                        fn(EloquentCollection $records) =>
                        $records->each(
                            fn($record) =>
                            in_array($record->estado, ['Pendiente', 'Confirmado']) ? static::ConfirmReject($record, 'Cancelado') : null
                        )
                    )
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Se cancelaran las reservas pendientes y confirmadas')
                    ->modalDescription('Esta seguro que quiere proceder?
                    Esta accion no se puede deshacer')
                    ->modalSubmitActionLabel('Confirmar')
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('Completada')
                    ->action(fn(EloquentCollection $records) => $records->each(
                        fn($record) => ($record->fecha_reserva >= now() && in_array($record->estado, ['Confirmado'])) ? static::ConfirmReject($record, 'Completado') : null
                    ))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Se marcaran como completadas las reservas confirmadas')
                    ->modalDescription('Esta seguro que quiere proceder?
                    Esta accion no se puede deshacer')
                    ->modalSubmitActionLabel('Confirmar')
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
                BulkAction::make('Borrar')
                    ->action(fn(EloquentCollection $records) => $records->each(
                        fn($record) => (
                            in_array($record->estado, ['Cancelado', 'Completado'])) ? $record->delete() : null
                    ))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Se borraran las reservas canceladas y completadas')
                    ->modalDescription('Esta seguro que quiere proceder?
                    Esta accion no se puede deshacer')
                    ->modalSubmitActionLabel('Confirmar')
                    ->deselectRecordsAfterCompletion(),
            ])
                ->tooltip('Seleccion')
                ->icon('heroicon-c-ellipsis-vertical')
                ->iconSize(IconSize::Large)
                ->label('Seleccion')
                ->iconButton()
                ->color('secondary'),
        ];
    }

    protected static function getHeaderActions(): array
    {
        return [
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
            ])
                ->color('primary')
                ->tooltip('Borrar reservas')
                ->icon('heroicon-o-trash')
                ->link()
                ->label('Limpieza')
        ];
    }

    public static function getActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('Confirmar')
                    ->action(fn($record) => static::ConfirmReject($record, 'Confirmado'))
                    ->icon('heroicon-o-check')
                    ->label('Confirmar')
                    ->disabled(fn($record) => $record->estado != 'Pendiente')
                    ->color('info'),
                Action::make('Completada')
                    ->action(fn($record) => static::ConfirmReject($record, 'Completado'))
                    ->icon('heroicon-o-check-circle')
                    ->label('Completada')
                    ->disabled(fn($record) => $record->estado != 'Confirmado' || ($record->estado == 'Confirmado' && $record->fecha_reserva > now()))
                    ->color('success'),
                Action::make('Cancelar')
                    ->action(fn($record) => static::ConfirmReject($record, 'Cancelado'))
                    ->icon('heroicon-o-x-mark')
                    ->label('Cancelar')
                    ->disabled(
                        fn($record) =>
                        !in_array($record->estado, ['Pendiente', 'Confirmado'])
                    )
                    ->color('warning'),
                Action::make('Borrar')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->delete())
                    ->icon('heroicon-o-trash')
                    ->label('Borrar')
                    ->disabled(fn($record) => !in_array($record->estado, ['Cancelado', 'Completado']))
                    ->color('danger'),
            ])
                ->iconButton()
                ->icon('heroicon-m-pencil-square')
                ->iconPosition(IconPosition::After)
        ];
    }

    protected static function getActionsCliente(): array
    {
        return [
            Action::make('Cancelar')
                ->action(fn($record) => static::ConfirmReject($record, 'Cancelado'))
                ->icon('mdi-cancel')
                ->label('Cancelar')
                ->disabled(fn($record) => $record->estado != 'Pendiente' && $record->estado != 'Confirmado')
                ->color('danger'),
        ];
    }

    protected static function getTableFilters(): array
    {
        return [
            Filter::make('estado')
                ->form([
                    Select::make('estado')
                        ->label('Estado')
                        ->options([
                            'pendiente' => 'Pendiente',
                            'confirmado' => 'Confirmado',
                            'rechazado' => 'Cancelado',
                            'completado' => 'Completado',
                        ]),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['estado'] ?? false,
                        fn(Builder $query, $estado): Builder => $query->where('estado', ucfirst($estado))
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    $states = [
                        'pendiente' => 'Pendiente',
                        'confirmado' => 'Confirmado',
                        'rechazado' => 'Cancelado',
                        'completado' => 'Completado',
                    ];

                    return $states[$data['estado']] ?? null;
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

    public static function getGrouping(): array
    {
        $groups = [];
        $estado =Group::make('estado')
            ->titlePrefixedWithLabel(false);
        $usuario = Group::make('user.name')
        ->label('Cliente')
            ->titlePrefixedWithLabel(false);
        $servicio = Group::make('servicio.nombre')
            ->titlePrefixedWithLabel(false);
        $proveedor = Group::make('servicio.proveedor.name')
            ->titlePrefixedWithLabel(false);

            if(auth()->user()->hasRole(['administrador', 'proveedor'])){
                $groups[] = $usuario;
            }
            if(auth()->user()->hasRole(['cliente', 'administrador'])){
                $groups[] = $proveedor;
            }
            $groups[] = $servicio;
            $groups[] = $estado;
        return $groups;
    }

    public static function getTableFiltersLayout(): FiltersLayout
    {
        return FiltersLayout::Modal;
    }

    /*     public static function getHeading($idServicio): string
    {
        if ($idServicio) {
            $servicio = Servicio::find($idServicio);
            return 'Reservas del servicio ' . $servicio->nombre;
        } else {
            return 'Mostrando todas las reservas';
        }
    } */
}
