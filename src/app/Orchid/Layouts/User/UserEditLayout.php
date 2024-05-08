<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Layouts\Rows;


class UserEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array {

        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('user.screen.user.name'))
                ->placeholder(__('user.screen.user.name')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('user.screen.user.email'))
                ->placeholder(__('user.screen.user.email.placeholder')),

            Cropper::make('user.avatar_id')
                ->remoteTag('avatars')
                ->minWidth(250)
                ->maxWidth(512)
                ->minHeight(250)
                ->maxHeight(512)
                ->maxFileSize(20),

            Quill::make('user.bio')
                ->title("About Me"),

            Input::make('user.pronouns')
                ->type('text')
                ->title("Mood")
                ->placeholder("Enter your mood here."),
        ];
    }
}
