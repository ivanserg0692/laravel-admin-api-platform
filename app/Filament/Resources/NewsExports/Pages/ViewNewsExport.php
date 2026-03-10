<?php

namespace App\Filament\Resources\NewsExports\Pages;

use App\Filament\Resources\NewsExports\NewsExportResource;
use Filament\Resources\Pages\ViewRecord;

class ViewNewsExport extends ViewRecord
{
    protected static string $resource = NewsExportResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
