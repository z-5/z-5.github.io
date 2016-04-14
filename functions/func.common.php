<?php
/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

/**
 * Возвращаем аватар по пользователю 
 *
 * @param int $id Ид пользователя- если не придет то текущий пользователь
 * @param int $size размер картинки по краю 
 * @return string путь до файла с превью
 */
function getAvatar($id=null,$size=58, $prefix="")
{
	global $AVE_DB;
	static $result=array();
	
	if ($id===null) $id=$_SESSION['user_id'];
	if(!isset($result[$id])){
		$user=get_user_rec_by_id($id);
		$ava = ABS_PATH. UPLOAD_DIR .'/avatars/'.(($prefix==="")?"":$prefix).md5($user->user_name);
		$ava = (file_exists(BASE_DIR.$ava.'.jpg') ? $ava.'.jpg' : (file_exists(BASE_DIR.$ava.'.png') ? $ava.'.png' : (file_exists(BASE_DIR.$ava.'.gif') ? $ava.'.gif' : '')));
		$result[$id]=$ava;
	}
	$ava=$result[$id];
	$src = (file_exists(BASE_DIR.$ava) ? 
		make_thumbnail(array('link' => $ava,'size' => 'c'.$size.'x'.$size)):
		make_thumbnail(array('link' => $AVE_DB->Query("SELECT default_avatar FROM ".PREFIX."_user_groups WHERE user_group=".(int)$user->user_group)->GetCell(),'size' => 'c'.$size.'x'.$size))
		);
	return $src;
}

/**
 * Устанавливаем аватар пользователю 
 *
 * @param int $id Ид пользователя
 * @param int $ava путь до картинки которая будет автаром 
 * @return bool установился аватар или нет
 */
function SetAvatar($id,$ava){
	if ($id===null) $id=$_SESSION['user_id'];
	$user=get_user_rec_by_id($id); 
	$file_ext=explode('.',basename($ava));
	$file_ext=$file_ext[count($file_ext)-1];
	if (!file_exists($ava)) return false;
	$new_ava=BASE_DIR.'/'. UPLOAD_DIR .'/avatars/'.md5($user->user_name).'.'.strtolower($file_ext);
	foreach (glob(BASE_DIR.'/'. UPLOAD_DIR .'/avatars/'.md5($user->user_name).'.*') as $filename) {
		@unlink($filename);
	}
	
	//Чистим превьюшки
	foreach (glob(BASE_DIR.'/'. UPLOAD_DIR .'/avatars/'.THUMBNAIL_DIR.'/'.md5($user->user_name).'*.*') as $filename) {
		@unlink($filename);
	}
	
	@file_put_contents($new_ava,file_get_contents($ava));
	@unlink($ava);
	return true;
}

// вставляем файл с пользовательскими функциями
if (file_exists(BASE_DIR."/functions/func.custom.php")) include (BASE_DIR."/functions/func.custom.php");

/**
 * Функция загрузки файлов с удаленного сервера через CURL
 * как альтернатива для file_get_conents
 */
function CURL_file_get_contents($sourceFileName){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $sourceFileName);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$st = curl_exec($ch);
	curl_close($ch);
	return ($st);
}

/**
 * Ищет по шаблону в указанном месте пути всех директорий, поддиректорий и файлов, находящихся в них
 *
 * @param (string)	$path - путь к директории
 * @param (string)	$pattern - шаблон поиска
 * @param (flags)	$flags - флаги для функции glob()
 * @param (int)		$depth - глубина вложенности, просматриваемая функцией. -1 - без ограничений.
 *
 * @return (array) - найденные пути
 */
function bfglob($path, $pattern = '*', $flags = GLOB_NOSORT, $depth = 0)
{
	$matches = array();
	$folders = array(rtrim($path, '/'));

	while($folder = array_shift($folders)) 
	{
		$matches = array_merge($matches, glob($folder.'/'.$pattern, $flags));
		if($depth != 0)
		{
			$moreFolders = glob($folder.'/'.'*', GLOB_ONLYDIR);
			$depth   = ($depth < -1) ? -1: $depth + count($moreFolders) - 2;
			$folders = array_merge($folders, $moreFolders);
		}
	}
	return $matches;
}

/**
 * Рекурсивно чистит директорию
 *
 * @param $dir	Директория
 * @return bool
 */
 
function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
} 		

 
/**
 * Возвращает исполненный php код в переменную
 *
 * @param int $id	идентификатор запроса
 * @return string
 */
function eval2var( $expression ) {
	global $AVE_DB,$AVE_Core,$AVE_Template;
	ob_start();
	eval( $expression );
	$content = ob_get_clean();
	return $content;
}

/**
 * Вычисление разницы между двумя метками времени
 *
 * @param string $a начальная метка
 * @param string $b конечная метка
 * @return int время между метками
 */
function microtime_diff($a, $b)
{
	list($a_dec, $a_sec) = explode(' ', $a);
	list($b_dec, $b_sec) = explode(' ', $b);
	return $b_sec - $a_sec + $b_dec - $a_dec;
}

/**
 * Регистронезависимый вариант функции strpos
 * Возвращает числовую позицию первого вхождения needle в строке haystack.
 *
 * @param unknown_type $haystack проверяемая строка
 * @param unknown_type $needle искомая подстрока
 * @param unknown_type $offset с какого символа в haystack начинать поиск.
 * @return int числовая позиция
 */
if (!function_exists("stripos"))
{
	function stripos($haystack, $needle, $offset = 0)
	{
		return strpos(strtoupper($haystack), strtoupper($needle), $offset);
	}
}

/**
 * Форматирование числа
 *
 * @param array $param значение и параметры
 * @return string отформатированное значение
 */
function num_format($param)
{
	if (is_array($param)) return number_format($param['val'], 0, ',', '.');
	return '';
}

/**
 * Проверка начинается ли строка с указанной подстроки
 *
 * @param string $str проверяемая строка
 * @param string $in подстрока
 * @return boolean результат проверки
 */
function start_with($str, $in)
{
	return(substr($in, 0, strlen($str)) == $str);
}

/**
 * Проверка прав пользователя
 *
 * @param string $action проверяемое право
 * @return boolean результат проверки
 */
function check_permission($action)
{
	global $_SESSION;

	if ((isset($_SESSION['user_group']) && $_SESSION['user_group'] == 1) ||
		(isset($_SESSION['alles'])      && $_SESSION['alles'] == 1) ||
		(isset($_SESSION[$action])      && $_SESSION[$action] == 1))
	{
		return true;
	}

	return false;
}


function clean_no_print_char($text)
{
	return trim(preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $text));
}

/**
 * Очистка текста от програмного кода
 *
 * @param string $text исходный текст
 * @return string очищенный текст
 */
function clean_php($text)
{
	return str_replace(array('<?', '?>', '<script'), '', $text);
}

/**
 * Вывод системного сообщения
 *
 * @param string $message сообщение
 */
function display_notice($message)
{
	echo '<div class="display_notice"><b>Системное сообщение: </b>' . $message . '</div>';
}

/**
 * Сообщение о запрете распечатки страницы
 *
 */
function print_error()
{
	display_notice('Запрашиваемая страница не может быть распечатана.');
	exit;
}

/**
 * Сообщение о проблемах доступа к файлам модуля
 *
 */
function module_error()
{
	display_notice('Запрашиваемый модуль не может быть загружен.');
	exit;
}

/**
 * Получение основных настроек
 *
 * @param string $field параметр настройки, если не указан - все параметры
 * @return mixed
 */
function get_settings($field = '')
{
	global $AVE_DB;

	static $settings = null;

	if ($settings === null)
	{
		$settings = $AVE_DB->Query("SELECT * FROM " . PREFIX . "_settings")->FetchAssocArray();
	}

	if ($field == '') return $settings;

	return isset($settings[$field]) ? $settings[$field] : null;
}

