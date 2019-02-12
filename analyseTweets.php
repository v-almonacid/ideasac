<?php
/**
 *
 *
 */

require_once 'twitteroauth/twitteroauth.php';
require './api/Slim/Slim.php';
require_once('./inc/initdb.php');
require_once('./inc/ContentManager.class.php');


error_reporting(E_ERROR | E_WARNING | E_PARSE);

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

// src: http://stackoverflow.com/questions/10589889/returning-header-as-array-using-curl
function get_headers_from_curl_response($response)
{
    $headers = array();

    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

    foreach (explode("\r\n", $header_text) as $i => $line)
        if ($i === 0)
            $headers['http_code'] = $line;
        else
        {
            list ($key, $value) = explode(': ', $line);

            $headers[$key] = $value;
        }

    return $headers;
}


/* ==========================================================================
   Begin script
   ========================================================================== */

 //echo time_elapsed();

$urlPattern = ContentManager::getUrlPattern();

$query = array(
"q" => "#asambleaconstituyente", // to-do: use the right hashtag
//"result_type" => "recent",
"count" => 100
);

// to-do: instead of doing a twitter search, we should query our database
$results = search($query);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HEADER, true); //if you want headers

if ( isset($results) )
{

  $countUrl = 0;
  $countAll = 0;


  foreach ($results->statuses as $result)
  {
    //$cont = $cont + 1;
    // check if tweet has already been stores
    $tweetID = $result->id;

    // context Connection: close is necessary to close the connection immediately and
    // therefore decrease latency
    // $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
    // $url = SITE_URL . "/api/mensajes/". $tweetID ."?type=tweet&id=" ;
    // // think about using curl instead!
    // $json = @file_get_contents($url, false, $context); // @ will supress warning message when resource not found
    $text = (string)$result->text;

    // search for urls
    if($nmatches = preg_match_all("#$urlPattern#i", $text, $matches)) {

      $countUrl = $countUrl + 1;
      //echo $matches[0][0].'<br>';
      curl_setopt($ch, CURLOPT_URL,$matches[0][0]);

      $output = curl_exec($ch);
      // echo $output . '<br>';
      $headers = get_headers_from_curl_response($output);

      echo $headers['location'];
      echo '<br>';
      // foreach ($matches as $matchedUrl) {
      //   //echo $matchedUrl . '<br>';
      // }

    }

    $countAll = $countAll + 1;
  }

  echo 'Count URLs: ' . $countUrl. '<br>';
  echo 'Count tweets: ' . $countAll. '<br>';

} else
{
  echo 'No results';
  // twitter search error
  exit(1);
}

?>
