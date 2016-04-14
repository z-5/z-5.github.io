<?php

/**
 * AVE.cms
 *
 * Класс, предназначенный для управления документами в Панели управления
 *
 *
 * @package AVE.cms
 * @filesource
 */

class AVE_Document
{

/**
 *	Свойства класса
 */

	/**
	 * Количество документов отображаемых на одной странице
	 *
	 * @var int
	 */
	var $_limit = 25;

	/**
	 * Ширина поля ввода (для элементов input)
	 *
	 * @var string
	 */
	var $_field_width = '400px';

	/**
	 * Ширина многострочного поля ввода (для элементов textarea)
	 *
	 * @var string
	 */
	var $_textarea_width = '98%';

	/**
	 * Высота многострочного поля ввода (для элементов textarea)
	 *
	 * @var string
	 */
	var $_textarea_height = '400px';

	/**
	 * Ширина маленького многострочного поля ввода
	 *
	 * @var string
	 */
	var $_textarea_width_small = '98%';

	/**
	 * Высота маленького многострочного поля ввода (для элементов textarea)
	 *
	 * @var string
	 */
	var $_textarea_height_small = '200px';

	/**
	 * Максимальное количество символов в Заметке к Документу
	 *
	 * @var int
	 */
	var $_max_remark_length = 5000;

/**
 *	Внутренние методы класса
 */

	/**
	 * Метод, предназначенный для формирование метки времени,
	 * которая будет определять начало периода показа списка Документов.
	 * Т.е. с какого числа/времени начать вывод списка документов.
	 *
	 * @return int	метка времени Unix
	 */
	function _documentListStart()
	{
		$published = explode(".", $_REQUEST['document_published']);
		$timestamp=time(0);
		if (!empty($published[0]))
		{
			$timestamp = mktime(
				0,
				0,
				0,
				$published[1],
				$published[0],
				$published[2]
			);
		}
		return ($timestamp==time(0) ? '' : $timestamp);
	}

	/**
	 * Метод, предназначенный для формирование метки времени,
	 * которая будет определять окончание периода показа списка Документов.
	 * Т.е. по какое число/время ограничить вывод списка документов.
	 *
	 * @return int	метка времени Unix
	 */
	function _documentListEnd()
	{
		$expire = explode(".", $_REQUEST['document_expire']);
		$timestamp=time(0);
		if (!empty($expire[0]))
		{
			$timestamp = mktime(
				23,
				59,
				59,
				$expire[1],
				$expire[0],
				$expire[2]
			);
		}
		return ($timestamp==time(0) ? '' : $timestamp);
	}

	/**
	 * Метод, предназначенный для формирование метки времени начала публикации Документа
	 *
	 * @return int	метка времени Unix
	 */
	function _documentStart($data=0)
	{
		
		$data = explode(" ", $data);
		$stamp[day] = explode(".", $data[0]);
		$stamp[time] = explode(":", $data[1]);
		
		if (!empty($stamp))
		{
			$timestamp = mktime(
				$stamp[time][0],
				$stamp[time][1],
				0,
				$stamp[day][1],
				$stamp[day][0],
				$stamp[day][2]
			);
		}

		return $timestamp;
	}

	/**
	 * Метод, предназначенный для формирование метки времени окончания публикации Документа
	 *
	 * @return int	метка времени Unix
	 */
	function _documentEnd($data=0)
	{
		$data = explode(" ", $data);
		$stamp[day] = explode(".", $data[0]);
		$stamp[time] = explode(":", $data[1]);
		
		if (!empty($stamp))
		{
			$timestamp = mktime(
				$stamp[time][0],
				$stamp[time][1],
				0,
				$stamp[day][1],
				$stamp[day][0],
				$stamp[day][2]
			);
		}

		return $timestamp;
	}


	/**
	 * Метод, предназначенный для получения типа поля
	 * (изображения, однострочное поле, многострочный текст и т.д.),
	 * а также формирования вспомогательных элементов управления этим полем (например кнопка)
	 *
	 * @param string $field_type	тип поля
	 * @param string $field_value	содержимое поля
	 * @param int    $field_id		идентификатор поля
	 * @param string $dropdown		значения для поля типа "Выпадающий список"
	 * @return string				HTML-код поля Документа
	 */
	function _documentFieldGet($field_type, $field_value, $field_id, $dropdown = '')
	{
		global $AVE_Template;

		$field = '';

		$func='get_field_'.$field_type;
		if(!is_callable($func)) $func='get_field_default';
		$field=$func($field_value,'edit',$field_id,'',0,$x,0,0,$dropdown);
		return $field;
	}


/**
 *	Внутренние методы
 */
 
	/**
	 *	Управление Документами
	 */

