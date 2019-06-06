<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Log;
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

		/**
		 * Create an Optimizely client with the default event dispatcher.
		 * Please note, if not provided it will default to this event dispatcher.
		 */
		$this->optimizelyClient = new Optimizely($datafile, new DefaultEventDispatcher(), null, new DefaultErrorHandler());

		// Set user id
		$this->userId = $this->getUserId();

		// Add a ACTIVATE listener
		// $activateId = $this->optimizelyClient->notificationCenter->addNotificationListener(
		// 	NotificationType::ACTIVATE,
		// 	$this->onActivate()
		// );

		// Add a TRACK listener
		// $trackId = $this->optimizelyClient->notificationCenter->addNotificationListener(
		// 	NotificationType::TRACK,
		// 	$this->onTrack()
		// );

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
		if ($special !== null && $special === '1') {
			$this->optimizelyClient->track('order_special', $this->userId, $attributes);
		}
	}

	function onActivate($experiment, $userId, $attributes, $variation)
	{
		echo 'activate experiment ' . $experiment->getKey() . ' for user ' . $userId;
	}

	function onTrack($eventKey, $userId, $attributes, $eventTags, $event)
	{
		echo 'conversion event ' . $eventKey . ' for user ' . $userId;
	}

}
