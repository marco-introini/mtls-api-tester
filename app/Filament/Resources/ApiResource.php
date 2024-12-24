<?php

namespace App\Filament\Resources;

use App\Enum\APITypeEnum;
use App\Enum\MethodEnum;
use App\Models\Api;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApiResource extends Resource
{
    protected static ?string $model = Api::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Site Apis';

    protected static ?int $navigationSort = 2;

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
                    ->options(APITypeEnum::class)
                    ->default(APITypeEnum::SOAP)
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->sortable()
                    ->label('Type')
                    ->badge(),
                Tables\Columns\TextColumn::make('method')
                    ->sortable()
                    ->label('Method')
                    ->badge(),
                Tables\Columns\TextColumn::make('certificate.name'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_type')
                    ->options(APITypeEnum::class),
                Tables\Filters\SelectFilter::make('method')
                    ->options(MethodEnum::class),
                Tables\Filters\SelectFilter::make('certificate')
                    ->relationship('certificate', 'name'),
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
            'index' => ApiResource\Pages\ListApis::route('/'),
            'create' => ApiResource\Pages\CreateApi::route('/create'),
            'edit' => ApiResource\Pages\EditApi::route('/{record}/edit'),
        ];
    }
}
