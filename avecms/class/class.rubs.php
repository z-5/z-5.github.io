<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

/**
 * Класс работы с рубриками
 */
class AVE_Rubric
{

/**
 *	СВОЙСТВА
 */

	/**
	 * Количество рубрик на странице
	 *
	 * @var int
	 */
	var $_limit = 30;

/**
 *	ВНУТРЕННИЕ МЕТОДЫ
 */


/**
 *	ВНЕШНИЕ МЕТОДЫ
 */

	/**
	 * Вывод списка рубрик
	 *
	 */
	function rubricList()
	{
		global $AVE_DB, $AVE_Template;

		$rubrics = array();
		$num = $AVE_DB->Query("SELECT COUNT(*) FROM " . PREFIX . "_rubrics")->GetCell();

		$page_limit = $this->_limit;
		$seiten = ceil($num / $page_limit);
		$set_start = get_current_page() * $page_limit - $page_limit;

		if ($num > $page_limit)
		{
			$page_nav = " <a class=\"pnav\" href=\"index.php?do=rubs&page={s}&cp=" . SESSION . "\">{t}</a> ";
			$page_nav = get_pagination($seiten, 'page', $page_nav);
			$AVE_Template->assign('page_nav', $page_nav);
		}

		$sql = $AVE_DB->Query("
			SELECT
				rub.*,
				COUNT(doc.Id) AS doc_count
			FROM
				" . PREFIX . "_rubrics AS rub
			LEFT JOIN
				" . PREFIX . "_documents AS doc
					ON rubric_id = rub.Id
			GROUP BY rub.Id
			ORDER BY rub.rubric_position
			LIMIT " . $set_start . "," . $page_limit
		);
		while ($row = $sql->FetchRow())
		{
			array_push($rubrics, $row);
		}

		$AVE_Template->assign('rubrics', $rubrics);
	}

