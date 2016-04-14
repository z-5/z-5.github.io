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

require(BASE_DIR . '/class/class.logs.php');

$AVE_Logs = new AVE_Logs;

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/logs.txt', 'logs');

switch ($_REQUEST['action'])
{
	case '':
		if (check_permission_acp('logs'))
		{
			$AVE_Logs->logList();
		}
		break;

	case 'delete':
		if (check_permission_acp('logs'))
		{
			$AVE_Logs->logDelete();
		}
		break;

	case 'export':
		if (check_permission_acp('logs'))
		{
			$AVE_Logs->logExport();
		}
		break;

	case 'log404':
		if (check_permission_acp('logs'))
		{
			$AVE_Logs->List404();
		}
		break;

	case 'delete404':
		if (check_permission_acp('logs'))
		{
			$AVE_Logs->Delete404();
		}
		break;

	case 'export404':
		if (check_permission_acp('logs'))
		{
			$AVE_Logs->Export404();
		}
		break;
}

?>