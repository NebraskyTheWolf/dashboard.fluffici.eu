<?php

namespace App\Orchid\Screens\Application\Scopes;

use App\Events\UpdateAudit;
use App\Models\ScopeGroup;
use App\Models\Scopes;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class ScopeEdit extends Screen
{

    public $scope;

    public function query(Scopes $scope): iterable
    {
        return [
            'scope' => $scope
        ];
    }


    public function name(): ?string
    {
        return $this->scope->exists ? 'Edit scope': 'Create new scope';
    }

    public function permission(): ?iterable
    {
        return [
            'auth.scope.write',
        ];
    }


    public function commandBar(): iterable
    {
        return [
            Button::make($this->scope->exists ? 'Save' : 'Create')
                ->icon($this->scope->exists ? 'bs.pencil' : 'bs.plus')
                ->method('createOrUpdate'),
            Button::make('Delete')
                ->canSee($this->scope->exists)
                ->icon('bs.trash')
                ->method('delete')
        ];
    }


    public function layout(): iterable
    {
        return [
            Layout::rows([
                Relation::make('scope.groupId')
                    ->fromModel(ScopeGroup::class, 'name', 'id')
                    ->title('Group')
                    ->help('Select the scope group.')
                    ->required(),

                Relation::make('scope.parent')
                    ->fromModel(Scopes::class, 'name', 'id')
                    ->title('Parent')
                    ->help('Select the parent scope.'),

                Input::make('scope.name')
                    ->title("Name")
                    ->help('Enter a name for the scope ex: "delegated:all"')
                    ->required(),

                Input::make('scope.description')
                    ->title("Description")
                    ->help('Enter a description for the scope')
                    ->required()
            ])
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->scope->fill($request->get('scope'))->save();

        Toast::success("You update " . $this->scope->name . ' scope.');

        event(new UpdateAudit('update_scope', 'Updated ' . $this->scope->name .'.', Auth::user()->name));

        return redirect()->route('platform.scope.list');
    }

    public function delete()
    {
        $this->scope->delete();

        Toast::success("You deleted " . $this->scope->name . ' scope.');

        event(new UpdateAudit('delete_scope', 'Deleted ' . $this->scope->name .'.', Auth::user()->name));

        return redirect()->route('platform.scope.list');
    }
}
