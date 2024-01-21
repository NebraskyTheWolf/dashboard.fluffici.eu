<?php

namespace App\Compoenents;

use Illuminate\View\Component;

class FilesViewComponent extends Component
{

    public $files;

    public function __construct($files)
    {
        $this->files = $files;
    }

    public function render() {
        return $this->view("partials.files")->with('files', $this->files);
    }
}
