<?php

namespace Assist\Task\Filament\Resources;

use Assist\Task\Models\Task;
use Filament\Resources\Resource;
use Assist\Task\Filament\Resources\TaskResource\Pages;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationLabel = 'Task';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Tools';

    protected static ?int $navigationSort = 3;

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}