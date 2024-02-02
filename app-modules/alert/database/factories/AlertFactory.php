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

namespace AdvisingApp\Alert\Database\Factories;

use AdvisingApp\Alert\Models\Alert;
use AdvisingApp\Alert\Enums\AlertStatus;
use AdvisingApp\Prospect\Models\Prospect;
use AdvisingApp\Alert\Enums\AlertSeverity;
use AdvisingApp\StudentDataModel\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @extends Factory<Alert>
 */
class AlertFactory extends Factory
{
    public function definition(): array
    {
        return [
            'concern_type' => fake()->randomElement([(new Student())->getMorphClass(), (new Prospect())->getMorphClass()]),
            'concern_id' => function (array $attributes) {
                $concernClass = Relation::getMorphedModel($attributes['concern_type']);

                /** @var Student|Prospect $concernModel */
                $concernModel = new $concernClass();

                $concern = $concernClass === Student::class
                    ? Student::inRandomOrder()->first() ?? Student::factory()->create()
                    : $concernModel::factory()->create();

                return $concern->getKey();
            },
            'description' => fake()->sentence(),
            'severity' => fake()->randomElement(AlertSeverity::cases()),
            'status' => fake()->randomElement(AlertStatus::cases()),
            'suggested_intervention' => fake()->sentence(),
        ];
    }
}
