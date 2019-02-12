<?php

$base = dirname( __FILE__ );
require_once("{$base}/initdb.php");

class Usuario
{

	public function __construct()
	{
		// $this->connectClass();
	}

	public function __destruct()
  {
    // R::close();
  }


	public function connectClass()
	{
		//$isConnected = R::testConnection() works only in ReadBean 4.1+
		// R::setup('mysql:host='.MYHOST.';dbname='.MYDB, MYUSR, MYPASS);
		// R::freeze(true); // won't allow modifications on the db schema
	}

	//obtiene array del usuario a partir de su email
	function getByEmail($email)
	{
		$user = R::findOne('usuario', 'u_email=?', array($email));
		return $user;
	}

	function getById($id = 0)
	{
		if( $id == 0){
			if( $this->isLogged() ){
				$id = $_SESSION['id'];
			} else {
				return null;
			}
		}
		$user = R::findOne('usuario', 'id=?', array($id));
		return $user;
	}


	//Devuelve id si lo agrega correctamente, 0 si hay error
	public function addUser($nombre, $email, $pass='', $twitterAuth='false', $twitterUser='', $timeZoneOffset='' )
	{
		if(isset($nombre) && isset($email) && !($this->getByEmail($email)) ) {
			//$this->connectClass();
			$usuario = R::dispense('usuario');
    	// if twitterAuth=='true', user is using twitter authentication
	    if ($twitterAuth == 'true') {
	      $usuario->u_twitter_username = "true";
	      $usuario->u_twitter_username = $twitterUser;
	    } else {
	    	$usuario->u_hpass = sha1($pass);
	    }
	    $usuario->u_nombre = $nombre;
	    $usuario->u_email = $email;
      $usuario->u_tz_offset = $timeZoneOffset;
      $id = R::store($usuario);
      //R::close();
			return $id;
		}
		return 0;
	}

	public function deleteById($id)
	{
		$user = R::load( 'usuario', $id );
		R::trash( $user );
		return 0;
	}

	public function login($id_usuario)
	{
		$_SESSION['id'] = $id_usuario;
		return 1;
	}

	public function logout()
	{
		$_SESSION['id'] = 0;
		return 1;
	}

	public function isLogged()
	{
		if(isset($_SESSION['id']))
			return ( $_SESSION['id'] > 0 );
		else
			return false;
	}

	public function checkPass($email,$pass)
	{
		if( isset($email) && isset($pass) ) {

			if($user = $this->getByEmail($email))
			{
				return ($user->u_hpass == sha1($pass));
			} else return false;
		}
	}

}
?>
