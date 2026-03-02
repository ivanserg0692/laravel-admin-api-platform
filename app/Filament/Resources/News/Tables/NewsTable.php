<?php

namespace App\Filament\Resources\News\Tables;

use App\Filament\Resources\News\Schemas\NewsForm;
use App\Filament\Tables\Actions\InlineFieldEditActionFactory;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->action(InlineFieldEditActionFactory::makeInput('editTitle', 'title')),
                TextInputColumn::make('slug')
                    ->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->selectablePlaceholder(false),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->action(InlineFieldEditActionFactory::makeDateTime('editPublishedAt', 'published_at')),
                TextColumn::make('author_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('cover_image')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('views_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                Filter::make('published_range')
                    ->label('Published date')
                    ->form([
                        DatePicker::make('published_from')->label('From'),
                        DatePicker::make('published_until')->label('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['published_from'] ?? null, fn(Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date))
                            ->when($data['published_until'] ?? null, fn(Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date));
                    }),
                Filter::make('published_today')
                    ->query(fn($query) => $query->whereDate('published_at', now()->toDateString()))
            ])
            ->recordActions([
                Action::make('editRow')
                    ->label('Редактировать')
                    ->icon('heroicon-o-pencil-square')
                    ->schema(fn(Schema $schema): Schema => NewsForm::configure($schema))
                    ->fillForm(fn($record) => $record->attributesToArray())
                    ->action(fn(array $data, $record) => $record->update($data)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
