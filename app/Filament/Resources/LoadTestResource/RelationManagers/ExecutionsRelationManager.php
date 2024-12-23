<?php

namespace App\Filament\Resources\LoadTestResource\RelationManagers;

use App\Enum\ExecutionStatusEnum;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class ExecutionsRelationManager extends RelationManager
{
    protected static string $relationship = 'executions';

    protected static ?string $recordTitleAttribute = 'scheduled_at';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->minDate(now())
                    ->withoutSeconds()
                    ->required(),

                Forms\Components\DateTimePicker::make('executed_at')
                    ->visibleOn('view'),

                TextInput::make('number_requests_effective')
                    ->label('Effective number of requests done')
                    ->integer()->disabled()->visibleOn('view'),

                TextInput::make('success_number')
                    ->label('Number of success requests')
                    ->integer()->disabled()->visibleOn('view'),

                TextInput::make('failure_number')
                    ->label('Number of failed requests')
                    ->integer()->disabled()->visibleOn('view'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_at'),
                Tables\Columns\TextColumn::make('executed_at'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([

                        'warning' => ExecutionStatusEnum::EXECUTING->value,
                        'success' => ExecutionStatusEnum::FINISHED->value,
                        'error' => ExecutionStatusEnum::ERROR->value,
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Execution'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ]);
    }    
}