	/**
	 * создание рубрики
	 *
	 */
	function rubricNew()
	{
		global $AVE_DB, $AVE_Template;

		switch ($_REQUEST['sub'])
		{
			case '':
				$AVE_Template->assign('AlleVorlagen', get_all_templates());
				$AVE_Template->assign('content', $AVE_Template->fetch('rubs/rubnew.tpl'));
				break;

			case 'save':
				$errors = array();

				if (empty($_POST['rubric_title']))
				{
					array_push($errors, $AVE_Template->get_config_vars('RUBRIK_NO_NAME'));
				}
				else
				{
					$name_exist = $AVE_DB->Query("
						SELECT 1
						FROM " . PREFIX . "_rubrics
						WHERE rubric_title = '" . $_POST['rubric_title'] . "'
						LIMIT 1
					")->NumRows();

					if ($name_exist) array_push($errors, $AVE_Template->get_config_vars('RUBRIK_NAME_EXIST'));

					if (!empty($_POST['rubric_alias']))
					{
						if (preg_match(TRANSLIT_URL ? '/[^\%HYa-z0-9\/-]+/' : '/[^\%HYa-zа-яА-Яёїєі0-9\/-]+/u', $_POST['rubric_alias']))
						{
							array_push($errors, $AVE_Template->get_config_vars('RUBRIK_PREFIX_BAD_CHAR'));
						}
						else
						{
							$prefix_exist = $AVE_DB->Query("
								SELECT 1
								FROM " . PREFIX . "_rubrics
								WHERE rubric_alias = '" . $_POST['rubric_alias'] . "'
								LIMIT 1
							")->NumRows();

							if ($prefix_exist) array_push($errors, $AVE_Template->get_config_vars('RUBRIK_PREFIX_EXIST'));
						}
					}

					if (!empty($errors))
					{
						$AVE_Template->assign('errors', $errors);
						$AVE_Template->assign('AlleVorlagen', get_all_templates());
						$AVE_Template->assign('content', $AVE_Template->fetch('rubs/rubnew.tpl'));
					}
					else
					{
						$AVE_DB->Query("
							INSERT " . PREFIX . "_rubrics
							SET
								rubric_title       = '" . $_POST['rubric_title'] . "',
								rubric_alias       = '" . $_POST['rubric_alias'] . "',
								rubric_template_id = '" . intval($_POST['rubric_template_id']) . "',
								rubric_author_id   = '" . $_SESSION['user_id'] . "',
								rubric_created     = '" . time() . "'
						");
						$iid = $AVE_DB->InsertId();

						// Выставляем всем право на просмотр рубрики, админу - все права
						$sql_user = $AVE_DB->Query("
							SELECT
								grp.*,
								COUNT(usr.Id) AS UserCount
							FROM
								" . PREFIX . "_user_groups AS grp
							LEFT JOIN
								" . PREFIX . "_users AS usr
									ON usr.user_group = grp.user_group
							GROUP BY grp.user_group
						");
						while ($row = $sql_user->FetchRow())
						{
							$AVE_DB->Query("
								INSERT " . PREFIX . "_rubric_permissions
								SET
									rubric_id         = '" . $iid . "',
									user_group_id     = '" . $row->user_group . "',
									rubric_permission = '". (($row->user_group == 1) ? "alles|docread|new|newnow|editown|editall" : "docread")."'
							");
						}

						reportLog($_SESSION['user_name'] . ' - добавил рубрику (' . stripslashes($_POST['rubric_title']) . ')', 2, 2);

						header('Location:index.php?do=rubs&action=edit&Id=' . $iid . '&cp=' . SESSION);
						exit;
					}
				}
				break;
		}
	}

	/**
	 * Запись настроек рубрики
	 *
	 */
	function quickSave()
	{
		global $AVE_DB;

		if (check_permission_acp('rubric_edit'))
		{
			foreach ($_POST['rubric_title'] as $rubric_id => $rubric_title)
			{
				if (!empty($rubric_title))
				{
					$set_rubric_title = '';
					$set_rubric_alias = '';

					$name_exist = $AVE_DB->Query("
						SELECT 1
						FROM " . PREFIX . "_rubrics
						WHERE
							rubric_title = '" . $rubric_title . "'
						AND
							Id != '" . $rubric_id . "'
						LIMIT 1
					")->NumRows();

					if (!$name_exist)
					{
						$set_rubric_title = "rubric_title = '" . $rubric_title . "',";
					}

					if (isset($_POST['rubric_alias'][$rubric_id]) && $_POST['rubric_alias'][$rubric_id] != '')
					{
						$pattern = TRANSLIT_URL ? '/[^\%HYa-z0-9\/-]+/' : '/[^\%HYa-zа-яА-Яёїєі0-9\/-]+/u';
						if (!(preg_match($pattern, $_POST['rubric_alias'][$rubric_id])))
						{
							$prefix_exist = $AVE_DB->Query("
								SELECT 1
								FROM " . PREFIX . "_rubrics
								WHERE
									rubric_alias = '" . $_POST['rubric_alias'][$rubric_id] . "'
								AND
									Id != '" . $rubric_id . "'
								LIMIT 1
							")->NumRows();

							if (!$prefix_exist)
							{
								$set_rubric_alias = "rubric_alias = '" . trim(preg_replace($pattern, '', $_POST['rubric_alias'][$rubric_id]), '/') . "',";
							}
						}
					}
					else
					{
						$set_rubric_alias = "rubric_alias = '',";
					}

					$AVE_DB->Query("
						UPDATE " . PREFIX . "_rubrics
						SET
							" . $set_rubric_title . "
							" . $set_rubric_alias . "
							rubric_template_id = '" . $_POST['rubric_template_id'][$rubric_id] . "',
							rubric_docs_active = '".(int)$_POST['rubric_docs_active'][$rubric_id]."',
							rubric_position = '".(int)$_POST['rubric_position'][$rubric_id]."'
						WHERE
							Id = '" . $rubric_id . "'
					");
				}
			}

			$page = !empty($_REQUEST['page']) ? '&page=' . $_REQUEST['page'] : '' ;
			header('Location:index.php?do=rubs' . $page . '&cp=' . SESSION);
		}
	}

	/**
	 * Копирование рубрики
	 *
	 */
	function rubricCopy()
	{
		global $AVE_DB, $AVE_Template;

		$rubric_id = (int)$_REQUEST['Id'];

		$errors = array();

		if (empty($_REQUEST['rubric_title']))
		{
			array_push($errors, $AVE_Template->get_config_vars('RUBRIK_NO_NAME'));
		}
		else
		{
			$name_exist = $AVE_DB->Query("
				SELECT 1
				FROM " . PREFIX . "_rubrics
				WHERE rubric_title = '" . $_POST['rubric_title'] . "'
				LIMIT 1
			")->NumRows();

			if ($name_exist) array_push($errors, $AVE_Template->get_config_vars('RUBRIK_NAME_EXIST'));
		}

		if (!empty($_POST['rubric_alias']))
		{
			if (preg_match(TRANSLIT_URL ? '/[^\%HYa-z0-9\/-]+/' : '/[^\%HYa-zа-яёїєі0-9\/-]+/', $_POST['rubric_alias']))
			{
				array_push($errors, $AVE_Template->get_config_vars('RUBRIK_PREFIX_BAD_CHAR'));
			}
			else
			{
				$prefix_exist = $AVE_DB->Query("
					SELECT 1
					FROM " . PREFIX . "_rubrics
					WHERE rubric_alias = '" . $_POST['rubric_alias'] . "'
					LIMIT 1
				")->NumRows();

				if ($prefix_exist) array_push($errors, $AVE_Template->get_config_vars('RUBRIK_PREFIX_EXIST'));
			}
		}

		$row = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_rubrics
			WHERE Id = '" . $rubric_id . "'
		")->FetchRow();

		if (!$row) array_push($errors, $AVE_Template->get_config_vars('RUBRIK_NO_RUBRIK'));

		if (!empty($errors))
		{
			$AVE_Template->assign('errors', $errors);
		}
		else
		{
			$AVE_DB->Query("
				INSERT " . PREFIX . "_rubrics
				SET
					rubric_title       = '" . $_POST['rubric_title'] . "',
					rubric_alias       = '" . $_POST['rubric_alias'] . "',
					rubric_template    = '" . addslashes($row->rubric_template) . "',
					rubric_template_id = '" . addslashes($row->rubric_template_id) . "',
					rubric_author_id   = '" . (int)$_SESSION['user_id'] . "',
					rubric_created     = '" . time() . "',
					rubric_teaser_template    = '" . addslashes($row->rubric_teaser_template) . "',
					rubric_header_template    = '" . addslashes($row->rubric_header_template) . "',
					rubric_admin_teaser_template    = '" . addslashes($row->rubric_admin_teaser_template) . "'
			");
			$iid = $AVE_DB->InsertId();

			$sql = $AVE_DB->Query("
				SELECT
					user_group_id,
					rubric_permission
				FROM " . PREFIX . "_rubric_permissions
				WHERE rubric_id = '" . $rubric_id . "'
			");
			while ($row = $sql->FetchRow())
			{
				$AVE_DB->Query("
					INSERT " . PREFIX . "_rubric_permissions
					SET
						rubric_id = '" . $iid . "',
						user_group_id = '" . (int)$row->user_group_id . "',
						rubric_permission = '" . addslashes($row->rubric_permission) . "'
				");
			}

			$sql = $AVE_DB->Query("
				SELECT
					rubric_field_title,
					rubric_field_alias,
					rubric_field_type,
					rubric_field_position,
					rubric_field_default,
					rubric_field_template,
					rubric_field_template_request
				FROM " . PREFIX . "_rubric_fields
				WHERE rubric_id = '" . $rubric_id . "'
				ORDER BY rubric_field_position ASC
			");
			while ($row = $sql->FetchRow())
			{
				$AVE_DB->Query("
					INSERT " . PREFIX . "_rubric_fields
					SET
						rubric_id                     = '" . $iid . "',
						rubric_field_title            = '" . addslashes($row->rubric_field_title) . "',
						rubric_field_alias            = '" . addslashes($row->rubric_field_alias) . "',
						rubric_field_type             = '" . addslashes($row->rubric_field_type) . "',
						rubric_field_position         = '" . (int)$row->rubric_field_position . "',
						rubric_field_default          = '" . addslashes($row->rubric_field_default) . "',
						rubric_field_template         = '" . addslashes($row->rubric_field_template) . "',
						rubric_field_template_request = '" . addslashes($row->rubric_field_template_request) . "'
				");
			}

			reportLog($_SESSION['user_name'] . ' - создал копию рубрики (' . $rubric_id . ')', 2, 2);

			echo '<script>window.opener.location.reload();window.close();</script>';
		}
	}

	/**
	 * Удаление рубрики
	 *
	 */
	function rubricDelete()
	{
		global $AVE_DB;

		$rubric_id = (int)$_REQUEST['Id'];

		if ($rubric_id <= 1)
		{
			header('Location:index.php?do=rubs&cp=' . SESSION);
			exit;
		}

		$rubric_not_empty = $AVE_DB->Query("
			SELECT 1
			FROM " . PREFIX . "_documents
			WHERE rubric_id = '" . $rubric_id . "'
			LIMIT 1
		")->GetCell();

		if (!$rubric_not_empty)
		{
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_rubrics
				WHERE Id = '" . $rubric_id . "'
			");
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_rubric_fields
				WHERE rubric_id = '" . $rubric_id . "'
			");
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_rubric_permissions
				WHERE rubric_id = '" . $rubric_id . "'
			");
			// Очищаем кэш шаблона документов рубрики
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_rubric_template_cache
				WHERE rub_id = '" . $rubric_id . "'
			");

			reportLog($_SESSION['user_name'] . ' - удалил рубрику (' . $rubric_id . ')', 2, 2);
		}

		header('Location:index.php?do=rubs&cp=' . SESSION);
		exit;
	}

	/**
	 * Вывод списка полей рубрики
	 *
	 * @param int $rubric_id	идентификатор рубрики
	 */
	function rubricFieldShow($rubric_id = 0)
	{
		global $AVE_DB, $AVE_Template;

		$rub_fields = array();
		$sql = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_rubric_fields
			WHERE rubric_id = '" . $rubric_id . "'
			ORDER BY rubric_field_position ASC
		");

		while ($row = $sql->FetchRow())
		{
			array_push($rub_fields,$row);
		}

		$AVE_Template->assign('rub_fields', $rub_fields);

		$groups = array();
		$sql = $AVE_DB->Query("SELECT * FROM " . PREFIX . "_user_groups");

		while ($row = $sql->FetchRow())
		{
			$row->doall = ($row->user_group == 1) ? ' disabled="disabled" checked="checked"' : '';
			$row->doall_h = ($row->user_group == 1) ? 1 : '';

			$rubric_permission = $AVE_DB->Query("
				SELECT rubric_permission
				FROM " . PREFIX . "_rubric_permissions
				WHERE user_group_id = '" . $row->user_group . "'
				AND rubric_id = '" . $rubric_id . "'
			")->GetCell();
			$row->permissions = @explode('|', $rubric_permission);

			array_push($groups,$row);
		}
		$sql = $AVE_DB->Query("
			SELECT rubric_title, rubric_code_start, rubric_code_end, rubric_linked_rubric, rubric_description
			FROM " . PREFIX . "_rubrics
			WHERE id = '" . $rubric_id . "'
			LIMIT 1
		");
		$rubrik = $sql->FetchRow();
		@$rubrik->rubric_linked_rubric = unserialize($rubrik->rubric_linked_rubric);
		$AVE_Template->assign('rubric', $rubrik);
		$AVE_Template->assign('groups', $groups);
		$AVE_Template->assign('felder', get_field_type());
		$AVE_Template->assign('rubs', $this->rubricShow());
		$AVE_Template->assign('content', $AVE_Template->fetch('rubs/rub_fields.tpl'));
	}

	/**
	 * Вывод списка рубрик
	 *
	 * @param int $rubric_id	идентификатор текущей рубрики
	 */
	function rubricShow($RubLink=null)
	{
		global $AVE_DB;

		if ($RubLink!==null) {
			$AVE_DB->Query("
				UPDATE " . PREFIX . "_rubrics
				SET
					rubric_linked_rubric = '" . serialize($_REQUEST['rubric_linked']) . "'
				WHERE
					Id = '" . (int)$_REQUEST['Id'] . "'
			");
			header('Location:index.php?do=rubs&action=edit&Id=' . (int)$_REQUEST['Id'] . '&cp=' . SESSION);
			exit;
		} else {
			$rubs = array();
			$sql = $AVE_DB->Query("
				SELECT rubric_title, Id
				FROM " . PREFIX . "_rubrics
				ORDER BY rubric_position ASC
			");

			while ($row = $sql->FetchRow())
			{
				array_push($rubs,$row);
			}
			return $rubs;
		}
	}

	/**
	 * Создание нового поля рубрики
	 *
	 * @param int $rubric_id	идентификатор рубрики
	 */
	function rubricFieldNew($rubric_id = 0)
	{
		global $AVE_DB;

		if (!empty($_POST['TitelNew']))
		{
			$position = (!empty($_POST['rubric_field_position_new'])) ? $_POST['rubric_field_position_new'] : 1;

			if ($_POST['RubTypNew'] == 'dropdown')
			{
				$rubric_field_default = trim($_POST['StdWertNew']);
				$rubric_field_default = preg_split('/\s*,\s*/', $rubric_field_default);
				$rubric_field_default = implode(',', $rubric_field_default);
			}
			else
			{
				$rubric_field_default = $_POST['StdWertNew'];
			}

			$AVE_DB->Query("
				INSERT " . PREFIX . "_rubric_fields
				SET
					rubric_id             = '" . $rubric_id . "',
					rubric_field_title    = '" . $_POST['TitelNew'] . "',
					rubric_field_type     = '" . $_POST['RubTypNew'] . "',
					rubric_field_position = '" . $position . "',
					rubric_field_default  = '" . $rubric_field_default . "'
			");
			$Update_RubrikFeld = $AVE_DB->InsertId();

			$sql = $AVE_DB->Query("
				SELECT Id
				FROM " . PREFIX . "_documents
				WHERE rubric_id = '" . $rubric_id . "'
			");

			while ($row = $sql->FetchRow())
			{
				$AVE_DB->Query("
					INSERT " . PREFIX . "_document_fields
					SET
						rubric_field_id = '" . $Update_RubrikFeld . "',
						document_id = '" . $row->Id . "'
				");
			}

			reportLog($_SESSION['user_name'] . ' - добавил поле рубрики (' . stripslashes($_POST['TitelNew']) . ')', 2, 2);
		}

		header('Location:index.php?do=rubs&action=edit&Id=' . $rubric_id . '&cp=' . SESSION);
		exit;
	}

	/**
	 * Редактирование кода для рубрики
	 *
	 * @param int $rubric_id	идентификатор рубрики
	 */
	function rubricCode($rubric_id = 0)
	{
		global $AVE_DB;

		$AVE_DB->Query("
					UPDATE " . PREFIX . "_rubrics
					SET
						rubric_code_start           = '" . $_POST['rubric_code_start'] . "',
						rubric_code_end             = '" . $_POST['rubric_code_end'] . "'
					WHERE
						Id = '" . $rubric_id . "'
		");

		header('Location:index.php?do=rubs&action=edit&Id=' . $rubric_id . '&cp=' . SESSION);
		exit;
	}
	/**
	 * Редактирование кода для рубрики
	 *
	 * @param int $rubric_id	идентификатор рубрики
	 */
	function rubricDesc($rubric_id = 0)
	{
		global $AVE_DB;

		$AVE_DB->Query("
					UPDATE " . PREFIX . "_rubrics
					SET
						rubric_description           = '" . $_POST['rubric_description'] . "'
					WHERE
						Id = '" . $rubric_id . "'
		");

		header('Location:index.php?do=rubs&action=edit&Id=' . $rubric_id . '&cp=' . SESSION);
		exit;
	}
	/**
	 * Управление полями рубрики
	 *
	 * @param int $rubric_id	идентификатор рубрики
	 */
	function rubricFieldSave($rubric_id = 0)
	{
		global $AVE_DB;

		foreach ($_POST['title'] as $id => $title)
		{
			if (!empty($title))
			{
				if ($_POST['rubric_field_type'][$id] == 'dropdown')
				{
					$rubric_field_default = trim($_POST['rubric_field_default'][$id]);
					$rubric_field_default = preg_split('/\s*,\s*/', $rubric_field_default);
					$rubric_field_default = implode(',', $rubric_field_default);
				}
				else
				{
					$rubric_field_default = $_POST['rubric_field_default'][$id];
				}

				$AVE_DB->Query("
					UPDATE " . PREFIX . "_rubric_fields
					SET
						rubric_field_title            = '" . $title . "',
						rubric_field_type             = '" . $_POST['rubric_field_type'][$id] . "',
						rubric_field_position         = '" . $_POST['rubric_field_position'][$id] . "',
						rubric_field_default          = '" . $rubric_field_default . "',
						rubric_field_template         = '" . $_POST['rubric_field_template'][$id] . "',
						rubric_field_template_request = '" . $_POST['rubric_field_template_request'][$id] . "'
					WHERE
						Id = '" . $id . "'
				");
				// Очищаем кэш шаблона документов рубрики
				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_rubric_template_cache
					WHERE rub_id = '" . $rubric_id . "'
				");
				reportLog($_SESSION['user_name'] . ' - отредактировал поле рубрики (' . stripslashes($title) . ')', 2, 2);
			}
		}

		foreach ($_POST['del'] as $id => $Del)
		{
			if (!empty($Del))
			{
				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_rubric_fields
					WHERE Id = '" . $id . "'
					AND rubric_id = '" . $rubric_id . "'
				");
				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_document_fields
					WHERE rubric_field_id = '" . $id . "'
				");
				// Очищаем кэш шаблона документов рубрики
				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_rubric_template_cache
					WHERE rub_id = '" . $rubric_id . "'
				");

				reportLog($_SESSION['user_name'] . ' - удалил поле рубрики (' . stripslashes($_POST['title'][$id]) . ')', 2, 2);
			}
		}

		header('Location:index.php?do=rubs&action=edit&Id=' . $rubric_id . '&cp=' . SESSION);
		exit;
	}

	/**
	 * Вывод шаблона рубрики
	 *
	 * @param int $show
	 * @param int $extern
	 */
	function rubricTemplateShow($show = '', $extern = '0')
	{
		global $AVE_DB, $AVE_Template;

		if ($extern==1)
		{
			$fetchId = (isset($_REQUEST['rubric_id']) && is_numeric($_REQUEST['rubric_id'])) ? $_REQUEST['rubric_id'] : 0;
		}
		else
		{
			$fetchId = (isset($_REQUEST['Id']) && is_numeric($_REQUEST['Id'])) ? $_REQUEST['Id'] : 0;
		}

		$row = $AVE_DB->Query("
			SELECT
				rubric_title,
				rubric_template,
				rubric_header_template,
				rubric_teaser_template,
				rubric_admin_teaser_template,
				rubric_description
			FROM " . PREFIX . "_rubrics
			WHERE Id = '" . $fetchId . "'
		")
		->FetchRow();

		$tags = array();
		$ddid = array();
		$sql = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_rubric_fields
			WHERE rubric_id = '" . $fetchId . "'
			ORDER BY rubric_field_position ASC
		");

		while ($row_rf = $sql->FetchRow())
		{
			array_push($tags, $row_rf);
			if ($row_rf->rubric_field_type == 'dropdown') array_push($ddid, $row_rf->Id);
		}
		$sql->Close();

		$AVE_Template->assign('feld_array', get_field_type());

		if ($show == 1 ) $row->rubric_template = stripslashes($_POST['rubric_template']);

		if ($extern == 1)
		{
			$AVE_Template->assign('tags_row', $row);
			$AVE_Template->assign('tags', $tags);
			$AVE_Template->assign('ddid', implode(',', $ddid));
		}
		else
		{
			$AVE_Template->assign('row', $row);
			$AVE_Template->assign('tags', $tags);
			$AVE_Template->assign('formaction', 'index.php?do=rubs&action=template&sub=save&Id=' . $fetchId . '&cp=' . SESSION);
			$AVE_Template->assign('content', $AVE_Template->fetch('rubs/form.tpl'));
		}
	}

	/**
	 * Редактирование шаблона рубрики
	 *
	 * @param string $data
	 */
	function rubricTemplateSave($Rtemplate,$Htemplate='',$Ttemplate='',$Atemplate='')
	{
		global $AVE_DB;

		$rubric_id = (int)$_REQUEST['Id'];

		$AVE_DB->Query("
			UPDATE " . PREFIX . "_rubrics
			SET 
				rubric_template = '" . ($Rtemplate) . "',
				rubric_header_template = '" . $Htemplate . "',
				rubric_teaser_template = '" . $Ttemplate . "',
				rubric_admin_teaser_template = '" . $Atemplate . "'
			WHERE Id = '" . $rubric_id . "'
		");
		// Очищаем кэш шаблона документов рубрики
		$AVE_DB->Query("
			DELETE
			FROM " . PREFIX . "_rubric_template_cache
			WHERE rub_id = '" . $rubric_id . "'
		");

		reportLog($_SESSION['user_name'] . ' - отредактировал шаблон рубрики (' . $rubric_id . ')', 2, 2);
		if (!$_REQUEST['next_edit']) {		
			header('Location:index.php?do=rubs&cp=' . SESSION);
		} else {
			header('Location:index.php?do=rubs&action=template&Id=' . $rubric_id . '&cp=' . SESSION);
		}
	}

	/**
	 * Управление правами доступа к документам рубрик
	 *
	 * @param int $rubric_id	идентификатор рубрики
	 */
	function rubricPermissionSave($rubric_id = 0)
	{
		global $AVE_DB;

		if (check_permission_acp('rubric_perms') && is_numeric($rubric_id) && $rubric_id > 0)
		{
			foreach ($_POST['user_group'] as $key => $user_group_id)
			{
				$exist = $AVE_DB->Query("
					SELECT 1
					FROM " . PREFIX . "_rubric_permissions
					WHERE user_group_id = '" . $user_group_id . "'
					AND rubric_id = '" . $rubric_id . "'
					LIMIT 1
				")->NumRows();

				$rubric_permission = @implode('|', $_POST['perm'][$key]);
				if ($exist)
				{
					$AVE_DB->Query("
						UPDATE " . PREFIX . "_rubric_permissions
						SET rubric_permission = '" . $rubric_permission . "'
						WHERE user_group_id = '" . $user_group_id . "'
						AND rubric_id = '" . $rubric_id . "'
					");
				}
				else
				{
					$AVE_DB->Query("
						INSERT " . PREFIX . "_rubric_permissions
						SET
							rubric_id = '" . $rubric_id . "',
							user_group_id = '" . $user_group_id . "',
							rubric_permission = '" . $rubric_permission . "'
					");
				}
			}

			header('Location:index.php?do=rubs&action=edit&Id=' . $rubric_id . '&cp=' . SESSION);
			exit;
		}
	}

	/**
	 * Получить наименование и URL-префикс Рубрики по идентификатору
	 *
	 * @param int $rubric_id идентификатор Рубрики
	 * @return object наименование Рубрики
	 */
	function rubricNameByIdGet($rubric_id = 0)
	{
		global $AVE_DB;

		static $rubrics = array();

		if (!isset($rubrics[$rubric_id]))
		{
			$rubrics[$rubric_id] = $AVE_DB->Query("
				SELECT
					rubric_title,
					rubric_alias
				FROM " . PREFIX . "_rubrics
				WHERE Id = '" . $rubric_id . "'
				LIMIT 1
			")->fetchRow();
		}

		return $rubrics[$rubric_id];
	}

	/**
	 * Формирование прав доступа Групп пользователей на все Рубрики
	 *
	 */
	function rubricPermissionFetch()
	{
		global $AVE_DB, $AVE_Document, $AVE_Template;

		$items = array();
		$sql = $AVE_DB->Query("
			SELECT
				Id,
				rubric_title,
				rubric_docs_active
			FROM " . PREFIX . "_rubrics
			ORDER BY rubric_position
		");
		while ($row = $sql->FetchRow())
		{
			$AVE_Document->documentPermissionFetch($row->Id);

			if (defined('UGROUP') && UGROUP == 1) $row->Show = 1;
			elseif (isset($_SESSION[$row->Id . '_editown']) && $_SESSION[$row->Id . '_editown'] == 1) $row->Show = 1;
			elseif (isset($_SESSION[$row->Id . '_editall']) && $_SESSION[$row->Id . '_editall'] == 1) $row->Show = 1;
			elseif (isset($_SESSION[$row->Id . '_new'])     && $_SESSION[$row->Id . '_new']     == 1) $row->Show = 1;
			elseif (isset($_SESSION[$row->Id . '_newnow'])  && $_SESSION[$row->Id . '_newnow']  == 1) $row->Show = 1;
			elseif (isset($_SESSION[$row->Id . '_alles'])   && $_SESSION[$row->Id . '_alles']   == 1) $row->Show = 1;

			array_push($items, $row);
		}

		$AVE_Template->assign('rubrics', $items);
	}

	/**
	 * Получить
	 */
	function rubricAliasAdd()
	{
		global $AVE_DB, $AVE_Template;

			$sql = $AVE_DB->Query("
				SELECT
					a.rubric_title,
					b.rubric_field_title,
					b.rubric_field_alias
				FROM " . PREFIX . "_rubrics AS a
				JOIN 
					 " . PREFIX . "_rubric_fields AS b
				WHERE a.Id = '" . $_REQUEST['rubric_id'] . "'
				AND b.Id = '" . $_REQUEST['field_id'] . "'
			")->FetchAssocArray();

		$AVE_Template->assign($sql);
		$AVE_Template->assign('content', $AVE_Template->fetch('rubs/alias.tpl'));
	}

	function rubricAliasCheck($rubric_id, $field_id, $value)
	{

	global $AVE_DB, $AVE_Template;

	$errors = array();

		if(!intval($rubric_id)>0){
			$errors[] = $AVE_Template->get_config_vars('RUBRIK_ALIAS_RUBID');
		}

		if(!intval($field_id)>0) {
			$errors[] = $AVE_Template->get_config_vars('RUBRIK_ALIAS_FIELDID');
		};

		if(!preg_match('/^[A-Za-z][[:word:]]{0,19}$/', $value)) {
			$errors[] = $AVE_Template->get_config_vars('RUBRIK_ALIAS_MATCH');
		};
		
		//Проверяем есть такой алиас уже
		$res = $AVE_DB->Query("
			SELECT COUNT(*)
			FROM
				" . PREFIX . "_rubric_fields
			WHERE
				Id <> " . intval($field_id) . "
				AND rubric_id = " . intval($rubric_id) . "
				AND rubric_field_alias = '" . addslashes($value) . "'
			")->GetCell();

		if($res>0){
			$errors[] = $AVE_Template->get_config_vars('RUBRIK_ALIAS_MATCH');
		};

		if (empty($errors))
		{
			$res = $AVE_DB->Query("
				UPDATE " . PREFIX . "_rubric_fields 
				SET
					rubric_field_alias = '" . addslashes($value) . "'
				WHERE 
					Id = '" . intval($field_id) . "'
				AND rubric_id = '" . intval($rubric_id) . "'
				");
			echo "
			<script language=\"javascript\" type=\"text/javascript\">
				window.opener.document.getElementById('". $_REQUEST['target'] ."').value = '" . addslashes($value) . "'
				window.close();
			</script>
			";
		}else{
			$sql = $AVE_DB->Query("
				SELECT
					a.rubric_title,
					b.rubric_field_title,
					b.rubric_field_alias
				FROM " . PREFIX . "_rubrics AS a
				JOIN 
					 " . PREFIX . "_rubric_fields AS b
				WHERE a.Id = '" . $_REQUEST['rubric_id'] . "'
				AND b.Id = '" . $_REQUEST['field_id'] . "'
			")->FetchAssocArray();

			$AVE_Template->assign('errors', $errors);
			$AVE_Template->assign($sql);
			$AVE_Template->assign('content', $AVE_Template->fetch('rubs/alias.tpl'));
		}

 	}

}

?>