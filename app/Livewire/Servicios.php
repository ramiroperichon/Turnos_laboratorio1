<?php

namespace App\Livewire;

use App\Models\Reserva;
use App\Models\Servicio;
use Carbon\Carbon;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table as TablesTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;

use function Laravel\Prompts\text;

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

        return $table
            ->columns([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Split::make([
                            Grid::make()
                                ->columns(1)
                                ->schema([
                                    TextColumn::make('nombre')->label('Nombre')->sortable()->searchable(),
                                ])->grow(false),


                            Grid::make()
                                ->columns(2)
                                ->schema([
                                    TextColumn::make('descripcion')->label('Descripcion')->sortable(),
                                    Stack::make([
                                        TextColumn::make('precio')->label('Precio')->sortable(),
                                        TextColumn::make('incio_turno')->label('Incio Turno')->sortable()->searchable(),
                                        TextColumn::make('fin_turno')->label('Fin Turno')->sortable()->searchable()
                                    ])->grow(false)
                                ])->grow(),

                        ])
                    ])
            ])->contentGrid([
                'md' => 1,
                'xl' => 3,
            ])
            ->query($query);
    }
}
