<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\ShopCategories;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ShopCategoryEdit extends Screen
{

    public $category;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ShopCategories $category): iterable
    {
        return [
            'category' => $category
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->category->exists ? __('category.screen.edit.title') : __('category.screen.edit.title.create');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('category.screen.edit.button.save'))
                ->icon('bs.pencil')
                ->method('createOrUpdate'),

            Button::make(__('category.screen.edit.button.remove'))
                ->icon('bs.trash')
                ->type(Color::DANGER)
                ->method('remove')
                ->canSee($this->category->exists)
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
            Layout::rows([
                Group::make([
                    Input::make('category.name')
                        ->title(__('category.screen.edit.input.name.title'))
                        ->placeholder(__('category.screen.edit.input.name.placeholder'))
                        ->max(50)
                        ->required(),

                    Input::make('category.order')
                        ->title(__('category.screen.edit.input.order.title'))
                        ->placeholder(__('category.screen.edit.input.order.placeholder'))
                        ->type('number')
                        ->required()
                ]),
                Group::make([
                    CheckBox::make('category.displayed')
                        ->title(__('category.screen.edit.input.displayed.title'))
                        ->placeholder(__('category.screen.edit.input.displayed.placeholder'))
                        ->sendTrueOrFalse(),

                    DateTimer::make('category.deleted_at')
                        ->title(__('category.screen.edit.input.deleted_at.title'))
                        ->placeholder(__('category.screen.edit.input.deleted_at.placeholder'))
                        ->allowInput()
                        ->shorthandCurrentMonth()
                ])
            ])
        ];
    }

    public function createOrUpdate(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = $this->category->fill($request->get('category'));

        if ($data->displayed === "ON") {
            $data->displayed = true;
        } else {
            $data->displayed = false;
        }

        $data->save();

        Toast::info('You created a new category.');

        event(new UpdateAudit("products", $this->category->name . " created.", Auth::user()->name));

        return redirect()->route('platform.shop.categories');
    }

    public function remove()
    {
        $this->category->delete();

        return redirect()->route('platform.shop.categories');
    }
}
