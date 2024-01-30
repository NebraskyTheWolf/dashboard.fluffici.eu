<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Events\UpdateAudit;
use App\Events\UserUpdated;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            Button::make(__('user.screen.edit.button.remove'))
                ->icon('bs.trash3')
                ->confirm('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')
                ->method('remove')
                ->canSee($this->user->exists),

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
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(User $user)
    {
        if ($user->name === 'Asherro'
            && Auth::user()->name !== "Asherro")
        {
            Toast::info('You cannot delete Asherro\'s account.');

            event(new UpdateAudit("deleted_user", "Tried to delete " . $user->name . " account.", Auth::user()->name));

            return redirect()->route('platform.systems.users');
        }

        $user->delete();

        Toast::info(__('user.screen.edit.toast.removed'));

        event(new UpdateAudit("deleted_user", "Deleted " . $user->name . " profile.", Auth::user()->name));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(User $user)
    {
        Impersonation::loginAs($user);

        Toast::info(__('You are now impersonating this user'));

        event(new UpdateAudit("impersonate", "Impersonating " . $user->name . " profile.", Auth::user()->name));

        return redirect()->route(config('platform.index'));
    }
}
