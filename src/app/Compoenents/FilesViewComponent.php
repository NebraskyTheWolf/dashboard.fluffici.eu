<?php

namespace App\Compoenents;

use Orchid\Screen\Layouts\View;

/**
 * Class FilesViewComponent
 *
 * This class extends the View class and represents a component for viewing files.
 * It provides a data source property to specify the template to be used for rendering.
 */
class FilesViewComponent extends View
{

    /**
     * Data source.
     *
     * @var string
     */
    public $template = 'partials.files';
}
