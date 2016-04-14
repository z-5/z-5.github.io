<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

/**
 * Класс управления настройками системы
 */
class AVE_Settings
{
/**
 *	СВОЙСТВА
 */

	/**
	 * Количество стран на странице
	 *
	 * @var int
	 */
	var $_limit = 15;

/**
 *	ВНУТРЕННИЕ МЕТОДЫ
 */

	function _clearCode($code)
	{
		return preg_replace(
			array("'<'",   "'>'",   "'<b>'i",   "'</b>'i", "'<i>'i", "'</i>'i", "'<br>'i", "'<br/>'i"),
			array('&lt;', '&gt;', '<strong>', '</strong>',   '<em>',   '</em>',  '<br />',   '<br />'),
			$code);
	}

/**
 *	ВНЕШНИЕ МЕТОДЫ
 */

	/**
	 * Метод отображения настроек
	 *
	 */
	function settingsShow()
	{
		global $AVE_Template;

		$date_formats = array(
			'%d.%m.%Y',
			'%d %B %Y',
			'%A, %d.%m.%Y',
			'%A, %d %B %Y'
		);

		$time_formats = array(
			'%d.%m.%Y, %H:%M',
			'%d %B %Y, %H:%M',
			'%A, %d.%m.%Y (%H:%M)',
			'%A, %d %B %Y (%H:%M)'
		);

		$AVE_Template->assign('date_formats', $date_formats);
		$AVE_Template->assign('time_formats', $time_formats);
		$AVE_Template->assign('row', get_settings());
		$AVE_Template->assign('available_countries', get_country_list(1));
		$AVE_Template->assign('content', $AVE_Template->fetch('settings/settings_main.tpl'));
	}

	/**
	 * Метод отображения дополнительных настроек
	 *
	 */
	function settingsCase() 	{
		 global $AVE_Template;
		 if (@$_REQUEST['dop']) {
			$set='<?php';
			foreach($_REQUEST['GLOB'] as $k=>$v){
				switch ($GLOBALS['CMS_CONFIG'][$k]['TYPE']) {
						case 'bool' : $v=$v ? 'true' : 'false'; break;
						case 'integer' : $v=intval($v); break;
						case 'string' : $v="'".add_slashes($v)."'";break;
						case 'dropdown' : $v="'".add_slashes($v)."'";break;
						default : $v="'".add_slashes($v)."'";break;
				}
				$set.="
				//".$GLOBALS['CMS_CONFIG'][$k]['DESCR']."\r\n";
				$set.="	define('".$k."',".$v.");\r\n\r\n";
			}
			$set.='?>';
			file_put_contents(BASE_DIR.'/inc/config.inc.php',$set);
			reportLog($_SESSION['user_name'] . " - " . $AVE_Template->get_config_vars('SETTINGS_SAVE_DOP'), 2, 2);
 		}
		$AVE_Template->assign('CMS_CONFIG',$GLOBALS['CMS_CONFIG']);
		$AVE_Template->assign('content', $AVE_Template->fetch('settings/settings_case.tpl'));
	}

