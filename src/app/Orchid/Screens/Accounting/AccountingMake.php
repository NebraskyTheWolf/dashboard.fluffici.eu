<?php

namespace app\Orchid\Screens\Accounting;

use App\Events\UpdateAudit;
use App\Models\Accounting;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class AccountingMake extends Screen
{

    public $accounting;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Accounting $accounting): iterable
    {
        return [
            'accounting' => $accounting
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'New Income / Expense';
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
                ->icon('bs.pencil')
                ->method('createOrUpdate')
                ->type(Color::PRIMARY)
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'platform.accounting.transactions',
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

                Select::make('accounting.type')
                    ->title('Type')
                    ->help('Select if this is a Income or Expense')
                    ->options([
                        'INCOME' => 'Income',
                        'EXPENSE' => 'Expense'
                    ]),

                Input::make('accounting.source')
                    ->title('Source')
                    ->help('The source of this operation'),

                Input::make('accounting.amount')
                    ->title('Amount')
                    ->help('Enter the amount in CZK')
                    ->type('number'),

                CheckBox::make("accounting.is_recurring")
                    ->title("Recurring?")
                    ->help("Is this is a recurring payment?"),

                DateTimer::make("accounting.recurring_at")
                    ->title("Recurring date")
                    ->help("The date when the payment will be recurring.")
                    ->allowInput()
                    ->format24hr()
                    ->allowEmpty()
            ])->title('Information')
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->accounting->fill($request->get('accounting'))->save();

        Toast::info('You created a new ' . $this->accounting->type);

        event(new UpdateAudit('accounting', 'Recorded a new ' . $this->accounting->type, Auth::user()->name));

        return redirect()->route('platform.accounting.main');
    }
}