/**
 * Возвращает меню навигации
 *
 * @param int $id идентификатор меню навигации
 * @return объект с навигацией по id, либо массив всех навигаций
 */
function get_navigations($id = null)
{
	global $AVE_DB;

	static $navigations = null;

	if ($navigations == null)
	{
		$navigations = array();

		$sql = $AVE_DB->Query("SELECT * FROM " . PREFIX . "_navigation",-1);

		while ($row = $sql->FetchRow())
		{
			$row->navi_user_group = explode(',', $row->navi_user_group);
			$navigations[$row->id] = $row;
		}
	}

	if ($id) return $navigations[$id];
	else return $navigations;
}

/**
 * Проверка прав доступа к навигации по группе пользователя
 *
 * @param int $id идентификатор меню навигации
 * @return boolean
 */
function check_navi_permission($id)
{
	$navigation = get_navigations($id);

	if (empty($navigation->navi_user_group)) return false;

	if (!defined('UGROUP')) define('UGROUP', 2);
	if (!in_array(UGROUP, $navigation->navi_user_group)) return false;

	return true;
}

/**
 * Обработка парного тега [tag:hide:X,X:text]...[/tag:hide] (скрытый текст)
 * Заменяет скрываемый текст в зависимости от группы пользователя
 *
 * @param string $data обрабатываемый текст
 * @return string обработанный текст
 */
function parse_hide($data){
	static $matches = null;
	static $i = null;
	preg_match_all('/\[tag:hide:(\d+,)*'.UGROUP.'(,\d+)*(:.*?)?].*?\[\/tag:hide]/s', $data, $matches, PREG_SET_ORDER);
	for ($i=0; $i<=count($matches); $i++) {
		$hidden_text = substr(@$matches[$i][3],1);
		if ($hidden_text == "") $hidden_text = trim(get_settings('hidden_text'));
		$data = preg_replace('/\[tag:hide:(\d+,)*'.UGROUP.'(,\d+)*(:.*?)?].*?\[\/tag:hide]/s', $hidden_text, $data, 1);
	}
	$data = preg_replace('/\[tag:hide:\d+(,\d+)*.*?](.*?)\[\/tag:hide]/s', '\\2', $data);
	return $data;
}

/**
 * Получить идентификатор текущего документа
 *
 * @return int идентификатор текущего документа
 */
function get_current_document_id()
{
	$_REQUEST['id'] = (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? $_REQUEST['id'] : 1;

	return $_REQUEST['id'];
}

/**
 * Получить идентификатор родительского документа
 *
 * @return int идентификатор родительского документа
 */
function get_parent_document_id()
{
	global $AVE_DB;
	return $AVE_DB->Query("SELECT document_parent FROM " . PREFIX . "_documents WHERE Id = '".get_current_document_id()."' ")->GetCell();
}

/**
 * Формирование URL редиректа
 *
 * @return string URL
 */
function get_redirect_link($exclude = '')
{
	global $AVE_Core;

	$link = 'index.php';

	if (!empty($_GET))
	{
		if ($exclude != '' && !is_array($exclude)) $exclude = explode(',', $exclude);

		$exclude[] = 'url';

		$params = array();
		foreach($_GET as $key => $value)
		{
			if (!in_array($key, $exclude))
			{
				if ($key == 'doc')
				{
					$params[] = 'doc=' . (empty($AVE_Core->curentdoc->document_alias) ? prepare_url($AVE_Core->curentdoc->document_title) : $AVE_Core->curentdoc->document_alias);
				}
				else
				{
					$params[] = @urlencode($key) . '=' . @urlencode($value);
				}
			}
		}

		if (sizeof($params)) $link .= '?' . implode('&', $params);
	}

	return $link;
}

/**
 * Ссылка на главную страницу
 *
 * @return string ссылка
 */
function get_home_link()
{
	return HOST . ABS_PATH.($_SESSION['user_language']==DEFAULT_LANGUAGE ? '' : $_SESSION['accept_langs'][$_SESSION['user_language']].URL_SUFF);
}

/**
 * Формирование хлебных крошек
 *
 * @return string ссылка
 */
function get_breadcrumb()
{
	global $AVE_DB;

	$crumb = array();
	$curent_document = get_current_document_id();
	$noprint=null;
	$sql="SELECT * from ".PREFIX."_documents where document_alias='".($_SESSION['user_language']==DEFAULT_LANGUAGE ? '/' : $_SESSION['accept_langs'][$_SESSION['user_language']])."' and document_lang='".$_SESSION['user_language']."'";
	$lang_home_alias=$AVE_DB->Query($sql)->FetchRow();
	
	$bread_crumb = $lang_home_alias ? "<a href=\"".get_home_link()."\">".$lang_home_alias->document_breadcrum_title."</a>&nbsp;&rarr;&nbsp;" : '';
	if ($curent_document == 1|| $curent_document == 2) $noprint = 1;

	$sql_document = $AVE_DB->Query("SELECT document_title, document_breadcrum_title, document_parent FROM " . PREFIX . "_documents WHERE Id = '".$curent_document."'",-1,'doc_'.$curent_document);
	$row_document = $sql_document->fetchrow();

	$current->document_breadcrum_title = (empty($row_document->document_breadcrum_title) ? $row_document->document_title : $row_document->document_breadcrum_title);

	if (isset($row_document->document_parent) && $row_document->document_parent != 0) {
		$i = 0;
		$current->document_parent = $row_document->document_parent;

		 while ($current->document_parent != 0) {
			$sql_doc = $AVE_DB->Query("SELECT Id, document_alias, document_breadcrum_title, document_title, document_parent FROM " . PREFIX . "_documents WHERE Id = '".$current->document_parent."'",-1,'doc_'.$current->document_parent);
			$row_doc = $sql_doc->fetchrow();
			$current->document_parent = $row_doc->document_parent;

			if ($row_doc->document_parent == $row_doc->Id) {
				echo "Ошибка! Вы указали в качестве родительского документа текущий документ.<br>";
				$current->document_parent = 1;
			}

			$crumb['document_breadcrum_title'][$i] = (empty($row_doc->document_breadcrum_title) ? $row_doc->document_title : $row_doc->document_breadcrum_title);
			$crumb['document_alias'][$i] = $row_doc->document_alias;
			$crumb['Id'][$i] = $row_doc->Id;
			$i++;
		 }

		$length = count($crumb['document_breadcrum_title']);
		$crumb['document_breadcrum_title'] = array_reverse($crumb['document_breadcrum_title']);
		$crumb['document_alias'] = array_reverse($crumb['document_alias']);
		$crumb['Id'] = array_reverse($crumb['Id']);
		
		for ($n=0; $n < $length; $n++) {
			$url = rewrite_link('index.php?id=' . $crumb['Id'][$n] . '&amp;doc=' . (empty($crumb['document_alias'][$n]) ? prepare_url($crumb['document_breadcrum_title'][$n]) : $crumb['document_alias'][$n]));
			$bread_crumb.= "<a href=\"".$url."\"  target=\"_self\">".$crumb['document_breadcrum_title'][$n]."</a>&nbsp;&rarr;&nbsp;";
		}
	}

	$bread_crumb.= "<span>".$current->document_breadcrum_title."</span>";

	 if (!$noprint)  return $bread_crumb;
}

/**
 * Ссылка на страницу версии для печати
 *
 * @return string ссылка
 */
function get_print_link()
{
	/*
	$link = get_redirect_link('print');
	$link .= (strpos($link, '?')===false ? '?print=1' : '&amp;print=1');
	*/
	/* Временное решение */
	$link = ABS_PATH."index.php?id=".get_current_document_id()."&print=1";

	return $link;
}

function get_referer_link()
{
	static $link = null;

	if ($link === null)
	{
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$link = parse_url($_SERVER['HTTP_REFERER']);
			$link = (trim($link['host']) == $_SERVER['SERVER_NAME']);
		}
		$link = ($link === true ? $_SERVER['HTTP_REFERER'] : get_home_link());
	}

	return $link;
}

