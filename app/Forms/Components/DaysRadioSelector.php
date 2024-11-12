<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class DaysRadioSelector extends Field
{
    protected string $view = 'forms.components.days-radio-selector';
    public $dias_disponible;

    public function getValue(): array
    {
        $value = parent::getValue();
        return $value;
    }
}
