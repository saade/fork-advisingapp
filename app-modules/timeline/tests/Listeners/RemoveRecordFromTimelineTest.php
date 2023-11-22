<?php

/*
<COPYRIGHT>

Copyright © 2022-2023, Canyon GBS LLC

All rights reserved.

This file is part of a project developed using Laravel, which is an open-source framework for PHP.
Canyon GBS LLC acknowledges and respects the copyright of Laravel and other open-source
projects used in the development of this solution.

This project is licensed under the Affero General Public License (AGPL) 3.0.
For more details, see https://github.com/canyongbs/assistbycanyongbs/blob/main/LICENSE.

Notice:
- The copyright notice in this file and across all files and applications in this
 repository cannot be removed or altered without violating the terms of the AGPL 3.0 License.
- The software solution, including services, infrastructure, and code, is offered as a
 Software as a Service (SaaS) by Canyon GBS LLC.
- Use of this software implies agreement to the license terms and conditions as stated
 in the AGPL 3.0 License.

For more information or inquiries please visit our website at
https://www.canyongbs.com or contact us via email at legal@canyongbs.com.

</COPYRIGHT>
*/

use Assist\Timeline\Models\Timeline;
use Illuminate\Support\Facades\Cache;
use Assist\Engagement\Models\EngagementResponse;
use Assist\Timeline\Listeners\AddRecordToTimeline;
use Assist\Timeline\Events\TimelineableRecordCreated;
use Assist\Timeline\Events\TimelineableRecordDeleted;
use Assist\Timeline\Listeners\RemoveRecordFromTimeline;

it('busts the timeline cache for the associated educatable', function () {
    // Given we have a timelineable record, like an EngagementResponse
    $initialResponse = EngagementResponse::factory()->create();

    // And our educatable has a timeline synced cache key
    cache()->put(
        "timeline.synced.{$initialResponse->sender->getMorphClass()}.{$initialResponse->sender->getKey()}",
        now(),
        now()->addMinutes(60)
    );

    // When we create another timelineable record
    $subsequentResponse = $initialResponse->sender->engagementResponses()->createQuietly([
        'content' => 'This is a test response',
        'sent_at' => now(),
    ]);

    $event = new TimelineableRecordDeleted($subsequentResponse->sender, $subsequentResponse);
    $listener = app(RemoveRecordFromTimeline::class);

    $listener->handle($event);

    // The cache key for the educatable should be busted
    expect(Cache::has(
        "timeline.synced.{$initialResponse->sender->getMorphClass()}.{$initialResponse->sender->getKey()}"
    ))->toBeFalse();
});

it('should remove the specified record to the timeline', function () {
    expect(Timeline::count())->toBe(0);

    $response = EngagementResponse::factory()->createQuietly();

    $event = new TimelineableRecordCreated($response->sender, $response);
    $listener = app(AddRecordToTimeline::class);

    $listener->handle($event);

    expect(Timeline::count())->toBe(1);

    $event = new TimelineableRecordDeleted($response->sender, $response);
    $listener = app(RemoveRecordFromTimeline::class);

    $listener->handle($event);

    expect(Timeline::count())->toBe(0);
});