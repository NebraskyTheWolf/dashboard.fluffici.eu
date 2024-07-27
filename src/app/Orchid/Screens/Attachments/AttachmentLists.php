<?php

namespace App\Orchid\Screens\Attachments;

use App\Models\AutumnFile;
use App\Models\PlatformAttachments;
use App\Orchid\Layouts\Attachments\AttachmentsLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

define('BINARY_UNITS', array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'));
define('METRIC_UNITS', array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'));

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
            'platform_attachments' => PlatformAttachments::paginate(),
            'metrics' => [
                'count' => [
                    'key' => 'count',
                    'value' => number_format(count(AutumnFile::all())),
                    'icon' => 'bs.file-binary'
                ],
                'storage' => [
                    'key' => 'storage',
                    'value' => $this->human_readable_bytes((new AutumnFile)->totalSize()),
                    'icon' => 'bs.hdd-stack'
                ],
                'storage_max' => [
                    'key' => 'storage_max',
                    'value' => $this->human_readable_bytes(disk_total_space("/")),
                    'icon' => 'bs.hdd-network'
                ],
            ],
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
            Layout::metrics([
                'Files' => 'metrics.count',
                'Used space' => 'metrics.storage',
                'Free space' => 'metrics.storage_max',
            ]),

            AttachmentsLayout::class
        ];
    }

    public function remove(Request $request)
    {
        $id = $request->get("attachment_id");
        $tag = $request->get("tag");

        $file = AutumnFile::where('_id', $id)->where('tag', $tag)->get()->first();
        if ($file->deleted != null && $file->deleted) {
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
        if ($file->reported != null && $file->reported) {

            $file->update([
                'reported' => false,
                'dmca' => false,
            ]);

            Toast::success('The file was reinstated.');
        } else {
            $file->update([
                'reported' => true,
                'dmca' => true,
            ]);

            Toast::success('The file was set has reported.');
        }

        return redirect()->route('platform.attachments');
    }

    /**
     * Converts bytes into a human-readable string representation.
     *
     * @param int $bytes The number of bytes.
     * @param int $decimals The number of decimal places to round the result to. Default is 2.
     * @param string $system The unit system to use. Can be either 'binary' or 'metric'. Default is 'binary'.
     * @return string A human-readable string representation of the bytes.
     */
    function human_readable_bytes(int $bytes, int $decimals = 2, string $system = 'binary'): string
    {
        $mod = ($system === 'binary') ? 1024 : 1000;
        $units = array('binary' => BINARY_UNITS, 'metric' => METRIC_UNITS);
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f%s", $bytes / pow($mod, $factor), $units[$system][$factor]);
    }
}
