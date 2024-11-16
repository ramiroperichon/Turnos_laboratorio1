<?php

namespace App\Livewire;

use App\Models\Proveedor;
use App\Services\Validators\CheckServicioFinSchedule;
use App\Services\Validators\CheckServicioInicioSchedule;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;
use Masmerise\Toaster\Toaster;

class Usuarios extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $query = Proveedor::query();


        return $table->headerActions([
            Action::make('crearProveedor')
                ->label('Crear Proveedor')
                ->color('primary')
                ->icon('heroicon-o-plus')
                ->url(route('administrador.crearProveedor'))
                ->openUrlInNewTab(false)
        ])
            ->query($query)
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('usuario.name')
                    ->sortable()
                    ->searchable(),
/*                     TextColumn::make('Servicios')
                    ->getStateUsing(fn($record) => $record->usuario->servicios()->count())
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->withCount('usuario.servicios')
                            ->orderBy('usuario_servicios_count', $direction);
                    }), */
                TextColumn::make('profesion')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('horario_inicio')
                    ->getStateUsing(fn($record) => ('' .
                        Carbon::parse($record->horario_inicio)->format('H:i') .
                        " a " .
                        Carbon::parse($record->horario_fin)->format('H:i')))
                    ->sortable()
                    ->label('Horarios'),
                TextColumn::make('estado')
                ->sortable()
                ->getstateusing(fn($record) => ('' . ($record->usuario->estado ? 'Activo' : 'Inactivo')))
                ->tooltip(function ($record) {

                        if ($record->usuario->estado==false) {
                            return $record->usuario->observacion;
                        }
                }),
                TextColumn::make('usuario.phone')
                ->sortable()
                ->label('Telefono'),
            ])
            ->filters([
                //
            ])
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

    protected static function getActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('crear servicio')->icon('heroicon-o-plus-circle')->color('success')->url(fn($record) => route('administrador.create', $record->usuario->id)),
                Action::make('Deshabilitar')
                ->icon('heroicon-o-x-circle')
                ->button()
                ->label('')
                ->outlined()
                ->tooltip('Deshabilitar el servicio')
                ->extraAttributes(['class' => 'pe-3.5'])
                ->color('warning')
                ->visible(fn($record) => $record->usuario->estado == true)
                ->form(
                    [
                        Section::make('')
                            ->description('Se cancelaran todas las reservas pendientes y confirmadas')
                            ->schema(
                                [
                                    Textarea::make('observacion')
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
                    $record->usuario->update(['estado' => false, 'observacion' => $data['observacion']]);
                    Toaster::success('Servicio deshabilitado exitosamente');
                })
                ->modalHeading('Deshabilitar Servicio')
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
            ])->icon('heroicon-c-bars-4')->color('success')
        ];
    }
}
