<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class ProfilePasswordLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Password::make('old_password')
                ->placeholder(__('user.screen.user.old_password.placeholder'))
                ->title(__('user.screen.user.old_password.title'))
                ->help(__('user.screen.user.old_password.help')),

            Password::make('password')
                ->placeholder(__('user.screen.user.common_password.placeholder'))
                ->title(__('user.screen.user.new_password.title')),

            Password::make('password_confirmation')
                ->placeholder(__('user.screen.user.common_password.placeholder'))
                ->title(__('user.screen.user.confirm_password.title'))
                ->help('user.screen.user.confirm_password.help'),
        ];
    }
}
