<?php

namespace App\Filament\Resources\NewsExports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NewsExportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('export_file')
                    ->required(),
                Select::make('job_batch_id')
                    ->relationship('jobBatch', 'name')
                    ->required(),
            ]);
    }
}
