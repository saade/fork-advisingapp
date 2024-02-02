<?php

/*
<COPYRIGHT>

    Copyright © 2022-2024, Canyon GBS LLC. All rights reserved.

    Advising App™ is licensed under the Elastic License 2.0. For more details,
    see https://github.com/canyongbs/advisingapp/blob/main/LICENSE.

    Notice:

    - You may not provide the software to third parties as a hosted or managed
      service, where the service provides users with access to any substantial set of
      the features or functionality of the software.
    - You may not move, change, disable, or circumvent the license key functionality
      in the software, and you may not remove or obscure any functionality in the
      software that is protected by the license key.
    - You may not alter, remove, or obscure any licensing, copyright, or other notices
      of the licensor in the software. Any use of the licensor’s trademarks is subject
      to applicable law.
    - Canyon GBS LLC respects the intellectual property rights of others and expects the
      same in return. Canyon GBS™ and Advising App™ are registered trademarks of
      Canyon GBS LLC, and we are committed to enforcing and protecting our trademarks
      vigorously.
    - The software solution, including services, infrastructure, and code, is offered as a
      Software as a Service (SaaS) by Canyon GBS LLC.
    - Use of this software implies agreement to the license terms and conditions as stated
      in the Elastic License 2.0.

    For more information or inquiries please visit our website at
    https://www.canyongbs.com or contact us via email at legal@canyongbs.com.

</COPYRIGHT>
*/

namespace AdvisingApp\Prospect\Filament\Resources\ProspectResource\Pages;

use Filament\Tables\Table;
use App\Filament\Columns\IdColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ManageRelatedRecords;
use AdvisingApp\MeetingCenter\Models\EventAttendee;
use AdvisingApp\MeetingCenter\Enums\EventAttendeeStatus;
use AdvisingApp\Prospect\Filament\Resources\ProspectResource;
use AdvisingApp\MeetingCenter\Filament\Resources\EventResource;
use AdvisingApp\MeetingCenter\Filament\Actions\InviteEventAttendeeAction;
use AdvisingApp\MeetingCenter\Filament\Actions\Table\ViewEventAttendeeAction;

class ManageProspectEvents extends ManageRelatedRecords
{
    protected static string $resource = ProspectResource::class;

    protected static string $relationship = 'eventAttendeeRecords';

    // TODO: Automatically set from Filament based on relationship name
    protected static ?string $navigationLabel = 'Events';

    // TODO: Automatically set from Filament based on relationship name
    protected static ?string $breadcrumb = 'Events';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                IdColumn::make(),
                TextColumn::make('event.title')
                    ->url(fn (EventAttendee $record) => EventResource::getUrl('view', ['record' => $record->event]))
                    ->color('primary'),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->actions([
                ViewEventAttendeeAction::make(),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [
                EventAttendeeStatus::Invited,
                EventAttendeeStatus::Attending,
            ]));
    }

    protected function getHeaderActions(): array
    {
        return [
            InviteEventAttendeeAction::make(),
        ];
    }
}
