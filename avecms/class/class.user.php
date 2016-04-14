<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

/**
 * Класс для работы с группами и учетными записями пользователей
 */
class AVE_User
{
/**
 *	СВОЙСТВА
 */

	/**
	 * Количество Пользователей отображаемых на одной странице списка
	 *
	 * @var int
	 */
	var $_limit = 15;

	/**
	 * Допустимые права доступа в административной панели
	 *
	 * @var array
	 */
	var $_allowed_admin_permission = array(
		'alles',																							// все права
		'adminpanel',																						// доступ в админку
		'gen_settings','gen_settings_more','gen_settings_countries',										// общие настройки
		'modules', 'modules_admin',																			// модули
		'group', 'group_new', 'group_edit',																	// группы пользователей
		'user', 'user_new', 'user_edit', 'user_del', 'user_perms',											// пользователи
		'template', 'template_new', 'template_edit', 'template_del', 'template_multi', 'template_php',		// шаблоны
		'rubrics', 'rubric_new', 'rubric_edit', 'rubric_del', 'rubric_multi', 'rubric_perms', 'rubric_php',	// рубрики
		'documents', 'document_php',																		// документы
		'remarks', 'remark_status', 'remark_del',															// заметки
		'request', 'request_new', 'request_del',															// запросы
		'navigation', 'navigation_new', 'navigation_edit',													// навигация
		'mediapool', 'mediapool_del', 'finder',																// файловый менеджер
		'dbactions',																						// база данных
		'sysblocks',																						// системные блоки
		'liveeditor',																						// настройки LiveEditor
		'logs','logs_clear',																				// логи
		'session_clear'																						// сессии и кеш
	);

	/**
	 * Разделитель используемый при записи даты рождения
	 *
	 * @var string
	 */
	var $_birthday_delimetr = '.';

/**
 *	ВНУТРЕННИЕ МЕТОДЫ
 */

	/**
	 * Проверка элементов учетной записи пользователя
	 *
	 * @param boolean $new признак проверки элементов новой учетной записи
	 * @return array
	 */
	function _userFieldValidate($new = false)
	{
		global $AVE_DB, $AVE_Template;

		$errors = array();

		$regex = '/[^\x20-\xFF]/';
		$regex_username = '/[^\w-]/';
		$regex_password = '/[^\x21-\xFF]/';
		$regex_birthday = '#(0[1-9]|[12][0-9]|3[01])([[:punct:]| ])(0[1-9]|1[012])\2(19|20)\d\d#';
//		$regex_email = "¬^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)$¬i";
		$regex_email = '/^[\w.-]+@[a-z0-9.-]+\.(?:[a-z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)$/i';

		// Проверка логина
		if (empty($_POST['user_name']))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_NO_USERNAME');
		}
		elseif (preg_match($regex_username, $_POST['user_name']))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_ERROR_USERNAME');
		}

		// Проверка имени
		if (empty($_POST['firstname']))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_NO_FIRSTNAME');
		}
		elseif (preg_match($regex, stripslashes($_POST['firstname'])))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_ERROR_FIRSTNAME');
		}

		// Проверка фамилии
		if (empty($_POST['lastname']))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_NO_LASTNAME');
		}
		elseif (preg_match($regex, stripslashes($_POST['lastname'])))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_ERROR_LASTNAME');
		}

		// Проверка e-Mail
		if (empty($_POST['email']))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_NO_EMAIL');
		}
		elseif (!preg_match($regex_email, $_POST['email']))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_EMAIL_ERROR');
		}
		else
		{
			$email_exist = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_users
				WHERE email != '" . $_POST['Email_Old'] . "'
				AND email = '" . $_POST['email'] . "'
				" . ($new ? "AND email != '" . $_SESSION['user_email'] . "'" : '') . "
				LIMIT 1
			")->NumRows();
			if ($email_exist==1)
			{
				$errors[] = @$AVE_Template->get_config_vars('USER_EMAIL_EXIST');
			}
		}

		// Проверка пароля
		if (isset($_REQUEST['action']) && $_REQUEST['action'] != 'edit')
		{
			if (empty($_POST['password']))
			{
				$errors[] = @$AVE_Template->get_config_vars('USER_NO_PASSWORD');
			}
			elseif (strlen($_POST['password']) < 4)
			{
				$errors[] = @$AVE_Template->get_config_vars('USER_PASSWORD_SHORT');
			}
			elseif (preg_match($regex_password, $_POST['password']))
			{
				$errors[] = @$AVE_Template->get_config_vars('USER_PASSWORD_ERROR');
			}
		}

		// Проверка даты рождения
		$match = '';
		if (!empty($_POST['birthday']) && !preg_match($regex_birthday, $_POST['birthday'], $match))
		{
			$errors[] = @$AVE_Template->get_config_vars('USER_ERROR_DATEFORMAT');
		}
		elseif (!empty($match))
		{

			$_POST['birthday'] = $match[1]
			. $this->_birthday_delimetr . $match[3]
			. $this->_birthday_delimetr . $match[4];
		}

		return $errors;
	}

