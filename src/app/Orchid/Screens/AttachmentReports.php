<?php

namespace App\Orchid\Screens;

use App\Models\ReportedAttachments;
use App\Orchid\Layouts\AttachmentReportLayout;
use Orchid\Screen\Screen;

class AttachmentReports extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'reports' => ReportedAttachments::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('report.screen.title');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            AttachmentReportLayout::class
        ];
    }
}
