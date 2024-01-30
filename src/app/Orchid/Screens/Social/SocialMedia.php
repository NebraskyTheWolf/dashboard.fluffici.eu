<?php

namespace App\Orchid\Screens\Social;

use App\Orchid\Layouts\Social\SocialMediaList;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;

class SocialMedia extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'social_media' => \App\Models\SocialMedia::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Social Media';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('New')
                ->icon('bs.plus')
                ->type(Color::SUCCESS)
                ->href(route('platform.social.edit'))
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
            SocialMediaList::class
        ];
    }
}
