<?php

namespace App\Orchid\Layouts;

use App\Models\User;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\AuditLogs;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use App\Orchid\Presenters\UserPresenter;

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
            TD::make('name', "User")
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (AuditLogs $auditLogs) {
                    $user = User::where('name', $auditLogs->name)->firstOrFail();
                    return new Persona(new UserPresenter($user));
                }),

            TD::make('slug', "Action")
                ->render(function (AuditLogs $auditLogs) {
                    return strtoupper($auditLogs->slug);
                }),

            TD::make('type', 'Operation')
                ->render(function (AuditLogs $auditLogs) {
                    if ($auditLogs->type == "DELETE" || $auditLogs->type == "CANCELLED") {
                        return "<a style=\"color: red;\">" . $auditLogs->type . "</a>";
                    } else if ($auditLogs->type == "CHANGE") {
                        return "<a style=\"color: blue;\">" . $auditLogs->type . "</a>";
                    } else if ($auditLogs->type == "ADDED" || $auditLogs->type == "FINISHED") {
                        return "<a style=\"color: green;\">" . $auditLogs->type . "</a>";
                    }
                    return "<a style=\"color: blue;\">" . $auditLogs->type . "</a>";
                }),

            TD::make('created_at', 'Created')
                ->render(function (AuditLogs $auditLogs) {
                    return $auditLogs->created_at->diffForHumans();
                }),
        ];
    }
}
