<?php

namespace App\Orchid\Screens\Accounting;

use App\Models\Shop\Accounting\AccountingDocument;
use App\Orchid\Layouts\AccountingReportLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class AccountingReportList extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'sources' => AccountingDocument::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Accounting Report List';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Refresh')
                ->icon('bs.arrow-repeat')
                ->method('refresh'),

            Button::make('Force Generate')
                ->icon('bs.journal-plus')
                ->type(Color::PRIMARY)
                ->download(true)
                ->method('force')
        ];
    }

    /**
     * Get the layout for the accounting report.
     *
     * @return array The accounting report layout.
     */
    public function layout(): iterable
    {
        return [
            AccountingReportLayout::class
        ];
    }

    /**
     * Refresh the page and redirect to the accounting reports page.
     *
     * @return RedirectResponse
     */
    public function refresh(): RedirectResponse
    {
        Toast::success('You refreshed the page.')
            ->autoHide();

        return redirect()->route('platform.accounting.reports');
    }

    /**
     * Force the generation of a new monthly accounting report.
     *
     * This method triggers the 'app:generate-accounting-report' Artisan command in the background,
     * generates a new monthly report, and notifies the user with a success toast message.
     *
     * @return RedirectResponse
     */
    public function force()
    {
        Artisan::queue('app:generate-accounting-report', []);

        Toast::success('You generated a new monthly report.')
            ->autoHide();

        return redirect()->route('platform.accounting.reports');
    }

    /**
     * Delete an accounting document.
     *
     * @param \Illuminate\Http\Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $report = AccountingDocument::where('report_id', $request->get('reportId'));
        if ($report->exists()) {
            $data = $report->first();
            $data->delete();

            Storage::disk('public')->delete($data->attachment_id);

            Toast::error('You deleted ' . $request->get('reportId'))
                ->autoHide();
        } else {
            Toast::error('This reportId does not exists.')
                ->autoHide();
        }

        return redirect()->route('platform.accounting.reports');
    }

}
