<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use App\Models\Api;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Personal and CA Certificates';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->label('Mnemonic name'),
                Forms\Components\Section::make('Certificates')
                    ->description('Certificates in PEM format')
                    ->schema([
                        Forms\Components\Textarea::make('private_key')
                            ->grow()
                            ->required(),
                        Forms\Components\Textarea::make('public_cert')
                            ->label('Public Certificate')
                            ->hint('Public certificate must correspond to the private key')
                            ->hintColor('danger')
                            ->grow()
                            ->required(),
                        Forms\Components\Textarea::make('ca_certificate')
                            ->hint('Optional - must be used only if server has an unknown Signer')
                            ->grow(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('apis_count')
                    ->label('Times Used')
                    ->counts('apis'),
            ])
            ->filters([
                //
            ])
            ->pushActions([
                Tables\Actions\DeleteAction::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->label('Delete Certificate'),
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
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }
}
