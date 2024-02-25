<?php

namespace App\Orchid\Screens\Email;

use App\Events\UpdateAudit;
use App\Mail\DefaultEmail;
use App\Models\CreateSendEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class SendEmail extends Screen
{

    public $email;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(CreateSendEmail $email): iterable
    {
        return [
            'email' => $email
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Send a new email.';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Send')
                ->icon('bs.envelope')
                ->method('sendEmail')
                ->type(Color::SUCCESS)
        ];
    }

    /**
     * Generate the layout for the email creation form.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('email.to')
                    ->title('To')
                    ->type('email')
                    ->placeholder('Please enter the receiver email.')
                    ->help('Example : john.smith@example.com')
                    ->max(40)
                    ->min(25)
                    ->required(),

                Input::make('email.subject')
                    ->title('Subject')
                    ->placeholder('Please enter the subject')
                    ->max(140)
                    ->min(15)
                    ->required(),

                Quill::make('email.message')
                    ->title('Please enter the email content')
                    ->help('If you need to use CSS please refer to the documentation.')
                    ->required()
                    // Using the collaborative space
                    ->collaborative()
                    ->roomId('sendemail')
            ])
        ];
    }

    /**
     * Send an email based on the given request.
     *
     * @param \Illuminate\Http\Request $request The request object containing email details.
     * @return RedirectResponse A redirect response to the sendmail route.
     */
    public function sendEmail(Request $request): RedirectResponse
    {
        $this->email->fill($request->get('email'))->save();

        Mail::to($this->email->to)->send(new DefaultEmail($this->email->subject, $this->email->message, Auth::user()));

        Toast::info("You send a email to " . $this->email->to);

        event(new UpdateAudit('send_email', 'Sent a email to ' . $this->email->to, Auth::user()->name));

        return redirect()->route('platform.admin.sendmail');
    }

}
