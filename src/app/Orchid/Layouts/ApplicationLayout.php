<?php

namespace App\Orchid\Layouts;

use App\Models\Application;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ApplicationLayout extends Table
{

    protected $target = 'applications';


    protected function columns(): iterable
    {
        return [
            TD::make('displayName', 'Service Name')
                ->render(function (Application $application) {
                    return Link::make($application->displayName)
                        ->icon('bs.pencil')
                        ->route('platform.application.edit', $application);
                }),
            TD::make('role', 'Role'),
            TD::make('created_at', 'Created At')
                ->render(function (Application $application) {
                    return Carbon::parse($application->created_at)->diffForHumans();
                }),
            TD::make('updated_at', 'Updated At')
                ->render(function (Application $application) {
                    return Carbon::parse($application->updated_at)->diffForHumans();
                }),
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.boxes';
    }

    protected function textNotFound(): string
    {
        return 'There is no application yet.';
    }

    protected function subNotFound(): string
    {
        return 'Create a application to allow the users to login on the different platform(s).';
    }
}