function truncate_text($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
{
	if ($length == 0) return '';

	if (strlen($string) > $length)
	{
		$length -= min($length, strlen($etc));
		if (!$break_words && !$middle)
		{
			$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
		}

		if (!$middle)
		{
			return substr($string, 0, $length) . $etc;
		}
		else
		{
			return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
		}
	}
	else
	{
		return $string;
	}
}

/**
 * Swap named HTML entities with numeric entities.
 *
 * @see http://www.lazycat.org/software/html_entity_decode_full.phps
 */
function convert_entity($matches, $destroy = true)
{
	$table = array(
		'Aacute'   => '&#193;',  'aacute'   => '&#225;',  'Acirc'    => '&#194;',  'acirc'    => '&#226;',  'acute'    => '&#180;',
		'AElig'    => '&#198;',  'aelig'    => '&#230;',  'Agrave'   => '&#192;',  'agrave'   => '&#224;',  'alefsym'  => '&#8501;',
		'Alpha'    => '&#913;',  'alpha'    => '&#945;',  'amp'      => '&#38;',   'and'      => '&#8743;', 'ang'      => '&#8736;',
		'Aring'    => '&#197;',  'aring'    => '&#229;',  'asymp'    => '&#8776;', 'Atilde'   => '&#195;',  'atilde'   => '&#227;',
		'Auml'     => '&#196;',  'auml'     => '&#228;',  'bdquo'    => '&#8222;', 'Beta'     => '&#914;',  'beta'     => '&#946;',
		'brvbar'   => '&#166;',  'bull'     => '&#8226;', 'cap'      => '&#8745;', 'Ccedil'   => '&#199;',  'ccedil'   => '&#231;',
		'cedil'    => '&#184;',  'cent'     => '&#162;',  'Chi'      => '&#935;',  'chi'      => '&#967;',  'circ'     => '&#710;',
		'clubs'    => '&#9827;', 'cong'     => '&#8773;', 'copy'     => '&#169;',  'crarr'    => '&#8629;', 'cup'      => '&#8746;',
		'curren'   => '&#164;',  'dagger'   => '&#8224;', 'Dagger'   => '&#8225;', 'darr'     => '&#8595;', 'dArr'     => '&#8659;',
		'deg'      => '&#176;',  'Delta'    => '&#916;',  'delta'    => '&#948;',  'diams'    => '&#9830;', 'divide'   => '&#247;',
		'Eacute'   => '&#201;',  'eacute'   => '&#233;',  'Ecirc'    => '&#202;',  'ecirc'    => '&#234;',  'Egrave'   => '&#200;',
		'egrave'   => '&#232;',  'empty'    => '&#8709;', 'emsp'     => '&#8195;', 'ensp'     => '&#8194;', 'Epsilon'  => '&#917;',
		'epsilon'  => '&#949;',  'equiv'    => '&#8801;', 'Eta'      => '&#919;',  'eta'      => '&#951;',  'ETH'      => '&#208;',
		'eth'      => '&#240;',  'Euml'     => '&#203;',  'euml'     => '&#235;',  'euro'     => '&#8364;', 'exist'    => '&#8707;',
		'fnof'     => '&#402;',  'forall'   => '&#8704;', 'frac12'   => '&#189;',  'frac14'   => '&#188;',  'frac34'   => '&#190;',
		'frasl'    => '&#8260;', 'Gamma'    => '&#915;',  'gamma'    => '&#947;',  'ge'       => '&#8805;', 'gt'       => '&#62;',
		'harr'     => '&#8596;', 'hArr'     => '&#8660;', 'hearts'   => '&#9829;', 'hellip'   => '&#8230;', 'Iacute'   => '&#205;',
		'iacute'   => '&#237;',  'Icirc'    => '&#206;',  'icirc'    => '&#238;',  'iexcl'    => '&#161;',  'Igrave'   => '&#204;',
		'igrave'   => '&#236;',  'image'    => '&#8465;', 'infin'    => '&#8734;', 'int'      => '&#8747;', 'Iota'     => '&#921;',
		'iota'     => '&#953;',  'iquest'   => '&#191;',  'isin'     => '&#8712;', 'Iuml'     => '&#207;',  'iuml'     => '&#239;',
		'Kappa'    => '&#922;',  'kappa'    => '&#954;',  'Lambda'   => '&#923;',  'lambda'   => '&#955;',  'lang'     => '&#9001;',
		'laquo'    => '&#171;',  'larr'     => '&#8592;', 'lArr'     => '&#8656;', 'lceil'    => '&#8968;', 'ldquo'    => '&#8220;',
		'le'       => '&#8804;', 'lfloor'   => '&#8970;', 'lowast'   => '&#8727;', 'loz'      => '&#9674;', 'lrm'      => '&#8206;',
		'lsaquo'   => '&#8249;', 'lsquo'    => '&#8216;', 'lt'       => '&#60;',   'macr'     => '&#175;',  'mdash'    => '&#8212;',
		'micro'    => '&#181;',  'middot'   => '&#183;',  'minus'    => '&#8722;', 'Mu'       => '&#924;',  'mu'       => '&#956;',
		'nabla'    => '&#8711;', 'nbsp'     => '&#160;',  'ndash'    => '&#8211;', 'ne'       => '&#8800;', 'ni'       => '&#8715;',
		'not'      => '&#172;',  'notin'    => '&#8713;', 'nsub'     => '&#8836;', 'Ntilde'   => '&#209;',  'ntilde'   => '&#241;',
		'Nu'       => '&#925;',  'nu'       => '&#957;',  'Oacute'   => '&#211;',  'oacute'   => '&#243;',  'Ocirc'    => '&#212;',
		'ocirc'    => '&#244;',  'OElig'    => '&#338;',  'oelig'    => '&#339;',  'Ograve'   => '&#210;',  'ograve'   => '&#242;',
		'oline'    => '&#8254;', 'Omega'    => '&#937;',  'omega'    => '&#969;',  'Omicron'  => '&#927;',  'omicron'  => '&#959;',
		'oplus'    => '&#8853;', 'or'       => '&#8744;', 'ordf'     => '&#170;',  'ordm'     => '&#186;',  'Oslash'   => '&#216;',
		'oslash'   => '&#248;',  'Otilde'   => '&#213;',  'otilde'   => '&#245;',  'otimes'   => '&#8855;', 'Ouml'     => '&#214;',
		'ouml'     => '&#246;',  'para'     => '&#182;',  'part'     => '&#8706;', 'permil'   => '&#8240;', 'perp'     => '&#8869;',
		'Phi'      => '&#934;',  'phi'      => '&#966;',  'Pi'       => '&#928;',  'pi'       => '&#960;',  'piv'      => '&#982;',
		'plusmn'   => '&#177;',  'pound'    => '&#163;',  'prime'    => '&#8242;', 'Prime'    => '&#8243;', 'prod'     => '&#8719;',
		'prop'     => '&#8733;', 'Psi'      => '&#936;',  'psi'      => '&#968;',  'quot'     => '&#34;',   'radic'    => '&#8730;',
		'rang'     => '&#9002;', 'raquo'    => '&#187;',  'rarr'     => '&#8594;', 'rArr'     => '&#8658;', 'rceil'    => '&#8969;',
		'rdquo'    => '&#8221;', 'real'     => '&#8476;', 'reg'      => '&#174;',  'rfloor'   => '&#8971;', 'Rho'      => '&#929;',
		'rho'      => '&#961;',  'rlm'      => '&#8207;', 'rsaquo'   => '&#8250;', 'rsquo'    => '&#8217;', 'sbquo'    => '&#8218;',
		'Scaron'   => '&#352;',  'scaron'   => '&#353;',  'sdot'     => '&#8901;', 'sect'     => '&#167;',  'shy'      => '&#173;',
		'Sigma'    => '&#931;',  'sigma'    => '&#963;',  'sigmaf'   => '&#962;',  'sim'      => '&#8764;', 'spades'   => '&#9824;',
		'sub'      => '&#8834;', 'sube'     => '&#8838;', 'sum'      => '&#8721;', 'sup'      => '&#8835;', 'sup1'     => '&#185;',
		'sup2'     => '&#178;',  'sup3'     => '&#179;',  'supe'     => '&#8839;', 'szlig'    => '&#223;',  'Tau'      => '&#932;',
		'tau'      => '&#964;',  'there4'   => '&#8756;', 'Theta'    => '&#920;',  'theta'    => '&#952;',  'thetasym' => '&#977;',
		'thinsp'   => '&#8201;', 'THORN'    => '&#222;',  'thorn'    => '&#254;',  'tilde'    => '&#732;',  'times'    => '&#215;',
		'trade'    => '&#8482;', 'Uacute'   => '&#218;',  'uacute'   => '&#250;',  'uarr'     => '&#8593;', 'uArr'     => '&#8657;',
		'Ucirc'    => '&#219;',  'ucirc'    => '&#251;',  'Ugrave'   => '&#217;',  'ugrave'   => '&#249;',  'uml'      => '&#168;',
		'upsih'    => '&#978;',  'Upsilon'  => '&#933;',  'upsilon'  => '&#965;',  'Uuml'     => '&#220;',  'uuml'     => '&#252;',
		'weierp'   => '&#8472;', 'Xi'       => '&#926;',  'xi'       => '&#958;',  'Yacute'   => '&#221;',  'yacute'   => '&#253;',
		'yen'      => '&#165;',  'Yuml'     => '&#376;',  'yuml'     => '&#255;',  'Zeta'     => '&#918;',  'zeta'     => '&#950;',
		'zwj'      => '&#8205;', 'zwnj'     => '&#8204;'
	);

	if (isset($table[$matches[1]])) return $table[$matches[1]];
	else							return $destroy ? '' : $matches[0];
}

/**
 * Замена некоторых символов на их сущности
 * замена и исправление HTML-тегов
 *
 * @param unknown_type $s
 * @return unknown
 */
function pretty_chars($s)
{
	return preg_replace(array("'©'"   , "'®'"  , "'<b>'i"  , "'</b>'i"  , "'<i>'i", "'</i>'i", "'<br>'i", "'<br/>'i"),
						array('&copy;', '&reg;', '<strong>', '</strong>', '<em>'  , '</em>'  , '<br />' , '<br />'), $s);
}

/**
 * Транслитерация
 *
 * @param string $st строка для транслитерации
 * @return string
 */
function translit_string($st)
{
//	$st = htmlspecialchars_decode($st);
//
//	// Convert all named HTML entities to numeric entities
//	$st = preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]{1,7});/', 'convert_entity', $st);
//
//	// Convert all numeric entities to their actual character
//	$st = preg_replace('/&#x([0-9a-f]{1,7});/ei', 'chr(hexdec("\\1"))', $st);
//	$st = preg_replace('/&#([0-9]{1,7});/e', 'chr("\\1")', $st);
//

	$table=Array(
	            'А' => 'A',
                'Б' => 'B',
                'В' => 'V',
                'Г' => 'G',
                'Д' => 'D',
                'Е' => 'E',
                'Ё' => 'YO',
                'Ж' => 'ZH',
                'З' => 'Z',
                'И' => 'I',
                'Й' => 'J',
                'К' => 'K',
                'Л' => 'L',
                'М' => 'M',
                'Н' => 'N',
                'О' => 'O',
                'П' => 'P',
                'Р' => 'R',
                'С' => 'S',
                'Т' => 'T',
                'У' => 'U',
                'Ф' => 'F',
                'Х' => 'H',
                'Ц' => 'C',
                'Ч' => 'CH',
                'Ш' => 'SH',
                'Щ' => 'CSH',
                'Ь' => '',
                'Ы' => 'Y',
                'Ъ' => '',
                'Э' => 'E',
                'Ю' => 'YU',
                'Я' => 'YA',

                'а' => 'a',
                'б' => 'b',
                'в' => 'v',
                'г' => 'g',
                'д' => 'd',
                'е' => 'e',
                'ё' => 'yo',
                'ж' => 'zh',
                'з' => 'z',
                'и' => 'i',
                'й' => 'j',
                'к' => 'k',
                'л' => 'l',
                'м' => 'm',
                'н' => 'n',
                'о' => 'o',
                'п' => 'p',
                'р' => 'r',
                'с' => 's',
                'т' => 't',
                'у' => 'u',
                'ф' => 'f',
                'х' => 'h',
                'ц' => 'c',
                'ч' => 'ch',
                'ш' => 'sh',
                'щ' => 'csh',
                'ь' => '',
                'ы' => 'y',
                'ъ' => '',
                'э' => 'e',
                'ю' => 'yu',
                'я' => 'ya',
// українська мова:				
                'і' => 'ya',				
                'І' => 'ya',				
                'ї' => 'ya',				
                'Ї' => 'ya',				
                'є' => 'ya',
                'Є' => 'ya',

// polski język			
                'Ą' => 'ya',				
                'ą' => 'ya',				
                'Ć' => 'ya',				
                'ć' => 'ya',
                'Ę' => 'ya',				
                'ę' => 'ya',				
                'Ł' => 'ya',				
                'ł' => 'ya',
                'Ń' => 'ya',				
                'ń' => 'ya',				
                'Ó' => 'ya',				
                'ó' => 'ya',
                'Ś' => 'ya',				
                'ś' => 'ya',				
                'Ź' => 'ya',				
                'ź' => 'ya',
                'Ż' => 'ya',				
                'ż' => 'ya',	

);
    $st = str_replace(array_keys($table),  array_values($table), $st); 
	
	$st = strtr($st, array('ье'=>'ye', 'ъе'=>'ye', 'ьи'=>'yi',  'ъи'=>'yi',
							'ъо'=>'yo', 'ьо'=>'yo', 'ё'=>'yo',   'ю'=>'yu',
							'я'=>'ya',  'ж'=>'zh',  'х'=>'kh',   'ц'=>'ts',
							'ч'=>'ch',  'ш'=>'sh',  'щ'=>'shch', 'ъ'=>'',
							'ь'=>'',    'ї'=>'yi',  'є'=>'ye')
	);
	$st = strtr($st,'абвгдезийклмнопрстуфыэі',
					'abvgdeziyklmnoprstufyei');

	return trim($st, '-');
}

/**
 * Подготовка текста через API Яндекса (перевод с русского на английский)
 *
 * @param string $st
 * @return string
 */
function y_translate($text) {
	include_once BASE_DIR.'/lib/translate/Yandex_Translate.php';
	$translator = new Yandex_Translate();
	$translatedText = $translator->yandexTranslate('ru', 'en', $text);
	$translatedText = strtolower($translatedText);
	$translatedText = preg_replace(
		array('/^[\/-]+|[\/-]+$|^[\/_]+|[\/_]+$|[^\.a-zа-яеёA-ZА-ЯЕЁ0-9\/_-]/u', '/--+/', '/-*\/+-*/', '/\/\/+/'),
		array('-',                                                      '-',     '/',         '/'),
		$translatedText
	);
	return $translatedText;
}

/**
 * Переводит кирилицу в нижний регистр
 *
 * @param string $st строка для перевода в нижний регистр
 * @return string
 */
function _strtolower($string)
{
    $small = array('а','б','в','г','д','е','ё','ж','з','и','й',
                   'к','л','м','н','о','п','р','с','т','у','ф',
                   'х','ч','ц','ш','щ','э','ю','я','ы','ъ','ь',
                   'э', 'ю', 'я');
    $large = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й',
                   'К','Л','М','Н','О','П','Р','С','Т','У','Ф',
                   'Х','Ч','Ц','Ш','Щ','Э','Ю','Я','Ы','Ъ','Ь',
                   'Э', 'Ю', 'Я');
    return str_replace($large, $small, $string);  
}

