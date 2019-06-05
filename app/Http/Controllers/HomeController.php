<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // Get Variation from Session
        $this->middleware(function ($request, $next) {

            $this->variation = session()->get('variation');
            $this->featureEnable = session()->get('featureEnable');
            $this->specialMenu = session()->get('specialMenu');

            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('experiment')->withVariation($this->variation)
            ->withFeatureEnable($this->featureEnable)
            ->withspecialMenu($this->specialMenu);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->session()->flash('success', 'The order are coming!');


        return view('order_complete');
    }
}
