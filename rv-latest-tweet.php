<?php
/*
Plugin Name: Latest Tweet Shortcode
Plugin URI: http://radicle.vision
Description: Creates latest_tweet shortcode
Author: Drew Hornbein
Version: 0.1
Author URI: http://radicle.vision
*/

function rv_get_latest_tweet($offset, $user){

  require_once('TwitterAPISettings.php');
  require_once('TwitterAPIExchange.php');
  
  $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
  $requestMethod = 'GET';
  $getfield = '?screen_name=@' . $user . '&exclude_replies=true';
  $twitter = new TwitterAPIExchange($settings);
  $results = $twitter->setGetfield($getfield)
      ->buildOauth($url, $requestMethod)
      ->performRequest();
      $results = json_decode($results,true);
      if(isset($results[$offset])){
        $tweet = array();
        $tweet["id"] = $results[$offset]["id"];
        $tweet["text"] = $results[$offset]["text"];
        $tweet["name"] = $results[$offset]["user"]["name"];
        $date = date("F d, Y", strtotime( $results[$offset]["created_at"]));
        $tweet["date"] = $date;
      }

  /*$out = '<blockquote class="twitter-tweet" data-lang="en-gb">
    <p class="tweetBody" lang="en" dir="ltr">' . $tweet['text'] .'</p>&mdash; '. $tweet['name'].' (@' . $user . ')
    <a class="tweetDate" href="https://twitter.com/' . $user . '/status/' . $tweet['id'] .'">' . $tweet['date'] .'</a>
  </blockquote>';*/

  $out2 = 'https://twitter.com/' . $user . '/status/' . $tweet['id'];
  
  return $out2;

}

function rv_get_latest_tweet_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'offset' => 0,
        'user' => 'hornbein'
    ), $atts );
    
    global $wp_embed;
    return $wp_embed->run_shortcode('[embed]' . rv_get_latest_tweet($a['offset'],$a['user']) . '[/embed]');
}
add_shortcode( 'last_tweet', 'rv_get_latest_tweet_shortcode' );