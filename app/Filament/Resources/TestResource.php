<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestResource\Pages;
use App\Models\Test;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationLabel = 'Test Results';

    protected static ?int $navigationSort = 4;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_date')
                    ->label('Request Timestamp')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('response_time')
                    ->label('Duration')
                    ->sortable(),
                Tables\Columns\IconColumn::make('response_ok')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('request_date', 'desc');
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
            'index' => Pages\ListTests::route('/'),
            'view' => Pages\ViewTest::route('/{record}'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
