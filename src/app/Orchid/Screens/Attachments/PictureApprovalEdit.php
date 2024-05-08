<?php

namespace App\Orchid\Screens\Attachments;

use App\Models\AutumnFile;
use App\Models\EventAttachments;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PictureApprovalEdit extends Screen
{

    public $attachment;

    public function query(EventAttachments $attachment): iterable
    {
        return [
            'attachment' => $attachment
        ];
    }


    public function name(): ?string
    {
        return "Do you approve this picture?";
    }


    public function commandBar(): iterable
    {
        return [
            Button::make('Approve')
                ->icon('bs.person-check-fill')
                ->type(Color::SUCCESS)
                ->method('approve')
                ->disabled($this->attachment->published == 1),
            Button::make('Deny')
                ->icon('bs.person-dash')
                ->type(Color::DANGER)
                ->method('deny')
                ->disabled($this->attachment->published == 1),

        ];
    }


    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    Picture::make('attachment.attachment_id')
                        ->url('https://autumn.fluffici.eu/photos/' . $this->attachment->attachment_id)
                ]),

                Group::make([
                    Input::make('attachment.user_id')
                        ->title('Author')
                        ->value(User::where('id', $this->attachment->user_id)->first()->name)
                ])
            ])
        ];
    }

    public function approve()
    {
        $this->attachment->published = 1;
        $this->attachment->save();

        Toast::info("You approved this picture to be shown.")->autoHide();

        return redirect()->route('platform.pictures');
    }

    public function deny()
    {

        $file = AutumnFile::where('_id', $this->attachment->attachment_id)->where('tag', 'photos')->get()->first();
        $file->deleted = 1;
        $file->save();

        Toast::info("You denied this picture to be shown.")->autoHide();

        return redirect()->route('platform.pictures');
    }
}
