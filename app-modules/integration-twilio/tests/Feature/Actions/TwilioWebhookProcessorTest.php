<?php

use Illuminate\Support\Facades\Queue;
use Assist\IntegrationTwilio\Actions\StatusCallback;
use Assist\IntegrationTwilio\Actions\MessageReceived;

it('will dispatch an appropriate job to process the incoming request', function () {
    $this->withoutMiddleware();

    Queue::fake();

    $this->post(
        route('inbound.webhook.twilio', 'message_received'),
        $this->loadFixtureFromModule('integration-twilio', 'MessageReceived/payload'),
    );

    Queue::assertPushed(MessageReceived::class);
    Queue::assertNotPushed(StatusCallback::class);
});