	/**
	 * Метод записи настроек
	 *
	 */
	function settingsSave()
	{
		global $AVE_DB,  $AVE_Template;

		$muname = ($_REQUEST['mail_smtp_login'])    ? "mail_smtp_login = '" . $_REQUEST['mail_smtp_login'] . "',"       : '';
		$mpass  = ($_REQUEST['mail_smtp_pass'])     ? "mail_smtp_pass = '" . $_REQUEST['mail_smtp_pass'] . "',"         : '';
		$msmp   = ($_REQUEST['mail_sendmail_path']) ? "mail_sendmail_path = '" . $_REQUEST['mail_sendmail_path'] . "'," : '';
		$mn     = ($_REQUEST['mail_from_name'])     ? "mail_from_name = '" . $_REQUEST['mail_from_name'] . "',"         : '';
		$ma     = ($_REQUEST['mail_from'])          ? "mail_from = '" . $_REQUEST['mail_from'] . "',"                   : '';
		$ep     = ($_REQUEST['page_not_found_id'])  ? "page_not_found_id = '" . $_REQUEST['page_not_found_id'] . "',"   : '';
		$sn     = ($_REQUEST['site_name'])          ? "site_name = '" . $_REQUEST['site_name'] . "',"                   : '';
		$mp     = ($_REQUEST['mail_port'])          ? "mail_port = '" . $_REQUEST['mail_port'] . "',"                   : '';
		$mh     = ($_REQUEST['mail_host'])          ? "mail_host = '" . $_REQUEST['mail_host'] . "',"                   : '';

		$AVE_DB->Query("
			UPDATE " . PREFIX . "_settings
			SET
				" . $muname . "
				" . $mpass . "
				mail_smtp_encrypt = '" . $_REQUEST['mail_smtp_encrypt'] . "',
				" . $msmp . "
				" . $ma . "
				" . $mn . "
				" . $ep . "
				" . $sn . "
				" . $mp . "
				" . $mh . "
				default_country   = '" . $_REQUEST['default_country'] . "',
				mail_type         = '" . $_REQUEST['mail_type'] . "',
				mail_content_type = '" . $_REQUEST['mail_content_type'] . "',
				mail_word_wrap    = '" . (int)$_REQUEST['mail_word_wrap'] . "',
				mail_new_user     = '" . $_REQUEST['mail_new_user'] . "',
				mail_signature    = '" . $_REQUEST['mail_signature'] . "',
            message_forbidden = '" . $_REQUEST['message_forbidden'] . "',
				hidden_text       = '" . $_REQUEST['hidden_text'] . "',
				navi_box          = '" . $_REQUEST['navi_box'] . "',
				total_label       = '" . $this->_clearCode($_REQUEST['total_label']) . "',
				start_label       = '" . $this->_clearCode($_REQUEST['start_label']) . "',
				end_label         = '" . $this->_clearCode($_REQUEST['end_label']) . "',
				separator_label   = '" . $this->_clearCode($_REQUEST['separator_label']) . "',
				next_label        = '" . $this->_clearCode($_REQUEST['next_label']) . "',
				prev_label        = '" . $this->_clearCode($_REQUEST['prev_label']) . "',
				date_format       = '" . $_REQUEST['date_format'] . "',
				time_format       = '" . $_REQUEST['time_format'] . "',
				use_doctime       = '" . intval($_REQUEST['use_doctime']) . "',
				use_editor       = '" . intval($_REQUEST['use_editor']) . "'
			WHERE
				Id = 1
		");
		
		reportLog($_SESSION['user_name'] . " - " . $AVE_Template->get_config_vars('SETTINGS_SAVE_MAIN'), 2, 2);
		//header('Location:index.php?do=settings&clear=1&cp=' . SESSION);
		exit;
	}

	/**
	 * Метод отображения списка стран
	 *
	 */
	function settingsCountriesList()
	{
		global $AVE_DB, $AVE_Template;

		$sql = $AVE_DB->Query("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM " . PREFIX . "_countries
			ORDER BY country_status ASC, country_name ASC
			LIMIT " . (get_current_page() * $this->_limit - $this->_limit) . "," . $this->_limit
		);

		$laender = array();
		while ($row = $sql->FetchAssocArray())
		{
			array_push($laender, $row);
		}

		$num = $AVE_DB->Query("SELECT FOUND_ROWS()")->GetCell();

		if ($num > $this->_limit)
		{
			$page_nav = "<li><a href=\"index.php?do=settings&sub=countries&page={s}&amp;cp=" . SESSION . "\">{t}</a></li>";
			$page_nav = get_pagination(ceil($num / $this->_limit), 'page', $page_nav);
			$AVE_Template->assign('page_nav', $page_nav);
		}

		$AVE_Template->assign('laender', $laender);
		$AVE_Template->assign('content', $AVE_Template->fetch('settings/settings_countries.tpl'));
	}

	/**
	 * Метод записи параметров стран
	 *
	 */
	function settingsCountriesSave()
	{
		global $AVE_DB, $AVE_Template;

		foreach ($_POST['country_name'] as $id => $country_name)
		{
			$AVE_DB->Query("
				UPDATE " . PREFIX . "_countries
				SET
					country_name   = '" . $country_name . "',
					country_status = '" . $_POST['country_status'][$id] . "',
					country_eu     = '" . $_POST['country_eu'][$id] . "'
				WHERE
					Id = '" . $id . "'
			");
		}

		reportLog($_SESSION['user_name'] . " - " . $AVE_Template->get_config_vars('SETTINGS_SAVE_COUNTRY'), 2, 2);
	}


	/**
	 * Метод отображения списка языков
	 *
	 */
	function settingsLanguageList()
	{
		global $AVE_DB, $AVE_Template;

		$sql = $AVE_DB->Query("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM " . PREFIX . "_settings_lang
			ORDER BY  lang_default ASC, lang_key ASC
		");

		$language = array();
		while ($row = $sql->FetchAssocArray())
		{
			array_push($language, $row);
		}
		$AVE_Template->assign('language', $language);
		$AVE_Template->assign('content', $AVE_Template->fetch('settings/settings_lang.tpl'));
	}

	/**
	 * Метод Редактирования параметров языков
	 *
	 */
	function settingsLanguageEdit()
	{
		global $AVE_DB, $AVE_Template;

		if (isset($_REQUEST["Id"]))
		{
			$items = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_settings_lang
			WHERE
					Id = '" . $_REQUEST["Id"] . "'
			")->FetchRow();

			$AVE_Template->assign('items', $items);
		}

		$AVE_Template->assign('content', $AVE_Template->fetch('settings/settings_lang_edit.tpl'));
	}
	
	function settingsLanguageEditSave()
	{
		global $AVE_DB, $AVE_Template;

		if (!empty($_REQUEST["Id"]))
		{
			$AVE_DB->Query("
			UPDATE 
			" . PREFIX . "_settings_lang
			SET
				lang_key = '" .$_REQUEST['lang_key']. "',
				lang_alias_pref = '" .$_REQUEST['lang_alias_pref']. "',
				lang_name = '" .$_REQUEST['lang_name']. "'
			WHERE
					Id = '" . $_REQUEST["Id"] . "'
			");
		}
		else
		{
			$AVE_DB->Query("
			INSERT INTO 
			" . PREFIX . "_settings_lang
			SET
				lang_key = '" .$_REQUEST['lang_key']. "',
				lang_name = '" .$_REQUEST['lang_name']. "',
				lang_alias_pref = '" .$_REQUEST['lang_alias_pref']. "',
				lang_default = '0',
				lang_status = '0'
			");
		
		}
		echo "<script>window.opener.location.reload(); window.close();</script>";
	}

}

?>