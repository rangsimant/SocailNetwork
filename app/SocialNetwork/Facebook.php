<?php namespace SocialNetwork;

use Facebook\Facebook as FacebookSDK;
use Facebook\FacebookRespons;
use DateTime;
use DateTimeZone;

class Facebook 
{
	
	function __construct($app_id, $app_secret, $api_version = 'v2.5')
	{
		$this->setApplication($app_id, $app_secret, $api_version);
	}

	public function setApplication($app_id, $app_secret, $api_version = 'v2.5')
	{
		$this->fb = new FacebookSDK([
			'app_id' => $app_id,
			'app_secret' => $app_secret,
			'default_graph_version' => $api_version,
			]);
	}

	public function setAccessToken($access_token)
	{
		$this->fb->setDefaultAccessToken($access_token);
	}

	public function getFeed($endpoint = '/me', $fields, $limit = 25)
	{
		if (is_array($fields)) {
			$fields = implode(',', $fields);
		}

		$endpoint = $endpoint.'/feed?fields='.$fields.'&limit='.$limit;

		try {
		  $response = $this->fb->get($endpoint);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		return $response;
	}

	public function getLoginUrl($url_callback, $permissions = ['email', 'user_likes'])
	{
		$helper = $this->fb->getRedirectLoginHelper();
		return $helper->getLoginUrl($url_callback, $permissions);
	}

	public function getNowToUntil($feed_edge, $until)
	{
		$feed = array();
		$response = $feed_edge->getGraphEdge();
		do {
			foreach ($response as $post) {
				$post = $post->asArray();
				$created_time = new DateTime($post['created_time']->format('c'));
				$created_time->setTimezone(new DateTimeZone('Asia/Bangkok'));

				if ($created_time->format('c') <= $until->format('c')) {
					break;
				}

			  	$feed[] = $post;
			}
		} while ($response = $this->fb->next($response));

		return $feed;
	}
}