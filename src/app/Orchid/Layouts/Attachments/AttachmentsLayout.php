<?php


namespace App\Orchid\Layouts\Attachments;

use app\Fields\RelativeTime;
use App\Models\DmcaRequest;
use App\Models\PlatformAttachments;
use App\Models\ReportedAttachments;
use App\Orchid\Presenters\AuditPresenter;
use Carbon\Carbon;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

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
    public function columns(): iterable
    {
        return [
            TD::make('user_id', __('attachments.table.attached_by'))
                ->render(function (PlatformAttachments $platformAttachments) {
                    $user = User::where('id', $platformAttachments->user_id);

                    if ($user->exists()) {
                        return new Persona(new AuditPresenter(User::find($user->first()->id)));
                    } else {
                        return new Persona(new AuditPresenter((object)[
                            'name' => 'Deleted User',
                            'roles' => array([]),
                            'avatar' => 0
                        ]));
                    }
                }),
            TD::make('action', 'Action')
                ->render(function (PlatformAttachments $platformAttachments) {
                    return DropDown::make('Menu')
                        ->list([
                            Link::make('Lookup')
                                ->href('https://autumn.fluffici.eu/' . $platformAttachments->bucket . '/' . $platformAttachments->attachment_id),
                            Button::make('Delete')
                                ->method('remove', [
                                    "attachment_id" => $platformAttachments->attachment_id,
                                    "tag" => $platformAttachments->bucket
                                ])->type(Color::BASIC),
                            Button::make('Take down')
                                ->method('report', [
                                    "attachment_id" => $platformAttachments->attachment_id,
                                    "tag" => $platformAttachments->bucket
                                ])->type(Color::WARNING),
                        ]);
                }),
            TD::make('bucket', __('attachments.table.tag'))
                ->render(function (PlatformAttachments $platformAttachments) {
                    if ($platformAttachments->bucket === null) {
                        return "No tag";
                    } else {
                        return $platformAttachments->bucket;
                    }
                }),
            TD::make('reported', __('attachments.table.reported'))
                ->render(function (PlatformAttachments $platformAttachments) {
                    $report = ReportedAttachments::where('attachment_id', $platformAttachments->attachment_id);
                    $dmca = DmcaRequest::where('attachment_id', $platformAttachments->attachment_id);

                    if ($report->exists()) {
                        if ($dmca->exists()) {
                            return '<div class="ui red label">' . __('attachments.table.reported.two') . '</div>';
                        } else {
                            return '<div class="ui red label">' . __('attachments.table.reported.one') . '</div>';
                        }

                    } else {
                        return '<div class="ui teal label">' .  __('attachments.table.reported.no_records') . '</div>';
                    }
                }),

            TD::make('created_at', __('attachments.table.created_at'))
                ->render(function (PlatformAttachments $platformAttachments) {
                    return RelativeTime::make('created_at')->setTime($platformAttachments->created_at);
                }),
        ];
    }
}
