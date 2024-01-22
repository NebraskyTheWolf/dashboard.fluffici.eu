<?php


namespace App\Orchid\Layouts;

use App\Models\DmcaRequest;
use App\Models\PlatformAttachments;
use App\Models\ReportedAttachments;
use App\Models\User;
use App\Orchid\Presenters\UserPresenter;
use Carbon\Carbon;
use Orchid\Screen\Layouts\Persona;
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
            TD::make('user_id', "Attached by")
                ->render(function (PlatformAttachments $platformAttachments) {
                    $user = User::where('id', $platformAttachments->id)->firstOrFail();
                    return new Persona(new UserPresenter($user));
                }),
            TD::make('bucket', 'Tag'),
            TD::make('reported', 'Reported')
                ->render(function (PlatformAttachments $platformAttachments) {
                    $report = ReportedAttachments::where('attachment_id', $platformAttachments->attachment_id);
                    $dmca = DmcaRequest::where('attachment_id', $platformAttachments->attachment_id);

                    if ($report->exists()) {
                        return '<div class="ui red label">Reported</div>';
                    } else if ($report->exists() && $dmca->exists()) {
                        return '<div class="ui red label">Reported + DMCA</div>';
                    } else {
                        return '<div class="ui red label">No records</div>';
                    }
                }),
            TD::make('created_at')
                ->render(function (PlatformAttachments $platformAttachments) {
                    return Carbon::parse($platformAttachments->created_at)->diffForHumans();
                }),
        ];
    }
}
