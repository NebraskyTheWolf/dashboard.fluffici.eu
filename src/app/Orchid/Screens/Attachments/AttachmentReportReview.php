<?php

namespace App\Orchid\Screens\Attachments;

use App\Mail\DefaultEmail;
use App\Models\AutumnFile;
use App\Models\PlatformAttachments;
use App\Models\ReportedAttachments;
use App\Models\ReportReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class AttachmentReportReview extends Screen
{

    public $case;
    public $attachment;
    public $review;

    public function query(ReportedAttachments $case): iterable
    {
        return [
            'case' => $case,
            'attachment' => PlatformAttachments::where('attachment_id', $case->attachment_id)->first(),
        ];
    }


    public function name(): ?string
    {
        return 'Review report case.';
    }


    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->type(Color::SUCCESS)
                ->method('submit')
                ->icon('bs.box-arrow-right')
        ];
    }

    /**
     * Generates a group of elements with the specified alignment.
     *
     * @param array $elements The elements to include in the group.
     * @return Group A Group instance with the elements aligned according to the specified alignment.
     */
    private function generateGroup(array $elements): Group
    {
        return Group::make($elements);
    }

    /**
     * Generates a form field instance with the specified attributes.
     *
     * @param string $class The class name of the form field instance.
     * @param string $name The name attribute of the form field.
     * @param string $title The title/description of the form field.
     * @param bool $disabled Optional. Whether the form field is disabled or not. Defaults to false.
     * @param string $help Optional. Help text to be displayed with the form field. Defaults to an empty string.
     * @param array $options Optional. Additional options to be applied to the form field instance. Defaults to an empty array.
     *
     * @return object A form field instance with the specified attributes.
     */
    private function generateFormField(string $class, string $name, string $title, bool $disabled = false, string $help = '', array $options = [ ]): object
    {
        return $class::make($name)->title($title)->disabled($disabled)->help($help)->options($options);
    }


    /**
     * The layout method is responsible for creating the layout of the screen.
     *
     * It generates three groups of form fields using the 'generateGroup' and 'generateFormField'
     * private methods.
     *
     * The first group contains two form fields: one for the reporter's username, and one for the
     * reasons for the report. Both of these form fields are disabled as they are displaying
     * pre-existing data.
     *
     * The second group contains a picture of the reported attachment and a checkbox form field
     * indicating whether the report is for a content DMCA. The picture is loaded from a specific URL
     * and the checkbox is not disabled, indicating it can be interacted with.
     *
     * The third group contains a select field for the report type and an additional Quill (rich-text)
     * field for the review note which will be sent to the reporter via email.
     *
     * These groups are then organized and returned in a specific layout.
     *
     * @return iterable The resulting layout is returned as an iterable.
     */
    public function layout(): iterable
    {
        if (!$this->attachment->exists) {
            redirect()->route("main");
        }

        $groupOneElements = [
            $this->generateFormField(Input::class, 'case.username', 'Reporter', true),
            $this->generateFormField(Quill::class, 'case.reason', "Report reasons.", true)
        ];

        $groupTwoElements = [
            Picture::make('case.attachment_id')
                ->title("Reported attachment")
                ->url("https://autumn.fluffici.eu/" . $this->attachment->bucket . '/' . $this->attachment->attachment_id),
            $this->generateFormField(CheckBox::class, 'case.isLegalPurpose', "Is DMCA", true, 'If this is checked, the report is for a content DMCA.')
        ];

        $groupThreeElements = [
            $this->generateFormField(Select::class, 'case.type', "Reporter", false, 'Select the action to perform on this content.', [
                'NOTHING' => 'Delete report.',
                'REPORT' => 'Ban content.',
                'DELETE' => 'Delete content.',
            ]),
            $this->generateFormField(Quill::class, 'case.message', "Review note", false, "This note will be sent to the reporter via email.")
        ];

        return [
            Layout::rows([
                $this->generateGroup($groupOneElements)->alignStart(),
                $this->generateGroup($groupTwoElements)->alignCenter(),
            ])->title("Report"),
            Layout::rows([
                $this->generateGroup($groupThreeElements)->alignEnd(),
            ])->title("Review")
        ];
    }

    /**
     * Submits a review for a case report.
     *
     * @param Request $request The HTTP request object containing the case data.
     * @return RedirectResponse A redirect response to the reports page.
     */
    public function submit(Request $request): RedirectResponse
    {
        $this->case->fill($request->get('case'))->save();
        $file = AutumnFile::where('_id', $this->review->attachment_id)->first();

        if ($this->case->type === "REPORT") {
            $file->update([
               'dmca' => true,
               'report' => true
            ]);
        } else if ($this->case->type === "DELETE") {
            $file->update([
                'deleted' => true
            ]);
        }

        Mail::to($this->case->email)->send(new DefaultEmail(
            "Review of your attachment report",
            $this->case->message,
            Auth::user()
        ));

        Toast::success("You reviewed " . $this->attachment->id . " report");

        return redirect()->route('platform.reports');
    }
}
