<?php

namespace App\Orchid\Screens\Attachments;

use App\Models\Event\EventAttachments;
use App\Orchid\Layouts\EventUserSubmittedPictures;
use Orchid\Screen\Screen;

class PictureApproval extends Screen
{

    public function query(): iterable
    {
        return [
            'pictures' => EventAttachments::orderBy('created_at', 'desc')
                ->where('published', 0)
                ->paginate()
        ];
    }


    public function name(): ?string
    {
        return 'Event Picture Approval';
    }


    public function commandBar(): iterable
    {
        return [];
    }


    public function layout(): iterable
    {
        return [
            EventUserSubmittedPictures::class
        ];
    }
}
