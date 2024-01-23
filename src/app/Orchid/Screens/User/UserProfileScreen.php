<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\ProfilePasswordLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Attach ;
use Orchid\Screen\Actions\Picture ;
use App\Models\User as AUser;

use App\Events\UpdateAudit;
use App\Events\UserUpdate;

class UserProfileScreen extends Screen
{

    /**
     * Fetch data to be displayed on the screen.
     *
     *
     * @return array
     */
    public function query(Request $request): iterable
    {
        return [
            'user' => $request->user(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('user.screen.profile.title');
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return __('user.screen.profile.descriptions');
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('user.screen.profile.button.back'))
                ->novalidate()
                ->canSee(Impersonation::isSwitch())
                ->icon('bs.people')
                ->route('switch.logout'),

            Button::make(__('user.screen.profile.button.logout'))
                ->novalidate()
                ->icon('bs.box-arrow-left')
                ->route('logout'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(UserEditLayout::class)
                ->title(__('user.screen.profile.layout.information.title'))
                ->description(__('user.screen.profile.layout.information.descriptions'))
                ->commands([
                    Button::make(__('user.screen.profile.button.save'))
                        ->type(Color::PRIMARY)
                        ->icon('bs.check-circle')
                        ->method('save'),
                ]),

            Layout::block(ProfilePasswordLayout::class)
                ->title(__('user.screen.profile.layout.password.title'))
                ->description(__('user.screen.profile.layout.password.descriptions'))
                ->commands(
                    Button::make(__('user.screen.profile.layout.password.title'))
                        ->type(Color::BASIC())
                        ->icon('bs.check-circle')
                        ->method('changePassword')
                ),
        ];
    }

    public function save(Request $request): void
    {
        $request->validate([
            'user.name'  => 'required|string',
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($request->user()),
            ],
        ]);

        $request->user()
            ->fill($request->get('user'))
            ->save();

        event(new UpdateAudit("profile_updated", "Updated their profile."));


        Toast::info(__('user.screen.profile.toast.saved'));
    }

    public function changePassword(Request $request): void
    {
        $guard = config('platform.guard', 'web');
        $request->validate([
            'old_password' => 'required|current_password:'.$guard,
            'password'     => 'required|confirmed|different:old_password',
        ]);

        tap($request->user(), function ($user) use ($request) {
            $user->password = Hash::make($request->get('password'));
        })->save();


        event(new UpdateAudit("profile_change", "Password changed."));
        event(new UserUpdate($request->user()->id));

        Toast::info(__('user.screen.profile.toast.password_changed'));
    }
}
