<?php

namespace App\Orchid\Layouts;

use App\Models\Scopes;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ScopeListLayout extends Table
{

    protected $target = 'scopes';


    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Group name')
                ->render(function (Scopes $scope) {
                    return Link::make($scope->name)
                        ->icon('bs.pencil')
                        ->route('platform.scope.edit',  $scope);
                }),
            TD::make('description')
        ];
    }
}
