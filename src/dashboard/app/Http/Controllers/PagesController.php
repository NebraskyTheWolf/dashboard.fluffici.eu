<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Pages;

use App\Events\Statistics;

class PagesController extends Controller {
    
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
    }
}
