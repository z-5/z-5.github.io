<?php

/**
 * AVE.cms
 *
 * Класс, предназначенный для сбора и формирования общей страницы перед показом в Публичной части.
 * Фактически, данный класс является ядром системы, на который ложится сборка страницы из отдельных компонентов,
 * замена системных тегов соответствующими функциями, а также разбор url параметров и поиск документов по url.
 *
 *
 * @package AVE.cms
 * @filesource
 */

class AVE_Core
{

/**
 *	Свойства класса
 */

    /**
     * Текущий документ
     *
     * @var object
     */
    var $curentdoc = null;

	/**
	 * Установленные модули
	 *
	 * @var array
	 */
	var $install_modules = null;

	/**
	 * Сообщение об ошибке, если документ не найден
	 *
	 * @var string
	 */
	var $_doc_not_found = '<center><h1>HTTP Error 404: Page not found</h1></center>';

	/**
	 * Сообщение об ошибке, если для рубрики не найден шаблон
	 *
	 * @var string
	 */
	var $_rubric_template_empty = '<h1>Ошибка</h1><br />Не задан шаблон рубрики.';

	/**
	 * Сообщение об ошибке, если документ запрещен к показу
	 *
	 * @var string
	 */
	var $_doc_not_published = 'Запрашиваемый документ запрещен к публикации.';

	/**
	 * Сообщение об ошибке, если модуль не может быть загружен
	 *
	 * @var string
	 */
	var $_module_error = 'Запрашиваемый модуль не может быть загружен.';

	/**
	 * Сообщение об ошибке, если модуль, указанный в шаблоне, не установлен в системе
	 *
	 * @var string
	 */
	var $_module_not_found = 'Запрашиваемый модуль не найден.';

/**
 *	Внутренние методы класса
 */

