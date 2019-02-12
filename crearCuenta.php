<?php
session_start();
require_once("./inc/Usuario.class.php");
$usuario = new Usuario();
if(isset($_POST['signup'])) {
  if( !empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['pass']) && !empty($_POST['pass-conf'])
    && !empty($_POST['email']) && !empty($_POST['email-conf'])) {
    $nombre = $_POST['nombre']. ' ' . $_POST['apellido'];
    if($usuario->getByEmail($_POST['email'])){
      $error = "Ya hemos registrado una cuenta con ese email. Verifícalo o <a href=\"#\">recupera</a> tu contraseña";
    } elseif ($_POST['email'] != $_POST['email-conf']) {
      $error = "Las direcciones de email no coinciden, inténtalo de nuevo.";
    }
    elseif ($_POST['pass'] != $_POST['pass-conf']) {
      $error = "Las contraseñas no coinciden, inténtalo de nuevo.";
    }
    elseif( $id = $usuario->addUser($nombre, $_POST['email'], $_POST['pass']) ) {
      $usuario->login($id);
      header("location: index.php");
    } else {
      $error = "Lo sentimos mucho, no pudimos crear tu cuenta. Inténtalo de nuevo más tarde o contáctanos.";
    }
  } else $error="Debes llenar todos los campos.";
}
include('header.php');
?>
     <div class="container">
      <div class="page-header text-center">
          <h2>Registro</h2>
        </div>
      <div class="row">
        <div class="col-lg-4">
        </div>
        <div class="col-lg-4">
          <div class="error text-danger"><?php if(isset($error)) { echo '<p>'.$error.'</p>'; }?> </div>
          <form role="form" name="sign-up-form" id="sign-up-form" method="post" action="">
           <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="reg-nombre" class="form-control" maxlength="100" />
          </div>
          <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" id="reg-apellido" class="form-control" maxlength="100" />
          </div>
          <div id="error-nb"></div>
          <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="reg-email" class="form-control" maxlength="100"/>
          </div>
          <div class="form-group">
            <label for="reg-email-conf">Confirma tu correo electrónico:</label>
            <input type="email" name="email-conf" id="reg-email-conf" class="form-control" maxlength="100"/>
            <div id="error-email"><i class='fa' id='check-email'></i><span class="error-mes"></span></div>
          </div>
          <div class="form-group">
            <label for="reg-pass">Contraseña: </label>
            <input type="password" name="pass" id="reg-pass" title="Puedes usar letras, números y otros caracteres" class="form-control" maxlength="100"/>
          </div>
          <div class="form-group">
            <label for="reg-pass-conf">Confirma tu contraseña: </label>
            <input type="password" name="pass-conf" id="reg-pass-conf" class="form-control" maxlength="100" />
            <div id="error-pass"><i class='fa' id='check-pass'></i><span class="error-mes"></span></div>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-default" name="signup">Crear cuenta</button>
          </div>
          </form>
        </div>
        <div class="col-lg-4">
        </div>
      </div> <!-- /row -->
    </div> <!-- /container -->

    <script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script type="text/javascript">

      mailVisited = false;
      passVisited = false;

      // validate emails
      $('#reg-email').keyup( function(){
        if(mailVisited){
          if( ($('#reg-email').val())  != ($('#reg-email-conf').val()) ){
            $('#error-email').find('.error-mes').html('Las direcciones no coinciden, verifícalas!');
            $('#check-email').addClass('fa-warning').removeClass('fa-check');
          } else {
            $('#error-email').find('.error-mes').html('Perfecto');
            $('#check-email').addClass('fa-check').removeClass('fa-warning');
          }
        }
        mailVisited = true;
      });


      $('#reg-email-conf').keyup( function(){
        if( ($('#reg-email').val())  != ($('#reg-email-conf').val()) ){
          $('#error-email').find('.error-mes').html('Las direcciones no coinciden, verifícalas!');
          $('#check-email').addClass('fa-warning').removeClass('fa-check');
        } else if( ($('#reg-email').val() != '')  && ($('#reg-email-conf').val() != '') ){
          $('#error-email').find('.error-mes').html('Perfecto');
          $('#check-email').addClass('fa-check').removeClass('fa-warning');
        }
      });

      // validate passwords
      $('#reg-pass').keyup( function(){
        if(passVisited){
          if( ($('#reg-pass').val())  != ($('#reg-pass-conf').val()) ){
            $('#error-pass').find('.error-mes').html('Las contraseñas no coinciden, verifícalas!');
            $('#check-pass').addClass('fa-warning').removeClass('fa-check');
          } else {
            $('#error-pass').find('.error-mes').html('Perfecto');
            $('#check-pass').addClass('fa-check').removeClass('fa-warning');
          }
        }
        passVisited = true;
      });


      $('#reg-pass-conf').keyup( function(){
        console.log('Checking pass...');
        console.log("Pass: " +  ($('#reg-pass').val()));
        console.log("Conf. Pass: " +  ($('#reg-pass-conf').val()));
        if( ($('#reg-pass').val())  != ($('#reg-pass-conf').val()) ){
          $('#error-pass').find('.error-mes').html('Las contraseñas no coinciden, verifícalas!');
          $('#check-pass').addClass('fa-warning').removeClass('fa-check');
        } else if(($('#reg-pass').val() != '')  && ($('#reg-pass-conf').val() != '')){
          $('#error-pass').find('.error-mes').html('Perfecto');
          $('#check-pass').addClass('fa-check').removeClass('fa-warning');
        }
      });

      $('#sign-up-form').submit(function (e) {
        error = false;
        $('.form-control').each( function() {
          if($.trim($(this).val()) === ''){
            error = true;
          }
        });
        if(error){
          e.preventDefault();
          $('.error').html("<p>Por favor, llena todos los campos.</p>");
          $("body, html").animate({
            scrollTop: 0
        }, 600);
          console.log("pass");
          return false;
        }
      });
    </script>


  </body>

</html>
