<?php
/* import into database tags defined in class
 * ContentManager
 */

$base = dirname( __FILE__ ); 
require_once("{$base}/inc/initdb.php");
require_once('./inc/ContentManager.class.php');

$tags = ContentManager::getTags();
// var_dump($tags);

foreach ($tags as $tag) {

  $tagBean = R::findOne('tag', 'tag=?', array($tag));

  if(!$tagBean){
    $tagBean = R::dispense('tag');
    $tagBean->tag = $tag;
    $tagId = R::store($tagBean);
    echo "Se agrego {$tagId}<br>";
  }
  
}

?>