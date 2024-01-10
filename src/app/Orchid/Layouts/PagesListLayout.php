<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use App\Models\Pages;
use Orchid\Screen\Actions\Link;


class PagesListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'pages';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title')
                ->render(function (Pages $page) {
                    return Link::make($page->title)
                        ->route('platform.pages.edit', $page);
                }),

            TD::make('url')
                ->render(function (Pages $page) {
                    return "<a href=\"" . url('/pages/' . $page->page_slug) . "\" target=\"_blank\" style=\"color: blue;\">" . $page->page_slug . "</a>";
                }),

            TD::make('visits')
                ->render(function (Pages $page) {
                    return Pages::where('id', $page->id)->firstOrFail()->visits ?: 0;
                }),
            
            TD::make('created_at', 'Created')
                ->render(function (Pages $page) {
                    return $page->created_at->diffForHumans();
                }),

            TD::make('updated_at', 'Last edit')
                ->render(function (Pages $page) {
                    return $page->updated_at->diffForHumans();
                }),
        ];
    }
}
