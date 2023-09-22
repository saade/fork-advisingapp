<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Filament\Widgets\TableWidget as BaseWidget;
use Assist\ServiceManagement\Models\ServiceRequest;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource;

class MyServiceRequests extends BaseWidget
{
    protected int | string | array $columnSpan = [
        'sm' => 1,
        'md' => 1,
        'lg' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('My Service Requests')
            ->query(
                auth()->user()
                    ->serviceRequests()
                    ->getQuery()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('service_request_number')
                    ->label('Service Request #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('respondent.display_name')
                    ->label('Respondent')
                    ->getStateUsing(fn (ServiceRequest $record) => $record->respondent->{$record->respondent::displayNameKey()})
                    ->searchable(query: fn (Builder $query, $search) => $query->educatableSearch(relationship: 'respondent', search: $search))
                    // TODO: Find a way to get IDE to recognize educatableSort() method
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->educatableSort($direction)),
                TextColumn::make('respondent.sisid')
                    ->label('SIS ID')
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        // Update this if any other relations are added to the ServiceRequest model respondent relationship
                        return $query->join('students', function (JoinClause $join) {
                            $join->on('service_requests.respondent_id', '=', 'students.sisid')
                                ->where('service_requests.respondent_type', '=', 'student');
                        })->orderBy('sisid', $direction);
                    }),
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->relationship('priority', 'name')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('status')
                    ->relationship('status', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn (ServiceRequest $record): string => ServiceRequestResource::getUrl(name: 'view', parameters: ['record' => $record])),
            ])
            ->recordUrl(
                fn (ServiceRequest $record): string => ServiceRequestResource::getUrl(name: 'view', parameters: ['record' => $record]),
            )
            ->paginated([5]);
    }
}