/**
 * Подготовка URL
 *
 * @param string $st
 * @return string
 */
function prepare_url($url)
{
	$new_url = strip_tags($url);

    $table = array(

// спецсимволы
                '«' => '',				
                '»' => '',
                '—' => '',
                '–' => '',
                '“' => '',
                '”' => ''
				
    );
    $new_url = str_replace(array_keys($table),  array_values($table), $new_url);
	if (defined('TRANSLIT_URL') && TRANSLIT_URL) $new_url = translit_string(trim(_strtolower($new_url)));

	$new_url = preg_replace(
		array('/^[\/-]+|[\/-]+$|^[\/_]+|[\/_]+$|[^\.a-zа-яеёA-ZА-ЯЕЁ0-9\/_-]/u', '/--+/', '/-*\/+-*/', '/\/\/+/'),
		array('-',                                                          '-',     '/',         '/'),
		$new_url
	);
	$new_url = trim($new_url, '-');

	if (substr(URL_SUFF, 0, 1) != '/' && substr($url, -1) == '/') $new_url = $new_url . "/";
	
	return mb_strtolower(rtrim($new_url,'.'),'UTF-8');
}

/**
 * Подготовка имени файла или директории
 *
 * @param string $st
 * @return string
 */
function prepare_fname($st)
{
	$st = strip_tags($st);

	$st = strtr($st,'ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЪЫЭЮЯ',
					'abcdefghijklmnopqrstuvwxyzабвгдеёжзийклмнопрстуфхцчшщьъыэюя');
			
	$st = translit_string(trim($st));

	$st = preg_replace(array('/[^a-z0-9_-]/', '/--+/'), '-', $st);

	return trim($st, '-');
}

