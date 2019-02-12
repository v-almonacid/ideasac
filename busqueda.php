<?php
session_start();
include('header.php');

if( isset($_GET['q'] ) ){
  $tag = $_GET['q'];
}

?>

  <section>
    <div class='container'>
      <div class="page-header text-center">
          <h2>Búsqueda: "<?php echo $tag; ?>"</h2>
        </div>
        <div id="timeline"></div>
        <div class="limit"></div>
      </div>
  </section>


  <!-- <footer> -->
    <div class="footer">
      <div class="container">
        <p class="text-muted"><a href="mailto:wikiac.cl@gmail.com?Subject=contacto" target="_top">Contacto</a> |
          Una iniciativa de WikiAC &copy;2014.</p>
      </div>
    </div>
  <!-- </footer> -->

    <script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
    <!-- <script src="js/grid/jquery.grid-a-licious.js" type="text/javascript"></script> -->
    <script src="js/grid/jquery.grid-a-licious-mod.min.js" type="text/javascript"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/moment.lang.min.js"></script>
    <script src="js/lib.js" type="text/javascript"></script>
    <script type="text/javascript"> <?php echo "tag='".$tag."';" ?></script>
    <script type="text/javascript">
      $(document).ready(function () {

        search(tag);

        $("#timeline").gridalicious({
          width: 400,
          animate: true,
          animationOptions: {
            duration: 500
          },
        });

      });
    </script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

  </body>
</html>
