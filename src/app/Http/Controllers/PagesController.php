<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Pages;

use App\Events\Statistics;

class PagesController extends Controller {

    public function index(Request $request) {
        $pageSlug = (isset($request->slug)) ? $request->slug : false;

        if (env("APP_ENV") == "production") {
            return redirect("https://fluffici.eu/pages/" .  $pageSlug);
        }

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
