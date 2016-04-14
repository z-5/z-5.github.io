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
	header('Location:index.php');
	exit;
}

require(BASE_DIR . '/class/class.rubs.php');
$AVE_Rubric = new AVE_Rubric;

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/rubs.txt', 'rubs');

switch($_REQUEST['action'])
{
	case '' :
		if(check_permission_acp('rubrics'))
		{
			switch($_REQUEST['sub'])
			{
				case 'quicksave':
					$AVE_Rubric->quickSave();
					break;
			}
			$AVE_Rubric->rubricList();
			$AVE_Template->assign('templates', get_all_templates());
		}

		$AVE_Template->assign('content', $AVE_Template->fetch('rubs/rubs.tpl'));
		break;

	case 'new':
		if(check_permission_acp('rubric_new'))
		{
			$AVE_Rubric->rubricNew();
		}
		break;


	case 'template':
		if(check_permission_acp('rubric_edit'))
		{
			switch($_REQUEST['sub'])
			{
				case '':
					$AVE_Rubric->rubricTemplateShow();
					break;

				case 'save':
					$Rtemplate = $_POST['rubric_template'];
					$Htemplate = $_POST['rubric_header_template'];
					$Ttemplate = $_POST['rubric_teaser_template'];
					$Atemplate = $_POST['rubric_admin_teaser_template'];
					$check_code = strtolower($Rtemplate.$Htemplate.$Ttemplate.$Atemplate);
					$ok = true;

					if((is_php_code($check_code)) && !check_permission('rubric_php') )
					{
						$AVE_Template->assign('php_forbidden', 1);
						$ok = false;
					}

					if(!$ok)
					{
						$AVE_Rubric->rubricTemplateShow(1);
					}
					else
					{
						$AVE_Rubric->rubricTemplateSave($Rtemplate, $Htemplate, $Ttemplate, $Atemplate);
					}
					break;
			}
		}
		break;

	case 'delete':
		if(check_permission_acp('rubric_del'))
		{
			$AVE_Rubric->rubricDelete();
		}
		break;

	case 'multi':
		if(check_permission_acp('rubric_multi'))
		{
			switch($_REQUEST['sub'])
			{
				case 'save':
					$AVE_Rubric->rubricCopy();
					break;
			}
		}

		$AVE_Template->assign('content', $AVE_Template->fetch('rubs/multi.tpl'));
		break;

	case 'edit':
		if(check_permission_acp('rubric_edit'))
		{
			switch($_REQUEST['sub'])
			{
				case '':
					switch($_REQUEST['submit'])
					{
						case 'saveperms':
							$AVE_Rubric->rubricPermissionSave((int)$_REQUEST['Id']);
							break;

						case 'save':
							$AVE_Rubric->rubricFieldSave((int)$_REQUEST['Id']);
							break;

						case 'linked_rubric':
							$AVE_Rubric->rubricShow(1);
							break;

						case 'code':
							$AVE_Rubric->rubricCode((int)$_REQUEST['Id']);
							break;

						case 'description':
							$AVE_Rubric->rubricDesc((int)$_REQUEST['Id']);
							break;

						case 'next':
							header('Location:index.php?do=rubs&action=template&Id=' . $_REQUEST['Id'] . '&cp=' . SESSION);
							exit;
//							break;

						case 'newfield':
							$AVE_Rubric->rubricFieldNew((int)$_REQUEST['Id']);
							break;
					}
					$AVE_Rubric->rubricFieldShow((int)$_REQUEST['Id']);
					break;
			}
		}
		break;

	case 'alias_add':
		$AVE_Rubric->rubricAliasAdd();
		break;

	case 'alias_check':
		$AVE_Rubric->rubricAliasCheck((int)$_REQUEST['rubric_id'],(int)$_REQUEST['field_id'], $_REQUEST['rubric_field_alias']);
		break;
}

?>