<?php
	define('ROOT', __DIR__);

	//Includo i parametri, la configurazione, la lingua e le funzioni
	require_once ROOT . '/includes/required.php';

$strInnerPage = "";

/** * Bug fix del mapwise: la gestione dello spostamento della mappa va gestita da main e non da mappaclick
 * @author Blancks
 */
if( ! empty($_GET['map_id'])) {
    $_SESSION['mappa'] = (int) $_GET['map_id'];
    gdrcd_query("UPDATE personaggio SET ultima_mappa=".gdrcd_filter('num', $_SESSION['mappa']).", ultimo_luogo=-1 WHERE nome = '".gdrcd_filter('in', $_SESSION['login'])."'");
}

if(isset($_REQUEST['page'])) {
    $strInnerPage = gdrcd_filter('include', $_REQUEST['page']).'.inc.php';

    //se e' impostato dir allora cambio stanza.
} elseif(isset($_REQUEST['dir']) && is_numeric($_REQUEST['dir'])) {
    if($_REQUEST['dir'] >= 0) {
        $strInnerPage = 'frame_chat.inc.php';
    } else {
        $strInnerPage = 'mappaclick.inc.php';
        $_REQUEST['id_map'] = $_SESSION['mappa'];
    }

    gdrcd_query("UPDATE personaggio SET ultimo_luogo=".gdrcd_filter('num', $_REQUEST['dir'])." WHERE nome='".gdrcd_filter('in', $_SESSION['login'])."'");
    /**    * Caso di fix
     * se non ci sono variabili via url, si ripristinano dei valori di default
     * @author Blancks
     */
} else {
    $strInnerPage = 'mappaclick.inc.php';
    $_REQUEST['id_map'] = $_SESSION['mappa'];
}
/**    * Fine caso di Fix */
if(gdrcd_controllo_esilio($_SESSION['login']) === true) {
    session_destroy();
} else {
    template\start('content');
	require('layouts/'.$PARAMETERS['themes']['kind_of_layout'].'_frames.php');
	template\end('content');
}

	echo $OUT['header'];	
	
	echo $OUT['content'];	
  
	echo $OUT['footer'];
