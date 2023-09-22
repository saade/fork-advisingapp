<?php

use Assist\AssistDataModel\Models\Student;
use Assist\ServiceManagement\Models\ServiceRequest;
use Assist\ServiceManagement\Models\ServiceRequestType;

test('relationships work cross connections', function () {
    ServiceRequestType::withoutSyncingToSearch(function () {
        $student = Student::factory()
            ->has(
                ServiceRequest::factory()
                    ->count(3),
                'serviceRequests'
            )
            ->create();

        expect($student->serviceRequests)->toHaveCount(3);

        Student::factory()->create();

        expect(Student::all())->toHaveCount(2);

        $whereHas = Student::whereHas('serviceRequests', function ($query) {
            $query->whereNotNull('res_details');
        })->get();

        expect($whereHas)->toHaveCount(1);
    });
});