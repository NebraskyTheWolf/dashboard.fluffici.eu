<?php

namespace Coderflex\LaravelTurnstile\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TurnstileWidget extends Component
{
    /**
     * Renders the turnstile widget view.
     *
     * @return View The rendered turnstile widget view.
     */
    public function render(): View
    {
        return view('turnstile::components.turnstile-widget');
    }
}
