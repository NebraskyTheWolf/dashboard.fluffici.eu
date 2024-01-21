<?php


namespace App\Orchid\Layouts;

use App\Compoenents\FilesViewComponent;
use App\Models\PlatformAttachments;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;

class AttachmentsLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'platform_attachments';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            new FilesViewComponent(PlatformAttachments::all())
        ];
    }
}
