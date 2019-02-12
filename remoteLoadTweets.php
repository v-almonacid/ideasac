<?php

/**
 * An script that searches for new tweets containing the tag TRACKING_TAG
 *(defined in config.php) and store them in the backed through the API.
 *
 * Log writing could bring some issues
 * Verify permissions when using this script
 * (specially if it is called from a crontab)
 *
 */

require_once 'twitteroauth/twitteroauth.php';
require_once 'inc/config.php';

/**
 * search()
 * performs a twitter search using the twitter REST API
 *
 * @param (array) query in the form :
 *   array(
 *     "q" => "keyword",
 *     "count" => n
 *   );
 * @return (array) an array of tweets
 */
function search(array $query)
{
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

  /* search for tweets */
  $results = $connection->get('search/tweets', $query);

  //var_dump(get_object_vars($connection)); // debug ********

  /* check if query was ok */
  if ($connection->http_code != 200) {
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
  }
  else{
    return $results;
  }

}

// for debugging purposes
function time_elapsed()
{
    static $last = null;

    $now = microtime(true);

    if ($last != null) {
        echo '<!-- ' . ($now - $last) . ' -->';
    }

    $last = $now;
}


/* ==========================================================================
   Begin script
   ========================================================================== */

 //echo time_elapsed();

if (SITE_RUNNING)
{
  $query = array(
  "q" => TRACKING_TAG,
  "result_type" => "recent",
  "count" => 200
  );

  // to-do: instead of doing a twitter search, we should query our database
  $results = search($query);
}

//echo '<!-- After twitter search... -->';
//echo  time_elapsed();

$newTweetsFound = false;
$log_file = "./remote_loaded_tweets.log";

if ( isset($results) )
{

  $cont = 0;

  foreach ($results->statuses as $result)
  {
    $cont = $cont + 1;
    // check if tweet has already been stored
    $tweetID = $result->id;
    // context Connection: close is necessary to close the connection immediately and
    // therefore decrease latency
    $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
    $url = "http://ideas.wikiac.cl/api/index.php/mensajes/". $tweetID ."?type=tweet&id=" ;
    // think about using curl instead!
    $json = @file_get_contents($url, false, $context); // @ will supress warning message when resource not found

    //echo "<!-- After GET $cont ... -->";
    //echo  time_elapsed();

    // if tweet not in database -> store it
    if (empty($json)) {

      $tweet = json_decode($json);

      $data = array(
          'nombre' => $result->user->name,
          'email' => '',
          'texto' => $result->text,
          'is_tweet' => 'true',
          'tweet_id' => $result->id,
          'tweet_username' => $result->user->screen_name,
          'date' => $result->created_at
      );

      // Create context for a POST request
      $context = stream_context_create(array(
          'http' => array(
              // http://www.php.net/manual/en/context.http.php
              'method' => 'POST',
              'header' => "Content-Type: application/json\r\n" . "Connection: close\r\n",
              'content' => json_encode($data)
          )
      ));

      // Send the request
      $response = file_get_contents('http://ideas.wikiac.cl/api/index.php/mensajes', FALSE, $context);

      // echo "<!-- After POST $cont ... -->";
      echo "<p> Storing tweet $cont </p>";
      //echo  time_elapsed();
      var_dump(json_decode($response));
      echo "<br /><br />";


      // update log file
      // to-do: use data from $response to be sure evereything ok
      $log  = 'id:'.$result->id.' | '.date("F j, Y, g:i   a").PHP_EOL;

      if(!$f = fopen($log_file, "a")){
        echo "Error. Imposible insertar datos. <br />";
      } else {
        fwrite($f, $log);
        fclose($f);
      }

      $newTweetsFound = true;

    } // }if(empty(json)...

  } // }foreach

  if(!$newTweetsFound){
    // update log file
    // to-do: use data from $response to be sure evereything ok
    $log  = 'No new tweets to add in the db at '.date("F j, Y, g:i   a").PHP_EOL;

    if(!$f = fopen($log_file, "a")){
      echo "Error. Imposible insertar datos. <br />";
    } else {
      fwrite($f, $log);
      fclose($f);
    }

  }

} else
{
  // twitter search error
  exit(1);
}

?>
