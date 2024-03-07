<?php

namespace App\Orchid\Screens\Application;

use App\Events\UpdateAudit;
use App\Models\Application;
use App\Models\Scopes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class ApplicationEditScreen extends Screen
{

    public $application;

    public function query(Application $application): iterable
    {
        return [
            'application' => $application
        ];
    }


    public function name(): ?string
    {
        return $this->application->exists ? 'Edit application' : 'New application';
    }


    public function commandBar(): iterable
    {
        return [
            Button::make($this->application->exists ? 'Save' : 'Create')
                ->icon($this->application->exists ? 'bs.pencil' : 'bs.plus')
                ->type($this->application->exists ? Color::PRIMARY : Color::SUCCESS)
                ->method('createOrUpdate')
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'auth.application.write',
        ];
    }


    /**
     * Get the layout for the application credentials and permissions section.
     *
     * @return iterable The layout configuration for the credentials and permissions section.
     */
    public function layout(): iterable
    {
        if (!$this->application->exists)
            $this->application->clientId = Uuid::uuid4()->toString();

        return [
            \Orchid\Support\Facades\Layout::rows([
                Group::make([
                    Input::make('application.clientId')
                        ->title('Application identifier')
                        ->disabled(),
                    Input::make('application.displayName')
                        ->title('Application name')
                        ->placeholder('Please enter a name.')
                        ->required(),
                    Input::make('application.redirectUri')
                        ->title('Redirect URL')
                        ->help('https://')
                        ->type('url')
                        ->required(),

                ])->alignStart(),
                Group::make([
                    Password::make('application.secret')
                        ->title('Secret')
                        ->help('Please enter the application secret. ( Optional )'),

                    Select::make('application.scope')
                        ->title('Scopes')
                        ->multiple()
                        ->allowAdd()
                        ->options($this->fetchOption())
                        ->taggable()
                        ->help('Select the scopes for this application.')
                        ->fromModel(Scopes::class, 'name', 'name'),

                    Select::make('application.role')
                        ->title('Role')
                        ->help('Select a role to determine if this app is internal or external.')
                        ->options([
                            'INTERNAL' => 'Internal',
                            'EXTERNAL' => 'External'
                        ])
                ])->alignCenter()
            ])->title('Credentials & Permissions')
        ];
    }

    /**
     * Create or update an application.
     *
     * @param \Illuminate\Http\Request $request The request object containing application details.
     *
     * @return RedirectResponse A redirect response to the application list page.
     */
    public function createOrUpdate(Request $request): RedirectResponse
    {
        $this->application->fill($request->get('application'));
        $this->application->scope = implode(',', $this->application->scope);
        $this->application->save();

        event(new UpdateAudit('new_application', 'Updated ' . $this->application->displayName . ' app.', Auth::user()->name));

        return redirect()->route('platform.application.list');
    }

    /**
     * Fetch the options of an application.
     *
     * @return array The list of options for the application.
     */
    public function fetchOption(): array
    {
        if (!$this->application->exists)
            return array();

        $options = array();
        $data = explode(',', $this->application->scope);

        foreach ($data as $option)
            $options[] = $option;
        return $options;
    }
}
