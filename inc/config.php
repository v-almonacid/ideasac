<?php

if(!defined('MYHOST')){
	define('MYHOST', 'localhost');
	define('MYUSR', '');
	define('MYPASS','');
	define('MYDB','ideasac');
	define('MYDBMS','MYSQL');
}

// twitter oauth
define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');
define('ACCESS_TOKEN', '');
define('ACCESS_TOKEN_SECRET', '');

// if the database config is set up and connection to twitter api is ok, then
// this variable should be set to true
define('SITE_RUNNING', true);

define('SITE_URL', 'http://ideasac.localhost');

define('TRACKING_TAG', '#ideasAC');

?>
