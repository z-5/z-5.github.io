<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @subpackage admin
 * @filesource
 */

if(!defined('ACP'))
{
	echo 'Извините, но Вы не имеете права доступа к данному разделу!';
	exit;
}

require(BASE_DIR . '/inc/init.php');
require(BASE_DIR . '/admin/functions/func.admin.common.php');
require(BASE_DIR . '/admin/editor/fckeditor.php');
require(BASE_DIR . '/admin/ckeditor/ckeditor.php');

if (! isset($_SESSION['admin_theme'])) $_SESSION['admin_theme'] = DEFAULT_ADMIN_THEME_FOLDER;
if (! isset($_SESSION['admin_language']))
{
	$_SESSION['admin_language'] = isset($_SESSION['user_language']) ? $_SESSION['user_language'] : DEFAULT_LANGUAGE;
}

$AVE_Template = new AVE_Template(BASE_DIR . '/admin/templates/' . $_SESSION['admin_theme']);
$AVE_Template->assign('tpl_dir', ABS_PATH . 'admin/templates/' . $_SESSION['admin_theme']);
$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/main.txt');

define('SESSION', session_id());
$AVE_Template->assign('sess', SESSION);

?>