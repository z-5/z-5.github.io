<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @subpackage admin
 * @filesource
 */

if (!defined("ACP"))
{
	header("Location:index.php");
	exit;
}

require(BASE_DIR . "/class/class.request.php");
require(BASE_DIR . "/class/class.docs.php");
require(BASE_DIR . "/class/class.rubs.php");

$AVE_Request = new AVE_Request;
$AVE_Document = new AVE_Document;
$AVE_Rubric = new AVE_Rubric;

$AVE_Rubric->rubricPermissionFetch();

$AVE_Template->config_load(BASE_DIR . "/admin/lang/" . $_SESSION['admin_language'] . "/request.txt", 'request');

switch ($_REQUEST['action'])
{
	case '':
		if(check_permission_acp('request'))
		{
			$AVE_Request->requestListShow();
		}
		break;

	case 'edit':
		if(check_permission_acp('request'))
		{
			$AVE_Rubric->rubricTemplateShow(0, 1);
			$AVE_Request->requestEdit((int)$_REQUEST['Id']);
		}
		break;

	case 'copy':
		if(check_permission_acp('request'))
		{
			$AVE_Request->requestCopy((int)$_REQUEST['Id']);
		}
		break;

	case 'new':
		if(check_permission_acp('request_new'))
		{
			$AVE_Rubric->rubricTemplateShow(0, 1);
			$AVE_Request->requestNew();
		}
		break;

	case 'delete_query':
		if(check_permission_acp('request_del'))
		{
			$AVE_Request->requestDelete((int)$_REQUEST['Id']);
		}
		break;

	case 'konditionen':
		if(check_permission_acp('request'))
		{
			$AVE_Rubric->rubricTemplateShow(0, 1);
			$AVE_Request->requestConditionEdit((int)$_REQUEST['Id']);
		}
		break;
}

?>