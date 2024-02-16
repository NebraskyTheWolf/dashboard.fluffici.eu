<?php

namespace App\Orchid\Layouts\Audit;

use App\Models\AuditLogs;
use App\Orchid\Presenters\AuditPresenter;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AuditLogsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'audit_logs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', __('audit.table.user'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (AuditLogs $auditLogs) {
                    if ($auditLogs->name == "SYSTEM") {
                        return new Persona(new AuditPresenter((object)[
                            'name' => 'System',
                            'roles' => array([]),
                            'avatar' => 0
                        ]));
                    }

                    $user = \Orchid\Platform\Models\User::where('name', $auditLogs->name);

                    if ($user->exists()) {
                        return new Persona(new AuditPresenter(\Orchid\Platform\Models\User::find($user->first()->id)));
                    } else {
                        return new Persona(new AuditPresenter((object)[
                            'name' => 'Deleted User',
                            'roles' => array([]),
                            'avatar' => 0
                        ]));
                    }
                }),

            TD::make('slug', __('audit.table.action'))
                ->render(function (AuditLogs $auditLogs) {
                    return strtoupper($auditLogs->slug);
                }),

            TD::make('type', __('audit.table.operation'))
                ->render(function (AuditLogs $auditLogs) {
                    return "<a style=\"color: red;\">" . strtolower($auditLogs->type) . "</a>";
                }),

            TD::make('created_at', __('audit.table.create_at'))
                ->render(function (AuditLogs $auditLogs) {
                    return $auditLogs->created_at->diffForHumans();
                }),
        ];
    }
}
