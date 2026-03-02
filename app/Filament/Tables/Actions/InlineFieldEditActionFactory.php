<?php

namespace App\Filament\Tables\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

final class InlineFieldEditActionFactory
{
    public static function makeInput(string $actionName, string $field): Action
    {
        return Action::make($actionName)
            ->fillForm(fn ($record): array => [
                $field => $record->{$field},
            ])
            ->schema([
                TextInput::make($field)
                    ->required(),
            ])
            ->action(fn (array $data, $record) => $record->update($data));
    }

    public static function makeSelect(string $actionName, string $field, array $options): Action
    {
        return Action::make($actionName)
            ->fillForm(fn ($record): array => [
                $field => $record->{$field},
            ])
            ->schema([
                Select::make($field)
                    ->options($options)
                    ->required(),
            ])
            ->action(fn (array $data, $record) => $record->update($data));
    }

    public static function makeDateTime(string $actionName, string $field): Action
    {
        return Action::make($actionName)
            ->fillForm(fn ($record): array => [
                $field => $record->{$field},
            ])
            ->schema([
                DateTimePicker::make($field),
            ])
            ->action(fn (array $data, $record) => $record->update($data));
    }
}
