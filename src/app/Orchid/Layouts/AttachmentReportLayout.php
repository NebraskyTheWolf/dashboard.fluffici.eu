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
            TD::make('username', 'Reporter'),
            TD::make('reason', 'Důvod'),
            TD::make('isLegalPurpose', 'DMCA')
                ->render(function (ReportedAttachments $attachments) {
                      if ($attachments->isLegalPurpose) {
                          return '<a class="ui green label">Ano</a>';
                      } else {
                          return '<a class="ui green label">Ne</a>';
                      }
                }),
        ];
    }
}
