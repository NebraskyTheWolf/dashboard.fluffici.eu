<?php

namespace App\Orchid\Layouts;

use App\Models\AccountingDocument;
use App\Models\AuditLogs;
use App\Models\PostsComments;
use App\Orchid\Presenters\AuditPresenter;
use Carbon\Carbon;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ArticleComments extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'posts_comments';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('author', 'Author')
                ->render(function (PostsComments $comments) {
                    $user = User::where('id', $comments->author);

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
            TD::make('message', 'Message')
                ->render(function (PostsComments $comments) {
                    return $comments->message;
                }),
            TD::make('delete', 'Action')
                ->render(function (PostsComments $comments) {
                    return Button::make('Delete')
                        ->icon('bs.trash')
                        ->type(Color::DANGER)
                        ->confirm(__('common.modal.confirm'))
                        ->method('deleteComment', [ 'commentId' => $comments->id ]);
                }),
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.clipboard-data';
    }

    protected function textNotFound(): string
    {
        return 'Dosud nebyla vytvořena žádná měsíční účetní zpráva.';
    }

    protected function subNotFound(): string
    {
        return 'Příští zpráva bude automaticky vygenerována ' . Carbon::now()->endOfMonth()->diffForHumans();
    }
}
