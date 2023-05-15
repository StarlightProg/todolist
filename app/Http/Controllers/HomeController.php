<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tasks = Tasks::where('user_id',Auth::user()->id)->get();
        $tags = Tags::where('user_id',Auth::user()->id)->get();

        Session::put('tasks', $tasks);
        Session::put('tags', $tags);
        
        return view('home',compact('tasks','tags'));
    }
}
