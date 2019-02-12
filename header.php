<?php
require_once("./inc/Usuario.class.php");
$usuario = new Usuario();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" href="images/icon.ico" >
    <!-- fonts  -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css' /> -->
    <link href='http://fonts.googleapis.com/css?family=Josefin+Sans:400,600,700' rel='stylesheet' type='text/css'>
    <!-- handwriting style font -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet' type='text/css'>  -->
    <link href="style/font-awesome/css/font-awesome.min.css" rel="stylesheet" />

    <title>#ideasAC, propuestas ciudadanas para una Nueva Constitución</title>

    <!-- Bootstrap -->
    <!-- enable grid System -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="style/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="style/bootstrap-theme.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- custom style -->
    <link href="style/style.css" rel="stylesheet" type="text/css" />

    <?php
    if ( $usuario->isLogged() )
    {
      echo "<script type=\"text/javascript\">var loggedIn=true;</script>\n";
    } else
    {
      echo "<script type=\"text/javascript\">var loggedIn=false;</script>\n";
    }
    ?>
  </head>


  <body>

    <!-- <header> -->

      <?php
        $menuItems = array();
        $menuItems[] = "<li><a href=\"./index.php\"><i class=\"fa fa-home\"></i></a></li>";
        if($usuario->isLogged())
        {
          $userBean = $usuario->getById($_SESSION['id']);  // get current user
          $name = explode(" ", $userBean->u_nombre);
          $fstName = $name[0];
          // $menuItems[] = "<a href=\"#\">Perfil</a>";
          $menuItems[] = '<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hola ' . $fstName . ' <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="./logout.php">Cerrar sesión</a></li>
                </ul>
              </li>';

        } else {
          $menuItems[] = "<li><a href=\"./crearCuenta.php\">Crear cuenta</a></li>";
          $menuItems[] = "<li><a href=\"./login.php\">Iniciar sesión</a></li>";
        }
        $menuItems[] = "<li><a href=\"https://twitter.com/wikiac\" target=\"_blank\"><i class=\"fa fa-twitter\"></i></a></li>";
        $menuItems[] = "<li><a href=\"https://www.facebook.com/wikiac\" target=\"_blank\"><i class=\"fa fa-facebook\"></i></a></li>";
      ?>

      <!-- Fixed navbar -->
      <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./index.php">#ideas<span class="logo-ac">AC</span></a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <?php
              foreach ($menuItems as $key => $value) {
                echo $value."\n";
              }
              ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>


    <!-- </header> -->
