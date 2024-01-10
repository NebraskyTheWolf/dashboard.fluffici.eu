<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Pages;

class PagesController extends Controller {
    
    public function index(Request $request) {
        $pageSlug = (isset($request->pageslug)) ? $request->pageslug : false;

        if ($pageSlug == false) {
            return view('error.404');
        }

        $page = Pages::where('page_slug', $pageSlug)->firstOrFail();
        $page->increment('visits', 1);
        $page->save();
        
        return view('pages.index', compact('page'));
    }
}
