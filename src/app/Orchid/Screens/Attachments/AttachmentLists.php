<?php

namespace App\Orchid\Screens\Attachments;

use App\Models\AutumnFile;
use App\Models\PlatformAttachments;
use App\Orchid\Layouts\Attachments\AttachmentsLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

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

    public function remove(Request $request)
    {
        $id = $request->get("attachment_id");
        $tag = $request->get("tag");

        $file = AutumnFile::where('_id', $id)->where('tag', $tag)->get()->first();
        if ($file->deleted) {
            Toast::warning('This file was already deleted.');
        } else {
            $file->update([
                'deleted' => true,
            ]);

            $platform = PlatformAttachments::where('attachment_id', $id)->where('bucket', $tag)->first();
            $platform->delete();

            Toast::success('File deleted successfully.');
        }

        return redirect()->route('platform.attachments');
    }

    public function report(Request $request)
    {
        $id = $request->get("attachment_id");
        $tag = $request->get("tag");

        $file = AutumnFile::where('_id', $id)->where('tag', $tag)->get()->first();
        if ($file->reported) {

            $file->update([
                'reported' => false,
            ]);

            Toast::success('The file was reinstated.');
        } else {
            $file->update([
                'reported' => true,
            ]);

            $platform = PlatformAttachments::where('attachment_id', $id)->where('bucket', $tag)->first();
            $platform->delete();

            Toast::success('The file was set has reported.');
        }

        return redirect()->route('platform.attachments');
    }
}
