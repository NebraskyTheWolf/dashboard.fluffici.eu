<?php

namespace App\Orchid\Layouts;

use App\Models\Event\EventAttachments;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EventUserSubmittedPictures extends Table
{

    protected $target = 'pictures';


    protected function columns(): iterable
    {
        return [
            TD::make('user_id', "User")
                ->sort()
                ->cantHide()
                ->render(function (EventAttachments $attachments) {
                    $user = User::where('id', $attachments->user_id);

                    if ($user->exists()) {
                        $user = $user->first();

                        return Link::make($user->name)
                            ->icon('bs.pencil')
                            ->route('platform.pictures.edit', $attachments);
                    } else {
                        return Link::make("Unknown user")
                            ->icon('bs.pencil')
                            ->route('platform.pictures.edit', $attachments);
                    }
                }),
            TD::make('attachment_id', "Picture")
                ->render(function (EventAttachments $attachments) {
                    return Picture::make('attachment_id')
                        ->url('https://autumn.fluffici.eu/photos/' . $attachments->attachment_id);
                }),
            TD::make('attachment_id', 'Attachment ID')
        ];
    }
}
