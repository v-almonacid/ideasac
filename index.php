<?php
/* nota: include no detiene flujo en caso de error. En tal caso se
   carga contenido por defecto */
// include ('loadContent.php');
session_start();
include('header.php');
?>

  <section id="section-1">
    <div class="shadow">

      <div class="container">
        <div class="main-board">
          <div class="logo text-center">
            <!-- <img src="images/logoSimple.png"> </img> non responsive  -->
            <div class="logo-titulo"><h1>#ideasAC</h1></div>
            <div class="logo-slogan"><i>Propuestas ciudadanas para una Nueva Constitución</i></div>
            <!-- <img src="images/logo_cam2.png"> </img> -->
          </div>

          <div class="text-center">
            <h2>Haz que tu voz cuente y súmate al debate por una Nueva Constitución</h2>

            <h3>¿Cómo participar?</h3>
            <p>
              Es muy simple y puedes hacerlo de dos formas:
            </p>
          </div>
          <div class="row h4">
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">
              <ul>
                <li>Crea una cuenta y comparte tus ideas a través de nuestra web. Siendo parte de la
                   comunidad podrás votar y ayudarnos a identificar temas de alto consenso.<br><br></li>
                <li>Desde twitter, usando el hashtag #ideasAC. <br><br></li>
              </ul>
            </div>
            <div class="col-lg-4">
            </div>
          </div>
          <div class="container text-center">
            <div class="btn-group">
              <a class='btn btn-default' href='./crearCuenta.php'>Inscríbete!</a>
              <a class='btn btn-danger send-msj'>Tuitea ya!</a>
            </div>
          </div>

        </div> <!-- /main board -->
      </div>  <!-- /container -->
    </div> <!-- /shadow -->
  </section> <!-- /section-1 -->

  <section id="section-2">
    <div class='container'>
      <div class="page-header text-center">
        <h2>¿De qué hablamos?</h2>
      </div>
      <div id='wordcloud'></div>
    </div>
  </section>

  <section id="section-3">
    <div class='container'>
      <div class="page-header text-center">
        <h2>Aportes Destacados</h2>
      </div>
      <div id='destacados'></div>
    </div>
  </section>

  <section id="section-4">
    <div class="container">
      <div class="page-header text-center">
        <h2>Comparte tus propuestas</h2>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-lg-4">
        </div>
        <div class="col-lg-4">
          <form id="postForm" role="form" name="postForm" method="post" action="">
              <?php
              if(!$usuario->isLogged())
              {
                echo '<p class="help-block"> Para enviar tus Propuestas, ideas u opiniones a través de nuestra web necesitas estar conectado.
                      <i>(Recuerda que también puedes participar simplemente usando el hashtag #ideasAC en twitter.)</i></p>'."\n";
                echo '<div class="center-block">';
                echo '<div class="text-center">';
                echo '<a class="btn btn-danger" href="./login.php">Iniciar sesión</a>';
                echo '<a class="btn btn-link" href="./crearCuenta.php">Registrarme</a>';
                echo '</div> ';
                echo '</div> ';
                //echo '<div class="center-block"><a class="btn btn-lg btn-danger center-block" href="./crearCuenta.php">Inscribirme ahora!</a></div>'."\n";
                // echo '<p class="help-block">Puedes participar sin inscribirte dejando tu nombre y correo electrónico</p>';
                // echo '<div class="form-group">'."\n";
                // echo '<label for="nombre">Nombre</label>'."\n";
                // echo '<input type="text" name="nombre" placeholder="Tu nombre..." id="nombre" class="form-control" maxlength="100" />'."\n";
                // echo '</div>';
                // echo '<div class="form-group">';
                // echo '<label for="email">Email</label>'."\n";
                // echo '<input type="email" name="email" placeholder="Tu email..." id="email" class="form-control" maxlength="100" />'."\n";
                // echo '</div>';
              } else {
                echo '<div class="form-group">'."\n";
                echo '<label for="mensaje">Mensaje</label>'."\n";
                echo '<textarea  name="mensaje" placeholder="Tu opinión en 140 caracteres..." id="mensaje" class="form-control" rows="3" maxlength="140"></textarea>'."\n";
                echo '</div>'."\n";
                echo '<div class="form-group">'."\n";
                echo '<button type="submit" class="btn btn-default">Enviar</button>'."\n";
                echo '</div>'."\n";
              }
              ?>
          </form>
        </div>
      </div>
    </div>
  </section> <!-- /section-4 -->

  <section id="section-5">
    <div class='container text-center'>
      <div class="page-header">
        <h2>Últimos Aportes</h2>
      </div>
      Ordenar por: <br>
      <div class="btn-group">
        <a id="votados" class="btn btn-default order-b" href="#">+ votados</a>
        <a id="recientes" class="btn btn-default order-b" href="#">+ recientes</a>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
          <div id="timeline"></div>
        </div>
        <div class="col-lg-3"></div>
        <div class="limit"></div>
      </div>
    </div>
  </section>  <!-- /section-5 -->

  <!-- <footer> -->
  <div class="footer">
    <div class="container">
      <p class="text-muted"><a href="mailto:wikiac.cl@gmail.com?Subject=contacto" target="_top">Contacto</a> |
        Una iniciativa de WikiAC &copy;2014.</p>
    </div>
  </div>
  <!-- </footer> -->

  <script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script src="js/jQuery.awesomeCloud/jquery.awesomeCloud-0.2.min.js" type="text/javascript"></script>
  <!-- <script src="js/grid/jquery.grid-a-licious.js" type="text/javascript"></script> -->
  <script src="js/grid/jquery.grid-a-licious-mod.min.js" type="text/javascript"></script>
  <script src="js/moment.min.js"></script>
  <script src="js/moment.lang.min.js"></script>
  <script src="js/lib.js" type="text/javascript"></script>
  <script src="js/main.js" type="text/javascript"></script>
  <script src="js/bootstrap.min.js"></script>

</body>
</html>
