<?php

namespace Assist\IntegrationGoogleAnalytics\Filament\Pages;

use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Assist\IntegrationGoogleAnalytics\Settings\GoogleAnalyticsSettings;

class ManageGoogleAnalyticsSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GoogleAnalyticsSettings::class;

    protected static ?string $navigationGroup = 'Integrations';

    protected static ?string $title = 'Google Analytics Settings';

    protected static ?string $navigationLabel = 'Google Analytics';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Toggle::make('is_enabled')
                    ->label('Enabled'),
                TextInput::make('id')
                    ->visible(fn (Get $get) => $get('is_enabled')),
                // TODO: add key value options?
            ]);
    }
}