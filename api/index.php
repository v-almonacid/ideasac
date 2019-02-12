<?php
/**
 * API v.0.1a
 * Author: Vicente Almonacid
 * last update:
 *
 * Version a para PHP>= 5.3
 * Probada usando:
 * PHP 5.3.10
 * Slim 2.42.
 * RedBeanPHP 4.03
 */

require 'Slim/Slim.php';
require_once('../inc/initdb.php');
require_once('../inc/ContentManager.class.php');
require_once('../inc/Usuario.class.php');

session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE);

// register Slim auto-loader
\Slim\Slim::registerAutoloader();

// set up database connection
// R::setup('mysql:host='.MYHOST.';dbname='.MYDB, MYUSR, MYPASS);
// R::freeze(true); // won't allow modifications on the db schema


// initialize app
$app = new \Slim\Slim();

// define classes for error handling
class ResourceNotFoundException extends Exception { }
class ResourceForbiddenException extends Exception { }


/**
 * handle GET requests for /mensajes
 *
 *
 * @return (json) mensaje(s)
 */
$app->get('/mensajes', function () use ($app) {

  try {
    $req = $app->request();
    $page = $req->get('page');
    $offset = $req->get('offset');
    $text = $req->get('text');
    $order = $req->get('order');

    $sql = '';

    if(isset($order)){
      switch ($order) {
        case 'date':
          $sql = $sql . ' ORDER BY msj_date DESC';
          break;
        case 'important':
          $sql = $sql . ' ORDER BY msj_nfavor - msj_ncontra DESC';
          break;
        default:
          $sql = $sql . ' ORDER BY msj_date DESC';
          break;
      }
    }

    if(isset($page)){
      if(isset($offset) && intval($offset) <= 100){ // never give more than 100 rows
        $from = (intval($page))*(intval($offset));
        $sql = $sql . " LIMIT {$from},{$offset}";
      } else{
        $from = (intval($page))*10;
        $sql = $sql . " LIMIT {$from},10";
      }

    } else {
      // query database for  50 items
      $sql = $sql . " LIMIT 50"; // default
    }

    $mensaje = R::find('mensaje', $sql);

    if ($mensaje) {
      if(isset($text)){
        switch ($text) {
          case 'html':
            $manager = new ContentManager();
            foreach ($mensaje as $m)
            {
              $m->msj_txt =  $manager->linkify($m->msj_txt);
            }
          default:
            // code...
            break;
        }
      }
      // send response header for JSON content type
      $app->response()->header('Content-Type', 'application/json');
      // return JSON-encoded response body with query results
      echo json_encode(R::exportAll($mensaje));
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    // return 404 server error
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
    echo($e->getMessage());
  }
});


/**
 * handle GET requests for /mensajes/:id (tested)
 *
 * @return (json) mensaje corresponding to id
 */
$app->get('/mensajes/:id', function ($id) use ($app) {
  try {
    $req = $app->request();
    $type = $req->get('type');
    if (isset($type) && $type == 'tweet') {
      // this route is used by script getNewTweets.php - tested
      $mensaje  = R::findOne( 'mensaje', ' msj_tweet_id = ? ', array($id));
    } else {
      // query database for single item. Tested
      $mensaje = R::findOne('mensaje', 'id=?', array($id));
    }

    if ($mensaje) {
      // if found, return JSON response
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($mensaje));
    } else {
      // else throw exception
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    // return 404 server error
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

/**
 * to-do: formatear links
 * handle GET requests for /mensajes/search/:q
 *
 * @return (json) mensajes
 */
$app->get('/mensajes/search/:q', function ($q) use ($app) {
  try {
    $req = $app->request();
    $text = $req->get('text');

    $q = '%' . $q . '%';
    // this route is used by script getNewTweets.php - tested
    $mensaje  = R::find( 'mensaje', 'msj_txt LIKE ? ORDER BY msj_date DESC LIMIT 50', array($q));

    if ($mensaje) {
      if(isset($text) && $text == 'html'){
        $manager = new ContentManager();
        foreach ($mensaje as $m)
        {
          $m->msj_txt =  $manager->linkify($m->msj_txt);
        }
      }
      // if found, return JSON response
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($mensaje));
    } else {
      // else throw exception
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    // return 404 server error
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


/**
 * handle POST requests for /mensajes
 * to-do: protect method from bots! no auh needed
 *
 * @return (json) new mensaje, if no error
 */
$app->post('/mensajes', function () use ($app) {
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body);

    // create new bean to store new message
    $mensaje = R::dispense('mensaje');

    // if is_tweet=='true', we assume that input data comes
    // from a twitter API request
    if ((string)$input->is_tweet == 'true') {
      $mensaje->msj_is_tweet = "true";
      $mensaje->msj_tweet_id = intval($input->tweet_id);
      $mensaje->msj_tweet_username = (string) $input->tweet_username;
      // convert twitter date to our db format
      $tweet_date = date( 'Y-m-d H:i:s', strtotime((string) $input->date) );
      $mensaje->msj_date = $tweet_date;
      $mensaje->msj_nombre = (string)$input->nombre;

    } else {
      // we assume input data comes from web form
      $mensaje->msj_is_tweet = "false";
      $mensaje->msj_date = (string)date("Y-m-d H:i:s");
      if( isset($_SESSION['id']) && $_SESSION['id'] > 0){ // means user logged in
        $uid = $_SESSION['id']; // get user id
        $usuario = R::load( 'usuario', $uid );
        $mensaje->usuario = $usuario;
        // these two are redundant
        $mensaje->msj_nombre = $usuario->u_nombre;
        $mensaje->msj_email = $usuario->u_email;
      } else {
        $mensaje->msj_nombre = (string)$input->nombre;
        $mensaje->msj_email = (string)$input->email;
      }
    }
    // store the new message in the DB
    $mensaje->msj_txt = (string)$input->texto;
    $id = R::store($mensaje);

    // search for tags
    $txt = (string)$input->texto;
    $txt = strtolower($txt);
    $tagBeans = R::findAll('tag');
    foreach ($tagBeans as $tag)
    {
      if (strpos($txt,$tag->tag)) {
        $tag->count = $tag->count + 1;
        R::store($tag);

        // $mencion = R::dispense('mencion');
        // $mencion->mensaje = $mensaje;
        // $mencion->tag = $tag;
        // R::store($mencion);

      }
    }

    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($mensaje));
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  } catch (ResourceForbiddenException $e) {
    $app->response()->status(403);
  }
});


/**
 * handle PUT requests for /mensajes/:id
 *
 * @return (json) updated mensaje, if no error
 */
$app->put('/mensajes/:id', function () use ($app) {
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body);

    $mid = $input->mid; // get message id
    if( isset($_SESSION['id']) && $_SESSION['id'] > 0){ // means user logged in
      $uid = $_SESSION['id']; // get user id
    } else {
      throw new ResourceForbiddenException();
    }

    // check type, only "like" or "dislike accepted"
    $type = $input->type;
    if ($type != 'like' && $type != 'dislike'){
      throw new Exception("Non valid input value for <type>", 1);
    }

    // load beans
    $mensaje = R::load( 'mensaje', $mid );
    $usuario = R::load( 'usuario', $uid );

    if ($mensaje && $usuario){

      $voto = R::findOne( "voto", "mensaje_id=? AND usuario_id=?", array($mid, $uid) );

      if( !$voto ){
        // if voto doesn't exist
        // create new bean to store new voto
        $voto = R::dispense('voto');
        $voto->mensaje = $mensaje;
        $voto->usuario = $usuario;
        switch($type){
          case "like":
            $mensaje->msj_nfavor = $mensaje->msj_nfavor + 1;
            break;
          case "dislike":
            $mensaje->msj_ncontra = $mensaje->msj_ncontra + 1;
            break;
        }
      } elseif($voto->v_type != $input->type)
      {
        // voto exists and user has changed his mind
        switch($type){
          case "like":
            $mensaje->msj_nfavor = $mensaje->msj_nfavor + 1;
            $mensaje->msj_ncontra = $mensaje->msj_ncontra - 1;
            break;
          case "dislike":
            $mensaje->msj_nfavor = $mensaje->msj_nfavor - 1;
            $mensaje->msj_ncontra = $mensaje->msj_ncontra + 1;
            break;
        }
      }
      $voto->v_type = $type;
      $voto->v_date = (string)date("Y-m-d H:i:s");
      R::store($voto);
      R::store($mensaje);

      // return JSON-encoded response body
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($mensaje));
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e){
    $app->response()->status(404);
  } catch (ResourceForbiddenException $e){
    $app->response()->status(403);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

/**
 * handle POST requests for /votos ********** on going
 *
 * @return (json) new voto, if wasn't already created
 */
$app->post('/votos', function () use ($app) {
  try {
    // get and decode JSON request body
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body);

    $mid = $input->mid; // message id

    if( isset($_SESSION['id']) && $_SESSION['id'] > 0){ // means user logged in
      $uid = $_SESSION['id'];; // user id
    } else {
      throw new ResourceForbiddenException();
    }

    $voto = R::findOne( "voto", "m_id=? AND u_id=?", array($mid, $uid) );

    if( !$voto ){
      // create new bean to store new voto
      $voto = R::dispense('voto');
      $voto->m_id = $mid;
      $voto->u_id = $uid;
      $voto->v_date = (string)date("Y-m-d H:i:s");
    } else {

    }

    $voto->v_type = $input->type;

    // store vote in the DB
    $id = R::store($voto);

    // return JSON-encoded response body
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(R::exportAll($voto));
  } catch (ResourceForbiddenException $e){
    $app->response()->status(403);
  }
  catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

/**
 * handle GET requests for /cloud
 *
 * @return (json) cloud tag
 */
$app->get('/cloud', function () use ($app) {
  try {
    $req = $app->request();
    $maxFontSize = $req->get('max_font_size');
    $minFontSize = $req->get('min_font_size');

    // query database to get outstanding messages
    $tagBeans = R::find( 'tag', 'WHERE count > 0 ORDER BY count DESC LIMIT 150' );
    if ($tagBeans) {
      $manager = new ContentManager();
      if(isset($maxFontSize) && isset($minFontSize)){
        echo $manager->cloudFormatter($tagBeans, $minFontSize, $maxFontSize);
      } else {
        echo $manager->cloudFormatter($tagBeans);
      }
    } else {
      // else throw exception
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    // return 404 server error
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

// run app
$app->run();
