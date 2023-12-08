<?php

/*
<COPYRIGHT>

    Copyright © 2022-2023, Canyon GBS LLC. All rights reserved.

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

use App\Models\User;

use function Tests\asSuperAdmin;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;
use function Pest\Laravel\assertDatabaseHas;

use AdvisingApp\ServiceManagement\Models\ServiceRequest;
use AdvisingApp\ServiceManagement\Filament\Resources\ServiceRequestResource;
use AdvisingApp\ServiceManagement\Tests\RequestFactories\EditServiceRequestRequestFactory;

test('A successful action on the EditServiceRequest page', function () {
    $serviceRequest = ServiceRequest::factory()->create();

    asSuperAdmin()
        ->get(
            ServiceRequestResource::getUrl('edit', [
                'record' => $serviceRequest->getRouteKey(),
            ])
        )
        ->assertSuccessful();

    $request = collect(EditServiceRequestRequestFactory::new()->create());

    livewire(ServiceRequestResource\Pages\EditServiceRequest::class, [
        'record' => $serviceRequest->getRouteKey(),
    ])
        ->fillForm($request->toArray())
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(
        ServiceRequest::class,
        $request->except(
            [
                'division_id',
                'status_id',
                'priority_id',
                'type_id',
            ]
        )->toArray()
    );

    $serviceRequest->refresh();

    expect($serviceRequest->division->id)
        ->toEqual($request->get('division_id'))
        ->and($serviceRequest->status->id)
        ->toEqual($request->get('status_id'))
        ->and($serviceRequest->priority->id)
        ->toEqual($request->get('priority_id'))
        ->and($serviceRequest->type->id)
        ->toEqual($request->get('type_id'));
});

test('EditServiceRequest requires valid data', function ($data, $errors) {
    $serviceRequest = ServiceRequest::factory()->create();

    asSuperAdmin();

    $request = collect(EditServiceRequestRequestFactory::new($data)->create());

    livewire(ServiceRequestResource\Pages\EditServiceRequest::class, [
        'record' => $serviceRequest->getRouteKey(),
    ])
        ->fillForm($request->toArray())
        ->call('save')
        ->assertHasFormErrors($errors);

    assertDatabaseHas(ServiceRequest::class, $serviceRequest->toArray());

    expect($serviceRequest->fresh()->division->id)
        ->toEqual($serviceRequest->division->id)
        ->and($serviceRequest->fresh()->status->id)
        ->toEqual($serviceRequest->status->id)
        ->and($serviceRequest->fresh()->priority->id)
        ->toEqual($serviceRequest->priority->id)
        ->and($serviceRequest->fresh()->type->id)
        ->toEqual($serviceRequest->type->id);
})->with(
    [
        'division_id missing' => [EditServiceRequestRequestFactory::new()->state(['division_id' => null]), ['division_id' => 'required']],
        'division_id does not exist' => [
            EditServiceRequestRequestFactory::new()->state(['division_id' => fake()->uuid()]),
            ['division_id' => 'exists'],
        ],
        'status_id missing' => [EditServiceRequestRequestFactory::new()->state(['status_id' => null]), ['status_id' => 'required']],
        'status_id does not exist' => [
            EditServiceRequestRequestFactory::new()->state(['status_id' => fake()->uuid()]),
            ['status_id' => 'exists'],
        ],
        'priority_id missing' => [EditServiceRequestRequestFactory::new()->state(['priority_id' => null]), ['priority_id' => 'required']],
        'priority_id does not exist' => [
            EditServiceRequestRequestFactory::new()->state(['priority_id' => fake()->uuid()]),
            ['priority_id' => 'exists'],
        ],
        'type_id missing' => [EditServiceRequestRequestFactory::new()->state(['type_id' => null]), ['type_id' => 'required']],
        'type_id does not exist' => [
            EditServiceRequestRequestFactory::new()->state(['type_id' => fake()->uuid()]),
            ['type_id' => 'exists'],
        ],
        'close_details is not a string' => [EditServiceRequestRequestFactory::new()->state(['close_details' => 1]), ['close_details' => 'string']],
        'res_details is not a string' => [EditServiceRequestRequestFactory::new()->state(['res_details' => 1]), ['res_details' => 'string']],
    ]
);

// Permission Tests

test('EditServiceRequest is gated with proper access control', function () {
    $user = User::factory()->create();

    $serviceRequest = ServiceRequest::factory()->create();

    actingAs($user)
        ->get(
            ServiceRequestResource::getUrl('edit', [
                'record' => $serviceRequest,
            ])
        )->assertForbidden();

    livewire(ServiceRequestResource\Pages\EditServiceRequest::class, [
        'record' => $serviceRequest->getRouteKey(),
    ])
        ->assertForbidden();

    $user->givePermissionTo('service_request.view-any');
    $user->givePermissionTo('service_request.*.update');

    actingAs($user)
        ->get(
            ServiceRequestResource::getUrl('edit', [
                'record' => $serviceRequest,
            ])
        )->assertSuccessful();

    $request = collect(EditServiceRequestRequestFactory::new()->create());

    livewire(ServiceRequestResource\Pages\EditServiceRequest::class, [
        'record' => $serviceRequest->getRouteKey(),
    ])
        ->fillForm($request->toArray())
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(
        ServiceRequest::class,
        $request->except(
            [
                'division_id',
                'status',
                'priority',
                'type',
            ]
        )->toArray()
    );

    $serviceRequest->refresh();

    expect($serviceRequest->division->id)
        ->toEqual($request->get('division_id'))
        ->and($serviceRequest->status->id)
        ->toEqual($request->get('status_id'))
        ->and($serviceRequest->priority->id)
        ->toEqual($request->get('priority_id'))
        ->and($serviceRequest->type->id)
        ->toEqual($request->get('type_id'));
});
