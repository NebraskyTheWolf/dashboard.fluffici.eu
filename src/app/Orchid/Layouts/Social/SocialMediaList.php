<?php

namespace App\Orchid\Layouts\Social;

use App\Models\SocialMedia;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

/**
 * Class SocialMediaList
 *
 * Představuje seznam položek sociálních médií.
 *
 * @package YourPackage
 */
class SocialMediaList extends Table
{
    /**
     * Zdroj dat.
     *
     * Název klíče pro získání z dotazu.
     * Výsledky tohoto budou prvky tabulky.
     *
     * @var string
     */
    protected $target = 'social_media';
    /**
     * Získejte buňky tabulky, které budou zobrazeny.
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
        return 'Žádná sociální média.';
    }
}
