<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @subpackage admin
 * @filesource
 */

if (!defined('ACP'))
{
	header('Location:index.php');
	exit;
}

require(BASE_DIR . '/class/class.docs.php');
require(BASE_DIR . '/class/class.rubs.php');
require(BASE_DIR . '/class/class.navigation.php');
require(BASE_DIR . '/class/class.request.php');

$AVE_Document   = new AVE_Document;
$AVE_Rubric     = new AVE_Rubric;
$AVE_Navigation = new AVE_Navigation;
$AVE_Request    = new AVE_Request;

$AVE_Document->documentTemplateTimeAssign();

$AVE_Rubric->rubricPermissionFetch();

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/docs.txt', 'docs');

$AVE_Template->assign("navi", $AVE_Template->fetch("navi/navi.tpl"));

switch($_REQUEST['action'])
{
	case '' :
		if (check_permission_acp('documents'))
		{
			switch($_REQUEST['sub'])
			{
				case 'quicksave':
					$_SESSION['use_editor'] = get_settings('use_editor');
					$AVE_Document->quickSave();
					break;
			}
			$AVE_Document->documentListGet();
		}
		$AVE_Template->assign('content', $AVE_Template->fetch('documents/docs.tpl'));
		break;

	case 'add_new':
		if (check_permission_acp('documents'))
		{
			$AVE_Request->requestListFetch();
			$AVE_Template->assign('content', $AVE_Template->fetch('documents/docs_add_new.tpl'));
		}
		break;

	case 'showsimple':
		if (check_permission_acp('documents'))
		{
			$AVE_Document->documentListGet();
			$AVE_Template->assign('content', $AVE_Template->fetch('documents/docs_simple.tpl'));
		}
		break;

	case 'edit':
		if (check_permission_acp('documents'))
		{
			$_SESSION['use_editor'] = get_settings('use_editor');
			$AVE_Navigation->navigationAllItemList();
			$AVE_Request->requestListFetch();
			$AVE_Document->documentEdit((int)$_REQUEST['Id']);
		}
		break;

	case 'copy':
		if (check_permission_acp('documents'))
		{
			$_SESSION['use_editor'] = get_settings('use_editor');
			$AVE_Navigation->navigationAllItemList();
			$AVE_Request->requestListFetch();
			$AVE_Document->documentCopy((int)$_REQUEST['Id']);
		}
		break;

	case 'new':
		if (check_permission_acp('documents'))
		{
			$_SESSION['use_editor'] = get_settings('use_editor');
			$AVE_Navigation->navigationAllItemList();
			$AVE_Request->requestListFetch();
			$AVE_Document->documentNew((int)$_REQUEST['rubric_id']);
		}
		break;

	case 'innavi':
		if (check_permission_acp('documents') && check_permission_acp('navigation_new'))
		{
			$AVE_Document->documentInNavi();
		}
		break;

	case 'after':
		if (check_permission_acp('documents'))
		{
			$AVE_Navigation->navigationAllItemList();
			$AVE_Document->documentFormAfter();
		}
		break;

	case 'open':
		if (check_permission_acp('documents'))
		{
			$AVE_Navigation->navigationItemStatusOn((int)$_REQUEST['Id']);
			$AVE_Document->documentStatusSet((int)$_REQUEST['Id'], 1);
		}
		break;

	case 'close':
		if (check_permission_acp('documents'))
		{
			$AVE_Navigation->navigationItemStatusOff((int)$_REQUEST['Id']);
			$AVE_Document->documentStatusSet((int)$_REQUEST['Id'], 0);
		}
		break;

	case 'delete':
		if (check_permission_acp('documents'))
		{
			$AVE_Navigation->navigationItemStatusOff((int)$_REQUEST['Id']);
			$AVE_Document->documentMarkDelete((int)$_REQUEST['Id']);
		}
		break;

	case 'redelete':
		if (check_permission_acp('alles'))
		{
			$AVE_Navigation->navigationItemStatusOn((int)$_REQUEST['Id']);
			$AVE_Document->documentUnmarkDelete((int)$_REQUEST['Id']);
		}
		break;

	case 'enddelete':
		if (check_permission_acp('alles'))
		{
			$AVE_Navigation->navigationItemDelete((int)$_REQUEST['Id']);
			$AVE_Document->documentDelete((int)$_REQUEST['Id']);
		}
		break;

	case 'recover':
		if (check_permission_acp('documents'))
		{
			$AVE_Document->RestoreRevission((int)$_REQUEST['doc_id'], (int)$_REQUEST['revission']);
			header('Location:index.php?do=docs&action=edit&Id=' . (int)$_REQUEST['doc_id'] . '&rubric_id=' . (int)$_REQUEST['rubric_id'] . '&cp=' . SESSION);
			exit;
		}
		break;

	case 'recover_del':
		if (check_permission_acp('documents'))
		{
			$AVE_Document->DeleteRevission((int)$_REQUEST['doc_id'], (int)$_REQUEST['revission'], (int)$_REQUEST['rubric_id']);
			exit;
		}
		break;

	case 'remark':
		if (check_permission_acp('remarks'))
		{
			$AVE_Document->documentRemarkNew((int)$_REQUEST['Id'], 0);
		}
		break;

	case 'remark_reply':
		if (check_permission_acp('remarks'))
		{
			$AVE_Document->documentRemarkNew((int)$_REQUEST['Id'], 1);
		}
		break;

	case 'remark_status':
		if (check_permission_acp('remark_status'))
		{
			$AVE_Document->documentRemarkStatus((int)$_REQUEST['Id'], (int)$_REQUEST['remark_status']);
		}
		break;

	case 'remark_del':
		if (check_permission_acp('remark_del'))
		{
			$AVE_Document->documentRemarkDelete((int)$_REQUEST['Id'], (int)$_REQUEST['remark_first']);
		}
		break;

	case 'change':
		if (check_permission_acp('documents'))
		{
			$AVE_Document->documentRubricChange();
		}
		break;

	case 'change_user':
		if (check_permission_acp('documents'))
		{
			switch($_REQUEST['sub'])
			{
				case 'save':
					$AVE_Document->changeAutorSave();
					break;
			}
			$AVE_Template->assign('content', $AVE_Template->fetch('documents/user.tpl'));
		}
		break;

	case 'find_user':
		if (check_permission_acp('documents'))
		{
			findautor($_REQUEST['q'], 10);
		}
		exit;

	case 'keywords':
		if (check_permission_acp('documents'))
		{
			searchKeywords($_REQUEST['q']);
		}
		exit;

	case 'editstatus':
		if (check_permission_acp('documents'))
		{
			$AVE_Document->editstatus();
		}
		break;

	case 'image_import':
		echo json_encode(array("respons"=>image_multi_import($_REQUEST['path']), "status"=>"error", "action"=>"return"));
		exit;

	case 'translit':
		echo($AVE_Document->documentAliasCreate());
		exit;

	case 'checkurl':
		echo($AVE_Document->documentAliasCheck());
		exit;
}

?>