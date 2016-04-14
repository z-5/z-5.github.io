<?php

/**
 * AVE.cms
 *
 * Класс, предназначеный для работы с системой запросов в Панели управления
 * @package AVE.cms
 * @filesource
 */

class AVE_Request
{

/**
 *	Свойстав класса
 */

	/**
	 * Количество Запросов на странице
	 *
	 * @var int
	 */
	var $_limit = 50;

/**
 *	Внутренние методы
 */

	/**
	 * Метод, предназначенный для получения и вывода списка Запросов
	 *
	 * @param boolean $pagination признак формирования постраничного списка
	 */
	function _requestListGet($pagination = true)
	{
		global $AVE_DB, $AVE_Template;

		$limit = '';

		// Если используется постраничная навигация
        if ($pagination)
		{
			// Определяем лимит записей на страницу и начало диапазона выборки
            $limit = $this->_limit;
			$start = get_current_page() * $limit - $limit;

			// Получаем общее количество запросов
            $num = $AVE_DB->Query("SELECT COUNT(*) FROM " . PREFIX . "_request")->GetCell();

			// Если количество больше, чем установленный лимит, тогда формируем постраничную навигацию
            if ($num > $limit)
			{
				$page_nav = "<li><a href=\"index.php?do=request&page={s}&amp;cp=" . SESSION . "\">{t}</a></li>";
				$page_nav = get_pagination(ceil($num / $limit), 'page', $page_nav);
				$AVE_Template->assign('page_nav', $page_nav);
			}

			$limit = $pagination ? "LIMIT " . $start . "," . $limit : '';
		}

        // Выполняем запрос к БД на получение списка запросов с учетом лимита вывода на страницу (если необходимо)
		$items = array();
		$sql = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_request
			ORDER BY Id ASC
			" . $limit . "
		");

        // Формируем массив из полученных данных
        while ($row = $sql->FetchRow())
		{
			$row->request_author = get_username_by_id($row->request_author_id);
			array_push($items, $row);
		}

		// Возвращаем массив
        return $items;
	}

/**
 *	Внешние методы класса
 */

	/**
	 * Метод, предназначенный для формирования списка Запросов
	 *
	 */
	function requestListFetch()
	{
		global $AVE_Template;

		$AVE_Template->assign('conditions', $this->_requestListGet(false));
	}

    /**
	 * Метод, предназначенный для отображения списка Запросов
	 *
	 */
	function requestListShow()
	{
		global $AVE_Template;

        // Получаем список запросов
		$AVE_Template->assign('items', $this->_requestListGet());

        // Передаем в шаблон и отображаем страницу со списком
		$AVE_Template->assign('content', $AVE_Template->fetch('request/request.tpl'));
	}

