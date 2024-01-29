<?php

namespace App\Orchid\Layouts;

use App\Models\SocialMedia;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SocialMediaList extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'social_media';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('slug', 'Slug')
                ->render(function (SocialMedia $socialMedia) {
                    return Link::make($socialMedia->slug)
                        ->icon('bs.pencil')
                        ->href(route('platform.social.edit', $socialMedia));
                }),
            TD::make('url', 'URL')
                ->render(function (SocialMedia $socialMedia) {
                    return Link::make($socialMedia->url)
                        ->icon('bs.'. $socialMedia->slug)
                        ->href($socialMedia->url);
                }),
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.link';
    }

    protected function textNotFound(): string
    {
        return 'No social medias.';
    }
}
