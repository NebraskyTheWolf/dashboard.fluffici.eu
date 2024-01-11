<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Pages;

class HomeController extends Controller {
    
    public function index(Request $request) {

        $result = $this->get('/api/test');

        return [
            'status' => true,
            'message' => 'test',
            'backend' => $result
        ];
    }
}
