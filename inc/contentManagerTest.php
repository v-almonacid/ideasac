<?php
/**
 *  
 * Test script for ContentManager.class.php
 * Author: Vicente Almonacid
 * last update:
 *
 */

include("./ContentManager.class.php");
$base = dirname( __FILE__ ); 
require_once("{$base}/initdb.php");

$manager = new ContentManager();

// timelineFormatter
echo "<p><h3>Testing timelineFormatter() method</h3>";
$m = '[{"id":"370","msj_is_tweet":"true","msj_tweet_id":"479955960275144705","msj_tweet_username":"barrocoaustral","msj_nombre":"CIUDADANO BARROCO ","msj_email":null,"msj_txt":"\u00a1GACETA DE LA #ASAMBLEACONSTITUYENTE est\u00e1 disponible! http:\/\/t.co\/yHt7imwQ19 Gracias a @voz_desplazados","msj_nfavor":"0","msj_ncontra":"0","msj_date":"2014-06-20 13:56:32","usuario_id":null},{"id":"369","msj_is_tweet":"false","msj_tweet_id":null,"msj_tweet_username":null,"msj_nombre":"nena","msj_email":"nena@mail.com","msj_txt":"sdlkmdfkj kjndfkjn","msj_nfavor":"0","msj_ncontra":"0","msj_date":"2014-06-20 09:22:34","usuario_id":null}]';
$mArray = json_decode($m);
// var_dump($mArray);
echo ($manager->timelineFormatter($mArray));

// tag array
echo "<p><h3>Testing if tag array is well defined</h3>";
echo("Number of elements in tag array: " . count($manager->getTags()));


// testing cloudFormatter
$tagBeans = R::find( 'tag', 'ORDER BY count DESC LIMIT 10' );
echo "<p><h3>Testing cloud formatter</h3>";
echo ($manager->cloudFormatter($tagBeans));

// testing linkify
echo "<p><h3>Testing linkify</h3>";
$texto = "@PROLOSLAGOS: PULSO: http://t.co/THr3piH8Bo vía @pulso_tw @LosProgresistas creemos...";
echo("<p>Texto sin formatear: " . $texto);
echo("<p>Texto formateado: " . $manager->linkify($texto));

