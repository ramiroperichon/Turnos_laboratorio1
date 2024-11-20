<?php

namespace App\Livewire;

use App\Models\Proveedor;
use App\Models\Servicio;
use App\Services\ServicioService;
use App\Services\Validators\CheckServicioFinSchedule;
use App\Services\Validators\CheckServicioInicioSchedule;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\DB;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;

class Usuarios extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $query = Proveedor::query();


        return $table
            ->query($query)
            ->groups(static::getGrouping())
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('full_name')
                            ->label('Nombre')
                            ->getStateUsing(fn($record) => $record->usuario->name . ' ' . $record->usuario->last_name)
                            ->sortable(query: function (Builder $query, string $direction): Builder {
                                return $query->join('users', 'proveedores.usuario_id', '=', 'users.id')
                                    ->select('proveedores.*')
                                    ->orderBy('users.name', $direction)
                                    ->orderBy('users.last_name', $direction);
                            })
                            ->searchable(query: function (Builder $query, string $search): Builder {
                                return $query->whereHas('usuario', function ($q) use ($search) {
                                    $q->where('name', 'like', '%' . $search . '%')
                                        ->orWhere('last_name', 'like', '%' . $search . '%');
                                });
                            })
                            ->icon('mdi-account-tie')
                            ->size('xl'),
                        TextColumn::make('profesion')
                            ->sortable()
                            ->searchable()
                            ->icon('mdi-briefcase')
                            ->iconColor('gray')
                            ->color('muted')
                            ->size('xl'),
                    ])->space(2),
                    Stack::make([
                        TextColumn::make('estado')
                            ->getstateusing(fn($record) => ('' . ($record->usuario->estado ? 'Activo' : 'Inactivo')))
                            ->color(fn($record) => $record->usuario->estado ? 'success' : 'danger')
                            ->icon(fn($record) => $record->usuario->estado ? 'mdi-check-circle' : 'mdi-close-circle')
                            ->tooltip(function ($record) {
                                if ($record->usuario->estado == false) {
                                    return $record->usuario->observacion;
                                }
                            })
                            ->size('xl'),
                        TextColumn::make('horario_inicio')
                            ->getStateUsing(fn($record) => ('' .
                                Carbon::parse($record->horario_inicio)->format('H:i') .
                                " a " .
                                Carbon::parse($record->horario_fin)->format('H:i')))
                            ->sortable()
                            ->icon('mdi-briefcase-clock')
                            ->label('Horarios')
                            ->size('xl'),
                    ])->space(2),
                    Stack::make([
                        TextColumn::make('usuario.email')
                            ->icon('mdi-email')
                            ->color('muted')
                            ->label('Email')
                            ->size('xl'),
                        TextColumn::make('usuario.phone')
                            ->label('Telefono')
                            ->color('muted')
                            ->icon('mdi-phone')
                            ->searchable()
                            ->size('xl'),
                    ])->space(2)
                ])
            ])
            ->filters(static::getFilters())
            ->actions(
                static::getActions()
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.usuarios');
    }

    protected static function getGrouping(): array
    {
        return [
            Group::make('usuario.estado')
                ->label('Estado')
                ->getTitleFromRecordUsing(fn($record) => $record->usuario->estado ? 'Activo' : 'Inactivo')
        ];
    }


    protected static function getFilters(): array
    {
        return [
            Filter::make('estado')
                ->label('Estado')
                ->form([
                    Select::make('estado')
                        ->options([
                            true => 'Activo',
                            false => 'Inactivo',
                        ])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        isset($data['estado']),
                        fn(Builder $query): Builder => $query->whereHas('usuario', function ($q) use ($data) {
                            $q->where('estado', (bool) $data['estado']);
                        })
                    );
                })
        ];
    }

    protected static function getActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('crear servicio')->icon('heroicon-o-plus-circle')->color('success')->url(fn($record) => route('administrador.create', $record->usuario->id)),
                Action::make('Deshabilitar')
                    ->icon('heroicon-o-x-circle')
                    ->label('Deshabilitar')
                    ->tooltip('Deshabilitar el proveedor y sus servicios')
                    ->color('warning')
                    ->visible(fn($record) => $record->usuario->estado == true)
                    ->form(
                        [
                            Section::make('')
                                ->description('Se cancelaran todos los servicios y sus reservas.')
                                ->schema(
                                    [
                                        Textarea::make('observacion')
                                            ->label('Observaciones')
                                            ->required()
                                            ->minLength(5)
                                            ->maxLength(255)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Debe ingresar una observaciÃ³n para deshabilitar el servicio'),
                                        ToggleButtons::make('cancelar')
                                            ->label('Cancelar servicios y reservas?')
                                            ->boolean()
                                            ->default(false)
                                            ->inline()
                                    ]
                                )
                        ]
                    )
                    ->action(function ($record, $data) {
                        $record->usuario->update(['estado' => false, 'observacion' => $data['observacion']]);

                        if ($data['cancelar'] == true) {
                            $servicioservice = new ServicioService();
                            foreach (Servicio::where('proveedor_id', $record->usuario->id)->get() as $servicio) {
                                $servicioservice->cancelAllReservas($servicio->id);
                                $servicio->update(['habilitado' => false, 'observaciones' => $data['observacion']]);
                            }
                        }
                        Toaster::success('Proveedor deshabilitado exitosamente' . ($data['cancelar'] == true ? ' y sus servicios cancelados exitosamente' : ''));
                    })
                    ->modalHeading('Deshabilitar Usuario')
                    ->requiresConfirmation(),
                Action::make('Habilitar')
                    ->icon('heroicon-o-check-circle')
                    ->label('Habilitar')
                    ->tooltip('Habilitar el proveedor y sus servicios')
                    ->color('secondary')
                    ->visible(fn($record) => $record->usuario->estado == false)
                    ->form([
                        Section::make('')
                            ->schema(
                                [
                                    ToggleButtons::make('habilitar')
                                        ->label('Volver a habilitar los servicios del proveedor?')
                                        ->boolean()
                                        ->default(false)
                                        ->inline()
                                ]
                            )
                    ])
                    ->action(function ($record, $data) {
                        $record->usuario->update(['estado' => true]);
                        if ($data['habilitar'] == true) {
                            foreach (Servicio::where('proveedor_id', $record->usuario->id)->get() as $servicio) {
                                $servicio->update(['habilitado' => true, 'observaciones' => '']);
                            }
                        }
                        Toaster::success('Proveedor habilitado exitosamente');
                    })
                    ->modalHeading('Habilitar usuario')
                    ->requiresConfirmation(),
                Action::make('ver servicios')->icon('heroicon-o-eye')->color('info')->url(fn($record) => route('administrador.serviciosProveedor', $record->usuario->id)),
                EditAction::make()
                    ->form(function ($record) {
                        return [
                            TextInput::make('profesion')
                                ->required()
                                ->maxLength(50),
                            TimePicker::make('horario_inicio')
                                ->required()
                                ->rules([
                                    new CheckServicioInicioSchedule($record->usuario_id)
                                ])
                                ->seconds(false),
                            TimePicker::make('horario_fin')
                                ->required()
                                ->after('horario_inicio')
                                ->rules([
                                    new CheckServicioFinSchedule($record->usuario_id)
                                ])
                                ->seconds(false),
                        ];
                    })
                    ->action(function ($record, $data) {

                        $record->update($data);

                        Toaster::success('Registro actualizado correctamente');
                    })
                    ->color('secondary')
            ])->iconButton()
                ->size('xl')
                ->color('primary')
                ->tooltip('Acciones')
        ];
    }
}
