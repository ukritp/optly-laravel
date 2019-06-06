<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\OptimizelyServices;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, OptimizelyServices $optimizely)
    {
        // Get Optimizely Client
        $this->optimizelyClient = $optimizely->optimizelyClient;

        // Get User ID and Set Audience
        $userId = $optimizely->userId;
        $this->attributes = ['device' => 'desktop'];

        // Activat experiment and get variation
        $this->variation = $this->optimizelyClient->activate('fullstack_assessment', $userId, $this->attributes);

        // Check if feature enable
        $this->featureEnabled = $this->optimizelyClient->isFeatureEnabled('special_menu_feature', $userId, $this->attributes);
        $this->specialMenu['appetizer'] = '';
        $this->specialMenu['main'] = '';
        $this->specialMenu['dessert'] = '';

        // Get Feature menu if enabled
        if ($this->featureEnabled) {
            $this->specialMenu['appetizer'] = $this->optimizelyClient->getFeatureVariableString('special_menu_feature', 'appetizer', $userId, $this->attributes);
            $this->specialMenu['main'] = $this->optimizelyClient->getFeatureVariableString('special_menu_feature', 'main', $userId, $this->attributes);
            $this->specialMenu['dessert'] = $this->optimizelyClient->getFeatureVariableString('special_menu_feature', 'dessert', $userId, $this->attributes);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('experiment')->withVariation($this->variation)
            ->withFeatureEnabled($this->featureEnabled)
            ->withspecialMenu($this->specialMenu);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OptimizelyServices $optimizely)
    {
        // Track custom events
        $optimizely->customEvents($this->attributes);

        return view('order_complete');
    }
}
