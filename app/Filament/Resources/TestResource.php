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
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationLabel = 'Test Results';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('called_url')
                    ->columnSpan(2),
                TextInput::make('created_at'),
                TextInput::make('updated_at'),
                Section::make('Status')
                    ->schema([
                        Toggle::make('response_ok')
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),
                        TextInput::make('response_time')
                            ->postfix('milliseconds'),
                    ])->columns(2),
                Section::make('Request')
                    ->schema([
                        Textarea::make('request'),
                        TextInput::make('request_date'),
                        Textarea::make('request_headers'),
                        TextInput::make('request_certificates'),
                    ])
                    ->collapsed(),
                Section::make('Response')
                    ->schema([
                        Textarea::make('response'),
                        TextInput::make('response_date'),
                        Textarea::make('server_certificates'),
                    ])
                    ->collapsed(),
                Section::make('Curl Debug')
                    ->schema([
                        KeyValue::make('curl_info'),
                    ])
                    ->collapsed(),
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
                Tables\Columns\BadgeColumn::make('response_ok')
                    ->colors([
                        'danger' => 0,
                        'success' => 1,
                    ])
                    ->enum([
                        false => 'Failed',
                        true => 'Success',
                    ])
                    ->label('Success'),
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
            'create' => Pages\CreateTest::route('/create'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
            'view' => Pages\ViewTest::route('/{record}'),
        ];
    }
}
