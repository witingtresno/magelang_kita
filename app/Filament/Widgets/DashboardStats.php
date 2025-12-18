<?php

namespace App\Filament\Widgets;

use App\Models\AppUser;
use App\Models\Kuliner;
use App\Models\News;
use App\Models\Place;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('App Users', number_format(AppUser::count())),
            Stat::make('Destinations', number_format(Place::count())),
            Stat::make('Kuliner', number_format(Kuliner::count())),
            Stat::make('News Articles', number_format(News::count())),
        ];
    }
}
