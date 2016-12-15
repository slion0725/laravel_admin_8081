<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Menulink;

class HomeController extends Controller
{
    public function index()
    {
        $menulink = Menulink::distinct()->select('menu_id')->where('user_id',Auth::id())->with('menuitem')->get();
        $availablemenu = [];
        foreach($menulink as $d){
          $availablemenu[$d['menuitem']['url']] = $d['menuitem']['name'];
        }

        return view('admin.features.home',[
            'availablemenu' => $availablemenu
        ]);
    }
}
