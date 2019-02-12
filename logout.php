<?php
session_start();
require_once("./inc/Usuario.class.php");
$usuario = new Usuario();
if($usuario->isLogged()) { 
  $usuario->logout();
}
header("location:index.php");
?>