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

global $AVE_Template;

require(BASE_DIR . '/class/class.settings.php');
$AVE_Settings = new AVE_Settings;

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/settings.txt','settings');

switch($_REQUEST['action'])
{
	case '':
		if(check_permission_acp('gen_settings'))
		{
			switch ($_REQUEST['sub'])
			{
				case '':
					$AVE_Settings->settingsShow();
					break;

				case 'case':
					$AVE_Settings->settingsCase();
					break;	

				case 'save':
					if ($_REQUEST['dop']) {
						$AVE_Settings->settingsCase();
					} else {
						$AVE_Settings->settingsSave();
					}
					//header('Location:index.php?do=settings&saved=1&cp=' . SESSION);
					//exit;
					break;

				case 'countries':
					if (isset($_REQUEST['save']) && $_REQUEST['save'] == 1)
					{
						$AVE_Settings->settingsCountriesSave();

						header('Location:index.php?do=settings&sub=countries&cp=' . SESSION);
						exit;
					}
					$AVE_Settings->settingsCountriesList();
					break;

				case 'language':
					if(isset($_REQUEST['func'])){
						switch($_REQUEST['func'])
						{
							case 'default':
								if(isset($_REQUEST['Id'])){
									$exists=$AVE_DB->Query("SELECT Id FROM ".PREFIX."_settings_lang WHERE Id=".(int)$_REQUEST['Id'])->GetCell();
									if($exist){
										$AVE_DB->Query("UPDATE ".PREFIX."_settings_lang SET lang_defult=0");
										$AVE_DB->Query("UPDATE ".PREFIX."_settings_lang SET lang_defult=1 WHERE Id=".(int)$_REQUEST['Id']." LIMIT 1");
									}
								}
								header('Location:index.php?do=settings&sub=language&cp=' . SESSION);
								exit;

							case 'on':
								if(isset($_REQUEST['Id'])){
									$AVE_DB->Query("UPDATE ".PREFIX."_settings_lang SET lang_status=1 WHERE Id=".(int)$_REQUEST['Id']);
								}
								header('Location:index.php?do=settings&sub=language&cp=' . SESSION);
								exit;

							case 'off':
								if(isset($_REQUEST['Id'])){
									$AVE_DB->Query("UPDATE ".PREFIX."_settings_lang SET lang_status=0 WHERE Id=".(int)$_REQUEST['Id']);
								}
								header('Location:index.php?do=settings&sub=language&cp=' . SESSION);
								exit;

							case 'save':
								$AVE_Settings->settingsLanguageEditSave();
								exit;
						}
					}
					else
					{
						$AVE_Settings->settingsLanguageList();
						break;
					}	

				case 'editlang':
					$AVE_Settings->settingsLanguageEdit();
					break;

				case 'clearcache':
					$AVE_Template->CacheClear();
					exit;

				case 'clearthumb':
					$AVE_Template->ThumbnailsClear();
					exit;

				case 'showcache':
					cacheShow();
					exit;
			}
		}
		break;
}

?>