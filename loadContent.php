<?php

require_once 'twitteroauth/twitteroauth.php';
require_once 'inc/config.php';


/**
 * getCloudElements()
 * generates the cloud from provided data according to the awesomeCloud format
 *
 * @param (array) (data) array containing a frequency value for each word key
 * @return (array) (cloud) a string in the format:
 *                       "  <span data-weight="weight1">word1</span>
 *                          <span data-weight="weight2">word2</span> ..."
 */
function getCloudElements($data = array(), $minFontSize = 10, $maxFontSize = 40 )
{

  $minimumCount = min( array_values( $data ) );
  $maximumCount = max( array_values( $data ) );
  $spread       = $maximumCount - $minimumCount;
  $cloudHTML    = '';
  $cloudTags    = array();

  $spread == 0 && $spread = 1;


  foreach( $data as $tag => $count )
  {
    $size = $minFontSize + ( $count - $minimumCount )
      * ( $maxFontSize - $minFontSize ) / $spread;
    $cloudTags[] = '<span data-weight="' . floor( $size )
                   . '" title="\'' . $tag  . '\' ha tenido ' . $count . ' menciones">'
                   . $tag . '</span>';
  }

  return join( "\n", $cloudTags ) . "\n";

}

/* ==========================================================================
   Begin script
   ========================================================================== */

if (SITE_RUNNING)
{

  // get all messages
  // remember, header 'Connection: close' is necessary to close the connection immediately
  $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
  $json = file_get_contents("http://ideasac.localhost/api/mensajes?page=0&offset=100&order=date", false, $context);
  $results = json_decode($json);

  // Store frequency of words in an array
  $freqData = array();
  // Store posts in html format
  $arr = array();
  /* will be used to search for urls and ingore them in the cloud */
  $pattern = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';

  if ( isset($results) )
  {
    foreach ($results as $result)
    {

      $txt = $result->msj_txt;

      // search for urls
      if($nmatches = preg_match_all("#$pattern#i", $txt, $matches)) {
        // make them shorter...
        for ($i = 0; $i < $nmatches; $i++) {
          $shortUrl = strlen($matches[0][$i]) > 20 ? substr($matches[0][$i],0,20)."..." : $matches[0][$i];
          // convert urls into html hyperlinks
          $hyperlink = '<a href="' .$matches[0][$i]. '">' .$shortUrl. '</a>';
          $txt = str_replace($matches[0][$i], $hyperlink, $txt);
        }
      }

      $dateObj =  new  DateTime($result->msj_date);

      $arr[] =  "<div class=\"item\">\n" .
                "  <div class=\"tweet-wrapper\">\n" .
                "    <div class=\"tweet-header\">\n" .
                "      <span class=\"time\">". $dateObj->format("d-m-Y") ."</span>\n" .
                "      <span class=\"counter\">#". $result->id ."</span>\n" .
                "      <span class=\"votos\"> \n" .
                "        <a href=\"#\" id=\"like-b-".$result->id."\" class=\"l-b l-".$result->id."\">\n" .
                "          <i class=\"fa fa-thumbs-up\"></i> \n" .
                "            <span class=\"nfavor\">".$result->msj_nfavor."</span> \n" .
                "       </a>\n" .
                " · " .
                "       <a href=\"#\" id=\"dislike-b-".$result->id."\" class=\"l-b d-".$result->id."\">\n" .
                "          <i class=\"fa fa-thumbs-down\"></i> \n" .
                "            <span class=\"ncontra\">".$result->msj_ncontra."</span> \n" .
                "       </a>\n" .
                "     </span>\n" .
                "    </div>\n" .
                "    <blockquote>".$txt."</blockquote>\n" .
                "    <cite>" . $result->msj_nombre . "</cite>\n" .
                "  </div>\n" .
                "</div>";

      $cleanText = preg_replace("#$pattern#i", '', $result->msj_txt);

      // Get individual words and build a frequency table
      foreach( str_word_count( $cleanText, 1 ) as $word )
      {
        if(strlen($word) > 3)
        {
          $word = strtolower ( $word );
          // For each word found in the frequency table, increment its value by one
          array_key_exists( $word, $freqData ) ? $freqData[ $word ]++ : $freqData[ $word ] = 1;
        }
      }
    }

    $posts = join( "\n", $arr ) . "\n";

  } else
  {
    // there was a problem accesing the database
  }


  // now query "mensajes destacados"
  // similar steps than before

  // reset array to store posts "destacados"
  $arr = array();

  // get 'mensajes destacados'
  $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
  $json = file_get_contents("http://ideasac.localhost/api/destacados", false, $context);
  $destacados = json_decode($json);


  if ( isset($destacados) )
  {
    foreach ($destacados as $item)
    {

      $txt = $item->msj_txt;

      // search for urls
      if($nmatches = preg_match_all("#$pattern#i", $txt, $matches)) {
        // make them shorter...
        for ($i = 0; $i < $nmatches; $i++) {
          $shortUrl = strlen($matches[0][$i]) > 20 ? substr($matches[0][$i],0,15)."..." : $matches[0][$i];
          // convert urls into html hyperlinks
          $hyperlink = '<a href="' .$matches[0][$i]. '">' .$shortUrl. '</a>';
          $txt = str_replace($matches[0][$i], $hyperlink, $txt);
        }
      }

      $arr[] =  "<div class=\"item\">\n" .
                "  <div class=\"tweet-wrapper\">\n" .
                "    <div class=\"votos\"> \n" .
                "      <a href=\"#\" id=\"like-b-".$item->id."\" class=\"l-b l-".$item->id."\">\n" .
                "        <i class=\"fa fa-thumbs-up\"></i>\n" .
                "          <span class=\"nfavor\">".$item->msj_nfavor."</span>\n" .
                "      </a>\n" .
                " · " .
                "      <a href=\"#\" id=\"dislike-b-".$item->id."\" class=\"l-b d-".$item->id."\">\n" .
                "        <i class=\"fa fa-thumbs-down\"></i>\n" .
                "          <span class=\"ncontra\">".$item->msj_ncontra."</span>\n" .
                "      </a>\n" .
                "    </div>\n" .
                "    <blockquote>".$txt."<cite>".$item->msj_nombre."</cite></blockquote>" .
                "  </div>\n" .
                "</div>";

    }

    $posts_destacados  = join( "\n", $arr ) . "\n";

  } else
  {
    // error al cargar informacion desde la base de datos
    // cargar informacion en "cache"
  }

}

?>
