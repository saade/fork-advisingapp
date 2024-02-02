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

namespace App\Filament\Fields;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;

class SecondsDurationInput extends Field
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-forms::components.fieldset';

    protected function setUp(): void
    {
        parent::setUp();

        $this->columns([
            'default' => 4,
        ]);

        $this->schema([
            TextInput::make('days')
                ->default(0)
                ->integer()
                ->minValue(0)
                ->dehydrated(false),
            TextInput::make('hours')
                ->default(0)
                ->integer()
                ->minValue(0)
                ->maxValue(24)
                ->dehydrated(false),
            TextInput::make('minutes')
                ->default(0)
                ->integer()
                ->minValue(0)
                ->maxValue(60)
                ->dehydrated(false),
            TextInput::make('seconds')
                ->default(0)
                ->integer()
                ->minValue(0)
                ->maxValue(60)
                ->dehydrated(false),
        ]);

        $this->formatStateUsing(function (int | array | null $state): array {
            if ($state === null) {
                return [
                    'days' => 0,
                    'hours' => 0,
                    'minutes' => 0,
                    'seconds' => 0,
                ];
            }

            if (is_array($state)) {
                return $state;
            }

            return [
                'days' => $days = floor($state / 86400),
                'hours' => $hours = floor(($state - ($days * 86400)) / 3600),
                'minutes' => $minutes = floor(($state - ($days * 86400) - ($hours * 3600)) / 60),
                'seconds' => floor(($state - ($days * 86400) - ($hours * 3600) - ($minutes * 60))),
            ];
        });

        $this->dehydrateStateUsing(function (array $state): ?int {
            $seconds = ($state['days'] * 86400) +
                ($state['hours'] * 3600) +
                ($state['minutes'] * 60) +
                $state['seconds'];

            if (! $seconds) {
                return null;
            }

            return $seconds;
        });
    }
}
