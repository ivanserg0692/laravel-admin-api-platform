<?php

namespace App\Filament\Resources\NewsExports;

use App\Filament\Resources\NewsExports\Pages\ListNewsExports;
use App\Filament\Resources\NewsExports\Pages\ViewNewsExport;
use App\Filament\Resources\NewsExports\Schemas\NewsExportInfolist;
use App\Filament\Resources\NewsExports\Tables\NewsExportsTable;
use App\Models\NewsExport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsExportResource extends Resource
{
    protected static ?string $model = NewsExport::class;

    protected static ?int $navigationSort = 20;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('filament.navigation.news_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.news_exports.navigation_label');
    }

    public static function infolist(Schema $schema): Schema
    {
        return NewsExportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsExportsTable::configure($table);
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
            'index' => ListNewsExports::route('/'),
            'view' => ViewNewsExport::route('/{record}'),
        ];
    }
}
