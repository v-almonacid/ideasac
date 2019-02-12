<?php

class ContentManager
{
  public function __construct()
  {
  }

  public function __destruct()
  {
  }

  public static function getTags(){

    $tags = array(
      'asamblea constituyente',
      '#asambleaconstituyente',
      '#SinACNoHayNuevaConstitucion',
      '#LaACSísePuede',
      'carta magna',
      '#nomasbinominal',
      '#nomasafp',
      'nueva constitución',
      '#nuevaconstitucion',
      'proceso constituyente',
      'poder constituyente',
      'representatividad',
      'pluralismo',
      'voluntad originaria',
      'soberania popular',
      '1980',
      'jaime guzman',
      'movimiento social',
      'movilizaciones',
      'plebiscito',
      'consulta ciudadana',
      'referendum',
      'propuestas',
      'ideas',
      'opiniones',
      'redactar',
      'pueblos originarios',
      'mapuche',
      'araucania',
      'conflicto indigena',
      'izquierda',
      'derecha',
      'centro',
      'ciudadania',
      'ciudadano',
      'personas',
      'patriotismo',
      'sociedad',
      'desarollo',
      'entorno',
      'segregacion',
      'marginados',
      'sistema',
      'civilizacion',
      'historia',
      'origenes',
      'chile',
      'chile necesita',
      'dictadura',
      'dictador',
      'pinochet',
      'allende',
      'transicion',
      'nueva mayoria',
      'concerta',
      'alianza',
      'michelle bachelet',
      'piñera',
      'udi',
      'rn',
      'pro',
      'dc',
      'ps',
      'ppd',
      'ph',
      'partido socialista',
      'pc',
      'red liberal',
      'redliberal_cl',
      'anef',
      'colegio de profesores',
      'cut',
      'sindicatos',
      'sindicalismo',
      'ocde',
      '@lafundacionsol',
      'fundacion sol',
      '@rdemocratica',
      'progresistas',
      'meo',
      '@marcoporchile',
      'politicos',
      'diputados',
      'senadores',
      'senado',
      'congreso',
      'educacion gratuita',
      'educacion de calidad',
      'lucro',
      'salud publica',
      'seguridad social',
      'demandas sociales',
      'colectivas',
      'afp',
      'isapres',
      'afp estatal',
      'duopolio',
      'binominal',
      'sistema electoral',
      'centralismo',
      'regionalismo',
      'regiones',
      'agua',
      'energia',
      'recursos naturales',
      'injusticia',
      'justicia',
      'justicia social',
      'abusos',
      'reforma',
      'reforma tributaria',
      'leyes',
      'impuestos',
      'ley antiterrorista',
      'cambios',
      'cambiar',
      'revolucion',
      'medio ambiente',
      'derechos',
      'derechos constitucionales',
      'derechos humanos',
      'garantias',
      'deberes',
      'derechos laborales',
      'sueldo minimo',
      'trabajo digno',
      'dignidad',
      '@hconstituyente',
      'MarcaAC',
      '@marcatuvoto',
      'igualdad',
      'equidad',
      'distribucion de ingresos',
      'igualdad de derechos',
      'estudiantes',
      'movimiento estudiantil',
      'movimiento social',
      'iglesia',
      'laico',
      'empresas',
      'emprendedores',
      'ricos',
      'pobres',
      'clase media',
      'futuro',
      'urge',
      'servicio publico',
      'ahora',
      'discriminacion',
      'intolerancia',
      'acuerdo',
      'construir acuerdos',
      'respecto',
      'convivencia',
      'paz',
      'vida',
      'bienestar',
      'reconciliacion',
      'polarizacion',
      'debate',
      'discusion',
      'cultura',
      'gobierno',
      'estado',
      'subsidiario',
      'cobre',
      'codelco',
      'nacionalizacion',
      'oligarquia',
      'liberalismo',
      'neoliberalismo',
      'individualismo',
      'liberal',
      'capitalismo',
      'conservador',
      'radical',
      'pueblo',
      'republica',
      'democracia participativa',
      'democracia',
      'orden institucional',
      'instituciones',
      'libertad',
      'libertad de expresion',
      'libertad individual',
      'moral',
      'participacion',
      'participacion ciudadana',
      'legitimidad',
      'autodeterminacion',
      'impuesta',
      'chilenostodos',
      'minoria',
      'mayoria',
      'sesgo ideologico',
      'evolucion',
      'tribunal militar',
      '##LaACSisePuede'
      );

    return $tags;

  }

