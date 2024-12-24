<?php

namespace App\Filament\Resources;

use App\Enum\APITypeEnum;
use App\Enum\MethodEnum;
use App\Models\Api;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
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
                Forms\Components\Section::make('General')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\TextInput::make('url')
                            ->url()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Api Type')
                    ->schema([
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
                    ])->columns()->collapsible(),

                Forms\Components\Section::make('mTLS Authentication')
                    ->schema([
                        Forms\Components\Select::make('certificate_id')
                            ->relationship('certificate', 'name')
                            ->label('Certificates')
                            ->searchable()
                            ->nullable(),
                    ]),

                Forms\Components\Section::make('Headers')
                    ->schema([
                        Forms\Components\Repeater::make('headers')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->required(),
                            ])->label('Headers used to call the Api')
                            ->nullable()
                            ->columns(2)
                            ->default(null),
                    ]),
                Forms\Components\Textarea::make('request')
                    ->required()
                    ->label('Request to be sent to URL')
                    ->grow()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('expected_response')
                    ->nullable()
                    ->label('Expected Response (will be checked as substring)')
                    ->grow()
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
                Tables\Actions\Action::make('Execute test')
                    ->button()
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function (Api $api) {

                        Notification::make()
                            ->success()
                            ->title('Execution Scheduled')
                            ->body("Correctly scheduled")
                            ->send();
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ])->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)->button()->color('danger'),
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
