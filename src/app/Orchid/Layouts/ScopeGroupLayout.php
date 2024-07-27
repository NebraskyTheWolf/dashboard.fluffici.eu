<?php

namespace App\Orchid\Layouts;

use App\Models\Security\OAuth\ScopeGroup;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ScopeGroupLayout extends Table
{

    protected $target = 'groups';


    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Group name')
                ->render(function (ScopeGroup $group) {
                    return Link::make($group->name)
                        ->icon('bs.pencil')
                        ->route('platform.scope_group.edit',  $group);
                }),
            TD::make('description')
        ];
    }
}
