<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('site.pages.index');
    }
    public function get_popup_data($type)
    {

        $view= view('site.pages.popup_data',compact('type'))->render();
        return response()->json(['status'=>true,'type'=>trans('site.nav.'.$type),'html'=>$view]);
    }
}
