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

namespace Assist\Assistant\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Assist\Assistant\Models\AssistantChat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Assist\Assistant\Enums\AssistantChatShareVia;
use Assist\Assistant\Models\AssistantChatMessage;
use Assist\Assistant\Notifications\SendAssistantTranscriptNotification;

class ShareAssistantChatJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(
        protected AssistantChat $chat,
        protected AssistantChatShareVia $via,
        protected User $user,
        protected User $sender
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        switch ($this->via) {
            case AssistantChatShareVia::Email:
                $this->user->notify(new SendAssistantTranscriptNotification($this->chat, $this->sender));

                break;
            case AssistantChatShareVia::Internal:

                $replica = $this->chat
                    ->replicate(['id', 'user_id', 'assistant_chat_folder_id'])
                    ->user()
                    ->associate($this->user);

                $replica->save();

                $this->chat
                    ->messages()
                    ->each(
                        fn (AssistantChatMessage $message) => $message
                            ->replicate(['id', 'assistant_chat_id'])
                            ->chat()
                            ->associate($replica)
                            ->save()
                    );

                break;
        }
    }
}