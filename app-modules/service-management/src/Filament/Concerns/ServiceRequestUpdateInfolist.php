<?php

namespace Assist\ServiceManagement\Filament\Concerns;

use Illuminate\Support\Str;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Assist\ServiceManagement\Models\ServiceRequestUpdate;
use Assist\ServiceManagement\Enums\ServiceRequestUpdateDirection;
use Assist\ServiceManagement\Filament\Resources\ServiceRequestResource;

// TODO Re-use this trait across other places where infolist is rendered
trait ServiceRequestUpdateInfolist
{
    public function serviceRequestUpdateInfolist(): array
    {
        return [
            TextEntry::make('serviceRequest.service_request_number')
                ->label('Service Request')
                ->translateLabel()
                ->url(fn (ServiceRequestUpdate $serviceRequestUpdate): string => ServiceRequestResource::getUrl('view', ['record' => $serviceRequestUpdate->serviceRequest]))
                ->color('primary'),
            IconEntry::make('internal')
                ->boolean(),
            TextEntry::make('direction')
                ->icon(fn (ServiceRequestUpdateDirection $state): string => match ($state) {
                    ServiceRequestUpdateDirection::Inbound => 'heroicon-o-arrow-down-tray',
                    ServiceRequestUpdateDirection::Outbound => 'heroicon-o-arrow-up-tray',
                })
                ->formatStateUsing(fn (ServiceRequestUpdateDirection $state): string => Str::ucfirst($state->value)),
            TextEntry::make('update')
                ->columnSpanFull(),
        ];
    }
}
