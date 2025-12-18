<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncAntara')
                ->label('Sync Antara')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function () {
                    Artisan::call('app:sync-antara-news');

                    Notification::make()
                        ->success()
                        ->title('Sync selesai')
                        ->body('Data RSS Antara berhasil diperbarui.')
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
