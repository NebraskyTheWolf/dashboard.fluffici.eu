<?php

namespace App\Orchid\Screens\Shop;

use App\Models\ShopReports;
use App\Orchid\Layouts\Shop\ShopReportLayout;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Orchid\Alert\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Symfony\Component\HttpFoundation\Request;

class ShopReportList extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'reports' => ShopReports::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Monthly Report';
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
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ShopReportLayout::class
        ];
    }

    public function refresh()
    {
        \Orchid\Support\Facades\Toast::success('You refreshed the page.')
            ->autoHide();

        return redirect()->route('platform.shop.reports');
    }

    public function force()
    {
        Artisan::queue('app:generate-monthly-report', []);

        \Orchid\Support\Facades\Toast::success('You generated a new monthly report.')
            ->autoHide();

        return redirect()->route('platform.shop.reports');
    }

    public function delete(Request $request)
    {
        $report = ShopReports::where('report_id', $request->get('reportId'));
        if ($report->exists()) {
            $data = $report->first();
            $data->delete();

            Storage::disk('public')->delete($data->attachment_id);

            \Orchid\Support\Facades\Toast::error('You deleted ' . $request->get('reportId'))
                ->autoHide();
        } else {
            \Orchid\Support\Facades\Toast::error('This reportId does not exists.')
                ->autoHide();
        }
        return redirect()->route('platform.shop.reports');
    }
}