/**
 * Формирование ЧПУ для документов
 *
 * @param string $s ссылка или текст с ссылками
 * @return string
 */
function rewrite_link($s)
{
	if (!REWRITE_MODE) return $s;

	$doc_regex = '/index.php(?:\?)id=(?:[0-9]+)&(?:amp;)*doc='.(TRANSLIT_URL ? '([\.a-z0-9\/_-]+)' : '([\.a-zа-яёїєі0-9\/_-]+)');
	$page_regex = '&(?:amp;)*(artpage|apage|page)=([{s}0-9]+)';

	$s = preg_replace($doc_regex.$page_regex.$page_regex.$page_regex.'/', ABS_PATH.'$1/$2-$3/$4-$5/$6-$7'.URL_SUFF, $s);
	$s = preg_replace($doc_regex.$page_regex.$page_regex.'/',             ABS_PATH.'$1/$2-$3/$4-$5'.URL_SUFF, $s);
	$s = preg_replace($doc_regex.$page_regex.'/',                         ABS_PATH.'$1/$2-$3'.URL_SUFF, $s);
	$s = preg_replace($doc_regex.'/',                                     ABS_PATH.'$1'.URL_SUFF, $s);
	$s = preg_replace('/'.preg_quote(URL_SUFF, '/').'[?|&](?:amp;)*print=1/', '/print'.URL_SUFF, $s);

	$mod_regex = '/index.php(?:\?)module=(shop|forums|guestbook|roadmap)';

	$s = preg_replace($mod_regex.'&(?:amp;)*page=([{s}]|\d+)/', ABS_PATH.'$1-$2.html', $s);
	$s = preg_replace($mod_regex.'&(?:amp;)*print=1/',          ABS_PATH.'$1-print.html', $s);
	$s = preg_replace($mod_regex.'(?!&)/',                      ABS_PATH.'$1.html', $s);
	return $s;
}

/**
 * Запись события в лог
 *
 * @param string $meldung Текст сообщения
 * @param int $typ тип сообщения
 * @param int $rub номер рубрики
 * @return 
 */
function reportLog($meldung, $typ = 0, $rub = 0)
{
	$logdata=array();
	
	$logfile=BASE_DIR.'/cache/log.php';
	if(file_exists($logfile))
		@eval('?>'.file_get_contents($logfile).'<?');
	$logdata[]=array('log_time' =>time(),'log_ip'=>$_SERVER['REMOTE_ADDR'],'log_url'=>$_SERVER['QUERY_STRING'],'log_text'=>$meldung,'log_type'=>(int)$typ,'log_rubric'=>(int)$rub);
	$messlimit=1000;
	$logdata=array_slice($logdata,-1*$messlimit);
	file_put_contents($logfile,'<?php $logdata='.var_export($logdata,true).' ?>');
}

/**
 * Запись события в лог для 404 ошибок
 *
 * @param string $meldung Текст сообщения
 * @param int $typ тип сообщения
 * @param int $rub номер рубрики
 * @return 
 */
function report404($meldung, $typ = 0, $rub = 0)
{
	$logdata=array();
	
	$logfile=BASE_DIR.'/cache/404.php';
	if(file_exists($logfile))
		@include($logfile);
	$logdata[]=array('log_time' =>time(),'log_ip'=>$_SERVER['REMOTE_ADDR'],'log_url'=>$_SERVER['QUERY_STRING'],'log_text'=>$meldung,'log_type'=>(int)$typ,'log_rubric'=>(int)$rub);
	$messlimit=1000;
	$logdata=array_slice($logdata,-1*$messlimit);
	file_put_contents($logfile,'<?php $logdata='.var_export($logdata,true).' ?>');
}

/**
 * Возвращаем истинное значение поля для документа
 *
 * @param int $id id документа
 * @param string $field id поля или его алиас
 * @return 
 */
function get_document_field($document_id,$field)
{
	$document_fields=get_document_fields($document_id);
	if (!is_array($document_fields[$field]))$field=intval($document_fields[$field]);
	if (empty($document_fields[$field])) return false;

	$field_value = $document_fields[$field]['field_value'];
	return $field_value;
}

/**
 * Функция возвращает массив со значениями полей
 *
 * @param int $id id документа
 * @param array $values если надо вернуть документ с произвольными значениями - используется для ревизий документов
 * @return array
 */
