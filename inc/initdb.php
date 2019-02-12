<?php
/* 
 * before calling this script, config.php must be included
 * Always call this script using require_once()
 */
$base = dirname( __FILE__ ); 
require_once("{$base}/config.php");
require_once ("{$base}/../api/RedBean/rb.phar");

//$isConnected = R::testConnection() works only in ReadBean 4.1+
R::setup('mysql:host='.MYHOST.';dbname='.MYDB, MYUSR, MYPASS);
// R::freeze(true); // won't allow modifications on the db schema

?>
