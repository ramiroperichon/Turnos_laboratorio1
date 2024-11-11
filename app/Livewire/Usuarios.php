<?php

namespace App\Livewire;

use App\Models\Proveedor;
use App\Models\User;
use App\Models\Servicio;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
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
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('usuario.name')->sortable()->searchable(),
                TextColumn::make('servicios')->getStateUsing(fn($record) => Servicio::where('proveedor_id', $record->usuario_id)->count()),
                TextColumn::make('profesion')->sortable()->searchable(),
                TextColumn::make('horario_inicio')->getStateUsing(fn($record) => ('' .
                    Carbon::parse($record->horario_inicio)->format('H:i') .
                    " a " .
                    Carbon::parse($record->horario_fin)->format('H:i')))
                    ->sortable()->label('Horarios'),
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
                Action::make('crear')->icon('heroicon-o-plus-circle')->color('success'),
                Action::make('dar de baja')->icon('heroicon-o-x-circle')->color('danger'),
                Action::make('ver servicios')->icon('heroicon-o-eye')->color('info')->url('/administrador/servicios'),
                EditAction::make()
                    ->form([
                        TextInput::make('profesion')->required(),
                        TextInput::make('horario_inicio')->required(),
                        TextInput::make('horario_fin')->required()
                    ])
                    ->action(function ($record, $data) {

                        $record->update($data);

                        Toaster::success('Registro actualizado correctamente');
                    })
            ])->icon('heroicon-c-bars-4')->color('success')
        ];
    }

    protected static function updateProveedor(array $data, $user_id)
    {

        $user = User::find($user_id);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'profesion' => $data['profesion'],
            'horario_inicio' => $data['horario_inicio'],
            'horario_fin' => $data['horario_fin'],
        ]);

        /*if($user->isDirty('email')){

            $user->email_verified_at= null;
        }*/

        $user->save();

        if ($user->proveedor) {
            $user->proveedor->update([
                'profesion' => $data['profesion'],
                'horario_inicio' => $data['horario_inicio'],
                'horario_fin' => $data['horario_fin'],
            ]);
        }

        return view('administrador.dashboard');

    }
}
