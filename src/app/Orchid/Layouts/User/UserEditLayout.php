<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Attach ;
use Orchid\Screen\Fields\Cropper ;



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
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),

            Cropper::make('user.avatar')
                ->userId(Auth::id())
                ->remoteTag('avatars')
                ->objectId(Auth::user()->avatar_id)
                ->minWidth(250)
                ->maxWidth(512)
                ->minHeight(250)
                ->maxHeight(512)
                ->maxFileSize(20)
        ];
    }
}
