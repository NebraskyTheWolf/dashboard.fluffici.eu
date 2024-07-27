<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\Shop\Internal\TaxGroup;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class TaxGroupEdit extends Screen
{
    public $group;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(TaxGroup $group): iterable
    {
        return [
            'group' => $group
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->group->exists ? 'Edit tax' : 'Create new tax group.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->type(Color::SUCCESS)
                ->icon('bs.pencil')
                ->method('createOrUpdate'),
            Button::make('Delete')
                ->type(Color::PRIMARY)
                ->icon('bs.trash')
                ->method('delete')
                ->canSee($this->group->exists),
        ];
    }

    public function permission(): iterable
    {
        return [
            'platform.shop.taxes.write'
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
                Input::make('group.name')
                    ->title('Name')
                    ->placeholder('Please enter the Tax group name')
                    ->help('Example: VAT')
                    ->required(),

                Input::make('group.percentage')
                    ->type('number')
                    ->title('Percentage')
                    ->placeholder('Please enter the tax percentage.')
                    ->help('Example : 21, 5, 1.5')
                    ->required()
            ])
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->group->fill($request->get('group'))->save();

        Toast::info('You created a new tax group.');

        event(new UpdateAudit('shop_tax', 'Created a new tax group', Auth::user()->name));

        return redirect()->route('tax.group.list');
    }

    public function delete()
    {
        $this->group->delete();

        Toast::info('Tax group deleted.');

        event(new UpdateAudit('shop_tax', 'Deleted a tax group', Auth::user()->name));

        return redirect()->route('tax.group.list');
    }
}
