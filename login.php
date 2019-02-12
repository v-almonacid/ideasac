<?php
session_start();
require_once("./inc/Usuario.class.php"); 
$usuario = new Usuario();

if(isset($_POST['signin'])) {
  if( !empty($_POST['pass']) && !empty($_POST['email']) ) {
    if( $usuario->checkPass($_POST['email'], $_POST['pass']) ) { 
      $userBean = $usuario->getByEmail($_POST['email']);
      $usuario->login($userBean->id);
      header("location: index.php");
    } else {
      $error = "Lo sentimos, la combinación email-contraseña no existe. Si aún no tienes una cuenta, "
              ." puedes crearla <a href=\"crearCuenta.php\">aquí</a>";      
    }
  } else $error="Debes llenar todos los campos.";  
}
include('header.php');  
?>

<div class="container">
  <div class="page-header text-center">
    <h2>Conéctate</h2>
  </div>
  <div class="row">
    <div class="col-lg-4">                                          
    </div> 
    <div class="col-lg-4">
      <div class="error text-danger"><?php if(isset($error)) { echo '<p>'.$error.'</p>';}?> </div>
      <form name="sign-in-form" id="sign-in-form" method="post" action=""> 
        <div class="form-group">
          <label for="email">Correo electrónico:</label>
          <input type="email" name="email" id="cnx-email" class="form-control" maxlength="100" />
        </div>
        <div class="form-group">
          <label for="pass">Contraseña:</label>
          <input type="password" name="pass" id="cnx-pass" class="form-control" maxlength="100" />
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-default" name="signin">Iniciar sesión</button>
        </div>
        <div class="form-group">
          <p class="help-block"> ¿Aún no tienes una cuenta? Regístrate <a href="./crearCuenta.php">aquí</a></p>
        </div>        
        <div id="error-nb"></div>
      </div>    
  </div>  <!-- /row -->


<!-- old form ============== -->
   <!--  <div class="wrapper">  
    
      <div class="form_container sign-up">
        <div class="titulo"><h2>Conéctate</h2></div>
        <?php // if(isset($error)) { echo "<div class=\"error\"><p>".$error."</p></div>"; }?>
        <form name="sign-in-form" method="post" action="">
        	<fieldset>
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="cnx-email" class="input-txt" />
          </fieldset>
          <fieldset>
            <label for="pass">Contraseña: </label>
            <input type="password" name="pass" id="cnx-pass" class="input-txt" />
          </fieldset>
          <fieldset>
            <input type="submit" value="Conectarse" class="button" name="signin" />
          </fieldset>
        </form>  
      </div>
    
    </div> -->
  
  </body>

</html>
