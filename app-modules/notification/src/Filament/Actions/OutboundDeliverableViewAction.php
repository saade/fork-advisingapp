<?php

/*
<COPYRIGHT>

    Copyright © 2016-2024, Canyon GBS LLC. All rights reserved.

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

namespace AdvisingApp\Notification\Filament\Actions;

use App\Models\User;
use Filament\Actions\ViewAction;
use Illuminate\Support\HtmlString;
use App\Filament\Resources\UserResource;
use AdvisingApp\Prospect\Models\Prospect;
use Filament\Infolists\Components\TextEntry;
use AdvisingApp\StudentDataModel\Models\Student;
use AdvisingApp\Notification\Models\OutboundDeliverable;
use AdvisingApp\Prospect\Filament\Resources\ProspectResource;
use AdvisingApp\StudentDataModel\Filament\Resources\StudentResource;

class OutboundDeliverableViewAction extends ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->infolist([
            TextEntry::make('recipient')
                ->getStateUsing(fn (OutboundDeliverable $record): ?string => $record->recipient?->{$record->recipient::displayNameKey()})
                ->url(fn (OutboundDeliverable $record) => match ($record->recipient ? $record->recipient::class : null) {
                    Student::class => StudentResource::getUrl('view', ['record' => $record->recipient]),
                    Prospect::class => ProspectResource::getUrl('view', ['record' => $record->recipient]),
                    User::class => UserResource::getUrl('view', ['record' => $record->recipient]),
                    default => null,
                })
                ->color('primary'),
            TextEntry::make('channel'),
            TextEntry::make('delivery_status'),
            TextEntry::make('subject')
                ->getStateUsing(fn (OutboundDeliverable $record): ?string => $record->content['subject']),
            TextEntry::make('body')
                ->getStateUsing(function (OutboundDeliverable $record): ?string {
                    $body = str($record->content['greeting']);

                    foreach ($record->content['introLines'] as $line) {
                        $body = $body->append("<br><br>{$line}");
                    }

                    $body = $record->content['salutation']
                        ? $body->append("<br><br>{$record->content['salutation']}")
                        : $body->append('<br><br>Regards,<br>' . config('app.name'));

                    return new HtmlString($body->trim());
                })
                ->html(),
        ]);
    }
}
