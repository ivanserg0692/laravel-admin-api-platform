<?php

namespace App\Filament\Resources\UserTags\Pages;

use App\Filament\Resources\UserTags\UserTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserTag extends CreateRecord
{
    protected static string $resource = UserTagResource::class;
}
