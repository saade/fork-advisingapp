{{--
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
--}}
@php
    use AdvisingApp\InAppCommunication\Enums\ConversationType;
    use AdvisingApp\InAppCommunication\Models\TwilioConversation;
    use AdvisingApp\InAppCommunication\Models\TwilioConversationUser;
    use Filament\Support\Facades\FilamentAsset;

    $conversationGroups = $this->conversations->reduce(
        function (array $carry, TwilioConversation $conversation): array {
            if ($conversation->type === ConversationType::Channel) {
                $carry[0][] = $conversation;
            } else {
                $carry[1][] = $conversation;
            }
            return $carry;
        },
        [[], []],
    );
@endphp

<x-filament-panels::page full-height="true">
    <div class="flex h-full flex-col">
        <div class="grid flex-1 grid-cols-1 gap-6 md:grid-cols-4">
            <div class="col-span-1">
                <div class="flex flex-col gap-y-6">
                    @foreach ($conversationGroups as $conversations)
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center justify-between">
                                <span class="flex-1 text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                                    {{ $loop->first ? 'Channels' : 'Direct messages' }}
                                </span>

                                @if (count($conversations))
                                    @if ($loop->first)
                                        <div class="flex items-center gap-1">
                                            {{ (clone $this->joinChannelsAction)->iconButton()->size('sm')->tooltip('Join channels')->icon('heroicon-m-list-bullet') }}
                                            {{ (clone $this->newChannelAction)->iconButton()->size('sm')->tooltip('Create a channel')->icon('heroicon-m-plus') }}
                                        </div>
                                    @else
                                        {{ (clone $this->newUserToUserChatAction)->iconButton()->size('sm')->tooltip('Start a chat')->icon('heroicon-m-plus') }}
                                    @endif
                                @endif
                            </div>

                            @if (count($conversations))
                                <ul
                                    class="flex flex-col gap-y-1 rounded-xl border border-gray-950/5 bg-white p-2 shadow-sm dark:border-white/10 dark:bg-gray-900">
                                    @foreach ($conversations as $conversationItem)
                                        @php
                                            /** @var TwilioConversation $conversationItem */
                                        @endphp
                                        <li @class([
                                            'px-2 group cursor-pointer flex rounded-lg w-full items-center outline-none transition duration-75 hover:bg-gray-100 focus:bg-gray-100 dark:hover:bg-white/5 dark:focus:bg-white/5 space-x-1',
                                            'bg-gray-100 dark:bg-white/5' =>
                                                $conversation?->getKey() === $conversationItem->getKey(),
                                        ])>
                                            <button
                                                type="button"
                                                @class([
                                                    'relative flex flex-1 items-center justify-between text-start gap-x-3 rounded-lg py-2 text-sm',
                                                ])
                                                wire:click="selectConversation('{{ $conversationItem->getKey() }}')"
                                            >
                                                <span @class([
                                                    'flex-1 truncate',
                                                    'text-gray-700 dark:text-gray-200' =>
                                                        $conversation?->getKey() !== $conversationItem->getKey(),
                                                    'text-primary-600 dark:text-primary-400' =>
                                                        $conversation?->getKey() === $conversationItem->getKey(),
                                                ])>
                                                    @if (filled($conversationItem->channel_name))
                                                        {{ $conversationItem->channel_name }}
                                                    @else
                                                        {{ $conversationItem->participants->where('id', '!=', auth()->id())->first()?->name }}
                                                    @endif
                                                </span>
                                                <x-filament::loading-indicator :attributes="(new \Illuminate\View\ComponentAttributeBag([
                                                    'wire:loading.delay.' .
                                                    config('filament.livewire_loading_delay', 'default') => '',
                                                    'wire:target' =>
                                                        'selectConversation(\'' . $conversationItem->getKey() . '\')',
                                                ]))->class(['w-5 h-5'])" />
                                            </button>
                                            @php
                                                /** @var TwilioConversationUser $participant */
                                                $participant = $conversationItem->participant;
                                            @endphp
                                            @if ($participant->is_pinned)
                                                {{ ($this->togglePinChannelAction)(['id' => $conversationItem->getKey()])->icon('heroicon-s-star')->tooltip('Unpin') }}
                                            @else
                                                {{ ($this->togglePinChannelAction)(['id' => $conversationItem->getKey()])->icon('heroicon-o-star')->tooltip('Pin') }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif ($loop->first)
                                <div class="text-sm">
                                    You do not belong to any channels yet.
                                    You can
                                    {{ (clone $this->joinChannelsAction)->link()->label('browse a list')->tooltip(null)->icon(null) }}
                                    or
                                    {{ (clone $this->newChannelAction)->link()->label('create a new one')->tooltip(null)->icon(null) }}
                                    .
                                </div>
                            @else
                                <div class="text-sm">
                                    You do not have any direct messages yet. You can
                                    {{ (clone $this->newUserToUserChatAction)->link()->label('start one')->tooltip(null)->icon(null) }}
                                    .
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            @php
                /** @var TwilioConversation $conversation */
            @endphp
            @if ($conversation)
                <div
                    class="col-span-1 flex h-full flex-col gap-2 overflow-hidden md:col-span-3"
                    x-data="userToUserChat({ selectedConversation: @js($conversation->getKey()), users: @js($users) })"
                    wire:key="conversation-{{ $conversation->getKey() }}"
                >
                    <div
                        class="flex flex-col items-center self-center"
                        x-show="loading"
                        x-transition.delay.800ms
                    >
                        <x-filament::loading-indicator class="h-12 w-12 text-primary-500" />
                        <p
                            class="text-center"
                            x-text="loadingMessage"
                        ></p>
                    </div>
                    <template x-if="!loading && error">
                        <div class="flex flex-col items-center self-center">
                            <x-filament::icon
                                class="h-12 w-12 text-primary-500"
                                icon="heroicon-m-exclamation-triangle"
                            />
                            <p class="text-center">Something went wrong...</p>
                            <p
                                class="text-center"
                                x-text="errorMessage"
                            ></p>
                            <x-filament::button
                                class="mt-2"
                                x-on:click="errorRetry"
                            >Retry
                            </x-filament::button>
                        </div>
                    </template>
                    <div
                        class="col-span-1 flex h-full flex-col gap-2 overflow-hidden md:col-span-3"
                        x-show="!loading && !error"
                        x-transition.delay.850ms
                    >
                        <div
                            class="flex max-h-[calc(100vh-24rem)] flex-1 flex-col-reverse overflow-y-scroll rounded-xl border border-gray-950/5 text-sm shadow-sm dark:border-white/10 dark:bg-gray-800">
                            <div class="divide-y dark:divide-gray-700">
                                <template
                                    x-for="message in messages"
                                    :key="message.message.index"
                                >
                                    <div class="group w-full dark:bg-gray-800">
                                        <div class="m-auto justify-center px-6 py-3 text-base">
                                            <div
                                                class="mx-auto flex flex-1 gap-6 text-base md:max-w-2xl lg:max-w-[38rem] xl:max-w-3xl">
                                                <div class="relative mt-1 flex flex-shrink-0 flex-col items-end">
                                                    <x-filament::avatar
                                                        class="rounded-full"
                                                        alt="User Avatar"
                                                        x-bind:src="message.avatar"
                                                        size="lg"
                                                    />
                                                </div>
                                                <div
                                                    class="relative flex w-[calc(100%-50px)] flex-col lg:w-[calc(100%-115px)]">
                                                    <div class="flex max-w-full flex-grow flex-col gap-y-1">
                                                        <div class="flex items-center gap-2 text-sm">
                                                            <span
                                                                class="font-medium text-gray-700 dark:text-gray-300"
                                                                x-text="message.author"
                                                            ></span>
                                                            <span
                                                                class="text-gray-500 dark:text-gray-500"
                                                                x-text="formatDate(message.date)"
                                                            ></span>
                                                        </div>

                                                        <div
                                                            class="flex min-h-[20px] flex-col items-start gap-3 overflow-x-auto break-words">
                                                            <div
                                                                x-html="generateHTML(JSON.parse(message.message.body))">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <template x-if="messagePaginator?.hasPrevPage">
                                <div
                                    class="mb-auto flex cursor-pointer justify-center bg-white p-3 text-center text-primary-500 dark:bg-gray-700"
                                    x-on:click="loadPreviousMessages"
                                >
                                    <p>Load previous messages...</p>
                                    <x-filament::loading-indicator
                                        class="ml-2 h-4 w-4 text-primary-500"
                                        x-show="loadingPreviousMessages"
                                    />
                                </div>
                            </template>
                        </div>

                        <form @submit.prevent="submit">
                            <div
                                class="w-full overflow-hidden rounded-xl border border-gray-950/5 bg-gray-50 shadow-sm dark:border-white/10 dark:bg-gray-700">
                                <div class="bg-white dark:bg-gray-800">
                                    <div
                                        x-data="chatEditor({ currentUser: @js(auth()->id()), users: @js($users) })"
                                        x-model="message"
                                        wire:ignore
                                        x-modelable="content"
                                    >
                                        <template x-if="isLoaded()">
                                            <div class="flex flex-wrap gap-1 border-b px-3 py-2 dark:border-gray-700">
                                                <button
                                                    class="rounded p-0.5"
                                                    type="button"
                                                    x-on:click="toggleBold()"
                                                    x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('bold', updatedAt) }"
                                                >
                                                    <svg
                                                        class="h-5 w-5"
                                                        fill="currentColor"
                                                        viewBox="0 0 1024 1024"
                                                        version="1.1"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M576 661.333H426.667v-128H576c35.413 0 64 28.587 64 64 0 35.414-28.587 64-64 64m-149.333-384h128c35.413 0 64 28.587 64 64 0 35.414-28.587 64-64 64h-128m238.933 55.04C706.987 431.36 736 384 736 341.333c0-96.426-74.667-170.666-170.667-170.666H298.667V768H599.04c89.6 0 158.293-72.533 158.293-161.707 0-64.853-36.693-120.32-91.733-145.92z"
                                                        />
                                                    </svg>
                                                </button>

                                                <button
                                                    class="rounded p-0.5"
                                                    type="button"
                                                    x-on:click="toggleItalic()"
                                                    x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('italic', updatedAt) }"
                                                >
                                                    <svg
                                                        class="h-5 w-5"
                                                        fill="currentColor"
                                                        viewBox="0 0 1024 1024"
                                                        version="1.1"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M426.667 170.667v128h94.293L375.04 640H256v128h341.333V640H503.04l145.92-341.333H768v-128H426.667z"
                                                        />
                                                    </svg>
                                                </button>

                                                <button
                                                    class="rounded p-0.5"
                                                    type="button"
                                                    x-on:click="toggleUnderline()"
                                                    x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('underline', updatedAt) }"
                                                >
                                                    <svg
                                                        class="h-5 w-5"
                                                        fill="currentColor"
                                                        viewBox="0 0 1024 1024"
                                                        version="1.1"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M232 872h560v-80H232v80m280-160c132.4 0 240-107.6 240-240V152H652v320c0 77.2-62.8 140-140 140s-140-62.8-140-140V152H272v320c0 132.4 107.6 240 240 240z"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>

                                        <div class="w-full px-4 py-2 text-gray-900 dark:text-white">
                                            <div x-ref="element"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between border-t px-3 py-2 dark:border-gray-600">
                                    <div class="flex items-center gap-3">
                                        <x-filament::button type="submit">
                                            Send
                                        </x-filament::button>

                                        <div
                                            class="relative flex h-6 items-center justify-center gap-0.5"
                                            x-show="usersTyping.length"
                                        >
                                            <template x-for="user in usersTyping">
                                                <x-filament::avatar
                                                    alt="User Avatar"
                                                    size="w-4 h-4"
                                                    x-bind:src="user.avatar"
                                                />
                                            </template>
                                            <span
                                                class="h-2 w-2 animate-bounce rounded-full bg-primary-500 text-primary-500 animation-delay-100"
                                            ></span>
                                            <span
                                                class="h-2 w-2 animate-bounce rounded-full bg-primary-500 text-primary-500 animation-delay-200"
                                            ></span>
                                            <span
                                                class="h-2 w-2 animate-bounce rounded-full bg-primary-500 text-primary-500 animation-delay-300"
                                            ></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @if ($conversation->type === ConversationType::Channel)
                            @php
                                $isManager = (bool) $conversation->managers()->find(auth()->user());
                            @endphp
                            <div class="{{ $isManager ? 'justify-between' : 'justify-end' }} flex items-center">
                                @if ($isManager)
                                    <div class="flex gap-3">
                                        {{ $this->editChannelAction }}

                                        {{ $this->deleteChannelAction }}
                                    </div>
                                @endif

                                <div class="flex gap-3">
                                    {{ $this->addUserToChannelAction }}

                                    {{ $this->leaveChannelAction }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="col-span-1 flex h-full flex-col gap-2 overflow-hidden md:col-span-3">
                    <p class="text-center text-xl">Select or create a new Chat</p>
                </div>
            @endif
        </div>
        <script src="{{ FilamentAsset::getScriptSrc('userToUserChat', 'canyon-gbs/in-app-communication') }}"></script>
        <style>
            .tiptap .is-editor-empty:first-child::before {
                color: #adb5bd;
                content: attr(data-placeholder);
                float: left;
                height: 0;
                pointer-events: none;
            }

            .ProseMirror-focused {
                outline-color: transparent;
            }
        </style>
    </div>
</x-filament-panels::page>
