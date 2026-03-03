<?php

namespace App\Filament\Resources\UserTags;

use App\Filament\Resources\UserTags\Pages\CreateUserTag;
use App\Filament\Resources\UserTags\Pages\EditUserTag;
use App\Filament\Resources\UserTags\Pages\ListUserTags;
use App\Filament\Resources\UserTags\Schemas\UserTagForm;
use App\Filament\Resources\UserTags\Tables\UserTagsTable;
use App\Models\UserTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserTagResource extends Resource
{
    protected static ?string $model = UserTag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return UserTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserTagsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserTags::route('/'),
            'create' => CreateUserTag::route('/create'),
            'edit' => EditUserTag::route('/{record}/edit'),
        ];
    }
}