	/**
	 * Метод, предназначенный для получения шаблонов
	 *
	 * @param int $rubrik_id идентификатор рубрики
	 * @param string $template шаблон
	 * @param string $fetched шаблон модуля
	 * @return string
	 */
	function _coreDocumentTemplateGet($rubrik_id = '', $template = '', $fetched = '')
	{
		global $AVE_DB;

		// Если выводится только содержимое модуля или это новое окно (например страница для печати),
        // просто возвращаем содержимое.
        if (defined('ONLYCONTENT') || (isset ($_REQUEST['pop']) && $_REQUEST['pop'] == 1))
		{
			$out = '[tag:maincontent]';
		}
		else
		{
			// В противном случае, если в качестве аргумента передан шаблон модуля, возвращаем его.
            if (!empty ($fetched))
			{
				$out = $fetched;
			}
			else
			{
				// В противном случае, если в качестве аргумента передан общий шаблон, возвращаем его
                if (!empty ($template))
				{
					$out = $template;
				}
				else // В противном случае, если аргументы не определены, тогда проверяем
				{
					// Если для текущего документа в свойстве класса $this->curentdoc определен шаблон, тогда возвращаем его
                    if (!empty ($this->curentdoc->template_text))
					{
						$out = stripslashes($this->curentdoc->template_text);
					}
					else
					{
    					// В противном случае, если не указан идентификатор рубрики
                        if (empty ($rubrik_id))
						{
							// Получаем id документа из запроса
                            $_REQUEST['id'] = (isset ($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? $_REQUEST['id'] : 1;

							// Выполняем запрос к БД на получение id рубрики на основании id документа
                            $rubrik_id = $AVE_DB->Query("
								SELECT rubric_id
								FROM " . PREFIX . "_documents
								WHERE Id = '" . $_REQUEST['id'] . "'
								LIMIT 1
							")->GetCell();

							// Если id рубрики не найден, возвращаем пустую строку
                            if (!$rubrik_id) return '';
						}

						// Выполняем запрос к БД на получение основного шаблона, а также шаблона рубрики
                        $tpl = $AVE_DB->Query("
							SELECT template_text
							FROM " . PREFIX . "_templates AS tpl
							LEFT JOIN " . PREFIX . "_rubrics AS rub ON tpl.Id = rubric_template_id
							WHERE rub.Id = '" . $rubrik_id . "'
							LIMIT 1
						")->GetCell();

						// Если запрос выполнился с нулевым результатом, возвращаем пустую строку
                        $out = $tpl ? $tpl : '';
					}
				}
			}
		}

		return $out;
	}

	/**
	 * Метод, предназначенный для получения шаблона модуля
	 *
	 * @return string
	 */
	function _coreModuleTemplateGet()
	{
		global $AVE_DB;

		// Если папка, с запрашиваемым модулем не существует, выполняем редирект
        // на главную страницу и отображаем сообщение с ошибкой
        if (!is_dir(BASE_DIR . '/modules/' . $_REQUEST['module']))
		{
			echo '<meta http-equiv="Refresh" content="2;URL=' . get_home_link() . '" />';
			$out = $this->_module_not_found;
		}
		// В противном случае
        else
		{
			// Выполняем запрос к БД на получение списка общих шаблонов имеющиюся в системе
            // и шаблоне, который установлен для данного модуля
            // Например, в системе есть шаблоны Template_1 и Template_2, а для модуля установлен Template_3
            $out = $AVE_DB->Query("
				SELECT tmpl.template_text
				FROM " . PREFIX . "_templates AS tmpl
				LEFT JOIN " . PREFIX . "_module AS mdl ON tmpl.Id = mdl.ModuleTemplate
				WHERE ModuleSysName = '" . $_REQUEST['module'] . "'
			")->GetCell();

			// Если шаблон, установленный для модуля не найден в системе, принудительно устанавливаем для него
            // первый шаблон (id=1)
            if (empty ($out))
			{
				$out = $AVE_DB->Query("
					SELECT template_text
					FROM " . PREFIX . "_templates
					WHERE Id = '1'
					LIMIT 1
				")->GetCell();
			}
		}
        // Возвращаем информацию о полученном шаблоне
		return $out;
	}

	/**
	 * Метод, предназначенный для получения прав доступа к документам рубрики
	 *
	 * @param int $rubrik_id идентификатор рубрики
	 */
	function _coreRubricPermissionFetch($rubrik_id = '')
	{
		global $AVE_DB;

		unset ($_SESSION[$rubrik_id . '_docread']);

		// Если для документа уже получены права доступа, тогда
        if (!empty ($this->curentdoc->rubric_permission))
		{
			// Формируем массив с правами доступа
            $rubric_permissions = explode('|', $this->curentdoc->rubric_permission);

            // Циклически обрабатываем сформированный массив и создаем в сессии соответсвующие переменные
            foreach ($rubric_permissions as $rubric_permission)
			{
				if (!empty ($rubric_permission))
				{
					$_SESSION[$rubrik_id . '_' . $rubric_permission] = 1;
				}
			}
		} // В противном случае
		else
		{
			// Выполняем запрос к БД на получение списка прав для данного документа
            $sql = $AVE_DB->Query("
				SELECT rubric_permission
				FROM " . PREFIX . "_rubric_permissions
				WHERE rubric_id = '" . $rubrik_id . "'
				AND user_group_id = '" . UGROUP . "'
			");

			// Обрабатываем полученные данные и создаем в сессии соответсвующие переменные
            while ($row = $sql->FetchRow())
			{
				$row->rubric_permission = explode('|', $row->rubric_permission);
				foreach ($row->rubric_permission as $rubric_permission)
				{
					if (!empty ($rubric_permission))
					{
						$_SESSION[$rubrik_id . '_' . $rubric_permission] = 1;
					}
				}
			}
		}
	}

	/**
	 * Метод, предназначенный для обработки события 404 Not Found, т.е. когда страница не найдена.
	 *
	 * @return unknown
	 */
	function _coreErrorPage404()
	{
		global $AVE_DB;

		// Выполняем запрос к БД на проверку существования страницы, которая содержит информацию о том, что
        // запрашиваемая страница не найдена
        $available = $AVE_DB->Query("
			SELECT COUNT(*)
			FROM " . PREFIX . "_documents
			WHERE Id = '" . PAGE_NOT_FOUND_ID . "'
			LIMIT 1
		")->GetCell();

		// Если такая страница в БД существует, выполняем переход на страницу с ошибкой
        if ($available)
		{
			header('Location:' . ABS_PATH . 'index.php?id=' . PAGE_NOT_FOUND_ID);
		}
		// Если не существует, тогда просто выводим текст, определенный в свойстве _doc_not_found
        else
		{
			echo $this->_doc_not_found;
		}

		exit;
	}

	/**
	 * Метод, предназначенный для формирования хэша страницы
	 *
	 * @return string
	 */
	function _get_cache_hash()
	{
		$hash  = 'g-' . UGROUP;
		$hash .= 'r-' . RUB_ID;
		$hash .= 'u-' . get_redirect_link();

		return md5($hash);
	}

    /**
     * Метод, предназначенный для проверки существования документа в БД
     *
     * @param int $document_id - id документа
     * @param int $user_group - группа пользователя
     * @return boolean
     */
    function _coreCurrentDocumentFetch($document_id = 1, $user_group = 2)
	{
		global $AVE_DB;

        // Выполняем составной  запрос к БД на получение информации о запрашиваемом документе
		$this->curentdoc = $AVE_DB->Query("
			SELECT
				doc.*,
				rubric_permission,
				rubric_template,
				template_text
			FROM
				" . PREFIX . "_documents AS doc
			JOIN
				" . PREFIX . "_rubrics AS rub
					ON rub.Id = doc.rubric_id
			JOIN
				" . PREFIX . "_templates AS tpl
					ON tpl.Id = rubric_template_id
			JOIN
				" . PREFIX . "_rubric_permissions AS prm
					ON doc.rubric_id = prm.rubric_id
			WHERE
				user_group_id = '" . $user_group . "'
			AND
				doc.Id = '" . $document_id . "'
			LIMIT 1
		")->FetchRow();

		// Возвращаем 1, если документ найден, либо 0 в противном случае
        return (isset($this->curentdoc->Id) && $this->curentdoc->Id == $document_id);
	}

    /**
     * Метод, предназначенный для получения содержимого страницы с 404 ошибкой
     *
     *
     * @param int $page_not_found_id
     * @param int $user_group
     * @return int/boolean
     */
    function _corePageNotFoundFetch($page_not_found_id = 2, $user_group = 2)
	{
		global $AVE_DB;

		// Выполняем запрос к БД на получение полной информации о странице с 404 ошибкой, включая
        // права доступа, шаблон рубрики и основной шаблон сайта
        $this->curentdoc = $AVE_DB->Query("
			SELECT
				doc.*,
				rubric_permission,
				rubric_template,
				template_text
			FROM
				" . PREFIX . "_documents AS doc
			JOIN
				" . PREFIX . "_rubrics AS rub
					ON rub.Id = doc.rubric_id
			JOIN
				" . PREFIX . "_templates AS tpl
					ON tpl.Id = rubric_template_id
			JOIN
				" . PREFIX . "_rubric_permissions AS prm
					ON doc.rubric_id = prm.rubric_id
			WHERE
				user_group_id = '" . $user_group . "'
			AND
				doc.Id = '" . $page_not_found_id . "'
			LIMIT 1
		")->FetchRow();

		return (isset($this->curentdoc->Id) && $this->curentdoc->Id == $page_not_found_id);
	}

    /**
     * Метод, предназначенный для получения МЕТА-тегов для различных модулей.
     * Мета-теги для модуля МАГАЗИН
     *
     * @return boolean
     */
    function _coreModuleMetatagsFetch()
	{
		global $AVE_DB;

        // Если в запросе не пришел параметр module, заврешаем работу
		if (! isset($_REQUEST['module'])) return false;

		// Если в запросе пришло значение shop, для параметра module и не указан id товара, тогда
        if ($_REQUEST['module'] == 'shop' && empty ($_REQUEST['product_id']))
		{
			// Выполняем запрос к БД на получение ОБЩИХ значений мета-тегов, установленных в настройках модуля "Магазин"
            $this->curentdoc = $AVE_DB->Query("
				SELECT
					1 AS Id,
					0 AS document_published,
					a.document_meta_robots,
					b.ShopKeywords AS document_meta_keywords,
					b.ShopDescription AS document_meta_description
		        FROM
		        	" . PREFIX . "_documents AS a,
		        	" . PREFIX . "_modul_shop AS b
		        WHERE a.Id = 1
		        AND b.Id = 1
			")->FetchRow();
		}
		// В противном случае, если запрашиваемй модуль "Магазин" и указан id товара, тогда
        elseif ($_REQUEST['module'] == 'shop' && !empty ($_REQUEST['product_id']) && is_numeric($_REQUEST['product_id']))
		{
			// Выполняем запрос к БД и получаем значения мета-тегов для конкретного товара
            $this->curentdoc = $AVE_DB->Query("
				SELECT
					1 AS Id,
					0 AS document_published,
					a.document_meta_robots,
					b.ProdKeywords AS document_meta_keywords,
					b.ProdDescription AS document_meta_description
				FROM
					" . PREFIX . "_documents AS a,
					" . PREFIX . "_modul_shop_artikel AS b
				WHERE a.Id = 1
				AND b.Id = '" . $_REQUEST['product_id'] . "'
			")->FetchRow();
		}
		// Если в запросе модуль не указан, получаем общие мета-теги, установленные для главной (id=1) страницы сайта
        else
		{
			$this->curentdoc = $AVE_DB->Query("
				SELECT
					1 AS Id,
					0 AS document_published,
					document_meta_robots,
					document_meta_keywords,
					document_meta_description,
					document_title
				FROM " . PREFIX . "_documents
				WHERE Id = 1
			")->FetchRow();
		}

		return (isset($this->curentdoc->Id) && $this->curentdoc->Id == 1);
	}

    /**
     * Метод, предназначенный для определения статуса документа (доступен ли он к публикации).
     *
     * @return int/boolean
     */
    function _coreDocumentIsPublished()
	{
		if (!empty ($this->curentdoc)																				// документ есть
			&& $this->curentdoc->Id != PAGE_NOT_FOUND_ID															// документ не сообщение ошибки 404
			&& ( $this->curentdoc->document_status != 1																// статус документа
				|| $this->curentdoc->document_deleted == 1															// пометка удаления
				|| ( get_settings('use_doctime')																	// время публикации контролируется и ...
					&& ($this->curentdoc->document_expire != 0 && $this->curentdoc->document_expire < time())		// время публикации не наступило
					|| ($this->curentdoc->document_published != 0 && $this->curentdoc->document_published > time())	// время публикации истекло
					)
				)
			)
		{
			// Если пользователь авторизован в Панели управления или имеет полные права на просмотр документа, тогда
            if (isset ($_SESSION['adminpanel']) || isset ($_SESSION['alles']))
			{
				// Отображаем информационное окно с сообщением, определенным в свойстве _doc_not_published
                display_notice($this->_doc_not_published);
			}
	        else // В противном случае фиксируем ошибку
			{
				$this->curentdoc = false;
			}
		}

		return (! empty($this->curentdoc));
	}

	/**
	 * Метод парсинга тега [tag:(css|js):files]
	 * для вывода css/js-файлов в шаблоне через combine.php
	 *
	 * @param array $tag параметры тега
	 * @return string что выводить в шаблоне
	 */
	function _parse_combine($tag)
	{
		// тип тега (css|js)
		$type = $tag[1];
		// имена файлов
		$files = explode(',',$tag[2]);

		// определяем путь. если указан - то считаем от корня, если нет, то в /[tag:mediapath]/css|js/
		if ($tag[3])
		{
			$path = '/' . trim($tag[3],'/') . '/';
		}
		else
		{
			$path = '/templates/' . THEME_FOLDER . '/' . $type . '/';
		}
		// уровень вложенности
		$level = substr_count($path,'/') - 1;

		// копируем combine.php, если он поменялся или отсутствует
		$dest_stat = stat(BASE_DIR . $path . 'combine.php');
		$source_stat = stat(BASE_DIR . '/lib/combine/combine.php');
		if (!file_exists(BASE_DIR . $path . 'combine.php') || $source_stat[9] > $dest_stat[9])
		{
			@copy(BASE_DIR . '/lib/combine/combine.php', BASE_DIR . $path . 'combine.php');
		}

		// удаляем из списка отсутствующие файлы
		foreach($files as $key=>$file)
		{
			if (!file_exists(BASE_DIR . $path . $file)) unset($files[$key]);
		}
		if ($files)
		{
			$combine = $path . 'combine.php?level=' . $level . '&amp;' . $type . '=' . implode(',',$files);
			$combine = @str_replace('//','/',$combine);
		}
		return $combine;
	}

/**
 *	Внешние методы класса
 */

	/**
	 * Метод, предназначенный для обработки системных тегов модулей. Здесь подключаются только те файлы модулей,
	 * системные теги которых обнаружены в шаблоне при парсинге. Также формирует массив всех установленных модулей
     * в системе, предварительно проверяя их доступность.
	 *
	 * @param string $template текст шаблона с тегами
	 * @return string текст шаблона с обработанными тегами модулей
	 */
	function coreModuleTagParse($template)
	{
		global $AVE_DB, $AVE_Template;

		$pattern = array();  // Массив системных тегов
		$replace = array();  // Массив функций, на которые будут заменены системные теги

		// Если уже имеются данные об установленных модулях
        if (null !== $this->install_modules)
		{
			// Циклически обрабатываем каждый модуль
            foreach ($this->install_modules as $row)
			{
				// Если в запросе пришел вызов модуля или у модуля есть функция вызываемая тегом,
                // который присутствует в шаблоне
                if ((isset($_REQUEST['module']) && $_REQUEST['module'] == $row->ModuleSysName) ||
					(1 == $row->ModuleIsFunction && !empty($row->ModuleAveTag) && 1 == preg_match($row->ModuleAveTag, $template)))
				{
					// Проверяем, существует ли для данного модуля функция. Если да,
                    // получаем php код функции.
                    if (function_exists($row->ModuleStatus))
					{
						$pattern[] = $row->ModuleAveTag;
						$replace[] = $row->ModulePHPTag;
					}
					else // В противном случае
					{
						// Проверяем, существует ли для данного модуля файл module.php в его персональной директории
						$mod_file = BASE_DIR . '/modules/' . $row->ModuleSysName . '/module.php';
                        if (is_file($mod_file) && include_once($mod_file))
						{
							// Если файл модуля найден, тогда
                            if ($row->ModuleAveTag)
							{
								$pattern[] = $row->ModuleAveTag;  // Получаем его системный тег

                                // Проверяем, существует ли для данного модуля функция. Если да,
                                // получаем php код функции, в противном случае формируем сообщение с ошибкой
                                $replace[] = function_exists($row->ModuleStatus)
									? $row->ModulePHPTag
									: ($this->_module_error . ' &quot;' . $row->ModuleName . '&quot;');
							}
						}
						// Если файла module.php не существует, формируем сообщение с ошибкой
                        elseif ($row->ModuleAveTag)
						{	$pattern[] = $row->ModuleAveTag;
							$replace[] = $this->_module_error . ' &quot;' . $row->ModuleName . '&quot;';
						}
					}
				}
			}

			// Выполняем замену систеного тега на php код и возвращаем результат
            return preg_replace($pattern, $replace, $template);
		}
		else  // В противном случае, если список модулей пустой
		{
			$this->install_modules = array();

            // Выполняем запрос к БД на получение информации о всех модулях, которые установлены в системе
            // (именно установлены, а не просто существуют в виде папок)
			$sql = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX. "_module
				WHERE ModuleStatus = '1'
			");

            // Циклически обрабатываем полученные данные
            while ($row = $sql->FetchRow())
			{
				// Если в запросе пришел параметр module и для данного названия модуля существует
                // директория или данный модуль имеет функцию и его системный тег указан в каком-либо шаблоне, тогда
                if ((isset($_REQUEST['module']) && $_REQUEST['module'] == $row->ModuleSysName) ||
					(1 == $row->ModuleIsFunction && !empty($row->ModuleAveTag) && 1 == preg_match($row->ModuleAveTag, $template)))
				{
					// Проверяем, существует ли для данного модуля файл module.php в его персональной директории
					$mod_file = BASE_DIR . '/modules/' . $row->ModuleSysName . '/module.php';
                    if (is_file($mod_file) && include_once($mod_file))
					{	// Если файл модуля найден, тогда
						if (!empty($row->ModuleAveTag))
						{
							$pattern[] = $row->ModuleAveTag;  // Получаем его системный тег

                            // Проверяем, существует ли для данного модуля функция. Если да,
                            // получаем php код функции, в противном случае формируем сообщение с ошибкой
                            $replace[] = function_exists($row->ModuleFunction)
								? $row->ModulePHPTag
								: ($this->_module_error . ' &quot;' . $row->ModuleName . '&quot;');
						}
						// Сохряняем информацию о модуле
                        $this->install_modules[$row->ModuleSysName] = $row;
					}
					elseif ($row->ModuleAveTag) // Если файла module.php не существует, формируем сообщение с ошибкой
					{
                        $pattern[] = $row->ModuleAveTag;
						$replace[] = $this->_module_error . ' &quot;' . $row->ModuleName . '&quot;';
					}
				}
				else
				{	// Если у модуля нет функции или тег модуля не используется - просто помещаем в массив информацию о модуле
					$this->install_modules[$row->ModuleSysName] = $row;
				}
			}
            // Выполняем замену систеного тега на php код и возвращаем результат
			return preg_replace($pattern, $replace, $template);
		}
	}

	/**
	 * Метод, предназанченный для сборки всей страницы в единое целое.
	 *
	 * @param int $id идентификатор документа
	 * @param int $rub_id идентификатор рубрики
	 */
	function coreSiteFetch($id, $rub_id = '')
	{
		global $AVE_DB;

		// Если происходит вызов модуля, получаем соответствующие мета-теги и получаем шаблон модуля
        if (!empty ($_REQUEST['module'])) {
			$out = $this->_coreModuleMetatagsFetch();
            $out = $this->_coreDocumentTemplateGet('', '', $this->_coreModuleTemplateGet());
		}
		else // В противном случае начинаем вывод документа
		{
            if (! isset($this->curentdoc->Id) && ! $this->_coreCurrentDocumentFetch($id, UGROUP))
			{
				// Определяем документ с 404 ошиюкой, в случае, если документ не найден
                if ($this->_corePageNotFoundFetch(PAGE_NOT_FOUND_ID, UGROUP))
				{
					$_REQUEST['id'] = $_GET['id'] = $id = PAGE_NOT_FOUND_ID;
				}
			}

			// проверяем параметры публикации документа
			if (! $this->_coreDocumentIsPublished())
			{
				$this->_coreErrorPage404();
			}

			// Определяем права доступа к документам рубрики
			define('RUB_ID', !empty ($rub_id) ? $rub_id : $this->curentdoc->rubric_id);
			$this->_coreRubricPermissionFetch(RUB_ID);

			if (! ((isset ($_SESSION[RUB_ID . '_docread']) && $_SESSION[RUB_ID . '_docread'] == 1)
				|| (isset ($_SESSION[RUB_ID . '_alles']) && $_SESSION[RUB_ID . '_alles'] == 1)) )
			{	// читать запрещено - извлекаем ругательство и отдаём вместо контента
				$main_content = get_settings('message_forbidden');
			}
			else
			{
				if (isset ($_REQUEST['print']) && $_REQUEST['print'] == 1)
				{	// увеличиваем счетчик версий для печати
					$AVE_DB->Query("
						UPDATE " . PREFIX . "_documents
						SET document_count_print = document_count_print+1
						WHERE Id = '" . $id . "'
					");
				}
				else
				{
					if (!isset ($_SESSION['doc_view'][$id]))
					{	// увеличиваем счетчик просмотров (1 раз в пределах сессии)
						$AVE_DB->Query("
							UPDATE " . PREFIX . "_documents
							SET document_count_view = document_count_view+1
							WHERE Id = '" . $id . "'
						");
						$_SESSION['doc_view'][$id] = time();
					}

					$curdate=mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
					if (!isset ($_SESSION['doc_view_dayly['.$curdate.'][' . $id . ']']))
					{

                        // и подневный счетчик просмотров тоже увеличиваем
						$curdate=mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
                        $AVE_DB->Query("
                            UPDATE
                                " . PREFIX . "_view_count
                            SET
                                count = count + 1
                            WHERE
                                document_id = '" . $id . "' AND
                                day_id = '".$curdate."'
                        ");
                        if (!$AVE_DB->_handle->affected_rows) {
                            $AVE_DB->Query("
                                INSERT INTO " . PREFIX . "_view_count (
                                    document_id,
                                    day_id,
                                    count
                                )
                                VALUES (
                                    '" . $id . "',  '".$curdate."', '1'
                                )
                            ");
                        }
						$_SESSION['doc_view_dayly['.$curdate.'][' . $id . ']'] = time();
					}

				}

				if (CACHE_DOC_TPL && empty ($_POST) && !(isset ($_SESSION['user_adminmode']) && $_SESSION['user_adminmode'] == 1))
				{	// кэширование разрешено
					// извлекаем скомпилированный шаблон документа из кэша
					$main_content = $AVE_DB->Query("
						SELECT compiled
						FROM " . PREFIX . "_rubric_template_cache
						WHERE hash  = '" . $this->_get_cache_hash() . "'
						LIMIT 1
					")->GetCell();
				}
				else
				{	// кэширование запрещено
					$main_content = false;
				}

				if (empty ($main_content))
				{	// кэш пустой или отключен, извлекаем и компилируем шаблон
					if (!empty ($this->curentdoc->rubric_template))
					{
						$rubTmpl = $this->curentdoc->rubric_template;
					}
					else
					{
						$rubTmpl = $AVE_DB->Query("
							SELECT rubric_template
							FROM " . PREFIX . "_rubrics
							WHERE Id = '" . RUB_ID . "'
							LIMIT 1
						")->GetCell();
					}
					$rubTmpl = trim($rubTmpl);
					if (empty ($rubTmpl))
					{	// не задан шаблон рубрики
						$main_content = $this->_rubric_template_empty;
					}
					else
					{
						// парсим теги полей в шаблоне документа
						$main_content = preg_replace_callback('/\[tag:fld:([a-zA-Z0-9-_]+)\]/', 'document_get_field', $rubTmpl);
						$main_content = preg_replace_callback('/\[tag:([r|c|f|t]\d+x\d+r*):(.+?)]/', 'callback_make_thumbnail', $main_content);

						// удаляем ошибочные теги полей
						$main_content = preg_replace('/\[tag:fld:\d*\]/', '', $main_content);

						if (CACHE_DOC_TPL && empty ($_POST) && !(isset ($_SESSION['user_adminmode']) && $_SESSION['user_adminmode'] == 1))
						{	// кэширование разрешено
							// сохраняем скомпилированный шаблон в кэш
							$AVE_DB->Query("
								INSERT " . PREFIX . "_rubric_template_cache
								SET
									hash     = '" . $this->_get_cache_hash() . "',
									rub_id   = '" . RUB_ID . "',
									grp_id   = '" . UGROUP . "',
									doc_id   = '" . $id . "',
									compiled = '" . addslashes($main_content) . "'
							");
						}
					}
				}
				$main_content = preg_replace('/\[tag:date:([a-zA-Z0-9-]+)\]/e', "RusDate(date('$1', ".$this->curentdoc->document_published."))", $main_content);
				$main_content = str_replace('[tag:docdate]', pretty_date(strftime(DATE_FORMAT, $this->curentdoc->document_published)), $main_content);
				$main_content = str_replace('[tag:doctime]', pretty_date(strftime(TIME_FORMAT, $this->curentdoc->document_published)), $main_content);
				$main_content = str_replace('[tag:docauthorid]', $this->curentdoc->document_author_id, $main_content);
				$main_content = str_replace('[tag:docauthor]', get_username_by_id($this->curentdoc->document_author_id), $main_content);
			}
			$out = str_replace('[tag:maincontent]', $main_content, $this->_coreDocumentTemplateGet(RUB_ID));
		}	// /вывод документа

		//Работа с условиями
/*
		$out = preg_replace('/\[tag:if_exp:?(.*)\]/u', '<?php 
	$my_exp000=true;
	$my_exp0001=\'$my_exp000=\'. str_replace(\'#var#\',\'$\',<<<BLOCK
$1;
BLOCK
);
	@eval($my_exp0001);
	if($my_exp000==true)
		{
?>', $out);
		$out = str_replace('[tag:if_exp_else]', '<?php }else{ ?>', $out);
		$out = str_replace('[tag:/if_exp]', '<?php } ?>', $out);

*/		
		// Тут мы вводим в хеадер иньекцию скриптов.
		if(defined('RUB_ID')){
			$rubheader=$AVE_DB->Query("
							SELECT rubric_header_template
							FROM " . PREFIX . "_rubrics
							WHERE Id = '" . RUB_ID . "'
							LIMIT 1
						",CACHE_LIFETIME)->GetCell();
			$out = str_replace('[tag:rubheader]', $rubheader.'[tag:rubheader]', $out);
		}
		$out = preg_replace('/\[tag:rfld:([a-zA-Z0-9-_]+)]\[(more|esc|img|[0-9-]+)]/e', "request_get_document_field(\"$1\", $id, \"$2\")", $out);
		// Если в запросе пришел параметр print, т.е. страница для печати, парсим контент, который обрамлен
        // тегами только для печати
		if (isset ($_REQUEST['print']) && $_REQUEST['print'] == 1)
		{
			$out = str_replace(array('[tag:if_print]', '[/tag:if_print]'), '', $out);
			$out = preg_replace('/\[tag:if_notprint\](.*?)\[\/tag:if_notprint\]/si', '', $out);
		}
		else
		{
			// В противном случае наоборот, парсим только тот контент, который предназначен НЕ для печати
            $out = preg_replace('/\[tag:if_print\](.*?)\[\/tag:if_print\]/si', '', $out);
			$out = str_replace(array('[tag:if_notprint]', '[/tag:if_notprint]'), '', $out);
		}

		// получаем из шаблона системный тег, определяющий название темы дизайна
		$match = '';
		preg_match('/\[tag:theme:(\w+)]/', $out, $match);
		define('THEME_FOLDER', empty ($match[1]) ? DEFAULT_THEME_FOLDER : $match[1]);
		$out = preg_replace('/\[tag:theme:(.*?)]/', '', $out);

		// парсим теги модулей
		$out = $this->coreModuleTagParse($out);

		if ( isset($_REQUEST['module'])
			&& ! (isset($this->install_modules[$_REQUEST['module']])
				&& '1' == $this->install_modules[$_REQUEST['module']]->ModuleStatus) )
		{
			display_notice($this->_module_error);
		}


		// парсим теги системных блоков
		$out = preg_replace_callback('/\[tag:sysblock:([0-9-]+)\]/', 'parse_sysblock', $out);

		// парсим теги системы внутренних запросов
		$out = preg_replace_callback('/\[tag:request:(\d+)\]/', 'request_parse', $out);

		// парсим теги навигации
		$out = preg_replace_callback('/\[tag:navigation:(\d+):?([0-9,]*)\]/', 'parse_navigation', $out);
		
		// парсим теги скрытого текста
		$out = parse_hide($out);

		// парсим остальные теги основного шаблона
		$search = array(
			'[tag:mediapath]',
			'[tag:path]',
			'[tag:sitename]',
			'[tag:document]',
			'[tag:alias]',
			'[tag:home]',
			'[tag:robots]',
			'[tag:canonical]',
			'[tag:docid]',
			'[tag:breadcrumb]'
		);

		$replace = array(
			ABS_PATH . 'templates/' . THEME_FOLDER . '/',
			ABS_PATH,
			htmlspecialchars(get_settings('site_name'), ENT_QUOTES),
			get_redirect_link('print'),
			@$this->curentdoc->document_alias,
			get_home_link(),
			(isset ($this->curentdoc->document_meta_robots) ? $this->curentdoc->document_meta_robots : ''),
			canonical($_SERVER['REQUEST_URI']),
			(isset ($this->curentdoc->Id) ? $this->curentdoc->Id : ''),
			get_breadcrumb()
		);

		if (defined('MODULE_CONTENT'))
		{	// парсинг тегов при выводе из модуля		
			$search[] = '[tag:maincontent]';
			$replace[] = MODULE_CONTENT;
			$search[] = '[tag:title]';
			$replace[] = htmlspecialchars(defined('MODULE_SITE') ? MODULE_SITE : '', ENT_QUOTES);
			$search[] = '[tag:description]';
			$replace[] =  htmlspecialchars(defined('MODULE_DESCRIPTION') ? MODULE_DESCRIPTION : '', ENT_QUOTES); 
			$search[] = '[tag:keywords]';
			$replace[] = htmlspecialchars(defined('MODULE_KEYWORDS') ? MODULE_KEYWORDS : '', ENT_QUOTES); 
		}
		else
		{
			$search[] = '[tag:keywords]';
			$replace[] = (isset ($this->curentdoc->document_meta_keywords) ? htmlspecialchars($this->curentdoc->document_meta_keywords, ENT_QUOTES) : '');
			$search[] = '[tag:description]';
			$replace[] = (isset ($this->curentdoc->document_meta_description) ? htmlspecialchars($this->curentdoc->document_meta_description, ENT_QUOTES) : '');
			$search[] = '[tag:title]';
			$replace[] = htmlspecialchars(pretty_chars($this->curentdoc->document_title), ENT_QUOTES);
		}

		$search[] = '[tag:maincontent]';
		$replace[] = '';
		$search[] = '[tag:printlink]';
		$replace[] = get_print_link();
		$search[] = '[tag:version]';
		$replace[] = APP_INFO;
		$search[] = '[tag:docviews]';
		$replace[] = isset ($this->curentdoc->document_count_view) ? $this->curentdoc->document_count_view : '';

		// парсим тизер документа
		$out = preg_replace('/\[tag:teaser:(\d+)\]/e', "showteaser($1)", $out);

		if(defined('RUB_ID'))$out = preg_replace('/\[tag:docauthoravatar:(\d+)\]/e', "getAvatar(".intval($this->curentdoc->document_author_id).",\"$1\")", $out);

		if(defined('RUB_ID'))
			{
				$out = preg_replace('/\[tag:lang:([a-zA-Z0-9-_]+)\]/', '<?php if($AVE_Core->curentdoc->document_lang=="$1") { ?>', $out);
			}
			else			
			{
				$out = preg_replace('/\[tag:lang:([a-zA-Z0-9-_]+)\]/', '<?php if($_SESSION["user_language"]=="$1") { ?>', $out);
			}
		$out = str_replace('[tag:/lang]', '<?php } ?>', $out);

		// парсим остальные теги основного шаблона
		$out = str_replace($search, $replace, $out);
		unset ($search, $replace);

		// парсим теги для combine.php
		$out = preg_replace_callback('/\[tag:(css|js):([^ :\/]+):?(\S+)*\]/', array($this, '_parse_combine'), $out);

		// ЧПУ
		$out = rewrite_link($out);

		echo $out;
	}

	/**
	 * Метод, предназначенный для формирования ЧПУ, а также для поиска документа и разбора
     * дополнительных параметров в URL
     *
     * @param string $get_url Строка символов
	 *
	 */
	function coreUrlParse($get_url = '')
	{
		global $AVE_DB;
		$get_url=(strpos($get_url,ABS_PATH.'?')===0 ? str_replace(ABS_PATH.'?',ABS_PATH.'index.php?',$get_url) : $get_url);
		if(
			substr($get_url,0,strlen(ABS_PATH.'index.php'))!=ABS_PATH.'index.php'&&strpos($get_url,'?')!==false
			)$get_url=substr($get_url,0,strpos($get_url,'?'));

		$get_url = rawurldecode($get_url);
		$get_url = mb_substr($get_url, strlen(ABS_PATH));
		$test_url = $get_url; // сохранение старого урла для првоерки использования суффикса

		if (mb_substr($get_url, - strlen(URL_SUFF)) == URL_SUFF)
		{
			$get_url = mb_substr($get_url, 0, - strlen(URL_SUFF));
		}

		// Разбиваем строку пароаметров на отдельные части
        $get_url = explode('/', $get_url);
		//$get_url = array_combine($get_url, $get_url);

		if (isset ($get_url['index']))
		{
			unset ($get_url['index']);
		}

		if (isset ($get_url['print']))
		{
			$_GET['print'] = $_REQUEST['print'] = 1;
			unset ($get_url['print']);
		}

        // Определяем, используется ли у нас разделение документа по страницам
		$pages = preg_grep('/^(a|art)?page-\d+$/i', $get_url);

        if (!empty ($pages))
		{
			$get_url = implode('/', array_diff($get_url, $pages));
			$pages = implode('/', $pages);

			preg_replace_callback(
				'/(page|apage|artpage)-(\d+)/i',
				create_function(
					'$matches',
					'$_GET[$matches[1]] = $matches[2]; $_REQUEST[$matches[1]] = $matches[2];'
				),
				$pages
			);
		}
		else // В противном случае формируем окончательную ссылку для документа
		{
			$get_url = implode('/', $get_url);
		}
		//var_dump($pages);
//		if ($get_url == 'index.php') $get_url = '';

//		unset ($pages);

		if(!empty($_REQUEST['id'])) { // проверка на наличие id в запросе
			$get_url = $AVE_DB->Query("SELECT document_alias FROM " . PREFIX . "_documents WHERE Id = '".(int)$_REQUEST['id']."' ")->GetCell();
		}

        // Выполняем запрос к БД на получение
		$sql = $AVE_DB->Query("
			SELECT
				doc.*,
				rubric_permission,
				rubric_template,
				template_text
			FROM
				" . PREFIX . "_documents AS doc
			JOIN
				" . PREFIX . "_rubrics AS rub
					ON rub.Id = doc.rubric_id
			JOIN
				" . PREFIX . "_templates AS tpl
					ON tpl.Id = rubric_template_id
			JOIN
				" . PREFIX . "_rubric_permissions AS prm
					ON doc.rubric_id = prm.rubric_id
			WHERE
				user_group_id = '" . UGROUP . "'
			AND
				" . (!empty ($get_url) ? "document_alias = '" . str_ireplace("'","\'",$get_url) . "'" : "doc.Id = 1") . "
			LIMIT 1
		");

		if ($this->curentdoc = $sql->FetchRow())
		{
			$_GET['id']  = $_REQUEST['id']  = $this->curentdoc->Id;
			$_GET['doc'] = $_REQUEST['doc'] = $this->curentdoc->document_alias;
			
			if($this->curentdoc->Id!=PAGE_NOT_FOUND_ID)$_SESSION['user_language']=$this->curentdoc->document_lang;
			
			// перенаправление на адреса с суффиксом
			if ($test_url !== $get_url.URL_SUFF && !$pages && $test_url && !$_REQUEST['ajax'] && !$_REQUEST['print']&& !$_REQUEST['tag']) {
				header('HTTP/1.1 301 Moved Permanently');
				header('Location:' . ABS_PATH.$get_url.URL_SUFF);
				exit();
			}
		}
		else
		{
			$_GET['id'] = $_REQUEST['id'] = PAGE_NOT_FOUND_ID;
		}
	}
}

?>