<?php

namespace App\Orchid\Screens\Application\Scopes;

use App\Models\Security\OAuth\ScopeGroup;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class ScopeGroupEdit extends Screen
{

    public $group;

    public function query(ScopeGroup $group): iterable
    {
        return [
            'group' => $group
        ];
    }


    public function name(): ?string
    {
        return $this->group->exists ? 'Edit group' : 'New group';
    }


    public function commandBar(): iterable
    {
        return [
            Button::make($this->group->exists ? 'Save' : 'Create')
                ->icon($this->group->exists ? 'bs.pencil' : 'bs.plus')
                ->method('createOrUpdate')
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'auth.scope_group.write',
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('group.name')
                    ->title('Name')
                    ->placeholder('Enter the group name')
                    ->required(),
                Input::make('group.description')
                    ->title('Descriptions')
                    ->placeholder('Enter a descriptions')
                    ->required(),
            ])
        ];
    }

    /**
     * Create or update a scope group.
     *
     * @param Request $request The HTTP request object.
     *                       It contains the data needed to create or update the scope group.
     *                       The data should be passed in the 'group' parameter.
     *
     * @return RedirectResponse Redirects the user to the scope group list page.
     *
     * @throws \Exception
     */
    public function createOrUpdate(Request $request): RedirectResponse
    {
        $this->group->fill($request->get('group'))->save();

        Toast::success('You created a new scope group');

        return redirect()->route('platform.scope_group.list');
    }
}
