<?php

namespace App\Orchid\Screens\Application\Scopes;

use App\Models\Security\OAuth\Scopes;
use App\Orchid\Layouts\ScopeListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ScopeList extends Screen
{

    public function query(): iterable
    {
        return [
            'scopes' => Scopes::paginate()
        ];
    }


    public function name(): ?string
    {
        return 'ScopeList';
    }

    public function permission(): ?iterable
    {
        return [
            'auth.scope.read',
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('New scope')
                ->icon('bs.plus')
                ->route('platform.scope.edit')
        ];
    }

    public function layout(): iterable
    {
        return [
            ScopeListLayout::class
        ];
    }
}