function get_document_fields($document_id,$values=null)
{
	global $AVE_DB, $request_documents;

	static $document_fields = array();

	if (!is_numeric($document_id)) return false;

	if (!isset ($document_fields[$document_id]))
	{
		$document_fields[$document_id] = false;
		$where = "WHERE doc_field.document_id = '" . $document_id . "'";

		$sql = $AVE_DB->Query("
			SELECT
				doc_field.Id,
				document_id,
				rubric_field_id,
				rubric_field_alias,
				rubric_field_type,
				field_value,
				document_author_id,
				rubric_field_template,
				rubric_field_template_request
			FROM
				" . PREFIX . "_document_fields AS doc_field
			JOIN
				" . PREFIX . "_rubric_fields AS rub_field
					ON doc_field.rubric_field_id = rub_field.Id
			JOIN
				" . PREFIX . "_documents AS doc
					ON doc.Id = doc_field.document_id
			" . $where
		,-1,'doc_'.$document_id);
		//Вдруг памяти мало!!!!
		if(memory_panic()&&(count($document_fields)>3))
			{
				$document_fields=array();
			}	
		while ($row = $sql->FetchAssocArray())
		{
			$row['tpl_req_empty'] = (trim($row['rubric_field_template_request']) == '');
			$row['tpl_field_empty'] = (trim($row['rubric_field_template']) == '');

			if($values){
				$row['field_value']=(isset($values[$row['rubric_field_id']]) ? $values[$row['rubric_field_id']] : $row['field_value']);
			}

			if ($row['field_value'] === '')
			{
				$row['rubric_field_template_request'] = preg_replace('/\[tag:if_notempty](.*?)\[\/tag:if_notempty]/si', '', $row['rubric_field_template_request']);
				$row['rubric_field_template_request'] = trim(str_replace(array('[tag:if_empty]','[/tag:if_empty]'), '', $row['rubric_field_template_request']));

				$row['rubric_field_template'] = preg_replace('/\[tag:if_notempty](.*?)\[\/tag:if_notempty]/si', '', $row['rubric_field_template']);
				$row['rubric_field_template'] = trim(str_replace(array('[tag:if_empty]','[/tag:if_empty]'), '', $row['rubric_field_template']));
			}
			else
			{
				$row['rubric_field_template_request'] = preg_replace('/\[tag:if_empty](.*?)\[\/tag:if_empty]/si', '', $row['rubric_field_template_request']);
				$row['rubric_field_template_request'] = trim(str_replace(array('[tag:if_notempty]','[/tag:if_notempty]'), '', $row['rubric_field_template_request']));

				$row['rubric_field_template'] = preg_replace('/\[tag:if_empty](.*?)\[\/tag:if_empty]/si', '', $row['rubric_field_template']);
				$row['rubric_field_template'] = trim(str_replace(array('[tag:if_notempty]','[/tag:if_notempty]'), '', $row['rubric_field_template']));
			}
		
			$document_fields[$row['document_id']][$row['rubric_field_id']] = $row;
			$document_fields[$row['document_id']][$row['rubric_field_alias']] = $row['rubric_field_id'];
		}
	}
	return $document_fields[$document_id];
}

function ucfirst_utf8($str){
        $string = mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
        return $string;
} 
 
/**
 * Формирование строки имени пользователя
 * При наличии всех параметров пытается сформировать строку <b>Имя Фамилия</b>
 * Если задать $short=1 - формирует короткую форму <b>И. Фамилия</b>
 * Когда отсутствует информация о Имени или Фамилии пытается сформировать
 * строку на основе имеющихся данных, а если данных нет вообще - выводит
 * имя анонимного пользователя которое задается в основных настройках системы.
 *
 * @todo добавить параметр 'anonymous' в настройки
 *
 * @param string $login логин пользователя
 * @param string $first_name имя пользователя
 * @param string $last_name фамилия пользователя
 * @param int $short {0|1} признак формирования короткой формы
 * @return string
 */
function get_username($login = '', $first_name = '', $last_name = '', $short = 1)
{
	if ($first_name != '' && $last_name != '')
	{
		if ($short == 1) $first_name = mb_substr($first_name, 0, 1) . '.';
		return ucfirst_utf8(mb_strtolower($first_name)) . ' ' . ucfirst_utf8(mb_strtolower($last_name));
		return ucfirst_utf8(mb_strtolower($login));
	}
	elseif ($first_name != '' && $last_name == '')
	{
		return ucfirst_utf8(mb_strtolower($first_name));
	}
	elseif ($first_name == '' && $last_name != '')
	{
		return ucfirst_utf8(mb_strtolower($last_name));
	}
	elseif ($login != '')
	{
		return ucfirst_utf8(mb_strtolower($login));
	}

//	return get_settings('anonymous');
	return 'Anonymous';
}

/**
 * Возвращает запись для пользователя по идентификатору
 * не делает лишних запросов
 *
 * @param int $id - идентификатор пользователя
 * @return object
 */

