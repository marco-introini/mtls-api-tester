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
                Forms\Components\FileUpload::make('private_key')
                    ->disk('certificates')
                    ->directory('private')
                    ->visibility('private')
                    ->preserveFilenames(),
                Forms\Components\FileUpload::make('public_key')
                    ->disk('certificates')
                    ->directory('public')
                    ->visibility('private')
                    ->preserveFilenames(),
                Forms\Components\FileUpload::make('ca_certificate')
                    ->disk('certificates')
                    ->directory('ca')
                    ->visibility('private')
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('urls')
                    ->label('Used in Urls')
                    ->getStateUsing(fn(Certificate $record) => Api::where('certificate_id', '=', $record->id)->count()),
            ])
            ->filters([
                //
            ])
            ->pushActions([
                Tables\Actions\LinkAction::make('delete')
                    ->action(fn (Certificate $record) => $record->delete())
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