  public static function getUrlPattern(){
    $pattern = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';
    return $pattern;
  }

  /**
   * linkify()
   * search for urls and convert them to html href.
   * also limit the url length to display
   * returns string
   */
  public function linkify($text){

    $pattern = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';

    // search for urls
    if($nmatches = preg_match_all("#$pattern#i", $text, $matches)) {
      // make them shorter...
      for ($i = 0; $i < $nmatches; $i++) {
        $shortUrl = strlen($matches[0][$i]) > 20 ? substr($matches[0][$i],0,20)."..." : $matches[0][$i];
        // convert urls into html hyperlinks
        $hyperlink = "<a href='" .$matches[0][$i]. "' target='_blank'>" .$shortUrl. '</a>';
        $text = str_replace($matches[0][$i], $hyperlink, $text);
      }
    }
    return $text;
  }

  /**
   * timelineFormatter()
   * returns a custom html timeline from array $messages
   */
  public function timelineFormatter($messages){

    $arr = array();

    foreach ($messages as $message)
    {
      $txt = $message->msj_txt;

      /* will be used to search for urls and ingore them in the cloud */
      $pattern = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';
      // search for urls
      if($nmatches = preg_match_all("#$pattern#i", $txt, $matches)) {
        // make them shorter...
        for ($i = 0; $i < $nmatches; $i++) {
          $shortUrl = strlen($matches[0][$i]) > 20 ? substr($matches[0][$i],0,15)."..." : $matches[0][$i];
          // convert urls into html hyperlinks
          $hyperlink = '<a href="' .$matches[0][$i]. '">' .$shortUrl. '</a>';
          $txt = str_replace($matches[0][$i], $hyperlink, $txt);
        }
      }

      $dateObj =  new  DateTime($message->msj_date);

      $arr[] =  "<div class=\"item\">\n" .
                "  <div class=\"tweet-wrapper\">\n" .
                "    <div class=\"tweet-header\">\n" .
                "      <span class=\"time\">". $dateObj->format("d-m-Y") ."</span>\n" .
                "      <span class=\"counter\">#". $message->id ."</span>\n" .
                "      <span class=\"votos\"> \n" .
                "        <a href=\"#\" id=\"like-b-".$message->id."\" class=\"l-b l-".$message->id."\">\n" .
                "          <i class=\"fa fa-thumbs-up\"></i> \n" .
                "            <span class=\"nfavor\">".$message->msj_nfavor."</span> \n" .
                "       </a>\n" .
                " · " .
                "       <a href=\"#\" id=\"dislike-b-".$message->id."\" class=\"l-b d-".$message->id."\">\n" .
                "          <i class=\"fa fa-thumbs-down\"></i> \n" .
                "            <span class=\"ncontra\">".$message->msj_ncontra."</span> \n" .
                "       </a>\n" .
                "     </span>\n" .
                "    </div>\n" .
                "    <div class=\"text\">" . $txt . "</div>\n" .
                "    <div class=\"user\">" . $message->msj_nombre . "</div>\n" .
                "  </div>\n" .
                "</div>";
    }
    $output = join( "\n", $arr ) . "\n";
    return $output;
  }

  // to-do: consultas se deben hacer por un solo lado, ya sea en este metodo
  // o desde la api. por definir
  public function cloudFormatter($tagBeans, $minFontSize = 10, $maxFontSize = 40 )
  {

    // $minimumCount = min( array_values( $data ) );
    $q = R::getCol( "SELECT min(count) FROM tag");
    $minimumCount = $q[0];
    $q = R::getCol( "SELECT max(count) FROM tag");
    $maximumCount = $q[0];
    $spread       = $maximumCount - $minimumCount;
    $cloudHTML    = '';
    $cloudTags    = array();

    $spread == 0 && $spread = 1;

    foreach( $tagBeans as $bean )
    {
	  $toDelete = array("#", "@");
      $cleanTag = str_replace($toDelete, "", $bean->tag);
      is_null($bean->count) ? $count = 0 : $count = $bean->count;
      $size = $minFontSize + ( $count - $minimumCount )
        * ( $maxFontSize - $minFontSize ) / $spread;
      $cloudTags[] = '<span data-weight="' . floor( $size )
                     . '" title="\'' . $bean->tag  . '\' ha tenido ' . $count . ' menciones">'
                     . '<a href="./busqueda.php?q='.$cleanTag.'" class="tag-link">' . $bean->tag . '</a></span>';
    }

    return join( "\n", $cloudTags ) . "\n";
  }

}
