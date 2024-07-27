<?php

namespace App\Orchid\Layouts\Attachments;

use App\Models\ReportedAttachments;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

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
            TD::make('review', 'Review')
                ->render(function (ReportedAttachments $attachments) {
                    if ($attachments->reviewed) {
                        return \Orchid\Screen\Actions\Link::make("Reviewed by " . $attachments->reviewed_by)
                            ->type(Color::SUCCESS)
                            ->icon('bs.check-all')
                            ->route('platform.attachments.review', $attachments);
                    } else {
                        return \Orchid\Screen\Actions\Link::make("Review")
                            ->type(Color::WARNING)
                            ->icon('bs.clock')
                            ->route('platform.attachments.review', $attachments);
                    }
                }),
            TD::make('username', __('report.table.reporter'))
                ->render(function (ReportedAttachments $attachments) {
                    if ($attachments->username === null) {
                        return "Anonymous";
                    } else {
                        return $attachments->username;
                    }
                }),
            TD::make('isLegalPurpose', __('report.table.dmca'))
                ->render(function (ReportedAttachments $attachments) {
                      if ($attachments->isLegalPurpose) {
                          return '<a class="ui red label">' . __('report.table.isDMCA.yes') . '</a>';
                      } else {
                          return '<a class="ui green label">' . __('report.table.isDMCA.no') . '</a>';
                      }
                }),
        ];
    }
}
