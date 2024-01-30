<?php

namespace App\Orchid\Screens\Attachments;

use App\Models\PlatformAttachments;
use App\Orchid\Layouts\Attachments\AttachmentsLayout;
use Illuminate\Support\Facades\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class AttachmentLists extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'platform_attachments' => PlatformAttachments::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('attachments.screen.title');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Upload')
                ->icon('bs.box-arrow-in-up')
                ->href(route('platform.attachments.upload')),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            AttachmentsLayout::class
        ];
    }

    public function upload(Request $request)
    {

    }
}
