<?php

namespace App\Orchid\Screens\Application;

use App\Models\Application;
use App\Orchid\Layouts\ApplicationLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ApplicationScreen extends Screen
{

    public function query(): iterable
    {
        return [
            'applications' => Application::paginate()
        ];
    }


    public function name(): ?string
    {
        return 'ApplicationScreen';
    }


    public function commandBar(): iterable
    {
        return [
            Link::make('New app')
                ->icon('bs.plus')
                ->route('platform.application.edit')
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'auth.application.read',
        ];
    }

    public function layout(): iterable
    {
        return [
            ApplicationLayout::class
        ];
    }
}
