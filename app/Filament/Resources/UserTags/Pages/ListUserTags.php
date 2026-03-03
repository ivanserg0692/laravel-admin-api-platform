<?php

namespace App\Filament\Resources\UserTags\Pages;

use App\Filament\Resources\UserTags\UserTagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserTags extends ListRecords
{
    protected static string $resource = UserTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
