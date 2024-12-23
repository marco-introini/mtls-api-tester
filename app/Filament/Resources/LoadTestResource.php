<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoadTestResource\Pages;
use App\Filament\Resources\LoadTestResource\RelationManagers\ExecutionsRelationManager;
use App\Models\LoadTest;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class LoadTestResource extends Resource
{
    protected static ?string $model = LoadTest::class;

    protected static ?string $slug = 'load-tests';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-chevron-double-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                Select::make('url_id')
                    ->required()
                    ->relationship('url','name'),

                TextInput::make('number_requests')
                    ->label('Number of requests')
                    ->required()
                    ->integer()
                    ->minValue(1)
                    ->default(1)
                    ->maxValue(100),

                TextInput::make('concurrent_requests')
                    ->label('Number of concurrent requests')
                    ->required()
                    ->integer()
                    ->minValue(1)
                    ->default(1)
                    ->maxValue(10),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->visibleOn('edit')
                    ->content(fn(?LoadTest $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Updated Date')
                    ->visibleOn('edit')
                    ->content(fn(?LoadTest $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url.name'),
                TextColumn::make('number_requests'),
                TextColumn::make('concurrent_requests'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoadTests::route('/'),
            'create' => Pages\CreateLoadTest::route('/create'),
            'edit' => Pages\EditLoadTest::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getRelations(): array
    {
        return [
            ExecutionsRelationManager::class,
        ];
    }
}