function get_user_rec_by_id($id){
	global $AVE_DB;

	static $users = array();

	if (!isset($users[$id]))
	{
		$row = $AVE_DB->Query("
			SELECT
				*
			FROM " . PREFIX . "_users
			WHERE Id = '" . (int)$id . "'
		")->FetchRow();

		$users[$id] = $row;
	}
	return $users[$id];

}

/**
 * Возвращает параметры группы пользователей по идентификатору
 * не делает лишних запросов
 *
 * @param int $id - идентификатор группы
 * @return object
 */

function get_usergroup_rec_by_id($id){
	global $AVE_DB;

	static $usergroups = array();

	if (!isset($usergroups[$id]))
	{
		$row = $AVE_DB->Query("
			SELECT
				*
			FROM " . PREFIX . "_user_groups
			WHERE user_group = '" . (int)$id . "'
		")->FetchRow();

		$usergroups[$id] = $row;
	}
	return $usergroups[$id];

}

/**
 * Возвращает login пользователя по его идентификатору
 *
 * @param int $id - идентификатор пользователя
 * @return string
 */
function get_userlogin_by_id($id)
{
	$rec=get_user_rec_by_id($id);
	
	return $rec->user_name;
}


/**
 * Возвращает имя группы пользователя по его идентификатору
 *
 * @param int $id - идентификатор группы пользователя
 * @return string
 */
function get_usergroup_by_id($id)
{
	$rec=get_usergroup_rec_by_id($id);
	
	return $rec->user_group_name;
}

/**
 * Возвращает email пользователя по его идентификатору
 *
 * @param int $id - идентификатор пользователя
 * @return string
 */
function get_useremail_by_id($id)
{
	$rec=get_user_rec_by_id($id);
	
	return $rec->email;
}


/**
 * Возвращает имя пользователя по его идентификатору
 *
 * @param int $id - идентификатор пользователя
 * @return string
 */
function get_username_by_id($id)
{
	$row=get_user_rec_by_id($id);
	$row = !empty($row) ? get_username($row->user_name, $row->firstname, $row->lastname, 1) : get_username();
	return $row;
}

/**
 * Исправление форматирования даты
 * Функцию можно использовать в шаблонах Smarty как модификатор
 *
 * @param string $string - дата отформатированная в соответствии с текущей локалью
 * @param string $language - язык
 * @return string
 */
function pretty_date($string, $language = '')
{
	// пытаемся решить проблему для кодировки дат на лок. серверах
	if (!mb_check_encoding($string,'UTF-8'))
	{
		$string = iconv('Windows-1251', 'UTF-8', $string);
	}

	if ($language == '')
	{
		$language = (defined('ACP') && ACP) ? $_SESSION['admin_language'] : $_SESSION['user_language'];
	}

	$language = strtolower($language);

	switch ($language)
	{
		case 'by':
			break;

		case 'de':
			break;

		case 'ru':
			$pretty = array(
				'Январь'     =>'января',      'Февраль'    =>'февраля',     'Март'    =>'марта',
				'Апрель'     =>'апреля',      'Май'        =>'мая',         'Июнь'    =>'июня',
				'Июль'       =>'июля',        'Август'     =>'августа',     'Сентябрь'=>'сентября',
				'Октябрь'    =>'октября',     'Ноябрь'     =>'ноября',      'Декабрь' =>'декабря',

				'воскресенье'=>'Воскресенье', 'понедельник'=>'Понедельник', 'вторник' =>'Вторник',
				'среда'      =>'Среда',       'четверг'    =>'Четверг',     'пятница' =>'Пятница',
				'суббота'    =>'Суббота'
			);
			break;

		case 'ua':
			$pretty = array(
				'Січень' =>'січня',  'Лютий'    =>'лютого',    'Березень'=>'березня',
				'Квітень'=>'квітня', 'Травень'  =>'травня',    'Червень' =>'червня',
				'Липень' =>'липня',  'Серпень'  =>'серпня',    'Вересень'=>'вересня',
				'Жовтень'=>'жовтня', 'Листопад' =>'листопада', 'Грудень' =>'грудня',

				'неділя' =>'Неділя', 'понеділок'=>'Понеділок', 'вівторок'=>'Вівторок',
				'середа' =>'Середа', 'четвер'   =>'Четвер',    "п'ятниця"=>"П'ятниця",
				'субота' =>'Субота'
			);
			break;

		default:
			break;
	}

	return (isset($pretty) ? strtr($string, $pretty) : $string);
}

/**
 * Формирование строки из случайных символов
 *
 * @param int $length количество символов в строке
 * @param string $chars набор символов для формирования строки
 * @return string сформированная строка
 */
function make_random_string($length = 16, $chars = '')
{
	if ($chars == '')
	{
		$chars  = 'abcdefghijklmnopqrstuvwxyz';
		$chars .= 'ABCDEFGHIJKLMNOPRQSTUVWXYZ';
		$chars .= '~!@#$%^&*()-_=+{[;:/?.,]}';
		$chars .= '0123456789';
	}

	$clen = strlen($chars) - 1;

	$string = '';
	while (strlen($string) < $length) $string .= $chars[mt_rand(0, $clen)];

	return $string;
}

function get_statistic($t=0, $m=0, $q=0, $l=0)
{
	global $AVE_DB;

	$s = '';

	if ($t) $s .= "\n<br>Время генерации: " . number_format(microtime_diff(START_MICROTIME, microtime()), 3, ',', ' ') . ' сек.';
	if ($m && function_exists('memory_get_peak_usage')) $s .= "\n<br>Пиковое значение " . number_format(memory_get_peak_usage()/1024, 0, ',', ' ') . 'Kb';
//	if ($q) $s .= "\n<br>Количество запросов: " . $AVE_DB->DBStatisticGet('count') . ' шт. за ' . number_format($AVE_DB->DBStatisticGet('time')*1000, 3, ',', '.') . ' мксек.';
//	if ($l) $s .= "\n<br><div style=\"text-align:left;padding-left:30px\"><small><ol>" . $AVE_DB->DBStatisticGet('list') . '</ol></small></div>';
	if ($q && !defined('SQL_PROFILING_DISABLE')) $s .= "\n<br>Количество запросов: " . $AVE_DB->DBProfilesGet('count') . ' шт. за ' . $AVE_DB->DBProfilesGet('time') . ' сек.';
	if ($l && !defined('SQL_PROFILING_DISABLE')) $s .= $AVE_DB->DBProfilesGet('list');

	return $s;
}

function add_template_comment($tpl_source, &$smarty)
{
    return "\n\n<!-- BEGIN SMARTY TEMPLATE " . $smarty->_current_file . " -->\n".$tpl_source."\n<!-- END SMARTY TEMPLATE " . $smarty->_current_file . " -->\n\n";
}

/**
 * Получения списка стран
 *
 * @param int $status статус стран входящих в список
 * <ul>
 * <li>1 - активные страны</li>
 * <li>0 - неактивные страны</li>
 * </ul>
 * если не указано возвращает список стран без учета статуса
 * @return array
 */
function get_country_list($status = '')
{
	global $AVE_DB;

	$countries = array();
	$sql = $AVE_DB->Query("
		SELECT
			country_code,
			country_name,
			country_status
		FROM " . PREFIX . "_countries
		" . (($status != '') ? "WHERE country_status = '" . $status . "'" : '') . "
		ORDER BY country_name ASC
	");
	while ($row = $sql->FetchRow()) array_push($countries, $row);

	return $countries;
}

/**
 * Получение списка изображений из заданной папки
 * @param $path путь до директории с изображениями
 * @return array
 */
function image_multi_import($path) {
	$images_ext =  array('jpg', 'jpeg', 'png', 'gif');
	$dir = BASE_DIR."/".$path;

	if($handle = opendir($dir))
	{
		while (false !== ($file = readdir($handle)))
		{
			$nameParts = explode('.', $file);
			$ext = strtolower(end($nameParts));

			if ($file != "." && $file != ".." && $ext == "png" || $ext == "jpg" || $ext == "gif")
			{
			  if(!is_dir($dir."/".$file))
				$files[] = $file;
			}
		}
		closedir($handle);
	}
	return $files;
}

/**
 * Отправка e-Mail
 *
 * @param string $to - email получателя
 * @param string $body - текст сообщения
 * @param string $subject - тема сообщения
 * @param string $from_email - e-mail отправителя
 * @param string $from_name - имя отправителя
 * @param string $type - содержимое (html или text)
 * @param array $attach - пути файлов вложений
 * @param bool $saveattach - сохранять вложения после отправки в ATTACH_DIR?
 * @param bool $signature - добавлять подпись из общих настроек?
 */
function send_mail($to,$body,$subject,$from_email,$from_name='',$type='text',$attach=array(),$saveattach=true,$signature=true)
{
	ob_start();
	require_once BASE_DIR . '/lib/SwiftMailer/swift_required.php';
	unset($transport,$message,$mailer);

	// Определяем тип письма
	$type = ((strtolower($type) == 'html' || strtolower($type) == 'text/html') ? 'text/html' : 'text/plain');
	// Добавляем подпись, если просили
	if ($signature)
	{
		if ($type == 'text/html')
		{
			$signature = '<br><br>' . nl2br(get_settings('mail_signature'));
		}
		else
		{
			$signature = "\r\n\r\n" . get_settings('mail_signature');
		}
	}
	else $signature = '';
	// Составляем тело письма
	$body = stripslashes($body) . $signature;
	// Формируем письмо
	$message = Swift_Message::newInstance($subject)
		-> setFrom(array($from_email => $from_name))
		-> setTo($to)
		-> setContentType($type)
		-> setBody($body)
		-> setMaxLineLength((int)get_settings('mail_word_wrap'));
	// Прикрепляем вложения
	if ($attach)
	{
		foreach ($attach as $attach_file)
		{
			$message -> attach(Swift_Attachment::fromPath(trim($attach_file)));
		}
	}
	// Выбираем метод отправки и формируем транспорт
	switch (get_settings('mail_type')) 
	{
		case 'mail':
			$transport = Swift_MailTransport::newInstance();
			break;

		case 'smtp':
			$transport = Swift_SmtpTransport::newInstance(stripslashes(get_settings('mail_host')), (int)get_settings('mail_port'));
			// Добавляем шифрование
			$smtp_encrypt = get_settings('mail_smtp_encrypt');
			if($smtp_encrypt)
				$transport
					->setEncryption(strtolower(stripslashes($smtp_encrypt)));
			// Имя пользователя/пароль
			$smtp_user = get_settings('mail_smtp_login');
			$smtp_pass = get_settings('mail_smtp_pass');
			if($smtp_user)
				$transport
					->setUsername(stripslashes($smtp_user))
					->setPassword(stripslashes($smtp_pass));
			break;

		case 'sendmail':
			$transport = Swift_SendmailTransport::newInstance(get_settings('mail_sendmail_path'));
			break;
	}
	// Отправляем письмо
	$mailer = Swift_Mailer::newInstance($transport);
	$mailer->send($message);

	// Сохраняем вложения в ATTACH_DIR, если просили 
	if ($attach && $saveattach)
	{
		$attach_dir = BASE_DIR . '/' . ATTACH_DIR . '/';
		foreach ($attach as $file_path)
		{
			if ($file_path && file_exists($file_path))
			{
				$file_name = basename($file_path);
				$file_name = str_replace(' ','',mb_strtolower(trim($file_name)));
				if (file_exists($attach_dir . $file_name))
				{
					$file_name = rand(1000, 9999) . '_' . $file_name;
				}
				$file_path_new = $attach_dir . $file_name;
				if (!@move_uploaded_file($file_path,$file_path_new))
				{
					copy($file_path,$file_path_new);
				}
			}
		}
	}
	ob_end_clean();
}

/**
 * Replace array_combine()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.array_combine
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.23 $
 * @since       PHP 5
 * @require     PHP 4.0.0 (user_error)
 */
function php_compat_array_combine($keys, $values)
{
    if (!is_array($keys)) {
        user_error('array_combine() expects parameter 1 to be array, ' .
            gettype($keys) . ' given', E_USER_WARNING);
        return;
    }

    if (!is_array($values)) {
        user_error('array_combine() expects parameter 2 to be array, ' .
            gettype($values) . ' given', E_USER_WARNING);
        return;
    }

    $key_count = count($keys);
    $value_count = count($values);
    if ($key_count !== $value_count) {
        user_error('array_combine() Both parameters should have equal number of elements', E_USER_WARNING);
        return false;
    }

    if ($key_count === 0 || $value_count === 0) {
        user_error('array_combine() Both parameters should have number of elements at least 0', E_USER_WARNING);
        return false;
    }

    $keys    = array_values($keys);
    $values  = array_values($values);

    $combined = array();
    for ($i = 0; $i < $key_count; $i++) {
        $combined[$keys[$i]] = $values[$i];
    }

    return $combined;
}
// Define
if (!function_exists('array_combine')) {
    function array_combine($keys, $values)
    {
        return php_compat_array_combine($keys, $values);
    }
}

/**
 * Replace PHP_EOL constant
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/reserved.constants.core
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.3 $
 * @since       PHP 5.0.2
 */
if (!defined('PHP_EOL')) {
    switch (strtoupper(substr(PHP_OS, 0, 3))) {
        // Windows
        case 'WIN':
            define('PHP_EOL', "\r\n");
            break;

        // Mac
        case 'DAR':
            define('PHP_EOL', "\r");
            break;

        // Unix
        default:
            define('PHP_EOL', "\n");
    }
}

/**
 * Формирование ссылки на миниатюру определённого размера,
 * если размер не указан формируется миниатюра шириной 120px
 *
 * @param array $params	параметры
 * <ul>
 * <li>link	путь к оригиналу</li>
 * <li>size	размер миниатюры</li>
 * </ul>
 * @return string
 */
function make_thumbnail($params)
{
	if (empty($params['link'])) return;

	if (isset($params['size']))
	{
		$size = $params['size'];

		if (!preg_match('/^[r|c|f|t]\d+x\d+r*$/', $size)) return;
	}
	else
	{
		$size = 't128x128';
	}

	$nameParts = explode('.', basename($params['link']));
	$countParts = count($nameParts);
	if ($countParts < 2) return;
	$nameParts[$countParts-2] .= '-' . $size;

	return dirname($params['link']) . '/' . THUMBNAIL_DIR . '/' . implode('.', $nameParts);
}

function callback_make_thumbnail($params)
{
	return ((is_array($params) && isset($params[2])) ? make_thumbnail(array('size' => $params[1], 'link' => $params[2])) : '');
}

/**
 * Функция preg_replace для кириллицы
 * если заменять русские символы в строке UTF-8 при помощи preg_replace, то появляются вопросы
 *
 * @param mixed $pattern     шаблон заменяемой части строки
 * @param mixed $replacement на что заменяем
 * @param mixed $string      входящая строка
 * @param int   $limit       максимум вхождений
 * mixed preg_replace_ru ( mixed pattern, mixed replacement, mixed subject [, int limit] )
 *
 * @return mixed
 */
function preg_replace_ru ($pattern="", $replacement="", $string="", $limit=-1)
{
	$string = iconv('UTF-8', 'cp1251', $string);
	$string = preg_replace($pattern, $replacement, $string, $limit);
	return iconv('cp1251', 'UTF-8', $string);
}

/**
 * Функция для вывода переменной (для отладки)
 *
 * @param mixed $var любая переменная
 * @param bool $echo true - выведет на экран, false - запишет в корень в файл debug.html
 */
function _var($var,$echo=false)
{
	ob_start();
	var_dump ($var);
	$var_dump = htmlspecialchars(ob_get_contents());
	ob_end_clean();
	if (!$echo)
	{
		$var_dump = 
'<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Отладка</title>
</head>
<body>
  <pre style="background:#eee;color:#000;margin:10px;padding:10px;min-width:600px">' . $var_dump . '</pre>
</body>
</html>';
		file_put_contents(BASE_DIR.'/debug.html',$var_dump);
	}
	else
	{
		echo '<pre style="background:#eee;color:#000;margin:10px;padding:10px;min-width:600px">' . $var_dump . '</pre>';
	}
}

/**
 * Функция записывает в указанную папку .htaccess с содержанием "Deny from all"
 *
 */
function write_htaccess_deny($dir)
{
	$file = $dir . '/.htaccess';
	if(!file_exists($file))
	{
		if(!is_dir($dir)) @mkdir($dir);
		@file_put_contents($dir . '/.htaccess','Deny from all');
	}
}

/**
 * Функция которая паникует если приблизились к memory_limit
 *
 * @return bool превышение лимита использования памяти
 */
 function memory_panic(){
	if(defined('MEMORY_LIMIT_PANIC')&&MEMORY_LIMIT_PANIC!=-1){
		$use_mem=memory_get_usage();
		$lim=MEMORY_LIMIT_PANIC*1024*1024;
		return ($use_mem>$lim ? true : false);
	}	
	else 
	return false;
}

/**
 * Функция возвращает каноническое имя страницы
 *
 * @param string $url текущий УРЛ
 * @return string
 */
function canonical($url) { 
	$link = preg_replace('/^(.+?)(\?.*?)?(#.*)?$/', '$1$3', $url);
return $link;
}

function findautor($string, $limit)
{
	global $AVE_DB;

	$search = "
		AND (UPPER(email) LIKE UPPER('%" . $string . "%')
		OR UPPER(email) = UPPER('" . $string . "')
		OR Id = '" . intval($string) . "'
		OR UPPER(user_name) LIKE UPPER('" . $string . "%')
		OR UPPER(firstname) LIKE UPPER('" . $string . "%')
		OR UPPER(lastname) LIKE UPPER('" . $string . "%'))
	";

	$sql = $AVE_DB->Query("
		SELECT *
		FROM " . PREFIX . "_users
		WHERE 1"
		. $search
	);

	$users = array();
	while ($row = $sql->FetchRow())
	{
		$ava=getAvatar($row->Id,40);
		$users[]=array(
			'userid'=>$row->Id,
			'login'=>$row->user_name,
			'email'=>$row->email,
			'lastname'=>$row->lastname,
			'firstname'=>$row->firstname,
			'avatar'=>($ava ? $ava : ABS_PATH.'admin/templates/'.DEFAULT_ADMIN_THEME_FOLDER.'/images/user.png')
		);
	}
	echo json_encode($users);
}

function searchKeywords($string)
{
	global $AVE_DB;

	$search = "
		AND (UPPER(keyword) LIKE UPPER('" . $string . "%'))
	";

	$sql = $AVE_DB->Query("
		SELECT *
		FROM " . PREFIX . "_document_keywords
		WHERE 1"
		. $search
	);

	while ($row = $sql->FetchRow())
	{
		$keyword = $row->keyword;
		echo "$keyword\n";
	}
}
?>