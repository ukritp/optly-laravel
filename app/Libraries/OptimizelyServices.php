<?php
namespace App\Libraries;

use Optimizely\Optimizely;
use Optimizely\Event\Dispatcher\DefaultEventDispatcher;
use Optimizely\ErrorHandler\DefaultErrorHandler;
use Optimizely\Notification\NotificationType;
use Illuminate\Support\Facades\Hash;
use Cookie;

class OptimizelyServices
{

	private $url;
	private $environment;

    public function __construct($request)
    {
		$this->request = $request;

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

        // Instantiate an Optimizely client
		// $this->optimizelyClient = new Optimizely($datafile);

		/**
		 * Create an Optimizely client with the default event dispatcher.
		 * Please note, if not provided it will default to this event dispatcher.
		 */
		$this->optimizelyClient = new Optimizely($datafile, new DefaultEventDispatcher(), null, new DefaultErrorHandler());

		// dd($this->optimizelyClient);
		$this->userId = $this->getUserId();
	}

	public function getUserId()
    {
    	// Get cookie to see if the userId exist
		$userId = Cookie::get('userId');

        // If there is no cookie, create userId using Hash
        if ($userId === null) {
			$userId =  str_random(20);
            Cookie::queue('userId', $userId, 10);
		}

		return $userId;
    }

    public function customEvents($attributes)
    {
		$appetizer = $this->request->appetizer;
		$main = $this->request->main;
		$dessert = $this->request->dessert;
		$special = $this->request->special;

		if ($appetizer) {
			$this->optimizelyClient->track('order_appitizer', $this->userId, $attributes);
		}
		if ($main) {
			$this->optimizelyClient->track('order_main', $this->userId, $attributes);
		}
		if ($dessert) {
			$this->optimizelyClient->track('order_dessert', $this->userId, $attributes);
		}
		if ($special !== null && $special === 1) {
			$this->optimizelyClient->track('order_special', $this->userId, $attributes);
		}
	}

	public function onDecision($type, $userId, $attributes, $decisionInfo)
	{
		// $decisionInfo will have information based on $type
		if ($type == 'ab-test') {
			// Access experiment key and variation key
			print($decisionInfo->experimentKey);
			print($decisionInfo->variationKey);
		}

		if ($type == 'feature') {
			// Access information about feature
			print($decisionInfo->featureKey);
			print($decisionInfo->featureEnabled);
			print($decisionInfo->source);
		}

		if ($type == 'feature-variable') {
			// Access information about feature's variable
			print($decisionInfo->featureKey);
			print($decisionInfo->featureEnabled);
			print($decisionInfo->source);
			print($decisionInfo->variableKey);
			print($decisionInfo->variableType);
			print($decisionInfo->variableValue);
		}
	}

}
