<?php

namespace App\Orchid\Layouts;

use App\Models\ReportedAttachments;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AttachmentReportLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'reports';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('username', __('report.reporter')),
            TD::make('reason', __('report.reason')),
            TD::make('isLegalPurpose', __('report.dmca'))
                ->render(function (ReportedAttachments $attachments) {
                      if ($attachments->isLegalPurpose) {
                          return '<a class="ui green label">' . __('report.isDMCA.yes') . '</a>';
                      } else {
                          return '<a class="ui green label">' . __('report.isDMCA.no') . '</a>';
                      }
                }),
        ];
    }
}
