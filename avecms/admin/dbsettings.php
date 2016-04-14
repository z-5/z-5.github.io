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

check_permission_acp('dbactions');

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/dbactions.txt', 'db');

require(BASE_DIR . '/class/class.dbdump.php');
$AVE_DB_Service = new AVE_DB_Service;

if (!empty($_REQUEST['action']))
{
	switch ($_REQUEST['action'])
	{
		case 'optimize':
			$AVE_DB_Service->databaseTableOptimize();
			break;

		case 'repair':
			$AVE_DB_Service->databaseTableRepair();
			break;

		case 'dump_top':
			$AVE_DB_Service->databaseDumpExport(1);
			exit;

		case 'dump':
			$AVE_DB_Service->databaseDumpExport();
			exit;

		case 'restore':
			$AVE_DB_Service->databaseDumpImport(BASE_DIR . "/" . ATTACH_DIR . "/");
			break;
	}
}

$AVE_Template->assign('db_size', get_mysql_size());
$AVE_Template->assign('tables', $AVE_DB_Service->databaseTableGet());
$AVE_Template->assign('content', $AVE_Template->fetch('dbactions/actions.tpl'));

?>