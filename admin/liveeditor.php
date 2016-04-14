<?php

/**
 * AVE.cms
 * since 2.0
 * LiveEditor
 * author Aleksandr Salnikov (Repellent) webstudio3v.ru
 * @package AVE.cms
 * @filesource
 */

if (!defined('ACP'))
{
	header('Location:index.php');
	exit;
}

global $AVE_DB, $AVE_Template;

require(BASE_DIR . '/class/class.liveeditor.php');
$AVE_LiveEditor = new AVE_LiveEditor;

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/liveeditor.txt', 'liveeditor');

switch ($_REQUEST['action'])
{
	case '':
		if (check_permission_acp('liveeditor'))
		{
			$AVE_LiveEditor->live_editorList();
		}
		break;

	case 'edit':
		if (check_permission_acp('liveeditor'))
		{
			$AVE_LiveEditor->live_editorEdit(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
		}
		break;

	case 'save':
		if (check_permission_acp('liveeditor'))
		{
			$AVE_LiveEditor->live_editorSave(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
		}
		break;

    case 'reg':
		if (check_permission_acp('liveeditor'))
		{
			$AVE_LiveEditor->live_editorReg();
		}
		break;
}
?>