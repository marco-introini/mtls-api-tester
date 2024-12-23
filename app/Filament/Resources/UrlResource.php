<?php

namespace App\Filament\Resources;

use App\Enum\MethodEnum;
use App\Enum\ServiceTypeEnum;
use App\Filament\Resources\UrlResource\Pages;
use App\Models\Certificate;
use App\Models\Url;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class UrlResource extends Resource
{
    protected static ?string $model = Url::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Site Urls';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->required()
                    ->columnSpan(2),
                Forms\Components\Select::make('service_type')
                    ->label('Service Type')
                    ->options(ServiceTypeEnum::class)
                    ->default(ServiceTypeEnum::SOAP)
                    ->required(),
                Forms\Components\Select::make('method')
                    ->label('Method')
                    ->options(MethodEnum::class)
                    ->default(MethodEnum::POST)
                    ->required(),
                Forms\Components\Select::make('certificate_id')
                    ->relationship('certificate', 'name')
                    ->label('Certificates')
                    ->searchable()
                    ->nullable(),
                Forms\Components\Section::make('Headers')
                    ->schema([
                        Forms\Components\TextInput::make('soap_action'),
                        Forms\Components\Repeater::make('headers')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->required(),
                            ])->label('Header used to call the URL')
                            ->nullable()
                            ->default(null),
                    ]),
                Forms\Components\Textarea::make('request')
                    ->required()
                    ->label('Request to be sent to URL')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('expected_response')
                    ->nullable()
                    ->label('Expected Response (will be checked as substring)')
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('service_type'),
                Tables\Columns\TextColumn::make('certificate.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
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
            'index' => Pages\ListUrls::route('/'),
            'create' => Pages\CreateUrl::route('/create'),
            'edit' => Pages\EditUrl::route('/{record}/edit'),
        ];
    }
}
