<?php

namespace App\Orchid\Screens\Audit;

use App\Models\AuditLogs;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\AuditLogsListLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class AuditLogsListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'audit_logs' => AuditLogs::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('audit.screen.title');
    }

    public function permission(): iterable
    {
        return [
            'platform.audit.read'
        ];
    }

    public function description(): ?string
    {
        return __('audit.screen.description');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('audit.screen.button.refresh'))
                ->icon('bs.arrow-clockwise')
                ->method('refresh')
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
            AuditLogsListLayout::class
        ];
    }

    public function refresh() {

        Toast::info(__('audit.screen.toast.refresh'));

        return redirect()->route('platform.audit');
    }
}
