<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

if (!defined('BASE_DIR')) exit;

/**
 * Удаление глобальных массивов
 *
 */
function unsetGlobals()
{
	if (!ini_get('register_globals')) return;

	$allowed = array('_ENV'=>1, '_GET'=>1, '_POST'=>1, '_COOKIE'=>1, '_FILES'=>1, '_SERVER'=>1, '_REQUEST'=>1, 'GLOBALS'=>1);

	foreach ($GLOBALS as $key => $value)
	{
		if (!isset($allowed[$key])) unset($GLOBALS[$key]);
	}
}
unsetGlobals();

if (isset($HTTP_POST_VARS))
{
	$_GET     = $HTTP_GET_VARS;
	$_POST    = $HTTP_POST_VARS;
	$_REQUEST = array_merge($_POST, $_GET);
	$_COOKIE  = $HTTP_COOKIE_VARS;
}

/**
 * Слешевание (для глобальных массивов)
 * рекурсивно обрабатывает вложенные массивы
 *
 * @param array $array обрабатываемый массив
 * @return array обработанный массив
 */
function add_slashes($array)
{
	reset($array);
	while (list($key, $val) = each($array))
	{
		if (is_string($val))	$array[$key] = addslashes($val);
		elseif (is_array($val))	$array[$key] = add_slashes($val);
	}

	return $array;
}

if (!get_magic_quotes_gpc())
{
	$_GET     = add_slashes($_GET);
	$_POST    = add_slashes($_POST);
	$_REQUEST = array_merge($_POST, $_GET);
	$_COOKIE  = add_slashes($_COOKIE);
}

function is_ssl()
{
	if (isset($_SERVER['HTTPS']))
	{
		if ('on' == strtolower($_SERVER['HTTPS'])) return true;
		if ('1' == $_SERVER['HTTPS']) return true;
	}
	elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT']))
	{
		return true;
	}

	return false;
}