	/**
	 * Метод, предназначенный для получения списка документов в Панели управления
	 *
	 */
	function documentListGet()
	{
		global $AVE_DB, $AVE_Rubric, $AVE_Template;

		$ex_titel = '';
		$nav_titel = '';
		$ex_zeit = '';
		$nav_zeit = '';
		$request = '';
		$ex_rub = '';
		$nav_rub = '';
		$ex_docstatus = '';
		$navi_docstatus = '';

		// Если в запросе пришел параметр на поиск документа по названию
		if (!empty($_REQUEST['QueryTitel']))
		{
			$request = $_REQUEST['QueryTitel'];
			$kette = explode(' ', $request);  // Получаем список слов, разделяя по пробелу (если их несколько)

			// Циклически обрабатываем слова, формируя условия, которые будут применены в запросе к БД
			foreach ($kette as $suche)
			{
				$und = @explode(' +', $suche);
				foreach ($und as $und_wort)
				{
					if (strpos($und_wort, '+') !== false)
					{
						$ex_titel .= " AND ((UPPER(doc.document_title) LIKE '%" . mb_strtoupper(substr($und_wort, 1)) . "%')OR(UPPER(doc.document_alias) LIKE '%" . mb_strtoupper(substr($und_wort, 1)) . "%'))";
					}
				}

				$und_nicht = @explode(' -', $suche);
				foreach ($und_nicht as $und_nicht_wort)
				{
					if (strpos($und_nicht_wort, '-') !== false)
					{
						$ex_titel .= " AND (UPPER(doc.document_title) NOT LIKE '%" . mb_strtoupper($und_nicht_wort, 1) . "%')";
					}
				}

				$start = explode(' +', $request);
				if (strpos($start[0], ' -') !== false) $start = explode(' -', $request);
				$start = $start[0];
			}

			$ex_titel = "AND ((UPPER(doc.document_title) LIKE '%" . mb_strtoupper($start) . "%')OR(UPPER(doc.document_alias) LIKE '%" . mb_strtoupper($start) . "%'))" . $ex_titel;
			$nav_titel = '&QueryTitel=' . urlencode($request);
		}

		// Если в запросе пришел id определенной рубрики
		if (isset($_REQUEST['rubric_id']) && $_REQUEST['rubric_id'] != 'all')
		{
			// Формируем условия, которые будут применены в запросе к БД
			$ex_rub = " AND doc.rubric_id = '" . $_REQUEST['rubric_id'] . "'";

			// формируем условия, которые будут применены в ссылках
			$nav_rub = '&rubric_id=' . (int)$_REQUEST['rubric_id'];
		}

		// Поиск с учётом условий настроек рубрик
		if (!isset($_REQUEST['rubric_id']) && empty($_REQUEST['QueryTitel'])) {
			// Формируем условия, которые будут применены в запросе к БД
			$ex_rub = " AND rub.rubric_docs_active = '1'";

			// формируем условия для бд
			$ex_db = "LEFT JOIN " . PREFIX . "_rubrics as rub on rub.Id = rubric_id";
		}

		// Поиск с выводом всех результатов из всех рубрик
		if (@$_REQUEST['rubric_id'] == 'all') {
			$nav_rub = '&rubric_id=all';
		}

		// Если в запросе пришел параметр на фильтрацию документов по определенному временному интервалу
		if (@$_REQUEST['document_published'] && @$_REQUEST['document_expire'])
		{
			// Формируем условия, которые будут применены в запросе к БД
			$ex_zeit = 'AND ((doc.document_published BETWEEN ' . $this->_documentListStart() . ' AND ' . $this->_documentListEnd() . ') OR doc.document_published = 0)';

			// формируем условия, которые будут применены в ссылках
			$nav_zeit = '&TimeSelect=1'
				. '&document_published=' . $_REQUEST['document_published']
				. '&document_expire='   . $_REQUEST['document_expire'];
		}

		// Если в запросе пришел параметр на фильтрацию документов по статусу
		if (!empty($_REQUEST['status']))
		{
			// Определяем, какой статус запрашивается и формируем условия, которые будут применены в запросе к БД,
			// а также в ссылках, для дальнейшей навигации
			switch ($_REQUEST['status'])
			{
				// С любым статусом
				case '':
				case 'All':
					break;

				// Только опубликованные
				case 'Opened':
					$ex_docstatus = "AND doc.document_status = '1'";
					$navi_docstatus = '&status=Opened';
					break;

				// Только неопубликованные
				case 'Closed':
					$ex_docstatus = "AND doc.document_status = '0'";
					$navi_docstatus = '&status=Closed';
					break;

				// Помеченные на удаление
				case 'Deleted':
					$ex_docstatus = "AND doc.document_deleted = '1'";
					$navi_docstatus = '&status=Deleted';
					break;
			}
		}

		// Определяем группу пользоваеля и id документа, если он присутствует в запросе
		$ex_Geloescht = (UGROUP != 1) ? "AND doc.document_deleted != '1'" : '' ;
		$w_id = !empty($_REQUEST['doc_id']) ? " AND doc.Id = '" . $_REQUEST['doc_id'] . "'" : '';

		// Выполняем запрос к БД на получение количества документов соответствующих вышеопределенным условиям
		$num = $AVE_DB->Query("
			SELECT COUNT(doc.Id)
			FROM " . PREFIX . "_documents as doc
			". @$ex_db ."
			WHERE 1
			" . $ex_Geloescht . "
			" . $ex_zeit . "
			" . $ex_titel . "
			" . $ex_rub . "
			" . $ex_docstatus . "
			" . $w_id . "
		")->GetCell();

		// Определяем лимит документов, который будет показан на 1 странице
		$limit = (isset($_REQUEST['Datalimit']) && is_numeric($_REQUEST['Datalimit']) && $_REQUEST['Datalimit'] > 0)
			? $_REQUEST['Datalimit']
			: $limit = $this->_limit;

		$nav_limit = '&Datalimit=' . $limit;

		// Определяем количество страниц, которые будут сформированы на основании количества полученных документов
		$seiten = ceil($num / $limit);
		$start = get_current_page() * $limit - $limit;

		$db_sort   = 'ORDER BY doc.Id DESC';
		$navi_sort = '&sort=id_desc';

		// Если в запросе используется параметр сортировки
		if (!empty($_REQUEST['sort']))
		{
			// Определяем, по какому параметру происходит сортировка
			switch ($_REQUEST['sort'])
			{
				// По id документа, по возрастанию
				case 'id' :
					$db_sort   = 'ORDER BY doc.Id ASC';
					$navi_sort = '&sort=id';
					break;

				// По id документа, по убыванию
				case 'id_desc' :
					$db_sort   = 'ORDER BY doc.Id DESC';
					$navi_sort = '&sort=id_desc';
					break;

				// По названию документа, в алфавитном порядке
				case 'title' :
					$db_sort   = 'ORDER BY doc.document_title ASC';
					$navi_sort = '&sort=title';
					break;

				// По названию документа, в обратном алфавитном порядке
				case 'title_desc' :
					$db_sort   = 'ORDER BY doc.document_title DESC';
					$navi_sort = '&sort=title_desc';
					break;

				// По url-адресу, в алфавитном порядке
				case 'alias' :
					$db_sort   = 'ORDER BY doc.document_alias ASC';
					$navi_sort = '&sort=alias';
					break;

				// По url-адресу, в обратном алфавитном порядке
				case 'alias_desc' :
					$db_sort   = 'ORDER BY doc.document_alias DESC';
					$navi_sort = '&sort=alias_desc';
					break;

				// По id рубрики, по возрастанию
				case 'rubric' :
					$db_sort   = 'ORDER BY doc.rubric_id ASC';
					$navi_sort = '&sort=rubric';
					break;

				// По id рубрики, по убыванию
				case 'rubric_desc' :
					$db_sort   = 'ORDER BY doc.rubric_id DESC';
					$navi_sort = '&sort=rubric_desc';
					break;

				// По дате публикации, по возрастанию
				case 'published' :
					$db_sort   = 'ORDER BY doc.document_published ASC';
					$navi_sort = '&sort=published';
					break;

				// По дате публикации, по убыванию
				case 'published_desc' :
					$db_sort   = 'ORDER BY doc.document_published DESC';
					$navi_sort = '&sort=published_desc';
					break;

				// По количеству просмотров, по возрастанию
				case 'view' :
					$db_sort   = 'ORDER BY doc.document_count_view ASC';
					$navi_sort = '&sort=view';
					break;

				// По количеству просмотров, по убыванию
				case 'view_desc' :
					$db_sort   = 'ORDER BY doc.document_count_view DESC';
					$navi_sort = '&sort=view_desc';
					break;

				// По количеству печати документа, по возрастанию
				case 'print' :
					$db_sort   = 'ORDER BY doc.document_count_print ASC';
					$navi_sort = '&sort=print';
					break;

				// По количеству печати документа, по убыванию
				case 'print_desc' :
					$db_sort   = 'ORDER BY doc.document_count_print DESC';
					$navi_sort = '&sort=print_desc';
					break;

				// По автору, по алфавитному возрастанию
				case 'author' :
					$db_sort   = 'ORDER BY doc.document_author_id ASC';
					$navi_sort = '&sort=author';
					break;

				// По автору, по алфавитному убыванию
				case 'author_desc' :
					$db_sort   = 'ORDER BY doc.document_author_id DESC';
					$navi_sort = '&sort=author_desc';
					break;

				// По дате последнего редактирования, по возрастанию
				case 'changed':
					$db_sort   = 'ORDER BY doc.document_changed ASC';
					$navi_sort = '&sort=changed';
					break;

				// По дате последнего редактирования, по убыванию
				case 'changed_desc':
					$db_sort   = 'ORDER BY doc.document_changed DESC';
					$navi_sort = '&sort=changed_desc';
					break;

				// По умолчанию, по дате последнего редактирования по убыванию.
				// Последний отредактированный документ, будет первым в списке.
				default :
					$db_sort   = 'ORDER BY doc.document_changed DESC';
					$navi_sort = '&sort=changed_desc';
					break;
			}
		}

		$docs = array();

		// Выполняем запрос к БД на получение уже не количества документов, отвечающих условиям, а уже на
		// получение всех данных, с учетом всех условий, а также типа сортировки и лимита для вывода на
		// одну страницу.
		$sql = "
			SELECT 
				doc.*,
				rub.rubric_admin_teaser_template
			FROM " . PREFIX . "_documents as doc
			LEFT JOIN " . PREFIX . "_rubrics AS rub ON rub.Id = doc.rubric_id

			WHERE 1
			" . $ex_rub . "
			" . $ex_Geloescht . "
			" . $ex_zeit . "
			" . $ex_titel . "
			" . $ex_docstatus . "
			" . $w_id . "
			" . $db_sort . "
			LIMIT " . $start . "," . $limit . "
		";
		$sql=$AVE_DB->Query($sql);
		
		// Циклически обрабатываем полученные данные с целью приведения некоторых из них к удобочитаемому виду
		while ($row = $sql->FetchRow())
		{
			// Определяем количество комментариев, оставленных для данного документа
			$row->ist_remark = $AVE_DB->Query("
				SELECT COUNT(*)
				FROM " . PREFIX . "_document_remarks
				WHERE document_id = '" . $row->Id . "'
			")->GetCell();

			$this->documentPermissionFetch($row->rubric_id);

			// Получаем название рубрики по ее Id
			$row->RubName         = $AVE_Rubric->rubricNameByIdGet($row->rubric_id)->rubric_title;
			$row->document_author = get_username_by_id($row->document_author_id); // Получаем имя пользователя (Автора)
			$row->cantEdit        = 0;
			$row->canDelete       = 0;
			$row->canEndDel       = 0;
			$row->canOpenClose    = 0;
			$row->rubric_admin_teaser_template=@eval2var('?>'.($row->rubric_admin_teaser_template>'' ? @showrequestelement($row,$row->rubric_admin_teaser_template) : '').'<?');

			// разрешаем редактирование и удаление
			// если автор имеет право изменять свои документы в рубрике
			// или пользователю разрешено изменять все документы в рубрике
			if ( ($row->document_author_id == @$_SESSION['user_id']
				&& isset($_SESSION[$row->rubric_id . '_editown']) && @$_SESSION[$row->rubric_id . '_editown'] == 1)
				|| (isset($_SESSION[$row->rubric_id . '_editall']) && $_SESSION[$row->rubric_id . '_editall'] == 1) )
			{
					$row->cantEdit  = 1;
					$row->canDelete = 1;
			}
			// запрещаем редактирование главной страницы и страницу ошибки 404 если требуется одобрение Администратора
			if ( ($row->Id == 1 || $row->Id == PAGE_NOT_FOUND_ID)
				&& isset($_SESSION[$row->rubric_id . '_newnow']) && @$_SESSION[$row->rubric_id . '_newnow'] != 1)
			{
				$row->cantEdit = 0;
			}
			// разрешаем автору блокировать и разблокировать свои документы если не требуется одобрение Администратора
			if ($row->document_author_id == @$_SESSION['user_id']
				&& isset($_SESSION[$row->rubric_id . '_newnow']) && @$_SESSION[$row->rubric_id . '_newnow'] == 1)
			{
				$row->canOpenClose = 1;
			}
			// разрешаем всё, если пользователь принадлежит группе Администраторов или имеет все права на рубрику
			if (UGROUP == 1 || @$_SESSION[$row->rubric_id . '_alles'] == 1)
			{
				$row->cantEdit     = 1;
				$row->canDelete    = 1;
				$row->canEndDel    = 1;
				$row->canOpenClose = 1;
			}
			// Запрещаем удаление Главной страницы и страницы с 404 ошибкой
			if ($row->Id == 1 || $row->Id == PAGE_NOT_FOUND_ID)
			{
				$row->canDelete = 0;
				$row->canEndDel = 0;
			}

			array_push($docs, $row);
		}

		// Передаем полученные данные в шаблон для вывода
		$AVE_Template->assign('docs', $docs);

		$link  = "index.php?do=docs";
		$link .= (isset($_REQUEST['action']) && $_REQUEST['action'] == 'showsimple') ? '&action=showsimple' : '';
		$link .= !empty($_REQUEST['target']) ? '&target=' . urlencode($_REQUEST['target']) : '';
		$link .= !empty($_REQUEST['doc']) ? '&doc=' . urlencode($_REQUEST['doc']) : '';
		$link .= !empty($_REQUEST['document_alias']) ? '&document_alias=' . urlencode($_REQUEST['document_alias']) : '';
		$link .= !empty($_REQUEST['navi_item_target']) ? '&navi_item_target=' . urlencode($_REQUEST['navi_item_target']) : '';
		$link .= $navi_docstatus;
		$link .= $nav_titel;
		$link .= $nav_rub;
		$link .= $nav_zeit;
		$link .= $nav_limit;
		$link .= (isset($_REQUEST['selurl']) && $_REQUEST['selurl'] == 1) ? '&selurl=1' : '';
		$link .= (isset($_REQUEST['idonly']) && $_REQUEST['idonly'] == 1) ? '&idonly=1' : '';
		$link .= (isset($_REQUEST['pop']) && $_REQUEST['pop'] == 1) ? '&pop=1' : '';

		$AVE_Template->assign('link', $link);

		// Если количество отобранных документов превышает лимит на одной странице - формируем постраничную навигацию
		if ($num > $limit)
		{
			$page_nav = get_pagination($seiten, 'page', ' <li><a href="' . $link . $navi_sort . '&page={s}&cp=' . SESSION . '">{t}</a></li> ');
			$AVE_Template->assign('page_nav', $page_nav);
		}

		$AVE_Template->assign('DEF_DOC_START_YEAR', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 10));
		$AVE_Template->assign('DEF_DOC_END_YEAR', mktime(0, 0, 0, date("m"), date("d"), date("Y") + 10));
	}

	/**
	 * Метод, предназначенный для сохранения статусов документа в БД
	 *
	 */
	 function editstatus() {
	 	global $AVE_DB;
	 	
		 switch(@$_REQUEST['moderation']) {
			// статусы
			case "1" :
				foreach (@$_REQUEST['document'] as $id => $status)
					{
						if (is_numeric($id) && is_numeric($status))
						{
							$AVE_DB->Query("UPDATE " . PREFIX . "_documents SET document_status = '1' WHERE Id = '".$id."'	");
						}
					}
			break;
			
			// статусы
			case "0" :
				foreach (@$_REQUEST['document'] as $id => $status)
					{
						if (is_numeric($id) && is_numeric($status))
						{
							$AVE_DB->Query("UPDATE " . PREFIX . "_documents SET document_status = '0' WHERE Id = '".$id."'	");
						}
					}
			break;
			
			// в корзину
			case "intrash" :
				foreach (@$_REQUEST['document'] as $id => $status)
					{
						if (is_numeric($id) && is_numeric($status))
						{
							$AVE_DB->Query("UPDATE " . PREFIX . "_documents SET document_deleted = '1' WHERE Id = '".$id."'	");
						}
					}
			break;
			
			// из корзины
			case "outtrash" :
				foreach (@$_REQUEST['document'] as $id => $status)
					{
						if (is_numeric($id) && is_numeric($status))
						{
							$AVE_DB->Query("UPDATE " . PREFIX . "_documents SET document_deleted = '0' WHERE Id = '".$id."'	");
						}
					}
			break;
			
			// совсем удалить
			case "trash" :
				foreach (@$_REQUEST['document'] as $id => $status)
					{
						if (is_numeric($id) && is_numeric($status))
						{
							$AVE_DB->Query("DELETE FROM " . PREFIX . "_documents WHERE Id = '".$id."'	");
							$AVE_DB->Query("DELETE FROM " . PREFIX . "_document_fields WHERE document_id = '".$id."'	");
						}
					}
			break;
		}
	 	header('Location:index.php?do=docs&cp=' . SESSION);
		exit;
	 }

	/**
	* Функция предназначенна для анализа ключевых слов и разненсения их по табличке _document_keyword
	*
	*/
	function generateKeywords($document_id,$keywords=null){
		global $AVE_DB;
		if(!$keywords)$keywords=$AVE_DB->Query("SELECT document_meta_keywords FROM ".PREFIX."_documents WHERE Id=".intval($document_id)." LIMIT 1")->GetCell();
		$keywords=explode(',',$keywords);
		$res=$AVE_DB->Query("DELETE FROM ".PREFIX."_document_keywords where document_id=".intval($document_id));
		foreach($keywords as $k=>$v){
			if(trim($v)>''){
				$key=trim(mb_substr($v,0,254));
				$res=$AVE_DB->Query("INSERT INTO ".PREFIX."_document_keywords
					(
						document_id,
						keyword
					)
					VALUES
					(
						'".intval($document_id)."',
						'".clean_no_print_char($key)."'
					)
					");
			}
		}
	}

	/**
	* Функция предназначенна для анализа ключевых слов и разненсения их по табличке _document_keyword
	*
	*/
	function generateTags($document_id,$keywords=null){
		global $AVE_DB;
		if(!$keywords)$keywords=$AVE_DB->Query("SELECT document_tags FROM ".PREFIX."_documents WHERE Id=".intval($document_id)." LIMIT 1")->GetCell();
		$keywords=explode(',',$keywords);
		$res=$AVE_DB->Query("DELETE FROM ".PREFIX."_document_tags where document_id=".intval($document_id));
		foreach($keywords as $k=>$v){
			if(trim($v)>''){
				$key=trim(mb_substr($v,0,254));
				$res=$AVE_DB->Query("INSERT INTO ".PREFIX."_document_tags
					(
						document_id,
						tag
					)
					VALUES
					(
						'".intval($document_id)."',
						'".clean_no_print_char($key)."'
					)
					");
			}
		}
	}

	/**
	 * Метод, предназначенный для сохранения ревизии документа в БД
	 *
	 */

	static	function SaveRevission($document_id){
			global $AVE_DB;
				$sql = $AVE_DB->Query("
				SELECT
					rubric_field_id,
					field_value
				FROM
					" . PREFIX . "_document_fields AS doc_field
				WHERE doc_field.document_id = '" . $document_id . "'
				");
			$rows=Array();
			while ($row = $sql->FetchAssocArray())
			{
				$rows[$row['rubric_field_id']] = pretty_chars(clean_no_print_char($row['field_value']));
			}
			$dtime = $AVE_DB->Query('SELECT document_changed FROM ' . PREFIX . '_documents WHERE Id=' . $document_id)->GetCell();
			$last_rev=@unserialize($AVE_DB->Query("SELECT doc_data FROM ".PREFIX."_document_rev WHERE doc_id=".$document_id." ORDER BY doc_revision DESC LIMIT 1")->GetCell());
			// это я долго пытался понять почему всегда старая ревизия не равна новой даже если просто нажали лишний раз сохранить
			// оказывается редактор подсовывет alt="" если альта в имге нету и сносит его если он есть там пустой ))))))))))
			// но пусть проверка будет - может редакторы сменятся/апдейтятся а может кто просто хардкором будет код править)))
			$dorev=false;
			foreach($rows as $k=>$v)
				if($rows[$k]<>$last_rev[$k]){
						$dorev=true;
					}
				
			if($dorev){
				$AVE_DB->Query("INSERT INTO " . PREFIX . "_document_rev
				SET
					doc_id   ='" . $document_id . "',
					doc_revision ='". $dtime . "',
					doc_data   ='" . addslashes(serialize($rows)) . "',
					user_id ='".$_SESSION['user_id']."'
				");
			}	
			return $rows;
		}

	/**
	 * Метод, предназначенный для востановления ревизии документа
	 *
	 */
	static	function RestoreRevission($document_id,$revision,$rubric_id){

	global $AVE_DB;

			$res = $AVE_DB->Query("SELECT doc_data FROM ".PREFIX."_document_rev WHERE doc_id='".$document_id."' AND doc_revision='".$revision."' LIMIT 1")->GetCell();

			if(!$res) return false;

			$data=@unserialize($res);

			foreach($data as $k=>$v){
				$sql=$AVE_DB->Query("UPDATE ".PREFIX."_document_fields
				SET
					field_value='".addslashes($v)."',
					field_number_value='".intval($v)."'
					WHERE document_id='".$document_id."' AND rubric_field_id='".$k."'
				");
			}
			// Сохраняем системное сообщение в журнал
			reportLog($_SESSION['user_name'] . " - Востановил версию документа (Doc: $document_id Rev: $revision)", 2, 2);
			return true;
		}

	/**
	 * Метод, предназначенный для удаления ревизии документа
	 *
	 */
	static	function DeleteRevission($document_id,$revision,$rubric_id){

	global $AVE_DB, $AVE_Rubric, $AVE_Template;

			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_document_rev
				WHERE doc_id = '" . $document_id . "' AND doc_revision='".$revision."'
			");
	
			// Сохраняем системное сообщение в журнал
			reportLog($_SESSION['user_name'] . " - Удалил версию документа (Doc: $document_id Rev: $revision)", 2, 2);

			if(!isset($_REQUEST['ajax'])){
				header('Location:index.php?do=docs&action=edit&rubric_id=' . $rubric_id . '&Id=' . $document_id . '&cp=' . SESSION);
			}
		}


	/**
	 * Метод, предназначенный для сохранения документа в БД
	 *
	 * @param int $rubric_id	идентификатор Рубрики
	 * @param int $document_id	идентификатор Документа
	 * @param array $data			Документ в массиве структура - хитрая
	 * @param bool $update_non_exists_fields	Изменять поля на пустые значения у не переданных полей или не надо
	 * вохвражает номер документа если все удачно и false если все плохо
	 */

	function documentSave($rubric_id,$document_id,$data,$update_non_exists_fields=false){
		global $AVE_DB;
		//Проверяем входящие данные
		$rubric_id=(int)$rubric_id;
		$document_id=(int)$document_id;
		if(!isset($data))return false;
		if(!isset($data['feld'])) return false;
		//определяем тип опреации
		$oper='INSERT';

		// выполняем стартовый код рубрики
		$code = $AVE_DB->Query("SELECT rubric_code_start, rubric_code_end FROM " . PREFIX . "_rubrics WHERE Id = '" . $rubric_id . "'")->FetchRow();
		eval ('?>' . $code->rubric_code_start . '<?');

		if($document_id>0)$oper='UPDATE';
		// Если пользователь имеет права на добавление документов в указанную рубрику, тогда
		if ($oper=='INSERT' && !( (isset($_SESSION[$rubric_id . '_newnow'])  && $_SESSION[$rubric_id . '_newnow'] == 1)
			|| (isset($_SESSION[$rubric_id . '_new'])   && $_SESSION[$rubric_id . '_new']    == 1)
			|| (isset($_SESSION[$rubric_id . '_alles']) && $_SESSION[$rubric_id . '_alles']  == 1)
			|| (defined('UGROUP') && UGROUP == 1) )) return false;

		if($oper=='UPDATE'){
			// Выполняем запрос к БД на получение автора документа и id Рубрики
				$row = $AVE_DB->Query("
					SELECT
						rubric_id,
						document_author_id
					FROM " . PREFIX . "_documents
					WHERE Id = '" . $document_id . "'
				")->FetchRow();
				$rubric_id=$row->rubric_id;
				$row->cantEdit = 0;

				// Определяем права доступа к документам в данной рубрики
				$this->documentPermissionFetch($row->rubric_id);

				// разрешаем редактирование
				// если автор имеет право изменять свои документы в рубрике
				// или пользователю разрешено изменять все документы в рубрике
				if ( (isset($_SESSION['user_id']) && $row->document_author_id == $_SESSION['user_id'] &&
						isset($_SESSION[$row->rubric_id . '_editown']) && $_SESSION[$row->rubric_id . '_editown'] == 1)
					|| (isset($_SESSION[$row->rubric_id . '_editall']) && @$_SESSION[$row->rubric_id . '_editall'] == 1) )
				{
					$row->cantEdit = 1;
				}
				// запрещаем редактирование главной страницы и страницы ошибки 404 если требуется одобрение Администратора
				if ( ($document_id == 1 || $document_id == PAGE_NOT_FOUND_ID) && @$_SESSION[$row->rubric_id . '_newnow'] != 1 )
				{
					$row->cantEdit = 0;
				}
				// разрешаем редактирование, если пользователь принадлежит группе Администраторов или имеет все права на рубрику
				if ( (defined('UGROUP') && UGROUP == 1)
					|| (isset($_SESSION[$row->rubric_id . '_alles']) && $_SESSION[$row->rubric_id . '_alles'] == 1) )
				{
					$row->cantEdit = 1;
				}

				//выходим если нельзя редактировать
				if(!$row->cantEdit==1 )return false;
					// Обрабатываем все данные, пришедшие в запросе
					$suche     = (isset($data['document_in_search']) && $data['document_in_search'] == 1) ? '1' : '0';
					$document_status = ( (isset($_SESSION[$row->rubric_id . '_newnow']) && $_SESSION[$row->rubric_id . '_newnow'] == 1)
								|| (isset($_SESSION[$row->rubric_id . '_alles']) && $_SESSION[$row->rubric_id . '_alles'] == 1)
								|| (defined('UGROUP') && UGROUP == 1) ) ? (isset($data['document_status']) ? $data['document_status'] : '0') : '0';
					$document_status = ($document_id == 1 || $document_id == PAGE_NOT_FOUND_ID) ? '1' : $docstatus;

					// Формируем/проверяем адрес на уникальность
					if ($document_id != 1)
					{
						$data['document_alias'] = $_url = prepare_url(empty($data['document_alias'])
							? trim($_POST['prefix'] . '/' . $data['doc_title'], '/')
							: $data['document_alias']);
					}
					else{
						$data['document_alias'] = "/";
					}
					$cnt = 1;
					while ($AVE_DB->Query("
						SELECT 1
						FROM " . PREFIX . "_documents
						WHERE Id != '" . $document_id . "'
						AND document_alias = '" . $data['document_alias'] . "'
						LIMIT 1
						")->NumRows() == 1)
					{
						$data['document_alias'] = $_url . '-' . $cnt;
						$cnt++;
					}
				}
				else
				{
					$suche = (isset($data['document_in_search']) && $data['document_in_search'] == 1) ? 1 : 0;
					$document_status = !empty($data['document_status']) ? (int)$data['document_status'] : '0';
					$document_status = ($document_id == 1 || $document_id == PAGE_NOT_FOUND_ID) ? '1' : $document_status;

					$data['document_alias'] = $_url = prepare_url(empty($data['document_alias']) ? trim($data['prefix'] . '/' . $data['doc_title'], '/') : $data['document_alias']);

					$cnt = 1;
					while (
						$AVE_DB->Query("
							SELECT 1
							FROM " . PREFIX . "_documents
							WHERE document_alias = '" . $data['document_alias'] . "'
							LIMIT 1
						")->NumRows())
						{
							$data['document_alias'] = $_url . '-' . $cnt;
							$cnt++;
						}

				}
					
					$docstart	= ($document_id == 1 || $document_id == PAGE_NOT_FOUND_ID) ? '0' : $this->_documentStart($data['document_published']);
					$docend		= ($document_id == 1 || $document_id == PAGE_NOT_FOUND_ID) ? '0' : $this->_documentEnd($data['document_expire']);
					
						//Получаем структуру документа
						$fields = array();

						if($oper=='INSERT'){
							$sql = $AVE_DB->Query("
								SELECT *
								FROM " . PREFIX . "_rubric_fields
								WHERE rubric_id = '" . $rubric_id . "'
								ORDER BY rubric_field_position ASC
							");
						}
						else{
							$sql = $AVE_DB->Query("
								SELECT
									doc.Id AS df_id,
									rub.*,
									rubric_field_default,
									doc.field_value
								FROM " . PREFIX . "_rubric_fields AS rub
								LEFT JOIN " . PREFIX . "_document_fields AS doc ON rubric_field_id = rub.Id
								WHERE document_id = '" . $document_id . "'
								ORDER BY rubric_field_position ASC
							");
						}
						while ($row = $sql->FetchRow())
						{
							$row->Feld = $this->_documentFieldGet($row->rubric_field_type, ($oper=='INSERT' ? $row->rubric_field_default : $row->field_value), $row->Id, $row->rubric_field_default);
							array_push($fields, $row);
						}

						$where=($oper=='UPDATE' ? 'WHERE Id='.$document_id : '');
						$author=($oper!='UPDATE' ? 'document_author_id = '.$_SESSION['user_id'].',' : '');
						$zag=($oper=='UPDATE' ? "UPDATE " . PREFIX . "_documents" : "INSERT INTO " . PREFIX . "_documents");
						$sql="
							$zag
							SET
								rubric_id                 = '" . $rubric_id . "',
								document_parent           = '" . (int)$data['document_parent'] . "',
								document_title            = '" . addslashes(clean_no_print_char($data['doc_title'])) . "',
								document_breadcrum_title  = '" . addslashes(clean_no_print_char($data['doc_breadcrum_title'])) . "',
								document_alias            = '" . $data['document_alias'] . "',
								document_published        = '" . $docstart . "',
								document_expire           = '" . $docend . "',
								document_changed          = '" . time() . "',
								$author
								document_in_search        = '" . $suche . "',
								document_meta_keywords    = '" . addslashes(clean_no_print_char($data['document_meta_keywords'])) . "',
								document_meta_description = '" . addslashes(clean_no_print_char($data['document_meta_description'])) . "',
								document_meta_robots      = '" . $data['document_meta_robots'] . "',
								document_status           = '" . $data['document_status'] . "',
								document_linked_navi_id   = '" . (int)$data['document_linked_navi_id'] . "',
								document_tags   		  = '" . addslashes(clean_no_print_char($data['document_tags'])). "',
								document_lang			  = '" . (empty($data['document_lang']) ? $_SESSION['user_language'] : $data['document_lang']). "'
							$where";
						$AVE_DB->Query($sql);

					if($oper=='UPDATE')$this->SaveRevission($document_id);

					// Получаем id добавленной записи
					$iid=$AVE_DB->InsertId();
					$document_id = ($oper=="INSERT" ? $iid : $document_id);
					// Сохраняем системное сообщение в журнал
					reportLog($_SESSION['user_name'] . ' - '.($oper=='INSERT' ? 'добавил' : 'отредактировал').' документ (' . $document_id . ')', 2, 2);

					// Циклически обрабатываем поля документа
					foreach ($fields as $k => $v)
					{
						$fld_id=$v->Id;
						//если в данных нет поля и мы редактируем документ - изменять ли это поле на пустое значение
						if($oper=='UPDATE' && (!(isset($data['feld'][$fld_id]))) && !$update_non_exists_fields) continue;
						$fld_val=(isset($data['feld'][$fld_id]) ? $data['feld'][$fld_id] : $v->rubric_field_default);
						
						if (!$AVE_DB->Query("
								SELECT 1
								FROM " . PREFIX . "_rubric_fields
								WHERE Id = '" . $fld_id . "'
								AND rubric_id = '" . $rubric_id . "'
								LIMIT 1
							")->NumRows())
						{
							continue;
						}
						if(is_array($fld_val)){
							foreach($fld_val as $k=>$v){
								if(!check_permission('document_php'))if (is_php_code($v)) $v = '';
								$v = clean_no_print_char($v);
								$v = pretty_chars($v);
								$fld_val[$k]=$v;
							}
						}
						else
						{
							// Если запрещено использование php кода, тогда обнуляем данные поля
							if (!check_permission('document_php'))
							{
								if (is_php_code($fld_val)) $fld_val = '';
							}

							// Убираем из текста непчатбемые символы
							$fld_val = clean_no_print_char($fld_val);
							$fld_val = pretty_chars($fld_val);
						}
						
						// Выполняем запрос к БД на добавление нового поля с его содержимым
						$where=($oper=='UPDATE' ? 'WHERE document_id='.$document_id.' AND rubric_field_id='.$fld_id : '');
						$zag=($oper=='UPDATE' ? "UPDATE " . PREFIX . "_document_fields" : "INSERT INTO " . PREFIX . "_document_fields");
						
						$sql="
							$zag
							SET
								rubric_field_id    = '" . $fld_id . "',
								document_id        = '" . $document_id . "',
								field_value        = '" . (is_array($fld_val) ? (serialize($fld_val)) : ($fld_val)) . "',
								field_number_value = '" . (int)(is_array($fld_val) ? serialize($fld_val) : $fld_val) . "',
								document_in_search = '" . $suche . "'
							$where	
						";
						$AVE_DB->Query($sql);
					}
					// Очищаем кэш шаблона
					$AVE_DB->Query("
						DELETE
						FROM " . PREFIX . "_rubric_template_cache
						WHERE doc_id = '" . $document_id . "'
					");
		// выполняем финишный код рубрики
		eval ('?>' . $code->rubric_code_end . '<?');

		//чистим кеш
		$AVE_DB->clearcache('rub_'.$rubric_id);
		$AVE_DB->clearcache('doc_'.$document_id);
		$this->generateKeywords($document_id);
		$this->generateTags($document_id);
		return $document_id;
	}


	/**
	 * Метод, предназначенный для добавления нового документа в БД
	 *
	 * @param int $rubric_id	идентификатор Рубрики
	 */
	function documentNew($rubric_id)
	{
		global $AVE_DB, $AVE_Rubric, $AVE_Template;

		$this->documentPermissionFetch($rubric_id);

		// Если пользователь имеет права на добавление документов в указанную рубрику, тогда
		if ( (isset($_SESSION[$rubric_id . '_newnow'])  && $_SESSION[$rubric_id . '_newnow'] == 1)
			|| (isset($_SESSION[$rubric_id . '_new'])   && $_SESSION[$rubric_id . '_new']    == 1)
			|| (isset($_SESSION[$rubric_id . '_alles']) && $_SESSION[$rubric_id . '_alles']  == 1)
			|| (defined('UGROUP') && UGROUP == 1) )
		{

			$fields = array();

			// Выполняем запрос к БД на получение списка полей, которые относятся к данному документу
			$sql = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_rubric_fields
				WHERE rubric_id = '" . $rubric_id . "'
				ORDER BY rubric_field_position ASC
			");
			while ($row = $sql->FetchRow())
			{
				$row->Feld = $this->_documentFieldGet($row->rubric_field_type, $row->rubric_field_default, $row->Id, $row->rubric_field_default);
				array_push($fields, $row);
			}
			//echo "<pre>";
			//var_dump($fields);
			//echo "</pre>";
			// Определяем вид действия, переданный в параметре sub
			switch ($_REQUEST['sub'])
			{
				case 'save': // Сохранение документа в БД
					$start  = $this->_documentStart(); // Дата/время начала публикации документа
					$ende   = $this->_documentEnd();   // Дата/время окончания публикации документа
					$innavi = check_permission_acp('navigation_new') ? '&innavi=1' : '';

					// Определяем статус документа
					$document_status = !empty($_REQUEST['document_status']) ? (int)$_REQUEST['document_status'] : '0';

					// Если статус документа не определен
					if (empty($document_status) && $_SESSION['user_group'] != 1)
					{
						$innavi = '';
						@reset($_POST);
						$newtext = "\n\n";

						// Формируем текст сообщения, состоящий из данных,
						// которые пользователь ввел в поля документа
						foreach ($_POST['feld'] as $val)
						{
							if (!empty($val))
							{
								$newtext .= $val;
								$newtext .= "\n---------------------\n";
							}
						}
						$text = strip_tags($newtext);

						// Получаем e-mail адрес из общих настроек системы
						$system_mail = get_settings('mail_from');
						$system_mail_name = get_settings('mail_from_name');

						// Отправляем администартору уведомление, о том что необходимо проверить документ
						$body_to_admin = $AVE_Template->get_config_vars('DOC_MAIL_BODY_CHECK');
						$body_to_admin = str_replace('%N%', "\n", $body_to_admin);
						$body_to_admin = str_replace('%TITLE%', stripslashes($_POST['doc_title']), $body_to_admin);
						$body_to_admin = str_replace('%USER%', "'" . $_SESSION['user_name'] . "'", $body_to_admin);
						send_mail(
							$system_mail,
							$body_to_admin . $text,
							$AVE_Template->get_config_vars('DOC_MAIL_SUBJECT_CHECK'),
							$system_mail,
							$system_mail_name,
							'text'
						);

						// Отправляем уведомление автору, о том что документ находится на проверке
						$body_to_author = str_replace('%N%', "\n", $AVE_Template->get_config_vars('DOC_MAIL_BODY_USER'));
						$body_to_author = str_replace('%TITLE%', stripslashes($_POST['doc_title']), $body_to_author);
						$body_to_author = str_replace('%USER%', "'" . $_SESSION['user_name'] . "'", $body_to_author);
						send_mail(
							$_SESSION['user_email'],
							$body_to_author,
							$AVE_Template->get_config_vars('DOC_MAIL_SUBJECT_USER'),
							$system_mail,
							$system_mail_name,
							'text'
						);
					}

					if (! ((isset($_SESSION[$rubric_id . '_newnow']) && $_SESSION[$rubric_id . '_newnow'] == 1)
						|| (isset($_SESSION[$rubric_id . '_alles']) && $_SESSION[$rubric_id . '_alles'] == 1)
						|| (defined('UGROUP') && UGROUP == 1)) )
					{
						$document_status = 0;
					}
					$_POST['document_status']=$document_status;
					$iid=$this->documentSave($rubric_id,null,$_POST,true);

					if (!$_REQUEST['next_edit']) {
						header('Location:index.php?do=docs&action=after&document_id=' . $iid . '&rubric_id=' . $rubric_id . '&cp=' . SESSION . $innavi);
					} else {
						header('Location:index.php?do=docs&action=edit&Id=' . $iid . '&rubric_id=' . $rubric_id . '&cp=' . SESSION);
					}

					exit;

				case '': // Действия по умолчанию, если не задано
					$document = new stdClass();

					// Получаем список прав доступа на добавление документов в определенную рубрику
					$this->documentPermissionFetch($rubric_id);

					// Определяем флаг, который будет активировать или запрещать смену статуса у документа
					if ( (defined('UGROUP') && UGROUP == 1)
						|| (isset($_SESSION[$rubric_id . '_alles']) && $_SESSION[$rubric_id . '_alles'] == 1)
						|| (isset($_SESSION[$rubric_id . '_newnow']) && $_SESSION[$rubric_id . '_newnow'] == 1) )
					{
						$document->dontChangeStatus = 0;
					}
					else
					{
						$document->dontChangeStatus = 1;
					}

					$maxId = $AVE_DB->Query("
						SELECT MAX(Id)
						FROM " . PREFIX . "_documents
					")->GetCell();

					// получения списка документов из связанной рубрики
					$linked_id = $AVE_DB->Query("
						SELECT rubric_linked_rubric
						FROM " . PREFIX . "_rubrics
						WHERE Id = '".$rubric_id."'
					")->GetCell();

					$linked_id = @unserialize($linked_id);
					$document_alias = array();
					if ($linked_id) {
						foreach ($linked_id as $linked_id) {
							$sql = $AVE_DB->Query("
								SELECT doc.document_alias, doc.document_title, doc.document_breadcrum_title, doc.Id, rub.rubric_title
								FROM " . PREFIX . "_documents as doc
								JOIN " . PREFIX . "_rubrics as rub on rub.Id=doc.rubric_id
								WHERE doc.rubric_id = '".$linked_id."'
							");

							while ($row = $sql->FetchRow())
							{
								$document_alias[$row->rubric_title][] = array(
									'document_alias'=>$row->document_alias,
									'document_title'=>$row->document_title,
									'document_breadcrum_title'=>$row->document_breadcrum_title,
									'Id'=>$row->Id
								);
							}
						}
					}
					$AVE_Template->assign('document_alias', $document_alias);
					// получения списка документов из связанной рубрики

					// Формируем данные и передаем в шаблон
					$document->fields = $fields;
					$document->rubric_title = $AVE_Rubric->rubricNameByIdGet($rubric_id)->rubric_title;
					$document->rubric_url_prefix = strftime(str_ireplace("%id", $maxId+1, $AVE_Rubric->rubricNameByIdGet($rubric_id)->rubric_alias));
					$document->formaction = 'index.php?do=docs&action=new&sub=save&rubric_id=' . $rubric_id . ((isset($_REQUEST['pop']) && $_REQUEST['pop']==1) ? 'pop=1' : '') . '&cp=' . SESSION;
					$document->document_published = time();
					$document->document_expire = mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y") + 10);

					$AVE_Template->assign('document', $document);
					$AVE_Template->assign('content', $AVE_Template->fetch('documents/form.tpl'));
					break;
			}
		}
		else
		{	// Пользователь не имеет прав на создание документа, формируем сообщение с ошибкой
			$AVE_Template->assign('content', $AVE_Template->get_config_vars('DOC_NO_PERMISSION_RUB'));
		}
	}

	/**
	 * Метод, предназначенный для редактирования документа
	 *
	 * @param int $document_id	идентификатор Документа
	 */
	function documentEdit($document_id)
	{
		global $AVE_DB, $AVE_Rubric, $AVE_Template;

		// Определяем действие, выбранное пользователем
		switch ($_REQUEST['sub'])
		{
			// Если была нажата кнопка Сохранить изменения
			case 'save':
					$row = $AVE_DB->Query("
					SELECT
						rubric_id,
						document_author_id
					FROM " . PREFIX . "_documents
					WHERE Id = '" . $document_id . "'
				")->FetchRow();

				$this->documentSave($row->rubric_id,$document_id,$_POST,true);

				if(isset($_REQUEST['closeafter']) && $_REQUEST['closeafter']==1) {
					echo "<script>window.opener.location.reload(); window.close();</script>";
				} else {

					if (!$_REQUEST['next_edit']) {
						header('Location:index.php?do=docs&action=after&document_id=' . $document_id . '&rubric_id=' . $row->rubric_id . '&cp=' . SESSION);
					} else {
						header('Location:index.php?do=docs&action=edit&Id=' . $document_id . '&rubric_id=' . $row->rubric_id . '&cp=' . SESSION);
					}
				}
				exit;

			// Если пользователь не выполнял никаких действий, а просто открыл документ для редактирования
			case '':
				// Выполняем запрос к БД на получение данных о документе
				$document = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_documents
					WHERE Id = '" . $document_id . "'
				")->FetchRow();

				$show = true;

				// Проверяем права доступа к документу
				$this->documentPermissionFetch($document->rubric_id);

				// запрещаем доступ,
				// если автору документа не разрешено изменять свои документы в рубрике
				// или пользователю не разрешено изменять все документы в рубрике
				if (!( (isset($_SESSION['user_id']) && $document->document_author_id == $_SESSION['user_id']
					&& isset($_SESSION[$document->rubric_id . '_editown']) && $_SESSION[$document->rubric_id . '_editown'] == 1)
					|| (isset($_SESSION[$document->rubric_id . '_editall']) && $_SESSION[$document->rubric_id . '_editall'] == 1)))
				{
					$show = false;
				}
				// запрещаем доступ к главной странице и странице ошибки 404, если требуется одобрение Администратора
				if ( ($document_id == 1 || $document_id == PAGE_NOT_FOUND_ID) &&
					!(isset($_SESSION[$document->rubric_id . '_newnow']) && $_SESSION[$document->rubric_id . '_newnow'] == 1) )
				{
					$show = false;
				}
				// разрешаем доступ, если пользователь принадлежит группе Администраторов или имеет все права на рубрику
				if ( (defined('UGROUP') && UGROUP == 1)
					|| (isset($_SESSION[$document->rubric_id . '_alles']) && $_SESSION[$document->rubric_id . '_alles'] == 1) )
				{
					$show = true;
				}

				if ($show)
				{
					$fields = array();

					if ( (defined('UGROUP') && UGROUP == 1)
						|| (isset($_SESSION[$document->rubric_id . '_newnow']) && $_SESSION[$document->rubric_id . '_newnow'] == 1) )
					{
						$document->dontChangeStatus = 0;
					}
					else
					{
						$document->dontChangeStatus = 1;
					}

					// Выполняем запрос к БД и получаем все данные для полей документа
					$sql = $AVE_DB->Query("
						SELECT
							doc.Id AS df_id,
							rub.*,
							rubric_field_default,
							doc.field_value
						FROM " . PREFIX . "_rubric_fields AS rub
						LEFT JOIN " . PREFIX . "_document_fields AS doc ON rubric_field_id = rub.Id
						WHERE document_id = '" . $document_id . "'
						ORDER BY rubric_field_position ASC
					");
					while ($row = $sql->FetchRow())
					{
						$row->Feld = $this->_documentFieldGet($row->rubric_field_type, $row->field_value, $row->Id, $row->rubric_field_default);
						array_push($fields, $row);
					}

					// Формируем ряд переменных и передаем их в шаблон для вывода
					$document->fields = $fields;
					$document->document_alias_breadcrumb = rewrite_link('index.php?id=' . $document->Id . '&amp;doc=' . (empty($document->document_alias) ? prepare_url($document->document_title) : $document->document_alias));
					$document->rubric_title = $AVE_Rubric->rubricNameByIdGet($document->rubric_id)->rubric_title;
					$document->rubric_url_prefix = $AVE_Rubric->rubricNameByIdGet($document->rubric_id)->rubric_alias;
					$document->formaction = 'index.php?do=docs&action=edit&sub=save&Id=' . $document_id . '&cp=' . SESSION;

					if ($document->document_parent != 0) $document->parent = $AVE_DB->Query("SELECT document_title, Id FROM " . PREFIX . "_documents WHERE Id = '".$document->document_parent."' ")->FetchRow();

					$document_rev = array();
					$sql_rev = $AVE_DB->Query("
						SELECT *
						FROM " . PREFIX . "_document_rev
						WHERE doc_id = '" . $document_id . "'
						ORDER BY doc_revision DESC
						LIMIT 7
					");
			        // Формируем массив из полученных данных
			        while ($result = $sql_rev->FetchRow())
					{
						$result->user_id = get_username_by_id($result->user_id);
						array_push($document_rev, $result);
					}

					$AVE_Template->assign('document_rev', $document_rev);

					// получения списка документов из связанной рубрики
					$linked_id = $AVE_DB->Query("
						SELECT rubric_linked_rubric
						FROM " . PREFIX . "_rubrics
						WHERE Id = '".$document->rubric_id."'
					")->GetCell();

					@$linked_id = unserialize($linked_id);
					$document_alias = array();
					if ($linked_id) {
						foreach ($linked_id as $linked_id) {
							$sql = $AVE_DB->Query("
								SELECT doc.document_alias, doc.document_title, doc.document_breadcrum_title, doc.Id, rub.rubric_title
								FROM " . PREFIX . "_documents as doc
								JOIN " . PREFIX . "_rubrics as rub on rub.Id=doc.rubric_id
								WHERE doc.rubric_id = '".$linked_id."'
							");

							while ($row = $sql->FetchRow())
							{
								$document_alias[$row->rubric_title][] = array(
									'document_alias'=>$row->document_alias,
									'document_title'=>$row->document_title,
									'document_breadcrum_title'=>$row->document_breadcrum_title,
									'Id'=>$row->Id
								);
							}
						}
					}
					$AVE_Template->assign('document_alias', $document_alias);
					// получения списка документов из связанной рубрики

					$AVE_Template->assign('document', $document);

					// Отображаем страницу для редактирования
					$AVE_Template->assign('content', $AVE_Template->fetch('documents/form.tpl'));
				}
				else // Если пользователь не имеет прав на редактирование, формируем сообщение об ошибке
				{
					$AVE_Template->assign('content', $AVE_Template->get_config_vars('DOC_NO_PERMISSION'));
				}
				break;
		}
	}


	/**
	 * Метод, предназначенный для копирования документа
	 *
	 * @param int $document_id	идентификатор Документа
	 */
	function documentCopy($document_id)
	{
		global $AVE_DB, $AVE_Rubric, $AVE_Template;

		// Определяем действие, выбранное пользователем
		switch ($_REQUEST['sub'])
		{
			// Если была нажата кнопка Сохранить изменения
				case 'save': // Сохранение документа в БД
					$start  = $this->_documentStart(); // Дата/время начала публикации документа
					$ende   = $this->_documentEnd();   // Дата/время окончания публикации документа
					$innavi = check_permission_acp('navigation_new') ? '&innavi=1' : '';

					// Определяем статус документа
					$document_status = !empty($_REQUEST['document_status']) ? (int)$_REQUEST['document_status'] : '0';

					// Если статус документа не определен
					if (empty($document_status) && $_SESSION['user_group'] != 1)
					{
						$innavi = '';
						@reset($_POST);
						$newtext = "\n\n";

						// Формируем текст сообщения, состоящий из данных,
						// которые пользователь ввел в поля документа
						foreach ($_POST['feld'] as $val)
						{
							if (!empty($val))
							{
								$newtext .= $val;
								$newtext .= "\n---------------------\n";
							}
						}
						$text = strip_tags($newtext);

						// Получаем e-mail адрес из общих настроек системы
						$system_mail = get_settings('mail_from');
						$system_mail_name = get_settings('mail_from_name');

						// Отправляем администартору уведомление, о том что необходимо проверить документ
						$body_to_admin = $AVE_Template->get_config_vars('DOC_MAIL_BODY_CHECK');
						$body_to_admin = str_replace('%N%', "\n", $body_to_admin);
						$body_to_admin = str_replace('%TITLE%', stripslashes($_POST['doc_title']), $body_to_admin);
						$body_to_admin = str_replace('%USER%', "'" . $_SESSION['user_name'] . "'", $body_to_admin);
						send_mail(
							$system_mail,
							$body_to_admin . $text,
							$AVE_Template->get_config_vars('DOC_MAIL_SUBJECT_CHECK'),
							$system_mail,
							$system_mail_name,
							'text'
						);

						// Отправляем уведомление автору, о том что документ находится на проверке
						$body_to_author = str_replace('%N%', "\n", $AVE_Template->get_config_vars('DOC_MAIL_BODY_USER'));
						$body_to_author = str_replace('%TITLE%', stripslashes($_POST['doc_title']), $body_to_author);
						$body_to_author = str_replace('%USER%', "'" . $_SESSION['user_name'] . "'", $body_to_author);
						send_mail(
							$_SESSION['user_email'],
							$body_to_author,
							$AVE_Template->get_config_vars('DOC_MAIL_SUBJECT_USER'),
							$system_mail,
							$system_mail_name,
							'text'
						);
					}

					if (! ((isset($_SESSION[$rubric_id . '_newnow']) && $_SESSION[$rubric_id . '_newnow'] == 1)
						|| (isset($_SESSION[$rubric_id . '_alles']) && $_SESSION[$rubric_id . '_alles'] == 1)
						|| (defined('UGROUP') && UGROUP == 1)) )
					{
						$document_status = 0;
					}
					$_POST['document_status']=$document_status;
					$iid=$this->documentSave($_REQUEST[rubric_id],null,$_POST,true);

					if (!$_REQUEST['next_edit']) {
						header('Location:index.php?do=docs&action=after&document_id=' . $iid . '&rubric_id=' . $rubric_id . '&cp=' . SESSION . $innavi);
					} else {
						header('Location:index.php?do=docs&action=edit&Id=' . $iid . '&rubric_id=' . $rubric_id . '&cp=' . SESSION);
					}
					exit;

			// Если пользователь не выполнял никаких действий, а просто открыл документ для копирования
			// Если пользователь не выполнял никаких действий, а просто открыл документ для редактирования
			case '':
				// Выполняем запрос к БД на получение данных о документе
				$document = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_documents
					WHERE Id = '" . $document_id . "'
				")->FetchRow();

				$show = true;

				// Проверяем права доступа к документу
				$this->documentPermissionFetch($document->rubric_id);

				// запрещаем доступ,
				// если автору документа не разрешено изменять свои документы в рубрике
				// или пользователю не разрешено изменять все документы в рубрике
				if (!( (isset($_SESSION['user_id']) && $document->document_author_id == $_SESSION['user_id']
					&& isset($_SESSION[$document->rubric_id . '_editown']) && $_SESSION[$document->rubric_id . '_editown'] == 1)
					|| (isset($_SESSION[$document->rubric_id . '_editall']) && $_SESSION[$document->rubric_id . '_editall'] == 1)))
				{
					$show = false;
				}
				// запрещаем доступ к главной странице и странице ошибки 404, если требуется одобрение Администратора
				if ( ($document_id == 1 || $document_id == PAGE_NOT_FOUND_ID) &&
					!(isset($_SESSION[$document->rubric_id . '_newnow']) && $_SESSION[$document->rubric_id . '_newnow'] == 1) )
				{
					$show = false;
				}
				// разрешаем доступ, если пользователь принадлежит группе Администраторов или имеет все права на рубрику
				if ( (defined('UGROUP') && UGROUP == 1)
					|| (isset($_SESSION[$document->rubric_id . '_alles']) && $_SESSION[$document->rubric_id . '_alles'] == 1) )
				{
					$show = true;
				}

				if ($show)
				{
					$fields = array();

					if ( (defined('UGROUP') && UGROUP == 1)
						|| (isset($_SESSION[$document->rubric_id . '_newnow']) && $_SESSION[$document->rubric_id . '_newnow'] == 1) )
					{
						$document->dontChangeStatus = 0;
					}
					else
					{
						$document->dontChangeStatus = 1;
					}

					// Выполняем запрос к БД и получаем все данные для полей документа
					$sql = $AVE_DB->Query("
						SELECT
							doc.Id AS df_id,
							rub.*,
							rubric_field_default,
							doc.field_value
						FROM " . PREFIX . "_rubric_fields AS rub
						LEFT JOIN " . PREFIX . "_document_fields AS doc ON rubric_field_id = rub.Id
						WHERE document_id = '" . $document_id . "'
						ORDER BY rubric_field_position ASC
					");
					while ($row = $sql->FetchRow())
					{
						$row->Feld = $this->_documentFieldGet($row->rubric_field_type, $row->field_value, $row->Id, $row->rubric_field_default);
						array_push($fields, $row);
					}

					$maxId = $AVE_DB->Query("
						SELECT MAX(Id)
						FROM " . PREFIX . "_documents
					")->GetCell();

					// Формируем ряд переменных и передаем их в шаблон для вывода
					$document->fields = $fields;
					$document->rubric_title = $AVE_Rubric->rubricNameByIdGet($_REQUEST[rubric_id])->rubric_title;
					$document->rubric_url_prefix = strftime(str_ireplace("%id", $maxId+1, $AVE_Rubric->rubricNameByIdGet($_REQUEST[rubric_id])->rubric_alias));
					$document->formaction = 'index.php?do=docs&action=copy&sub=save&rubric_id=' . $_REQUEST[rubric_id] . ((isset($_REQUEST['pop']) && $_REQUEST['pop']==1) ? 'pop=1' : '') . '&cp=' . SESSION;
					$document->document_published = time();
					$document->document_expire = mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y") + 10);

					if ($document->document_parent != 0) $document->parent = $AVE_DB->Query("SELECT document_title, Id FROM " . PREFIX . "_documents WHERE Id = '".$document->document_parent."' ")->FetchRow();

					$AVE_Template->assign('document', $document);

					// Отображаем страницу для редактирования
					$AVE_Template->assign('content', $AVE_Template->fetch('documents/form.tpl'));
				}
				else // Если пользователь не имеет прав на редактирование, формируем сообщение об ошибке
				{
					$AVE_Template->assign('content', $AVE_Template->get_config_vars('DOC_NO_PERMISSION'));
				}
				break;
		}
	}

	/**
	 * Метод, предназначенный для пометки документа к удалению
	 *
	 * @param int $document_id	идентификатор Документа
	 */
	function documentMarkDelete($document_id)
	{
		global $AVE_DB;

		// Выполняем запрос к БД на получение информации о документе (id, id рубрики, автор)
		$row = $AVE_DB->Query("
			SELECT
				Id,
				rubric_id,
				document_author_id
			FROM " . PREFIX . "_documents
			WHERE Id = '" . $document_id . "'
		")->FetchRow();

		// Если у пользователя достаточно прав на выполнение данной операции
		if ( (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row->document_author_id)
			&& (isset($_SESSION[$row->rubric_id . '_editown']) && $_SESSION[$row->rubric_id . '_editown'] == 1)
			|| (isset($_SESSION[$row->rubric_id . '_alles']) && $_SESSION[$row->rubric_id . '_alles'] == 1)
			|| (defined('UGROUP') && UGROUP == 1) )
		{
			// и это не главная страница и не страница с ошибкой 404
			if ($document_id != 1 && $document_id != PAGE_NOT_FOUND_ID)
			{
				// Выполняем запрос к БД на обновление данных (пометка на удаление)
				$AVE_DB->Query("
					UPDATE " . PREFIX . "_documents
					SET document_deleted = '1'
					WHERE Id = '" . $document_id . "'
				");

				$AVE_DB->clearcache('rub_'.$row->rubric_id);			
				$AVE_DB->clearcache('doc_'.$document_id);			
				// Сохраняем системное сообщение в журнал
				reportLog($_SESSION['user_name'] . ' - временно удалил документ (' . $document_id . ')', 2, 2);
			}
		}

		// Выполняем обновление страницы
		header('Location:index.php?do=docs&cp=' . SESSION);
	}

	/**
	 * Метод, предназначенный для снятия отметки об удаления
	 *
	 * @param int $document_id	идентификатор Документа
	 */
	function documentUnmarkDelete($document_id)
	{
		global $AVE_DB;

		// Выполняем запрос к БД на обновление информации (снятие отметки об удалении)
		$row = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_documents
			WHERE Id = '" . $document_id . "'
		")->FetchRow();

		$AVE_DB->Query("
			UPDATE " . PREFIX . "_documents
			SET document_deleted = '0'
			WHERE Id = '" . $document_id . "'
		");

		// Сохраняем системное сообщение в журнал
		reportLog($_SESSION['user_name'] . ' - восстановил удаленный документ (' . $document_id . ')', 2, 2);

		$AVE_DB->clearcache('rub_'.$row->rubric_id);			
		$AVE_DB->clearcache('doc_'.$document_id);			

		// Выполняем обновление страницы
		header('Location:index.php?do=docs&cp=' . SESSION);
	}

	/**
	 * Метод, предназначенный для полного удаления документа без возможности восстановления
	 *
	 * @param int $document_id	идентификатор Документа
	 */
	function documentDelete($document_id)
	{
		global $AVE_DB;

		// Проверяем, чтобы удаляемый документ не являлся главной страницей и не страницей с 404 ощибкой
		if ($document_id != 1 && $document_id != PAGE_NOT_FOUND_ID)
		{
			$row = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_documents
				WHERE Id = '" . $document_id . "'
			")->FetchRow();
			// Выполняем запрос к БД на удаление информации о документе
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_documents
				WHERE Id = '" . $document_id . "'
			");

			// Выполняем запрос к БД на удаление полей, которые относились к документу
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_document_fields
				WHERE document_id = '" . $document_id . "'
			");

			// Очищаем кэш шаблона
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_rubric_template_cache
				WHERE doc_id = '" . $document_id . "'
			");

			// Сохраняем системное сообщение в журнал
			reportLog($_SESSION['user_name'] . ' - окончательно удалил документ (' . $document_id . ')', 2, 2);
		}

		$AVE_DB->clearcache('rub_'.$row->rubric_id);			
		$AVE_DB->clearcache('doc_'.$document_id);			

		// Выполняем обновление страницы
		header('Location:index.php?do=docs&cp=' . SESSION);
	}

	/**
	 * Метод, предназначенный для публикации или отмены публикации документа
	 *
	 * @param int $document_id	идентификатор Документа
	 * @param string $openclose	статус Документа {open|close}
	 */
	function documentStatusSet($document_id, $openclose = 0)
	{
		global $AVE_DB, $AVE_Template;

		$errors = array();

		// Выполняем запрос к БД на получение id автора документа, чтобы проверить уровень прав доступа
		$row = $AVE_DB->Query("
			SELECT
				rubric_id,
				document_author_id
			FROM " . PREFIX . "_documents
			WHERE Id = '" . $document_id . "'
		")->FetchRow();

		// Проверем, чтобы у пользователя было достаточно прав на выполнение данной операции
		if ( ($row->document_author_id == @$_SESSION['user_id'])
			&& (isset($_SESSION[$row->rubric_id . '_newnow']) && @$_SESSION[$row->rubric_id . '_newnow'] == 1)
			|| @$_SESSION[$row->rubric_id . '_alles'] == 1
			|| UGROUP == 1)
		{
			// Если это не главная страница и не страница с 404 ошибкой
			if ($document_id != 1 && $document_id != PAGE_NOT_FOUND_ID)
			{
				// Выполянем запрос к БД на смену статуса у документа
				$AVE_DB->Query("
					UPDATE " . PREFIX . "_documents
					SET document_status = '" . $openclose . "'
					WHERE Id = '" . $document_id . "'
				");

				$AVE_DB->clearcache('rub_'.$row->rubric_id);			
				$AVE_DB->clearcache('doc_'.$document_id);	

				// Сохраняем системное сообщение в журнал
				reportLog($_SESSION['user_name'] . ' - ' . (($openclose==1) ? $AVE_Template->get_config_vars('DOC_DOCUMENT_ACT') : $AVE_Template->get_config_vars('DOC_DOCUMENT_DISACT')) . ' ' . $AVE_Template->get_config_vars('DOC_DOCUMENT_DOC') . ' (' . $document_id . ')', 2, 2);
			
			}else{

				$errors[] = $AVE_Template->get_config_vars('DOC_DOCUMENT_OPEN_ERR');
			}

		}else{

			$errors[] = $AVE_Template->get_config_vars('DOC_DOCUMENT_OPEN_PRIVE');
		}

		if (isset($_REQUEST['ajax'])) {

			if (empty($errors))
			{
				// Если ошибок не найдено, формируем сообщение об успешной операции
				echo json_encode(array((($openclose==1) ? $AVE_Template->get_config_vars('DOC_DOCUMENT_OPEN') : $AVE_Template->get_config_vars('DOC_DOCUMENT_CLOSE')) . implode(',<br />', $errors), 'accept'));
			}else{

				// В противном случае формируем сообщение с ошибкой
				echo json_encode(array($AVE_Template->get_config_vars('DOC_URL_CHECK_ER') . implode(',<br />', $errors), 'error'));

			}

			$AVE_DB->clearcache('rub_'.$row->rubric_id);
			$AVE_DB->clearcache('doc_'.$document_id);
			exit;

		}else{

			$AVE_DB->clearcache('rub_'.$row->rubric_id);
			$AVE_DB->clearcache('doc_'.$document_id);
			// Выполняем обновление страницы
			header('Location:index.php?do=docs&cp=' . SESSION);
			exit;
		}

	}

	/**
	 * Метод, предназначенный для передачи в Smarty шаблонизатор меток периода времени отображаемых
	 * в списке документов
	 *
	 */
	function documentTemplateTimeAssign()
	{
		global $AVE_Template;

		if (!empty($_REQUEST['TimeSelect']))
		{
			$AVE_Template->assign('sel_start', $this->_documentListStart());
			$AVE_Template->assign('sel_ende', $this->_documentListEnd());
		}
	}

	/**
	 * Метод, предназначенный для переноса документа в другую рубрику
	 *
	 */
	function documentRubricChange()
	{
		global $AVE_DB, $AVE_Template;

		$document_id = (int)$_REQUEST['Id'];        // идентификатор документа
		$rubric_id   = (int)$_REQUEST['rubric_id']; // идентификатор текущей рубрики

		// Если в запросе пришел идентификатор новой рубрики и id документа, тогда
		// выполняем автоматический перенос документа из одной рубрики в другую
		if ((!empty($_POST['NewRubr'])) and (!empty($_GET['Id'])))
		{
			$new_rubric_id = (int)$_POST['NewRubr']; // идентификатор целевой рубрики

			// Циклически обрабатываем данные, пришедшие в запросе методо POST
			foreach ($_POST as $key => $value)
			{
				if (is_integer($key))
				{
					// Определяем флаг поля
					switch ($value)
					{
						// Если 0, тогда
						case 0:
							// Выполняем запрос к БД на удаление старого поля (лишнее или не требует переноса)
							$AVE_DB->Query("
								DELETE
								FROM " . PREFIX . "_document_fields
								WHERE document_id = '" . $document_id . "'
								AND rubric_field_id = '" . $key . "'
							");
							break;

						// Если -1, тогда
						case -1:
							// Выполняем запрос на получение данных для этого (старого) поля
							$row_fd = $AVE_DB->Query("
								SELECT
									rubric_field_title,
									rubric_field_type
								FROM " . PREFIX . "_rubric_fields
								WHERE Id = '" . $key . "'
							")->FetchRow();

							// Выполняем запрос к БД и получаем последнюю позицию полей в рубрики КУДА переносим
							$new_pos = $AVE_DB->Query("
								SELECT rubric_field_position
								FROM " . PREFIX . "_rubric_fields
								WHERE rubric_id = '" . $new_rubric_id . "'
								ORDER BY rubric_field_position DESC
								LIMIT 1
							")->GetCell();
							++$new_pos;

							// Выполняем запрос к БД и добавляем новое поле в новую рубрику
							$AVE_DB->Query("
								INSERT
								INTO " . PREFIX . "_rubric_fields
								SET
									rubric_id             = '" . $new_rubric_id . "',
									rubric_field_title    = '" . addslashes($row_fd->rubric_field_title) . "',
									rubric_field_type     = '" . addslashes($row_fd->rubric_field_type) . "',
									rubric_field_position = '" . $new_pos . "'
							");

							$lastid = $AVE_DB->InsertId();

							// Выполняем запрос к БД и добавляем запись о поле в таблицу с полями документов
							$sql_docs = $AVE_DB->Query("
								SELECT Id
								FROM " . PREFIX . "_documents
								WHERE rubric_id = '" . $new_rubric_id . "'
							");

							while ($row_docs = $sql_docs->FetchRow())
							{
								$AVE_DB->Query("
									INSERT
									INTO " . PREFIX . "_document_fields
									SET
										rubric_field_id    = '" . $lastid . "',
										document_id        = '" . $row_docs->Id . "',
										field_value        = '',
										document_in_search = '1'
								");
							}

							// Выполняем запрос к БД и создаем новое поле для изменяемого документа
							$AVE_DB->Query("
								UPDATE " . PREFIX . "_document_fields
								SET rubric_field_id   = '" . $lastid . "'
								WHERE rubric_field_id = '" . $key . "'
								AND document_id       = '" . $document_id . "'
							");
							break;

						// По умолчанию
						default:
							// Выполняем запрос к БД и просто обновляем имеющиеся данные
							$AVE_DB->Query("
								UPDATE " . PREFIX . "_document_fields
								SET rubric_field_id   = '" . $value . "'
								WHERE rubric_field_id = '" . $key . "'
								AND document_id       = '" . $document_id . "'
							");
							break;
					}
				}
			}

			// Выполняем запрос к БД и получаем список всех полей у новой рубрики
			$sql_rub = $AVE_DB->Query("
				SELECT Id
				FROM " . PREFIX . "_rubric_fields
				WHERE rubric_id = '" . $new_rubric_id . "'
				ORDER BY Id ASC
			");

			// Выполняем запросы к БД на проверку наличия нужных полей.
			while ($row_rub = $sql_rub->FetchRow())
			{
				$num = $AVE_DB->Query("
					SELECT 1
					FROM " . PREFIX . "_document_fields
					WHERE rubric_field_id = '" . $row_rub->Id . "'
					AND document_id = '" . $document_id . "'
					LIMIT 1
				")->NumRows();

				// Если в новой рубрики требуемого поля нет, выполняем запрос к БД на добавление нового типа поля
				if ($num != 1)
				{
					$AVE_DB->Query("
						INSERT " . PREFIX . "_document_fields
						SET
							rubric_field_id    = '" . $row_rub->Id . "',
							document_id        = '" . $document_id . "',
							field_value        = '',
							document_in_search = '1'
					");
				}
			}

			// Выполянем запрос к БД на обновление информации, в котором устанавливаем для перенесенного документа
			// новое значение id рубрики
			$AVE_DB->Query("
				UPDATE " . PREFIX . "_documents
				SET rubric_id = '" . $new_rubric_id . "'
				WHERE Id = '" . $document_id . "'
			");

			// Выполняем запрос к БД и очищаем кэш шаблона документа
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_rubric_template_cache
				WHERE doc_id = '" . $document_id . "'
			");

			echo '<script>window.opener.location.reload(); window.close();</script>';
		}
		else  // Если в запросе не был указан id рубрики и id документа
		{
			// Формируем и отображаем форму, где пользователь самостоятельно определяет перенос
			$fields = array();

			if ((!empty($_GET['NewRubr'])) and ($rubric_id != (int)$_GET['NewRubr']))
			{
				// Выполняем запрос к БД  и выбираем все поля новой рубрики
				$sql_rub = $AVE_DB->Query("
					SELECT
						Id,
						rubric_field_title,
						rubric_field_type
					FROM " . PREFIX . "_rubric_fields
					WHERE rubric_id = '" . (int)$_GET['NewRubr'] . "'
					ORDER BY Id ASC
				");
				$mass_new_rubr = array();
				while ($row_rub = $sql_rub->FetchRow())
				{
					$mass_new_rubr[] = array('Id'                => $row_rub->Id,
											 'title'             => $row_rub->rubric_field_title,
											 'rubric_field_type' => $row_rub->rubric_field_type
					);
				}

				// Выполняем запрос к БД и выбираем все поля старой рубрики
				$sql_old_rub = $AVE_DB->Query("
					SELECT
						Id,
						rubric_field_title,
						rubric_field_type
					FROM " . PREFIX . "_rubric_fields
					WHERE rubric_id = '" . $rubric_id . "'
					ORDER BY Id ASC
				");

				// Циклически обрабатываем полученные данные
				while ($row_nr = $sql_old_rub->FetchRow()) {
					$type = $row_nr->rubric_field_type;
					$option_arr = array('0'  => $AVE_Template->get_config_vars('DOC_CHANGE_DROP_FIELD'),
										'-1' => $AVE_Template->get_config_vars('DOC_CHANGE_CREATE_FIELD')
					);
					$selected = -1;
					foreach ($mass_new_rubr as $row)
					{
						if ($row['rubric_field_type'] == $type)
						{
							$option_arr[$row['Id']] = $row['title'];
							if ($row_nr->rubric_field_title == $row['title']) $selected = $row['Id'];
						}
					}
					$fields[$row_nr->Id] = array('title'    => $row_nr->rubric_field_title,
												 'Options'  => $option_arr,
												 'Selected' => $selected
					);
				}
			}

			// Формируем ряд переменых и отображаем страницу с выбором рубрики
			$AVE_Template->assign('fields', $fields);
			$AVE_Template->assign('formaction', 'index.php?do=docs&action=change&Id=' . $document_id . '&rubric_id=' . $rubric_id . '&pop=1&cp=' . SESSION);
			$AVE_Template->assign('content', $AVE_Template->fetch('documents/change.tpl'));
		}
	}

	/**
	 * Метод, предназначенный для формирования URL
	 *
	 */
	function documentAliasCreate()
	{
		$alias  = empty($_REQUEST['alias'])  ? '' : prepare_url($_REQUEST['alias']);
		$prefix = empty($_REQUEST['prefix']) ? '' : prepare_url($_REQUEST['prefix']);
		$title  = empty($_REQUEST['title'])  ? '' : $_REQUEST['title'];
		$title  = (URL_YANDEX==true) ? y_translate($title) : prepare_url($title);
		
		if ($alias != $title && $alias != trim($prefix . '/' . $title, '/')) $alias = trim($alias . '/' . $title, '/');

		return $alias;
	}

	/**
	 * Метод, предназначенный для контроля уникальности URL
	 *
	 */
	function documentAliasCheck()
	{
		global $AVE_DB, $AVE_Template;

		$document_id = (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
		$document_alias = (isset($_REQUEST['alias'])) ? $_REQUEST['alias'] : '';

		$errors = array();

		// Если указанный URL пользователем не пустой
		if (!empty($document_alias))
		{
			// Проверяем, чтобы данный URL соответствовал требованиям
			if (preg_match(TRANSLIT_URL ? '/[^\.a-z0-9\/-]+/' : '/^[^0-9A-Za-zА-Яа-яЁё]+$/u', $document_alias))
			{
				$errors[] = $AVE_Template->get_config_vars('DOC_URL_ERROR_SYMBOL');
			}

			// Если URL начинается с "/" - фиксируем ошибку
			if ($document_alias[0] == '/') $errors[] = $AVE_Template->get_config_vars('DOC_URL_ERROR_START');

			// Если суффикс URL заканчивается на "/" и URL заканчивается на "/" - фиксируем ошибку
			if (substr(URL_SUFF, 0, 1) == '/' && substr($document_alias, -1) == '/') $errors[] = $AVE_Template->get_config_vars('DOC_URL_ERROR_END');

			// Если в URL используются слова apage-XX, artpage-XX,page-XX,print, фиксируем ошибку, где ХХ - число
			$matches = preg_grep('/^(apage-\d+|artpage-\d+|page-\d+|print)$/i', explode('/', $document_alias));
			if (!empty($matches)) $errors[] = $AVE_Template->get_config_vars('DOC_URL_ERROR_SEGMENT') . implode(', ', $matches);

			// Выполняем запрос к БД на получение всех URL и проверку на уникальность
			if (empty($errors))
			{
				$alias_exist = $AVE_DB->Query("
					SELECT 1
					FROM " . PREFIX . "_documents
					WHERE document_alias = '" . $document_alias . "'
					AND Id != '" . $document_id . "'
					LIMIT 1
				")->NumRows();

				if ($alias_exist) $errors[] = $AVE_Template->get_config_vars('DOC_URL_ERROR_DUPLICATES');
			}
		}
		else
		{  // В противном случае, если URL пустой, формируем сообщение об ошибке
			$errors[] = $AVE_Template->get_config_vars('DOC_URL_ERROR_EMTY');
		}

		// Если ошибок не найдено, формируем сообщение об успешной операции
		if (empty($errors))
		{
			return json_encode(array($AVE_Template->get_config_vars('DOC_URL_CHECK_OK') . implode(',<br />', $errors), 'accept'));
		}
		else
		{ // В противном случае формируем сообщение с ошибкой
			return json_encode(array($AVE_Template->get_config_vars('DOC_URL_CHECK_ER') . implode(',<br />', $errors), 'error'));
		}
	}

	/**
	 * Метод, предназначенный для формирования прав доступа Группы пользователей на Документы определённой Рубрики
	 *
	 * @param int $rubric_id	идентификатор Рубрики
	 */
	function documentPermissionFetch($rubric_id)
	{
		global $AVE_DB;

		// Массив прав пользователей
		static $rubric_permissions = array();

		// Если у нас уже имеются полученные права для данной рубрики, просто прерываем проверку
		if (isset($rubric_permissions[$rubric_id])) return;

		// Выполняем запрос к БД на получение прав для данной рубрики
		$sql = $AVE_DB->Query("
			SELECT
				rubric_id,
				rubric_permission
			FROM " . PREFIX . "_rubric_permissions
			WHERE user_group_id = '" . UGROUP . "'
		");

		// Циклически обрабатываем полученные данные и формируем массив прав
		while ($row = $sql->FetchRow())
		{
			$rubric_permissions[$row->rubric_id] = 1;

			$permissions = explode('|', $row->rubric_permission);

			foreach ($permissions as $rubric_permission)
			{
				if (!empty($rubric_permission))
				{
					$_SESSION[$row->rubric_id . '_' . $rubric_permission] = 1;
				}
			}
		}
	}

	/**
	 * Метод, предназначенный для просмотра и добавления Заметок к Документу
	 *
	 * @param int $reply	признак ответа на Заметку
	 */
	function documentRemarkNew($document_id = 0, $reply = 0)
	{
		global $AVE_DB, $AVE_Template;

		// Если id документа не число или 0, прерываем выполнение
		if (!(is_numeric($document_id) && $document_id > 0)) exit;

		// Если в запросе пришел параметр на Сохранение
		if (isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			// Если пользователь оставил комментарий и у него имеются права и это не ответ, а новая заметка, тогда
			if (!empty($_REQUEST['remark_text']) && check_permission('remarks') && empty($_REQUEST['reply']))
			{
				// Выполняем запрос к БД на добавление новой заметки для документа
				$AVE_DB->Query("
					INSERT " . PREFIX . "_document_remarks
					SET
						document_id         = '" . $document_id . "',
						remark_title        = '" . clean_no_print_char($_REQUEST['remark_title']) . "',
						remark_text         = '" . substr(clean_no_print_char($_REQUEST['remark_text']), 0, $this->_max_remark_length) . "',
						remark_author_id    = '" . $_SESSION['user_id'] . "',
						remark_published    = '" . time() . "',
						remark_first        = '1',
						remark_author_email = '" . $_SESSION['user_email'] . "'
				");
			}

			// Выполняем обновление страницы
			header('Location:index.php?do=docs&action=remark_reply&Id=' . $document_id . '&pop=1&cp=' . SESSION);
		}

		// Если это ответ на уже существующую заметку
		if ($reply == 1)
		{
			if (isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
			{
				// Если пользователь оставил ответ и имеет на это права
				if (!empty($_REQUEST['remark_text']) && check_permission('remarks'))
				{
					// Выполняем запрос на получение e-mail адреса автора заметки
					$remark_author_email = $AVE_DB->Query("
						SELECT remark_author_email
						FROM  " . PREFIX . "_document_remarks
						WHERE remark_first = '1'
						AND document_id = '" . $document_id . "'
					")->GetCell();

					// Выполняем запрос к БД на добавление заметки в БД
					$AVE_DB->Query("
						INSERT " . PREFIX . "_document_remarks
						SET
							document_id         = '" . $document_id . "',
							remark_title        = '" . clean_no_print_char($_REQUEST['remark_title']) . "',
							remark_text         = '" . substr(clean_no_print_char($_REQUEST['remark_text']), 0, $this->_max_remark_length) . "',
							remark_author_id    = '" . $_SESSION['user_id'] . "',
							remark_published    = '" . time() . "',
							remark_first        = '0',
							remark_author_email = '" . $_SESSION['user_email'] . "'
					");
				}

				// Формируем сообщение и отправляем письмо автору, с информацией о том, что на его заметку есть ответ
				$system_mail = get_settings('mail_from');
				$system_mail_name = get_settings('mail_from_name');
				$link = get_home_link() . 'index.php?do=docs&doc_id=' . $document_id;

				$body_to_admin = $AVE_Template->get_config_vars('DOC_MAIL_BODY_NOTICE');
				$body_to_admin = str_replace('%N%', "\n", $body_to_admin);
				$body_to_admin = str_replace('%TITLE%', stripslashes($_POST['remark_title']), $body_to_admin);
				$body_to_admin = str_replace('%USER%', get_username_by_id($_SESSION['user_id']), $body_to_admin);
				$body_to_admin = str_replace('%LINK%', $link, $body_to_admin);
				send_mail(
					$remark_author_email,
					$body_to_admin,
					$AVE_Template->get_config_vars('DOC_MAIL_SUBJECT_NOTICE'),
					$system_mail,
					$system_mail_name,
					'text'
				);

				// Выполняем обновление страницы
				header('Location:index.php?do=docs&action=remark_reply&Id=' . $document_id . '&pop=1&cp=' . SESSION);
			}

			// Получаем общее количество заметок для документа
			$num = $AVE_DB->Query("
				SELECT COUNT(*)
				FROM " . PREFIX . "_document_remarks
				WHERE document_id = '" . $document_id . "'
			")->GetCell();

			// Определяыем лимит заметок на 1 странице и подсчитываем количество страниц
			$limit = 10;
			$seiten = ceil($num / $limit);
			$start = get_current_page() * $limit - $limit;

			$answers = array();

			// Выполняем запрос к БД на получение заметок с учетом количества на 1 странцу
			$sql = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_document_remarks
				WHERE document_id = '" . $document_id . "'
				ORDER BY Id DESC
				LIMIT " . $start . "," . $limit
			);
			while ($row = $sql->FetchAssocArray())
			{
				$row['remark_author'] = get_username_by_id($row['remark_author_id']);
				$row['remark_text'] = nl2br($row['remark_text']);
				$row['remark_avatar'] = getAvatar($row['remark_author_id'],40);
				array_push($answers, $row);
			}

			$remark_status = $AVE_DB->Query("
				SELECT remark_status
				FROM " . PREFIX . "_document_remarks
				WHERE document_id = '" . $document_id . "'
				AND remark_first = '1'
			")->GetCell();

			// Если количество заметок превышает допустимое значение, определенное в переменной $limit, тогда
			// формируем постраничную навигацию
			if ($num > $limit)
			{
				$page_nav = "<li><a href=\"index.php?do=docs&action=remark_reply&Id=" . $document_id . "&page={s}&pop=1&cp=" . SESSION . "\">{t}</a></li>";
				$page_nav = get_pagination($seiten, 'page', $page_nav);
				$AVE_Template->assign('page_nav', $page_nav);
			}

			// Передаем данные  в шаблон и отображаем страницу со списком заметок
			$AVE_Template->assign('remark_status', $remark_status);
			$AVE_Template->assign('answers', $answers);
			$AVE_Template->assign('reply', 1);
			$AVE_Template->assign('formaction', 'index.php?do=docs&action=remark_reply&sub=save&Id=' . $document_id . '&reply=1&cp=' . SESSION);
			$AVE_Template->assign('content', $AVE_Template->fetch('documents/newremark.tpl'));
		}
		else
		{ // В противном случае, если заметок еще нет, открываем форму для добавление заметки
			$AVE_Template->assign('reply', 1);
			$AVE_Template->assign('new', 1);
			$AVE_Template->assign('formaction', 'index.php?do=docs&action=remark&sub=save&Id=' . $document_id . '&cp=' . SESSION);
			$AVE_Template->assign('content', $AVE_Template->fetch('documents/newremark.tpl'));
		}
	}

	/**
	 * Метод, предназначенный для управления статусами дискусии (разрешить или запретить оставлять
	 * ответы на заметки для других пользователей)
	 *
	 * @param int $document_id	идентификатор документа
	 * @param int $status		статус дискусии
	 */
	function documentRemarkStatus($document_id = 0, $status = 0)
	{
		global $AVE_DB;

		// Если id документа число и оно больше 0, тогда
		if (is_numeric($document_id) && $document_id > 0)
		{
			// Выполняем запрос к БД на обновление статуса у заметок
			$AVE_DB->Query("
				UPDATE " . PREFIX . "_document_remarks
				SET remark_status  = '" . ($status != 1 ? 0 : 1) . "'
				WHERE remark_first = '1'
				AND document_id    = '" . $document_id . "'
			");
		}

		// Выполняем обновление данных
		header('Location:index.php?do=docs&action=remark_reply&Id=' . $document_id . '&pop=1&cp=' . SESSION);
		exit;
	}

	/**
	 * Метод, предназначенный для удаление заметок
	 *
	 * @param int $all	признак удаления всех Заметок (1 - удалить все)
	 */
	function documentRemarkDelete($document_id = 0, $all = 0)
	{
		global $AVE_DB;

		// Если id документа не число или 0, прерываем выполнение
		if (!(is_numeric($document_id) && $document_id > 0)) exit;

		// Если в запросе пришел параметр на удаление всех заметок
		if ($all == 1)
		{
			// Выполянем запрос к БД и удалаем заметки
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_document_remarks
				WHERE document_id = '" . $document_id . "'
			");

			// Выполняем обновление страницы
			header('Location:index.php?do=docs&action=remark&Id=' . $document_id . '&pop=1&cp=' . SESSION);
			exit;
		}
		else
		{
			if (!(isset($_REQUEST['CId']) && is_numeric($_REQUEST['CId']) && $_REQUEST['CId'] > 0)) exit;

			// В противном случае, выполняем запрос к БД и удаляем только ту заметку, которая была выбрана
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_document_remarks
				WHERE document_id = '" . $document_id . "'
				AND Id = '" . $_REQUEST['CId'] . "'
			");

			// Выполняем обновление страницы
			header('Location:index.php?do=docs&action=remark_reply&Id=' . $document_id . '&pop=1&cp=' . SESSION);
			exit;
		}
	}

	/**
	 * Добавить в навигацию пункт ссылающийся на документ
	 *
	 */
	function documentInNavi()
	{
		global $AVE_DB;

		$document_id = isset($_REQUEST['document_id']) ? (int)$_REQUEST['document_id'] : 0;
		$rubric_id = isset($_REQUEST['rubric_id']) ? (int)$_REQUEST['rubric_id'] : 0;
		$title  = isset($_REQUEST['navi_title']) ? clean_no_print_char($_REQUEST['navi_title']) : '';

		if ($document_id > 0 && $rubric_id > 0 && $title != '' && check_permission_acp('navigation_new'))
		{
			$document_alias = $AVE_DB->Query("
				SELECT document_alias
				FROM " . PREFIX . "_documents
				WHERE Id = '" . $document_id . "'
				AND rubric_id = '" . $rubric_id . "'
				LIMIT 1
			")->GetCell();
		}

		if (isset($document_alias) && $document_alias !== false)
		{
			// Получаем id пункта меню из запроса
			$parent_id = isset($_REQUEST['parent_id']) ? (int)$_REQUEST['parent_id'] : 0;

			// Если пункт не родительский, а какой-либо дочерний
			if ($parent_id > 0)
			{
				// Выполняем запрос к БД на получение id меню навигации и уровня
				list($navi_id, $status, $level) = $AVE_DB->Query("
					SELECT
						navi_id,
						navi_item_status,
						navi_item_level+1
					FROM " . PREFIX . "_navigation_items
					WHERE Id = '" . $parent_id . "'
					LIMIT 1
				")->FetchArray();
			}
			else
			{
				$navi_id = (isset($_REQUEST['navi_id']) && (int)$_REQUEST['navi_id'] > 0) ? (int)$_REQUEST['navi_id'] : 1;
				$status  = 1;
				$level   = 1;
			}

			$target = (isset($_REQUEST['navi_item_target']) && $_REQUEST['navi_item_target'] == '_blank') ? '_blank' : '_self';

			$position = empty($_REQUEST['navi_item_position']) ? 1 : (int)$_REQUEST['navi_item_position'];

			// Добавляем информации о новой связке Документ<->Пункт меню
			$AVE_DB->Query("
				INSERT
				INTO " . PREFIX . "_navigation_items
				SET
					title              = '" . $title . "',
					document_alias     = '" . $document_alias . "',
					parent_id          = '" . $parent_id . "',
					navi_id            = '" . $navi_id . "',
					navi_item_level    = '" . $level . "',
					navi_item_target   = '" . $target . "',
					navi_item_position = '" . $position . "',
					navi_item_status   = '" . $status . "',
					navi_item_link     = 'index.php?id=" . $document_id . "'
			");
		}

		header('Location:index.php?do=docs&action=after&document_id=' . $document_id . '&rubric_id=' . $rubric_id . '&cp=' . SESSION);
		exit;
	}

	/**
	 * Вывод формы дополнительных действий с новым или отредактированным документом
	 *
	 */
	function documentFormAfter()
	{
		global $AVE_DB, $AVE_Template;

		$document_id = isset($_REQUEST['document_id']) ? (int)$_REQUEST['document_id'] : 0;
		$rubric_id = isset($_REQUEST['rubric_id']) ? (int)$_REQUEST['rubric_id'] : 0;
		$innavi = (isset($_REQUEST['innavi']) && check_permission_acp('navigation_new')) ? 1 : 0;

		if ($document_id > 0 && $rubric_id > 0)
		{
			$document = $AVE_DB->Query("
				SELECT
					Id AS document_id,
					rubric_id,
					document_title AS document_title,
					'" . $innavi . "' AS innavi
				FROM " . PREFIX . "_documents
				WHERE Id = '" . $document_id . "'
				AND rubric_id = '" . $rubric_id . "'
				LIMIT 1
			")->FetchAssocArray();
		}

		if (empty($document))
		{
			header('Location:index.php?do=docs&cp=' . SESSION);
			exit;
		}

		$AVE_Template->assign($document);
		$AVE_Template->assign('content', $AVE_Template->fetch('documents/form_after.tpl'));
	}

	/**
	 * Метод, предназначенный для смены автора документа
	 *
	 * @param int $doc_id		идентификатор документа
	 * @param int $user_id		идентификатор пользователя
	 */
	function changeAutorSave()
	{
		global $AVE_DB;

		// Если id документа число и оно больше 0, тогда
		if (is_numeric($_REQUEST['doc_id']) && $_REQUEST['doc_id'] > 0)
		{
			// Выполняем запрос к БД на обновление статуса у заметок
			$AVE_DB->Query("
				UPDATE " . PREFIX . "_documents
				SET document_author_id  = '" . $_REQUEST['user_id'] . "'
				WHERE Id = '" . $_REQUEST['doc_id'] . "'
			");

			$username = get_username_by_id($_REQUEST['user_id']);
			echo "
				<script>
					window.opener.document.getElementById('doc_id_". $_REQUEST['doc_id'] ."').textContent = '$username';
					window.close();
				</script>
			";
		}
		exit;
	}

}
?>