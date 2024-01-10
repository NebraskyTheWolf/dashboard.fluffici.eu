<?php
declare(strict_types=1);

namespace App\Orchid\Screens\Settings;

use App\Models\Seo;

use Orchid\Screen\Screen;
use App\Orchid\Layouts\Seo\SeoSelectLayout;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Access\Impersonation;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;


class SeoScreen extends Screen
{

    public $seo;

     /**
     * @var SeoConfiguration
     */

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Seo $seoConfiguration): iterable
    {
        return [
            'seo' => $seoConfiguration
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return "SEO";
    }

    public function description(): ?string
    {
        return 'Change the SEO settings.';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.seo',
        ];
    }


    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make("Save")
                ->icon('bs.check-circle')
                ->method('save'),

            Button::make('Remove')
                ->icon('bs.trash')
                ->method('remove')
                ->canSee($this->seo->exists),
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
            Layout::block(SeoSelectLayout::class)
                ->title("SEO Information")
                ->description("Update the tags and save.")
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->method('save')
                )
        ];
    }


    public function save(Request $request) {
        $this->seo->fill($request->collect('seo')->toArray())->save();

        Toast::info('You have successfully created the SEO tags.');

        return redirect()->route('platform.main');
    }

    public function remove() {
        $this->seo->delete();

        Toast::info('You have successfully deleted the SEO tags.');

        return redirect()->route('platform.main');
    }
}
