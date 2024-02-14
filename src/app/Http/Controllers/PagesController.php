<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Pages;

use App\Events\Statistics;

class PagesController extends Controller {

    /**
     * index() is a method that handles the logic for displaying a page based on a given slug.
     *
     * @param Request $request The request object containing the slug parameter.
     *
     * @return \Illuminate\View\View The view object for the page to be displayed.
     *                             If the page is found, returns the 'pages.index' view with the 'page' variable compacted.
     *                             If the page is not found, returns the 'errors.404' view.
     */
    public function index(Request $request) {
        $pageSlug = (isset($request->slug)) ? $request->slug : false;

        $page = Pages::where('page_slug', $pageSlug)->firstOrFail();

        if ($page->exists()) {
            $page->increment('visits', 1);
            $page->save();

            event(new Statistics());

            return view('pages.index', compact('page'));
        } else {
            return view('errors.404');
        }
    }
}
