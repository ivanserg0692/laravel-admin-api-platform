<?php

namespace App\Filament\Resources\UserTags\Pages;

use App\Filament\Resources\UserTags\UserTagResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserTag extends EditRecord
{
    protected static string $resource = UserTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
