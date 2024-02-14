<?php

namespace App\Compoenents;

use Illuminate\View\Component;

/**
 * Class FileReportViewComponent
 *
 * This class represents a view component for displaying file reports.
 * It extends the Component class.
 */
class FileReportViewComponent extends Component
{

    public $reports;

    public function __construct($reports)
    {
        $this->$reports = $reports;
    }

    public function render() {

        return $this->view("partials.reports")->with('reports', $this->reports);
    }
}
