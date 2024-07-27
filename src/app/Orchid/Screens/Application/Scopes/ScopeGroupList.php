<?php

namespace App\Orchid\Screens\Application\Scopes;

use App\Models\Security\OAuth\ScopeGroup;
use App\Orchid\Layouts\ScopeGroupLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ScopeGroupList extends Screen
{

    public function query(): iterable
    {
        return [
            'groups' => ScopeGroup::paginate()
        ];
    }


    public function name(): ?string
    {
        return 'Scope Groups';
    }

    public function permission(): ?iterable
    {
        return [
            'auth.scope_group.read',
        ];
    }


    public function commandBar(): iterable
    {
        return [
            Link::make('New group')
                ->icon('bs.plus')
                ->route('platform.scope_group.edit')
        ];
    }


    public function layout(): iterable
    {
        return [
            ScopeGroupLayout::class
        ];
    }
}
