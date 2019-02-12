<?php
/**
 *
 * Test script for Usuario.class.php
 * Author: Vicente Almonacid
 * last update:
 *
 * for tests purposes, you may use default user 'Juan' predefined in the DB:
 * INSERT INTO `usuario` (`id`, `u_twitter_auth`, `u_nombre`, `u_email`, `u_fecha_reg`) VALUES
 * (1, 'false','Juan', 'juan@mail.com','2012-03-13 00:00:00');
 *
 */

include("./Usuario.class.php");
session_start();

$user = new Usuario();

// testing addUser()
echo "<p><h3>Testing addUser() method</h3>";
$nombre = "pablo";
$mail = "pablo@mail.com";
$pass = "pass";
if($id= $user->addUser($nombre, $mail, $pass)){
  echo 'Passed: addUser() retorna: <br />';
  echo "id: " . $id . "<br />";
} else {
  echo "<div style='color:red'>Failed: addUser() retorna 0</div>";
}

// testing addUser() con email ya ingresado
echo "<p><h3>Testing addUser() method</h3>";
if(!$id= $user->addUser($nombre, $mail, $pass)){
  echo 'Passed: addUser() retorna nulo, usuario no se puede ingresar <br />';
} else {
  echo "<div style='color:red'>Failed.</div>";
}


// testing getByEmail()
echo "<p><h3>Testing getByEmail() method</h3>";
// nota: pablo is a Bean, not an Usuario object
if($pablo = $user->getByEmail($mail)){
  echo "Passed: getInfoByEmail({$mail}) retorna: <br />";
  echo "usuario id: " . $pablo->id . "<br />";
  echo "usuario nombre: " . $pablo->u_nombre . "<br />";
} else{
  echo "<div style='color:red'>Failed: getInfoByEmail() retorna null</div>";
}
echo '</p>';

// testing login()
echo "<p><h3>Testing login() method</h3></p>";
if($user->login($pablo->id)){
  echo 'Passed:  login() retorna 1 <br />';
} else{
  echo "<div style='color:red'>Failed.</div><br />";
}

// testing logout()
echo "<p><h3>Testing logout() method</h3>";
if($user->logout()){
  echo 'Passed:  logout() retorna 1 <br />';
} else{
  echo "<div style='color:red'>Failed.</div><br />";
}

// testing isLogged()
echo "<p><h3>Testing isLogged() method</h3>";
$user->login($pablo->id);
if($user->isLogged()){
  echo 'Passed:  isLogged() retorna 1 <br />';
  $user->logout();
} else{
  echo "<div style='color:red'>Failed.</div><br />";
}

// testing getById()
echo "<p><h3>Testing getById() method</h3>";
$user->login($pablo->id);
if( $pablo = $user->getById() ) {
  echo "Passed:  getById() retorna {$pablo->id} <br />";
  $user->logout();
} else{
  echo "<div style='color:red'>Failed.</div><br />";
}

// testing checkPass()
echo "<p><h3>Testing checkPass() method</h3>";
if( $user->checkPass($pablo->u_email, $pass) ){
  echo 'Passed:  checkPass() retorna true <br />';
} else{
  echo "<div style='color:red'>Failed.</div><br />";
}

// testing deleteById()
echo "<p><h3>Testing deleteById() method</h3>";
$user->deleteById($pablo->id);
if( !$user->getByEmail($mail) ){
  echo "Passed:  se elimino el usurio con id {$pablo->id} <br />";
} else{
  echo "Failed.<br />";
}


?>
