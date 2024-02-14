<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Events\UpdateAudit;
use App\Events\UserUpdated;
use App\Mail\UserTermination;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Orchid\Access\Impersonation;
use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserEditScreen extends Screen
{
    /**
     * @var User
     */
    public $user;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(User $user): iterable
    {
        $user->load(['roles']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->user->exists ? __('user.screen.edit.title') : __('user.screen.edit.title.created');
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return __('user.screen.edit.descriptions');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make($this->user->isTerminated() ? 'Reinstate' : 'Terminate')
                ->icon('bs.slash-circle')
                ->confirm('Are you sure to continue?')
                ->method('terminate')
                ->canSee($this->user->exists)
                ->canSee($this->user->hasUserBiggerPower(Auth::user())),

            Button::make(__('user.screen.edit.button.save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(UserEditLayout::class)
                ->title(__('user.screen.edit.layout.information.title'))
                ->description(__('user.screen.edit.layout.information.descriptions'))
                ->commands(
                    Button::make(__('user.screen.edit.button.save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserPasswordLayout::class)
                ->title(__('user.screen.edit.layout.password.title'))
                ->description(__('user.screen.edit.layout.password.descriptions'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserRoleLayout::class)
                ->title(__('user.screen.edit.layout.roles.title'))
                ->description(__('user.screen.edit.layout.roles.descriptions'))
                ->commands(
                    Button::make(__('user.screen.edit.button.save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(RolePermissionLayout::class)
                ->title(__('user.screen.edit.layout.permissions.title'))
                ->description(__('user.screen.edit.layout.permissions.descriptions'))
                ->commands(
                    Button::make(__('user.screen.edit.button.save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, Request $request)
    {
        if ($user->name === 'Asherro'
            && Auth::user()->name !== "Asherro")
        {
            Toast::info('You cannot delete Asherro\'s account.');

            event(new UpdateAudit("edit_user", "Tried to edit Asherro account.", Auth::user()->name));

            return redirect()->route('platform.systems.users');
        }

        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
        ]);

        $permissions = collect($request->get('permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $user->when($request->filled('user.password'), function (Builder $builder) use ($request) {
            $builder->getModel()->password = Hash::make($request->input('user.password'));
        });

        $user
            ->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray())
            ->forceFill(['permissions' => $permissions])
            ->save();

        $user->replaceRoles($request->input('user.roles'));

        event(new UpdateAudit("user_change", "Changed " . $user->name . " profile.", Auth::user()->name));
        event(new UserUpdated($user->id));

        Toast::info(__('user.screen.edit.toast.updated'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * Terminate a user account.
     *
     * @param User $user The user account to terminate.
     *
     * @return \Illuminate\Http\RedirectResponse The redirect response to the systems users page.
     */
    public function terminate(User $user)
    {
        if ($user->name === 'Asherro'
            && Auth::user()->name !== "Asherro")
        {
            Toast::info('You cannot delete Asherro\'s account.');

            event(new UpdateAudit("deleted_user", "Tried to terminate " . $user->name . " account.", Auth::user()->name));

            return redirect()->route('platform.systems.users');
        }

        if ($this->user->hasUserBiggerPower(Auth::user())) {
            Toast::info("You cannot terminate this user, this user have bigger power than yours.");

            return redirect()->route('platform.systems.users');
        }

        $user->terminate(Auth::id());

        if ($user->isTerminated()) {
            $user->replaceRoles([]);

            Toast::info("You terminated " . $user->name . " account.");

            Mail::to($user)->send(new UserTermination($user->email));

            // Laravel trick to logout a distant user..
            $user->update([
                'logout' => true
            ]);

            event(new UpdateAudit("terminated_user", "Terminated " . $user->name . " account.", Auth::user()->name));
        } else {
            Toast::info("You reinstated " . $user->name . " account.");

            event(new UpdateAudit("terminated_user", "Reinstated " . $user->name . " account.", Auth::user()->name));
        }

        return redirect()->route('platform.systems.users');
    }
}
