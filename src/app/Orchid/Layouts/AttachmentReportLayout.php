<?php

namespace App\Orchid\Layouts;

use App\Models\DmcaRequest;
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
            TD::make('username', __('report.table.reporter'))
                ->render(function (ReportedAttachments $attachments) {
                    if ($attachments->username === null) {
                        return "Anonymous";
                    } else {
                        return $attachments->username;
                    }
                }),
            TD::make('reason', __('report.table.reason'))
                ->render(function (ReportedAttachments $attachments) {
                    if ($attachments->reason === null) {
                        return "No reason";
                    } else {
                        return $attachments->reason;
                    }
                }),
            TD::make('isLegalPurpose', __('report.table.dmca'))
                ->render(function (ReportedAttachments $attachments) {
                    $dmca = DmcaRequest::where('attachment_id', $attachments->attachment_id);

                      if ($dmca->exists()) {
                          return '<a class="ui red label">' . __('report.table.isDMCA.yes') . '</a>';
                      } else {
                          return '<a class="ui teal label">' . __('report.table.isDMCA.no') . '</a>';
                      }
                }),
        ];
    }
}
