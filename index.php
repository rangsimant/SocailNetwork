<?php
use SocialNetwork\Facebook;

require_once "app/start.php";

$fb = new Facebook(
		$app_id = '1059313717415435',
		$app_secret = 'aeae0c6e2ba3f32b3f5d2da93d8a9ea7',
		$api_version = 'v2.6'
	);

$fb->setAccessToken('EAAPDcLHVDgsBALjzloDd8VdACmTXIfZBbZCAcrdUbhbXtZC8eOBlz0PD36DzPZCpvKeKzaD80zf6UPPZCeNZAypNEOysZAK2MIBO0kD1aaoDmugW6nlSo8PwbDYaoPwaKegYSWQKU5T5H9ROlSbkZBMknPEvFAZCNOb8MD1RrsVJdxAZDZD
');

$fields = ['id', 'message', 'created_time', 'updated_time', 'from', 'comments', 'attachments'];
$limit = 100;

$response = $fb->getFeed('OverwatchThai', $fields, $limit);

$until = new DateTime('2016-06-15T23:59:59+07:00');
$feeds = $fb->getNowToUntil($response, $until);

echo "<pre>";
print_r($feeds);
