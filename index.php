<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @version 3.x
 * @filesource
 * @copyright © 2007-2013 AVE.CMS, http://www.ave-cms.ru
 *
 */

@date_default_timezone_set('Europe/Moscow');

define('START_MICROTIME', microtime());
define('BASE_DIR', str_replace("\\", "/", dirname(__FILE__)));
if (! @filesize(BASE_DIR . '/inc/db.config.php')) { header('Location:install/index.php'); exit; }
if (! empty($_REQUEST['thumb'])) { require(BASE_DIR . '/inc/thumb.php'); exit; }

ob_start();

require(BASE_DIR . '/inc/init.php');
// не на всех хостингах настройки одинаковые ((((
if (strpos($_SERVER['REQUEST_URI'],ABS_PATH.UPLOAD_DIR.'/')===0) { require(BASE_DIR . '/inc/thumb.php'); exit; }

$AVE_Template = new AVE_Template(BASE_DIR . '/templates/' . DEFAULT_THEME_FOLDER);

if (! isset($_REQUEST['sub'])) $_REQUEST['sub'] = '';

require(BASE_DIR . '/class/class.core.php');
$AVE_Core = new AVE_Core;
if (empty($_REQUEST['module'])) $AVE_Core->coreUrlParse($_SERVER['REQUEST_URI']);

if (!empty($_REQUEST['revission'])){
	$res=$AVE_DB->Query("SELECT doc_data FROM ".PREFIX."_document_rev WHERE doc_id='".(int)$_REQUEST['id']."' AND doc_revision='".(int)$_REQUEST['revission']."' LIMIT 1")->GetCell();
	$res=@unserialize($res);
	$flds=get_document_fields((int)$_REQUEST['id'],$res);
}

$AVE_Core->coreSiteFetch(get_current_document_id());

$content = ob_get_clean();
//file_put_contents(BASE_DIR.'/cache/doc-'.$_REQUEST['id'].'.html',$content);
ob_start();
eval('?>' . $content . '<?');
$cont=ob_get_clean();
$rubheader=(empty($GLOBALS["user_header"]) ? "" : implode(chr(10),$GLOBALS["user_header"]));
$cont = str_replace('[tag:rubheader]', $rubheader, $cont);

if (isset($_REQUEST['id']) AND ($_REQUEST['id']) == PAGE_NOT_FOUND_ID){
	report404(
		"<strong class=\"code_red\">404 ERROR:</strong> "
		. "<br />" .
		"<strong class=\"code\">HTTP_USER_AGENT</strong> - ". $_SERVER['HTTP_USER_AGENT']
		. "<br />" .
		"<strong class=\"code\">HTTP_REFERER</strong> - ". @$_SERVER['HTTP_REFERER']
		. "<br />" .
		"<strong class=\"code\">REQUEST_URI</strong> - " . $_SERVER['REQUEST_URI']
		, 2, 2);
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true);
}

if (((isset($_REQUEST['apage']) && is_numeric($_REQUEST['apage']) && $_REQUEST['apage'] > $GLOBALS['page_id'][$_REQUEST['id']]['apage']))
		OR ((isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > $GLOBALS['page_id'][$_REQUEST['id']]['page']))
		OR ((isset($_REQUEST['artpage']) && is_numeric($_REQUEST['artpage']) && $_REQUEST['artpage'] > $GLOBALS['page_id'][$_REQUEST['id']]['artpage'])))
{
	if($_REQUEST['id']==1){header('Location:' . ABS_PATH);}else{header('Location:' . ABS_PATH.$AVE_Core->curentdoc->document_alias.URL_SUFF);}
	exit;
}
echo $cont;

//if (isset($cache) && is_object($cache)) $cache->end();

if (!defined('ONLYCONTENT') && UGROUP == 1 && defined('PROFILING') && PROFILING) echo get_statistic(1, 1, 1, 1);

?>