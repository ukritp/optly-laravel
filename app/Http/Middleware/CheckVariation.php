<?php

namespace App\Http\Middleware;

use Optimizely\Optimizely;
use Illuminate\Support\Facades\Hash;
use Closure;
use Cookie;


class CheckVariation
{

    private $url;
    private $environment;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $environment = env('APP_ENV');

        $url['dev'] = 'https://cdn.optimizely.com/datafiles/AP6qpFrWuy82GgykcMwj7M.json';
        $url['staging'] = 'https://cdn.optimizely.com/datafiles/Pw5btoKfhCxpwvEycGdBqJ.json';
        $url['prod'] = 'https://cdn.optimizely.com/datafiles/CR8xgP24g41TkMLShcGn1M.json';

        // Check environment and get data file
        if ($environment === 'local') {
            $datafile = file_get_contents($url['dev']);
        } else if ($environment === 'staging') {
            $datafile = file_get_contents($url['staging']);
        } else if ($environment === 'production') {
            $datafile = file_get_contents($url['prod']);
        }

        // dd($datafile);

        // Instantiate an Optimizely client
        $optimizelyClient = new Optimizely($datafile);

        // Get cookie to see if the userId exist
        $cookie = $request->cookie('userId');
        $userId = $cookie;

        // dd($userId);

        // If there is no cookie, create userId using Hash
        if ($cookie === null) {
            $userId = Hash::make('plain-text');
            // $cookie = cookie('userId', $userId, 10);
            Cookie::queue('userId', $userId, 10);
        }

        // Get variation
        $variation = $optimizelyClient->activate('fullstack_assessment', $userId);
        // dd($variation);

        $enabled = $optimizelyClient->isFeatureEnabled('special_menu_feature', $userId);
        $specialMenu['appetizer'] = '';
        $specialMenu['main'] = '';
        $specialMenu['dessert'] = '';
        if ($enabled) {
            $specialMenu['appetizer'] = $optimizelyClient->getFeatureVariableString('special_menu_feature', 'appetizer', $userId);
            $specialMenu['main'] = $optimizelyClient->getFeatureVariableString('special_menu_feature', 'main', $userId);
            $specialMenu['dessert'] = $optimizelyClient->getFeatureVariableString('special_menu_feature', 'dessert', $userId);
        }

        // Add variation into request session
        // $request->attributes->add(['variation' => $variation] );
        $request->session()->put('variation', $variation);
        $request->session()->put('featureEnable', $enabled);
        $request->session()->put('specialMenu', $specialMenu);
        // dd($request);

        return $next($request);
    }
}