    /**
	 * Метод, предназначенный для создания нового Запроса
	 *
	 */
	function requestNew()
	{
		global $AVE_DB, $AVE_Template;

		// Определяем действие пользователя
        switch ($_REQUEST['sub'])
		{
			// Действие не определено
            case '':
				// Отображаем пустую форму для создания нового запроса
                $AVE_Template->assign('formaction', 'index.php?do=request&amp;action=new&amp;sub=save&amp;cp=' . SESSION);
				$AVE_Template->assign('content', $AVE_Template->fetch('request/form.tpl'));
				break;

            // Нажата кнопка Сохранить запрос
            case 'save':
                // Выполняем запрос к БД и сохраняем введенную пользователем информацию
                $AVE_DB->Query("
					INSERT " . PREFIX . "_request
					SET
						rubric_id               = '" . $_REQUEST['rubric_id'] . "',
						request_title           = '" . $_REQUEST['request_title'] . "',
						request_items_per_page  = '" . $_REQUEST['request_items_per_page'] . "',
						request_template_item   = '" . $_REQUEST['request_template_item'] . "',
						request_template_main   = '" . $_REQUEST['request_template_main'] . "',
						request_order_by        = '" . $_REQUEST['request_order_by'] . "',
						request_order_by_nat    = '" . $_REQUEST['request_order_by_nat'] . "',
						request_asc_desc        = '" . $_REQUEST['request_asc_desc'] . "',
						request_author_id       = '" . (int)$_SESSION['user_id'] . "',
						request_created         = '" . time() . "',
						request_description     = '" . $_REQUEST['request_description'] . "',
						request_show_pagination = '" . @$_REQUEST['request_show_pagination'] . "',
						request_cache_lifetime  = '" . (int)$_REQUEST['request_cache_lifetime'] . "',
						request_lang            = '" . (isset($_REQUEST['request_lang']) ? (int)$_REQUEST['request_lang'] : 0) . "'
				");

                // Получаем id последней записи
                $iid = $AVE_DB->InsertId();

				// Сохраняем системное сообщение в журнал
                reportLog($_SESSION['user_name'] . ' - добавил новый запрос (' . stripslashes($_REQUEST['request_title']) . ')', 2, 2);

                // Если в запросе пришел параметр на продолжение редактирования запроса
                if ($_REQUEST['reedit'] == 1)
				{
					// Выполняем переход на страницу с редактированием запроса
                    header('Location:index.php?do=request&action=edit&Id=' . $iid . '&rubric_id=' . $_REQUEST['rubric_id'] . '&cp=' . SESSION);
				}
				else
				{
					// В противном случае выполняем переход к списку запросов
			      if (!$_REQUEST['next_edit']) {
						header('Location:index.php?do=request&cp=' . SESSION);
					} else {
						header('Location:index.php?do=request&action=edit&Id=' . $iid . '&rubric_id='.$_REQUEST['rubric_id'].'&cp=' . SESSION);
					}
				}
				exit;
		}
	}

	/**
	 * Метод, предназначенный для редактирования Запроса
	 *
	 * @param int $request_id идентификатор запроса
	 */
	function requestEdit($request_id)
	{
		global $AVE_DB, $AVE_Template;

		// Определяем действие пользователя
        switch ($_REQUEST['sub'])
		{
			// Если действие не определено
            case '':
				// Выполняем запрос к БД и получаем всю информацию о запросе
                $sql = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_request
					WHERE Id = '" . $request_id . "'
				");
				$row = $sql->FetchRow();

                // Передаем данные в шаблон и отображаем страницу с редактированием запроса
				$AVE_Template->assign('row', $row);
				$AVE_Template->assign('formaction', 'index.php?do=request&action=edit&sub=save&Id=' . $request_id . '&cp=' . SESSION);
				$AVE_Template->assign('content', $AVE_Template->fetch('request/form.tpl'));
				break;

            // Пользователь нажал кнопку Сохранить изменения
            case 'save':
				// Выполняем запрос к БД и обновляем имеющиеся данные
                $AVE_DB->Query("
					UPDATE " . PREFIX . "_request
					SET
						rubric_id               = '" . $_REQUEST['rubric_id'] . "',
						request_title           = '" . $_REQUEST['request_title'] . "',
						request_items_per_page  = '" . $_REQUEST['request_items_per_page'] . "',
						request_template_item   = '" . $_REQUEST['request_template_item'] . "',
						request_template_main   = '" . $_REQUEST['request_template_main'] . "',
						request_order_by        = '" . $_REQUEST['request_order_by'] . "',
						request_order_by_nat    = '" . $_REQUEST['request_order_by_nat'] . "',
						request_description     = '" . $_REQUEST['request_description'] . "',
						request_asc_desc        = '" . $_REQUEST['request_asc_desc'] . "',
						request_show_pagination = '" . @$_REQUEST['request_show_pagination'] . "',
						request_cache_lifetime  = '" . (int)($_REQUEST['request_cache_lifetime']>'' ? $_REQUEST['request_cache_lifetime'] : '-1') . "',
						request_lang            = '" . (isset($_REQUEST['request_lang']) ? (int)$_REQUEST['request_lang'] : 0). "'
					WHERE
						Id = '" . $request_id . "'
				");
                $errors = array();
                // Сохраняем системное сообщение в журнал
                reportLog($_SESSION['user_name'] . ' - Отредактировал запрос (' . stripslashes($_REQUEST['request_title']) . ')', 2, 2);

				// Если редактирование было в отдельном окне, закрываем его
                if ($_REQUEST['pop'] == 1)
				{
					echo '<script>self.close();</script>';
				}
				else
				{
                    // В противном случае выполняем переход к списку запросов
					if (!$_REQUEST['ajax']) {
						header('Location:index.php?do=request&cp=' . SESSION);
					} else {
						echo json_encode(array(($AVE_Template->get_config_vars('REQUEST_TEMPLATE_SAVED')) . implode(',<br />', $errors), 'accept'));
					}
					exit;
				}
				break;
		}
	}

	/**
	 * Метод, предназначенный для создания копии Запроса
	 *
	 * @param int $request_id идентификатор запроса
	 */
	function requestCopy($request_id)
	{
		global $AVE_DB;

		// Выполняем запрос к БД на получение информации о копиреумом запросе
        $row = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_request
			WHERE Id = '" . $request_id . "'
		")->FetchRow();

        // Выполняем запрос к БД на добавление нового запроса на основании полученных ранее данных
        $AVE_DB->Query("
			INSERT " . PREFIX . "_request
			SET
				rubric_id               = '" . $row->rubric_id . "',
				request_items_per_page  = '" . $row->request_items_per_page . "',
				request_title           = '" . $_REQUEST['cname'] . "',
				request_template_item   = '" . addslashes($row->request_template_item) . "',
				request_template_main   = '" . addslashes($row->request_template_main) . "',
				request_order_by        = '" . addslashes($row->request_order_by) . "',
				request_order_by_nat    = '" . addslashes($row->request_order_by_nat) . "',
				request_author_id       = '" . (int)$_SESSION['user_id'] . "',
				request_created         = '" . time() . "',
				request_description     = '" . addslashes($row->request_description) . "',
				request_asc_desc        = '" . $row->request_asc_desc . "',
				request_show_pagination = '" . $row->request_show_pagination . "',
				request_lang            = '" . $row->request_lang. "'
		");

        // Получаем id добавленной записи
        $iid = $AVE_DB->InsertId();

		// Сохраняем системное сообщение в журнал
        reportLog($_SESSION['user_name'] . ' - создал копию запроса (' . $request_id . ')', 2, 2);

        // Выполняем запрос к БД и получаем все условия запроса для копируемого запроса
        $sql = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_request_conditions
			WHERE request_id = '" . $request_id . "'
		");

		// Обрабатываем полученные данные и
        while ($row_ak = $sql->FetchRow())
		{
			// Выполняем запрос к БД на добавление условий для нового, скопированного запроса
            $AVE_DB->Query("
				INSERT " . PREFIX . "_request_conditions
				SET
					request_id                  = '" . $iid . "',
					condition_compare   = '" . $row_ak->condition_compare . "',
					condition_field_id  = '" . $row_ak->condition_field_id . "',
					condition_value     = '" . $row_ak->condition_value . "',
					condition_join      = '" . $row_ak->condition_join . "'
			");
		}

        // Выполянем переход к списку запросов
        header('Location:index.php?do=request&cp=' . SESSION);
		exit;
	}

    /**
	 * Метод, предназначенный для удаления запроса
	 *
	 * @param int $request_id идентификатор запроса
	 */
	function requestDelete($request_id)
	{
		global $AVE_DB;

		// Выполняем запрос к БД на удаление общей информации о запросе
        $AVE_DB->Query("
			DELETE
			FROM " . PREFIX . "_request
			WHERE Id = '" . $request_id . "'
		");

        // Выполняем запрос к БД на удаление условий запроса
        $AVE_DB->Query("
			DELETE
			FROM " . PREFIX . "_request_conditions
			WHERE request_id = '" . $request_id . "'
		");

		// Сохраняем системное сообщение в журнал
        reportLog($_SESSION['user_name'] . ' - удалил запрос (' . $request_id . ')', 2, 2);

        // Выполянем переход к списку запросов
        header('Location:index.php?do=request&cp=' . SESSION);
		exit;
	}

	/**
	 * Метод, предназначенный для редактирования условий Запроса
	 *
	 * @param int $request_id идентификатор запроса
	 */
	function requestConditionEdit($request_id)
	{
		global $AVE_DB, $AVE_Template;

		// Определяем действие пользователя
        switch ($_REQUEST['sub'])
		{
			// Если действие не определено
            case '':
				$felder = array();

                // Выполняем запрос к БД и получаем список полей у той рубрики, к которой относится данный запрос
                $sql = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_rubric_fields
					WHERE rubric_id = '" . $_REQUEST['rubric_id'] . "'
				");

                // Обрабатываем полученные данные и формируем массив
                while ($row = $sql->FetchRow())
				{
					array_push($felder, $row);
				}

				$afkonditionen = array();

                // Выполняем запрос к БД и получаем условия запроса
                $sql = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_request_conditions
					WHERE request_id = '" . $request_id . "'
				");

                // Обрабатываем полученные данные и формируем массив
                while ($row = $sql->FetchRow())
				{
					array_push($afkonditionen, $row);
				}

				// Выполняем запрос к БД и получаем название запроса
                $titel = $AVE_DB->Query("
					SELECT request_title
					FROM " . PREFIX . "_request
					WHERE Id = '" . $request_id . "'
					LIMIT 1
				")->GetCell();

                // Передаем данные в шаблон и отображаем страницу с редактированием условий
                $AVE_Template->assign('request_title', $titel);
				$AVE_Template->assign('fields', $felder);
				$AVE_Template->assign('afkonditionen', $afkonditionen);
				$AVE_Template->assign('content', $AVE_Template->fetch('request/conditions.tpl'));
				break;

            // Если пользователь нажал кнопку Сохранить изменения
            case 'save':
                // Если пользователь добавил новое условие
                if (!empty($_POST['Wert_Neu']))
				{
					// Выполняем запрос к БД на добавление нового условия
                    $AVE_DB->Query("
						INSERT " . PREFIX . "_request_conditions
						SET
							request_id                  = '" . $request_id . "',
							condition_compare   = '" . $_POST['Operator_Neu'] . "',
							condition_field_id  = '" . $_POST['Feld_Neu'] . "',
							condition_value     = '" . $_POST['Wert_Neu'] . "',
							condition_join      = '" . $_POST['Oper_Neu'] . "'
					");

					// Сохраняем системное сообщение в журнал
                    reportLog($_SESSION['user_name'] . ' - добавил условие запроса (' . $request_id . ')', 2, 2);
				}

                // Если существует хотя бы одно условие, тогда
                if (isset($_POST['condition_field_id']) && is_array($_POST['condition_field_id']))
				{
					$condition_edited = false;

					// Обрабатываем данные полей
                    foreach ($_POST['condition_field_id'] as $condition_id => $val)
					{
						if (!empty($_POST['condition_value'][$condition_id]))
						{
							// Выполняем запрос к БД на обновление информации об условиях
                            $AVE_DB->Query("
								UPDATE " . PREFIX . "_request_conditions
								SET
									request_id                  = '" . $request_id . "',
									condition_compare   = '" . $_POST['condition_compare'][$condition_id] . "',
									condition_field_id  = '" . $val . "',
									condition_value     = '" . $_POST['condition_value'][$condition_id] . "',
									condition_join      = '" . $_POST['Oper_Neu'] . "'
								WHERE
									Id = '" . $condition_id . "'
							");

							$condition_edited = true;
						}
					}

					// Если изменения были, сохраняем системное сообщение в журнал
                    if ($condition_edited) reportLog($_SESSION['user_name'] . ' - изменил условия запроса (' . $request_id . ')', 2, 2);
				}

                // Если некоторые из условий были помечены на удаление
                if (isset($_POST['del']) && is_array($_POST['del']))
				{
					// Обрабатываем все поля помеченные на удаление
                    foreach ($_POST['del'] as $condition_id => $val)
					{
						// Выполняем запрос к БД на удаление условий
                        $AVE_DB->Query("
							DELETE
							FROM " . PREFIX . "_request_conditions
							WHERE Id = '" . $condition_id . "'
						");
					}

					// Сохраняем системное сообщение в журнал
                    reportLog($_SESSION['user_name'] . ' - удалил условия запроса (' . $request_id . ')', 2, 2);
				}

				// Нет смысла каждый раз формировать SQL-запрос с условиями Запроса
				// поэтому формируем SQL-запрос только при изменении условий
				// require(BASE_DIR . '/functions/func.parserequest.php');
				request_get_condition_sql_string($request_id);

				// Выполняем обновление страницы
                header('Location:index.php?do=request&action=konditionen&rubric_id=' . $_REQUEST['rubric_id'] . '&Id=' . $request_id . '&pop=1&cp=' . SESSION);
				exit;
		}
	}
}

?>