/**
 *	ВНЕШНИЕ МЕТОДЫ
 */

	/**
	 * Группы пользователей
	 */

	/**
	 * Получение списка Групп пользователей
	 *
	 * @param string $exclude идентификатор исключаемой Группы пользователей (гостей)
	 * @return array
	 */
	function userGroupListGet($exclude = '')
	{
		global $AVE_DB;

		$user_groups = array();
		$sql = $AVE_DB->Query("
			SELECT
				grp.*,
				COUNT(usr.Id) AS UserCount
			FROM
				" . PREFIX . "_user_groups AS grp
			LEFT JOIN
				" . PREFIX . "_users AS usr
					ON usr.user_group = grp.user_group
			" . (($exclude != '' && is_numeric($exclude)) ? "WHERE grp.user_group != '" . $exclude . "'" : '') . "
			GROUP BY grp.user_group
		");

		while ($row = $sql->FetchRow())
		{
			array_push($user_groups, $row);
		}

		return $user_groups;
	}

	/**
	 * Отобразить список Групп пользователей
	 *
	 */
	function userGroupListShow()
	{
		global $AVE_Template;

		$AVE_Template->assign('ugroups', $this->userGroupListGet());
		$AVE_Template->assign('content', $AVE_Template->fetch('groups/groups.tpl'));
	}

	/**
	 * Создание новой Группы пользователей
	 *
	 */
	function userGroupNew()
	{
		global $AVE_DB;

		if (!empty($_POST['user_group_name']))
		{
			$AVE_DB->Query("
				INSERT
				INTO " . PREFIX . "_user_groups
				SET
					user_group            = '',
					user_group_name       = '" . $_POST['user_group_name'] . "',
					status                = '1',
					user_group_permission = ''
			");
			$iid = $AVE_DB->InsertId();

			reportLog($_SESSION['user_name'] . ' - Создал группу пользователей (' . $iid . ')', 2, 2);

			header('Location:index.php?do=groups&action=grouprights&Id=' . $iid . '&cp=' . SESSION);
		}
		else
		{
			header('Location:index.php?do=groups&cp=' . SESSION);
		}
	}

	/**
	 * Удаление Группы пользователей
	 *
	 * @param int $user_group_id идентификатор Группы пользователей
	 */
	function userGroupDelete($user_group_id = '0')
	{
		global $AVE_DB;

		if (is_numeric($user_group_id) && $user_group_id > 2)
		{
			$exist_user_in_group = $AVE_DB->Query("
				SELECT user_group
				FROM " . PREFIX . "_users
				WHERE user_group = '" . $user_group_id . "'
				LIMIT 1
			")->NumRows();

			if (!$exist_user_in_group)
			{
				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_user_groups
					WHERE user_group = '" . $user_group_id . "'
				");

				reportLog($_SESSION['user_name'] . ' - Удалил группу пользователей (' . $user_group_id . ')', 2, 2);
			}
		}

		header('Location:index.php?do=groups&cp=' . SESSION);
	}

	/**
	 * Редактирование прав Группы пользователей
	 *
	 * @param int $user_group_id идентификатор Группы пользователей
	 */
	function userGroupPermissionEdit($user_group_id)
	{
		global $AVE_DB, $AVE_Template, $AVE_Module;

		if (UGROUP != 1 && UGROUP == $user_group_id)
		{
			$AVE_Template->assign('own_group', true);
		}
		else
		{
			if (is_numeric($user_group_id) && $user_group_id)
			{
				$row = $AVE_DB->Query("
					SELECT
						user_group_name,
						user_group_permission
					FROM " . PREFIX . "_user_groups
					WHERE user_group = '" . $user_group_id . "'
				")->FetchRow();
			}

			if (empty($row))
			{
				$AVE_Template->assign('no_group', true);
			}
			else
			{
				$AVE_Template->assign('g_all_permissions', $this->_allowed_admin_permission);
				$AVE_Template->assign('g_group_permissions', explode('|', $row->user_group_permission));
				$AVE_Template->assign('g_name', $row->user_group_name);
				$AVE_Template->assign('modules', $AVE_Module->moduleListGet(1));
			}
		}

		$AVE_Template->assign('content', $AVE_Template->fetch('groups/perms.tpl'));
	}

	/**
	 * Запись прав Групп пользователей
	 *
	 * @param int $user_group_id идентификатор Группы пользователей
	 */
	function userGroupPermissionSave($user_group_id)
	{
		global $AVE_DB;

		if (is_numeric($user_group_id))
		{
			$perms = (!empty($_REQUEST['perms']) && is_array($_REQUEST['perms'])) ? implode('|', $_REQUEST['perms']) : '';
			$perms = ($user_group_id == '1' || in_array('alles', $_REQUEST['perms'])) ? 'alles' : $perms;
			$perms = ($user_group_id == '2') ? '' : $perms;

			$AVE_DB->Query("
				UPDATE " . PREFIX . "_user_groups
				SET user_group_permission = '" . $perms . "'
				" . (!empty($_POST['user_group_name']) ? ", user_group_name = '" . $_POST['user_group_name'] . "'" : '') . "
				WHERE user_group = '" . $user_group_id . "'
			");

			reportLog($_SESSION['user_name'] . ' - Изменил права доступа для группы (' . $user_group_id . ')', 2, 2);
		}

		header('Location:index.php?do=groups&cp=' . SESSION);
		exit;
	}

	/**
	 * Учетные записи пользователей
	 */

	/**
	 * Формирование спискка учетных записей пользователей
	 *
	 * @param int $user_group_id идентификатор Группы пользователей
	 */
	function userListFetch($user_group_id = '')
	{
		global $AVE_DB, $AVE_Template;

		$search_by_group = '';
		$search_by_id_or_name = '';
		$user_group_navi = '';
		$query_navi = '';
		$status_search = '';
		$status_navi = '';

		if (isset($_REQUEST['user_group']) && $_REQUEST['user_group'] != '0')
		{
			$user_group_id = ($user_group_id != '') ? $user_group_id : $_REQUEST['user_group'];
			$user_group_navi = '&amp;user_group=' . $user_group_id;
			$search_by_group = " AND user_group = '" . $user_group_id . "' ";
		}

		if (!empty($_REQUEST['query']))
		{
			$q = urldecode($_REQUEST['query']);
			$search_by_id_or_name = "
				AND (email LIKE '%" . $q . "%'
				OR email = '" . $q . "'
				OR Id = '" . $q . "'
				OR firstname LIKE '" . $q . "%'
				OR lastname LIKE '" . $q . "%')
			";
			$query_navi = '&amp;query=' . urlencode($_REQUEST['query']);
		}

		if (isset($_REQUEST['status']) && $_REQUEST['status'] != 'all')
		{
			$status_search = " AND status = '" . $_REQUEST['status'] . "' ";
			$status_navi   = '&amp;status=' . $_REQUEST['status'];
		}

		$num = $AVE_DB->Query("
			SELECT COUNT(*)
			FROM " . PREFIX . "_users
			WHERE 1"
			. $search_by_group
			. $search_by_id_or_name
			. $status_search
		)->GetCell();

		$sql = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_users
			WHERE 1"
			. $search_by_group
			. $search_by_id_or_name
			. $status_search
			. " LIMIT " . (get_current_page()*$this->_limit-$this->_limit) . "," . $this->_limit
		);

		$isShop = $AVE_DB->Query("SHOW TABLES LIKE '" . PREFIX . "_modul_shop_bestellungen'")->GetCell();
		$users = array();

		while ($row = $sql->FetchRow())
		{
			// для комментариев
			//$sqla = $AVE_DB->Query("SELECT * FROM " . PREFIX . "_modul_comment_info WHERE comment_author_id = '".(int)$row->Id."'");
			//$row->comments = $sqla->numrows();
			$row->avatar=getAvatar($row->Id,40);
			array_push($users, $row);
		}

		if ($num > $this->_limit)
		{
			$page_nav = '<li><a href="index.php?do=user' . $status_navi . '&page={s}&cp=' . SESSION . $user_group_navi . $query_navi . '">{t}</a></li>';
			$page_nav = get_pagination(ceil($num/$this->_limit), 'page', $page_nav);
			$AVE_Template->assign('page_nav', $page_nav);
		}

		$AVE_Template->assign('ugroups', $this->userGroupListGet());
		$AVE_Template->assign('users', $users);
	}

	/**
	 * Создание новой учетной записи
	 *
	 */
	function userNew()
	{
		global $AVE_DB, $AVE_Template;

		switch($_REQUEST['sub'])
		{
			case '':
				$AVE_Template->assign('available_countries', get_country_list(1));
				$AVE_Template->assign('ugroups', $this->userGroupListGet(2));
				$AVE_Template->assign('formaction', 'index.php?do=user&action=new&sub=save&cp=' . SESSION);
				$AVE_Template->assign('content', $AVE_Template->fetch('user/form.tpl'));
				break;

			case 'save':
				$errors = $this->_userFieldValidate(1);
				if (!empty($errors))
				{
					$AVE_Template->assign('errors', $errors);
					$AVE_Template->assign('available_countries', get_country_list(1));
					$AVE_Template->assign('ugroups', $this->userGroupListGet(2));
					$AVE_Template->assign('formaction', 'index.php?do=user&action=new&sub=save&cp=' . SESSION);
					$AVE_Template->assign('content', $AVE_Template->fetch('user/form.tpl'));
				}
				else
				{
					$salt = make_random_string();
					$password = md5(md5(trim($_POST['password']) . $salt));
					$AVE_DB->Query("
						INSERT INTO " . PREFIX . "_users
						SET
							Id          = '',
							password    = '" . $password . "',
							salt        = '" . $salt . "',
							email       = '" . $_POST['email'] . "',
							street      = '" . $_POST['street'] . "',
							street_nr   = '" . $_POST['street_nr'] . "',
							zipcode     = '" . $_POST['zipcode'] . "',
							city        = '" . $_POST['city'] . "',
							phone       = '" . $_POST['phone'] . "',
							telefax     = '" . $_POST['telefax'] . "',
							description = '" . $_POST['description'] . "',
							firstname   = '" . $_POST['firstname'] . "',
							lastname    = '" . $_POST['lastname'] . "',
							user_name   = '" . $_POST['user_name'] . "',
							user_group  = '" . $_POST['user_group'] . "',
							reg_time    = '" . time() . "',
							status      = '" . $_POST['status'] . "',
							last_visit  = '" . time() . "',
							country     = '" . $_POST['country'] . "',
							birthday    = '" . $_POST['birthday'] . "',
							company     = '" . $_POST['company'] . "',
							taxpay      = '" . $_POST['taxpay'] . "',
							user_group_extra = '" . @implode(';', $_POST['user_group_extra']) . "'
					");
					$user_id=$AVE_DB->InsertId();
					if(is_uploaded_file($_FILES["avatar"]["tmp_name"]))
					   {
						 // Если файл загружен успешно, перемещаем его
						 // из временной директории в конечную
						 $newf_n= BASE_DIR.'/'. UPLOAD_DIR.'/avatars/new/'.$_FILES["avatar"]["name"];
						 move_uploaded_file($_FILES["avatar"]["tmp_name"],$newf_n);
						 SetAvatar($user_id,$newf_n);
					   }

					$message = get_settings('mail_new_user');
					$message = str_replace('%NAME%', $_POST['user_name'], $message);
					$message = str_replace('%HOST%', HOST . ABS_PATH, $message);
					$message = str_replace('%PASSWORD%', $_POST['password'], $message);
					$message = str_replace('%EMAIL%', $_POST['email'], $message);
					$message = str_replace('%EMAILSIGNATURE%', get_settings('mail_signature'), $message);
/*
					send_mail(
						$_POST['email'],
						$message,
						$AVE_Template->get_config_vars('USER_MAIL_SUBJECT')
					);
*/
					reportLog($_SESSION['user_name'] . ' - Добавил пользователя (' . stripslashes($_POST['user_name']) . ')', 2, 2);

					header('Location:index.php?do=user&cp=' . SESSION);
				}
				break;
		}
	}

	/**
	 * Редактирование учетной записи пользователя
	 *
	 * @param int $user_id идентификатор учетной записи пользователя
	 */
	function userEdit($user_id)
	{
		global $AVE_DB, $AVE_Template;

		$user_id = (int)$user_id;

		switch($_REQUEST['sub'])
		{
			case '':
				$row = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_users
					WHERE Id = '" . $user_id . "'
				")->FetchRow();

				if (!$row)
				{
					header('Location:index.php?do=user&cp=' . SESSION);
					exit;
				}
				$row->avatar=getAvatar($user_id,70);
				$AVE_Template->assign('row', $row);
				$AVE_Template->assign('user_group_extra', explode(';', $row->user_group_extra));

				if ($AVE_DB->Query("SHOW TABLES LIKE '" . PREFIX . "_modul_shop'")->GetCell())
				{
					$AVE_Template->assign('is_shop', 1);
				}

				if ($AVE_DB->Query("SHOW TABLES LIKE '" . PREFIX . "_modul_forum_userprofile'")->GetCell())
				{
					$row = $AVE_DB->Query("
						SELECT *
						FROM " . PREFIX . "_modul_forum_userprofile
						WHERE BenutzerId = '" . $user_id . "'
					")->FetchRow();

					if (is_object($row))
					{
						$AVE_Template->assign('row_fp', $row);
						$AVE_Template->assign('is_forum', 1);
					}
				}

				$AVE_Template->assign('available_countries', get_country_list(1));
				$AVE_Template->assign('ugroups', $this->userGroupListGet(2));
				$AVE_Template->assign('formaction', 'index.php?do=user&amp;action=edit&amp;sub=save&amp;cp=' . SESSION . '&amp;Id=' . $user_id);
				$AVE_Template->assign('content', $AVE_Template->fetch('user/form.tpl'));
				break;

			case 'save':
				$errors = $this->_userFieldValidate();
				if (!empty($errors))
				{
					$AVE_Template->assign('errors', $errors);
					$AVE_Template->assign('available_countries', get_country_list(1));
					$AVE_Template->assign('ugroups', $this->userGroupListGet(2));
					$AVE_Template->assign('formaction', 'index.php?do=user&amp;action=edit&amp;sub=save&amp;cp=' . SESSION . '&amp;Id=' . $user_id);
					$AVE_Template->assign('content', $AVE_Template->fetch('user/form.tpl'));
				}
				else
				{
					if (!empty($_REQUEST['password']))
					{
						$salt = make_random_string();
						$password = md5(md5(trim($_POST['password']) . $salt));
						$password_set = "password = '" . $password . "', salt = '" . $salt . "',";
					}
					else
					{
						$password_set = '';
					}

					$user_group_set = ($_SESSION['user_id'] != $user_id) ? "user_group = '" . $_REQUEST['user_group'] . "'," : '';
					
					$times = ($_REQUEST['deleted'] == "1") ? time() : '';
					
					if(is_uploaded_file($_FILES["avatar"]["tmp_name"]))
					   {
						 // Если файл загружен успешно, перемещаем его
						 // из временной директории в конечную
						 $newf_n= BASE_DIR.'/'. UPLOAD_DIR.'/avatars/new/'.$_FILES["avatar"]["name"];
						 move_uploaded_file($_FILES["avatar"]["tmp_name"],$newf_n);
						 SetAvatar($user_id,$newf_n);
					   }
					   
					$AVE_DB->Query("
						UPDATE " . PREFIX . "_users
						SET
							" . $password_set . "
							" . $user_group_set . "
							email       = '" . $_REQUEST['email'] . "',
							street      = '" . $_REQUEST['street'] . "',
							street_nr   = '" . $_REQUEST['street_nr'] . "',
							zipcode     = '" . $_REQUEST['zipcode'] . "',
							city        = '" . $_REQUEST['city'] . "',
							phone       = '" . $_REQUEST['phone'] . "',
							telefax     = '" . $_REQUEST['telefax'] . "',
							description = '" . $_REQUEST['description'] . "',
							firstname   = '" . $_REQUEST['firstname'] . "',
							lastname    = '" . $_REQUEST['lastname'] . "',
							user_name   = '" . $_REQUEST['user_name'] . "',
							status      = '" . $_REQUEST['status'] . "',
							country     = '" . $_REQUEST['country'] . "',
							birthday    = '" . $_REQUEST['birthday'] . "',
							deleted     = '" . $_REQUEST['deleted'] . "',
							del_time      = '" . $times . "',
							taxpay      = '" . $_REQUEST['taxpay'] . "',
							company     = '" . $_REQUEST['company'] . "',
							user_group_extra = '" . @implode(';', $_REQUEST['user_group_extra']) . "'
						WHERE
							Id = '" . $user_id . "'
					");
										
					if ($AVE_DB->Query("SHOW TABLES LIKE '" . PREFIX . "_modul_forum_userprofile'")->GetCell())
					{
						$AVE_DB->Query("
							UPDATE " . PREFIX . "_modul_forum_userprofile
							SET
								GroupIdMisc  = '" . @implode(';', $_REQUEST['user_group_extra']) . "',
								BenutzerName = '" . @$_REQUEST['BenutzerName_fp']. "',
								Signatur     = '" . @$_REQUEST['Signatur_fp'] . "' ,
								Avatar       = '" . @$_REQUEST['Avatar_fp'] . "'
							WHERE
								BenutzerId = '" . $user_id . "'
						");
					}

					if ($_REQUEST['status'] == 1 && @$_REQUEST['SendFreeMail'] == 1)
					{
						$host = HOST . ABS_PATH;
						$body_start  = $AVE_Template->get_config_vars('USER_MAIL_BODY1');
						$body_start  = str_replace('%USER%', $_REQUEST['user_name'], $body_start);
						$body_start .= str_replace('%HOST%', $host, $AVE_Template->get_config_vars('USER_MAIL_BODY2'));
						$body_start .= str_replace('%HOMEPAGENAME%', get_settings('site_name'), $AVE_Template->get_config_vars('USER_MAIL_FOOTER'));
						$body_start  = str_replace('%N%', "\n", $body_start);
						$body_start  = str_replace('%HOST%', $host, $body_start);

						send_mail(
							$_POST['email'],
							$body_start,
							$AVE_Template->get_config_vars('USER_MAIL_SUBJECT'),
							get_settings('mail_from'),
							get_settings('mail_from_name') . ' (' . get_settings('site_name') . ')',
							'text'							
						);
					}

					if (!empty($_REQUEST['password']) && $_REQUEST['PassChange'] == 1)
					{
						$host = HOST . ABS_PATH;
						$body_start  = $AVE_Template->get_config_vars('USER_MAIL_BODY1');
						$body_start  = str_replace('%USER%', $_REQUEST['user_name'], $body_start);
						$body_start .= str_replace('%HOST%', $host, $AVE_Template->get_config_vars('USER_MAIL_PASSWORD2'));
						$body_start  = str_replace('%NEWPASS%', $_REQUEST['password'], $body_start);
						$body_start .= str_replace('%HOMEPAGENAME%', get_settings('site_name'), $AVE_Template->get_config_vars('USER_MAIL_FOOTER'));
						$body_start  = str_replace('%N%', "\n", $body_start);
						$body_start  = str_replace('%HOST%', $host, $body_start);

						send_mail(
							$_POST['email'],
							$body_start,
							$AVE_Template->get_config_vars('USER_MAIL_PASSWORD'),
							get_settings('mail_from'),
							get_settings('mail_from_name') . ' (' . get_settings('site_name') . ')',
							'text'							
						);
					}

					if ($_REQUEST['SimpleMessage'] != '')
					{
						send_mail(
							$_POST['email'],
							stripslashes($_POST['SimpleMessage']),
							stripslashes($_POST['SubjectMessage']),
							$_SESSION['user_email'],
							$_SESSION['user_name'],
							'text'							
						);
					}

					if (!empty($_REQUEST['password']) && $_SESSION['user_id'] == $user_id)
					{
						$_SESSION['user_pass'] = $password;
						$_SESSION['user_email'] = $_POST['email'];
					}

					reportLog($_SESSION['user_name'] . ' - Отредактировал параметры пользователя (' . stripslashes($_POST['user_name']) . ')', 2, 2);

					header('Location:index.php?do=user&cp=' . SESSION);
					exit;
				}
				break;
		}
	}

	/**
	 * Удаление учетной записи пользователя
	 *
	 * @param int $user_id идентификатор учетной записи пользователя
	 */
	function userDelete($user_id)
	{
		global $AVE_DB;

		if (is_numeric($user_id) && $user_id != 1)
		{
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_users
				WHERE Id = '" . $user_id . "'
			");

			if ($AVE_DB->Query("SHOW TABLES LIKE '" . PREFIX . "_modul_forum_userprofile'")->GetCell())
			{
				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_modul_forum_userprofile
					WHERE BenutzerId = '" . $user_id . "'
				");
			}

			reportLog($_SESSION['user_name'] . ' - Удалил пользователя (' . $user_id . ')', 2, 2);
		}

		header('Location:index.php?do=user&cp=' . SESSION);
	}

	/**
	 * Запись изменений учетных записей пользователей в списке
	 *
	 */
	function userListEdit()
	{
		global $AVE_DB;

		foreach ($_POST['del'] as $user_id => $del)
		{
			if (is_numeric($user_id) && $user_id > 1)
			{
				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_users
					WHERE Id = '" . $user_id . "'
				");
				reportLog($_SESSION['user_name'] . ' - Удалил пользователя (' . $user_id . ')', 2, 2);
			}
		}

		foreach ($_POST['user_group'] as $user_id => $user_group_id)
		{
			if (is_numeric($user_id) && $user_id > 0 &&
				is_numeric($user_group_id) && $user_group_id > 0)
			{
				$AVE_DB->Query("
					UPDATE " . PREFIX . "_users
					SET user_group = '" . $user_group_id . "'
					WHERE Id = '" . $user_id . "'
				");
				reportLog($_SESSION['user_name'] . ' - Изменил группу для пользователя (' . $user_id . ')', 2, 2);
			}
		}

		header('Location:index.php?do=user&cp=' . SESSION);
		exit;
	}
}

?>