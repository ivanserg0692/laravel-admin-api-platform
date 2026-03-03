<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\UserTag;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Tag name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set): void {
                                $set('slug', Str::slug((string)$state));
                            })
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Tag slug')
                            ->required()
                            ->alphaDash()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (TextInput $component): void {
                                $component->getContainer()
                                    ->getLivewire()
                                    ->validateOnly($component->getStatePath());
                            })
                            ->rule(Rule::unique('user_tags', 'slug'))
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $name = trim((string)($data['name'] ?? ''));
                        $slugInput = trim((string)($data['slug'] ?? ''));
                        $slug = Str::slug($slugInput !== '' ? $slugInput : $name);

                        if ($slug === '') {
                            $slug = Str::uuid()->toString();
                        }

                        $tag = UserTag::query()->firstOrCreate(
                            ['slug' => $slug],
                            ['name' => $name]
                        );

                        return (int)$tag->getKey();
                    }),
                Toggle::make('is_blocked')
                    ->label('Blocked'),
                TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }
}
