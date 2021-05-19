<?php

require_once('TwitterAPIExchange.php');
/** Set access tokens here - see: https://dev.twitter.com/apps/ * */
$settings = array(
    'oauth_access_token' => "",
    'oauth_access_token_secret' => "",
    'consumer_key' => "",
    'consumer_secret' => ""
);

$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = "GET";
if (isset($_GET['h'])) {
    $hashtag = preg_replace("/[^A-Za-z0-9_]/", '', $_GET['h']);
} else {
    $hashtag = "larangan mudik lebaran";
}
if (isset($_GET['t']) && is_numeric($_GET['t'])) {
    $count = $_GET['t'];
} else {
    $count = 20;
}
$getfield = "?q=$hashtag&count=$count&tweet_mode=extended";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest(), $assoc = TRUE);
if (array_key_exists("errors", $string)) {
    echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>" . $string[errors][0]["message"] . "</em></p>";
    exit();
}
echo "<h2>".$count." Result: ". $hashtag."</h2><br><br>";
foreach ($string['statuses'] as $items) {
    echo "Time and Date of Tweet: " . $items['created_at'] . "<br />";
    echo "Tweet: " . $items['full_text'] . "<br />";
    echo "Tweeted by: " . $items['user']['name'] . "<br />";
    echo "Screen name: " . $items['user']['screen_name'] . "<br />";
    echo "Followers: " . $items['user']['followers_count'] . "<br />";
    echo "Friends: " . $items['user']['friends_count'] . "<br />";
    echo "Listed: " . $items['user']['listed_count'] . "<br /><hr />";
}
?>