function set_host()
{
	if (isset($_SERVER['HTTP_HOST']))
	{
		// Все символы $_SERVER['HTTP_HOST'] приводим к строчным и проверяем
		// на наличие запрещённых символов в соответствии с RFC 952 и RFC 2181.
		$_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);
		if (!preg_match('/^\[?(?:[a-z0-9-:\]_]+\.?)+$/', $_SERVER['HTTP_HOST']))
		{
			// $_SERVER['HTTP_HOST'] не соответствует спецификациям.
			// Возможно попытка взлома, даём отлуп статусом 400.
			header('HTTP/1.1 400 Bad Request');
			exit;
		}
	}
	else
	{
		$_SERVER['HTTP_HOST'] = '';
	}

	$ssl = is_ssl();
	$shema = ($ssl) ? 'https://' : 'http://';
	$host = str_replace(':' . $_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
	$port = ($_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443' || $ssl) ? '' : ':' . $_SERVER['SERVER_PORT'];
	define('HOST', $shema . $host . $port);

	$abs_path = dirname((!strstr($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) && (@php_sapi_name() == 'cgi')) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
	if (defined('ACP')) $abs_path = dirname($abs_path);
	define('ABS_PATH', rtrim(str_replace("\\", "/", $abs_path), '/') . '/');
}
set_host();

set_include_path(get_include_path() . PATH_SEPARATOR . BASE_DIR . '/lib');

ini_set('arg_separator.output',     '&amp;');
ini_set('session.cache_limiter',    'none');
ini_set('session.cookie_lifetime',  60*60*24*14);
ini_set('session.gc_maxlifetime',   60*24);
ini_set('session.use_cookies',      1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid',    0);
ini_set('url_rewriter.tags',        '');

mb_internal_encoding("UTF-8"); // переключение для нормальной работы с русскими буквами в некоторых функциях
require_once(BASE_DIR . '/inc/config.php');

if (!PHP_DEBUGGING)
{
	error_reporting(E_ERROR);
	ini_set('display_errors',7);
}
else
{
	error_reporting(E_ALL);
	ini_set('display_errors',1);
}

require(BASE_DIR . '/functions/func.common.php');
require(BASE_DIR . '/functions/func.login.php');
require(BASE_DIR . '/functions/func.pagination.php');
//if (!defined('ACP'))
{
	require(BASE_DIR . '/functions/func.navigation.php');
	require(BASE_DIR . '/functions/func.sysblock.php');
	require(BASE_DIR . '/functions/func.parsefields.php');
	require(BASE_DIR . '/functions/func.parserequest.php');
}

/**
 * Создание папок и файлов
 *
 */
foreach(array('cache','session') as $dir)
{
	write_htaccess_deny(BASE_DIR . '/' . $dir);
}
foreach(array('attachments','combine','IDS','module','redactor','smarty','sql','tpl') as $dir)
{
	write_htaccess_deny(BASE_DIR . '/cache/' . $dir);
}
if(!file_exists(BASE_DIR . '/cache/IDS/phpids_log.txt'))
{
	file_put_contents(BASE_DIR . '/cache/IDS/phpids_log.txt','');
}

function set_cookie_domain($cookie_domain = '')
{
	global $cookie_domain;

	if ($cookie_domain == '' && defined('COOKIE_DOMAIN') && COOKIE_DOMAIN != '')
	{
		$cookie_domain = COOKIE_DOMAIN;
	}
	elseif ($cookie_domain == '' && !empty($_SERVER['HTTP_HOST']))
	{
		$cookie_domain = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES);
	}

	// Удаляем ведущие www. и номер порта в имени домена для использования в cookie.
	$cookie_domain = ltrim($cookie_domain, '.');
	if (strpos($cookie_domain, 'www.') === 0)
	{
		$cookie_domain = substr($cookie_domain, 4);
	}
	$cookie_domain = explode(':', $cookie_domain);
	$cookie_domain = '.'. $cookie_domain[0];

	// В соответствии с RFC 2109, имя домена для cookie должно быть второго или более уровня.
	// Для хостов 'localhost' или указанных IP-адресом имя домена для cookie не устанавливается.
	if (count(explode('.', $cookie_domain)) > 2 && !is_numeric(str_replace('.', '', $cookie_domain)))
	{
		ini_set('session.cookie_domain', $cookie_domain);
	}

	ini_set('session.cookie_path', ABS_PATH);
}
set_cookie_domain();

if (!SESSION_SAVE_HANDLER)
{
	require(BASE_DIR . '/functions/func.session.userfiles.php');
}
else
{
	require(BASE_DIR . '/functions/func.session.php');
}

//session_name('SESS'. md5($session_name));
session_name('cp');
session_start();

if (isset($HTTP_SESSION_VARS)) $_SESSION = $HTTP_SESSION_VARS;

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout')
{
	user_logout();

	header('Location:' . get_referer_link());
	exit;
}

// для параноиков
if (IDS_LIB)
{
	require(BASE_DIR . '/inc/ids.php');
}
require(BASE_DIR . '/class/class.database.php');

if (!defined('ACPL') && !auth_sessions())
{
	if (!auth_cookie())
	{
		// чистим данные авторизации в сессии
		unset($_SESSION['user_id'], $_SESSION['user_pass']);

		// считаем пользователя Гостем
		$_SESSION['user_group'] = 2;
		$_SESSION['user_name'] = get_username();
		define('UID', 0);
		define('UGROUP', 2);
		define('UNAME', $_SESSION['user_name']);
	}
}
if(!empty($_SESSION['user_id'])) $AVE_DB->Query("UPDATE ".PREFIX."_users SET last_visit='".time()."' WHERE Id='".intval($_SESSION['user_id'])."'");

//Запоминаем язык браузера
$browlang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$browlang = explode("-", $browlang);
$browlang = $browlang[0];	

$_SESSION['accept_langs']=array();
$sql=$AVE_DB->Query("SELECT * FROM ".PREFIX."_settings_lang where lang_status='1' order by lang_default ASC");
while($row=$sql->FetchRow()){
	if(trim($row->lang_key)>''){
			$_SESSION['accept_langs'][trim($row->lang_key)]=trim($row->lang_alias_pref);
			if(!defined('DEFAULT_LANGUAGE'))define('DEFAULT_LANGUAGE',trim($row->lang_key));
		}
}

$_SESSION['user_language']=(isset($_SESSION['user_language']) ? $_SESSION['user_language'] :(isset($_SESSION['accept_langs'][$browlang]) ? $browlang : DEFAULT_LANGUAGE));

// Эксперимент с кэшированием
if (!defined('ACP') && empty($_POST) && !isset($_REQUEST['module']) && UGROUP == 2 && CACHE_LIFETIME)
{
	require(BASE_DIR . '/lib/Cache/Lite/Output.php');

	$options = array(
		'writeControl' => false,
		'readControl'  => false,
		'lifeTime'     => CACHE_LIFETIME,
		'cacheDir'     => BASE_DIR . '/cache/'
	);

	$cache = new Cache_Lite_Output($options);

	if ($cache->start($_SERVER['REQUEST_URI']))
	{
		if (defined('PROFILING') && PROFILING) echo get_statistic(1,1,1,0);
		exit;
	}
}

define('DATE_FORMAT', get_settings('date_format'));
define('TIME_FORMAT', get_settings('time_format'));
define('PAGE_NOT_FOUND_ID', intval(get_settings('page_not_found_id')));
if (isset($_REQUEST['onlycontent']) && 1 == $_REQUEST['onlycontent'])
{
	define('ONLYCONTENT', 1);
}

function set_locale()
{
	$acp_language = empty($_SESSION['admin_language'])
						? $_SESSION['user_language']
						: $_SESSION['admin_language'];

	$locale = strtolower(defined('ACP') ? $acp_language : $_SESSION['user_language']);

	switch ($locale)
	{
		case 'de':
			@setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
			break;

		case 'ru':
			@setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'russian');
			break;

		default:
			@setlocale (LC_ALL, $locale . '_' . strtoupper($locale), $locale, '');
			break;
	}
}
set_locale();

require(BASE_DIR . '/class/class.template.php');
require(BASE_DIR . '/functions/func.fields.php');

?>