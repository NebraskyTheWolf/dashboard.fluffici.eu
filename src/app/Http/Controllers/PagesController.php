<<<<<<< HEAD
<?php

namespace App\Http\Controllers;

use Exception;
=======
<?php 

namespace App\Http\Controllers;

>>>>>>> 10223f9b78d8fa2d63823686a7307cb95204bfe1
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Pages;

use App\Events\Statistics;

class PagesController extends Controller {
<<<<<<< HEAD

    public function index(Request $request) {
        $pageSlug = (isset($request->slug)) ? $request->slug : false;

        if (!$pageSlug) {
            return view('errors.error');
        }

        try {
            $page = Pages::where('page_slug', $pageSlug)->firstOrFail();
            $page->increment('visits', 1);
            $page->save();

            event(new Statistics());

            return view('pages.index', compact('page'));
        } catch (exception) {
            return view('errors.error');
        }
=======
    
    public function index(Request $request) {
        $pageSlug = (isset($request->slug)) ? $request->slug : false;

        if ($pageSlug == false) {
            return view('error.404');
        }

        $page = Pages::where('page_slug', $pageSlug)->firstOrFail();
        $page->increment('visits', 1);
        $page->save();

        event(new Statistics());
        
        return view('pages.index', compact('page'));
>>>>>>> 10223f9b78d8fa2d63823686a7307cb95204bfe1
    }
}
