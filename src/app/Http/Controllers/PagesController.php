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
    public function index(Request $request)
    {
        $pageSlug = $request->slug ?? false;

        $page = Pages::where('page_slug', $pageSlug)->firstOrFail();

        $this->updatePageViews($page);

        return view('pages.index', compact('page'));
    }

    /**
     * Update the page views count for a given page.
     *
     * @param object $page The page object to update the page views for.
     *
     * @return void
     */
    public function updatePageViews($page)
    {
        $page->increment('visits', 1);
        $page->save();
    }
}
