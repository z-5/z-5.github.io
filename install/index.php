<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

/**
 * Функции
 */
function check_db_connect($dbhost = '', $dbuser = '', $dbpass = '', $dbname = '')
{
	if ($dbhost != '' && $dbuser != '' && $dbname != '')
	{
		if (@mysql_select_db($dbname, @mysql_connect($dbhost, $dbuser, $dbpass))) return true;
	}

	return false;
}
function check_installed($prefix)
{
	global $config;

	$query = @mysql_query("SELECT 1 FROM " . $prefix . "_users LIMIT 1");

	if (@mysql_num_rows($query)) return true;
	else return false;
}
function check_required()
{
	global $error_is_required, $lang_i;

	$required_php = 423;
	$required = array();
	$required[] = '/install/eula/ru.tpl';

	foreach ($required as $is_required)
	{
		if (@!is_file(BASE_DIR . $is_required))
		{
			array_push($error_is_required, $lang_i['error_is_required'] . $is_required . $lang_i['error_is_required_2'] );
		}
	}

	$myphp = @PHP_VERSION;
	if ($myphp)
	{
		$myphp_v = str_replace('.', '', $myphp);
		if ($myphp_v < $required_php)
		{
			array_push($error_is_required, $lang_i['phpversion_toold'] . $required_php);
		}
	}
}
function check_writable()
{
	global $error_is_required, $lang_i;

	$writeable = array();
	$writeable[] = '/cache/';
	$writeable[] = '/uploads/';
	$writeable[] = '/inc/db.config.php';

	foreach ($writeable as $must_writeable)
	{
		if (!is_writable(BASE_DIR . $must_writeable))
		{
			array_push($error_is_required, $lang_i['error_is_writeable'] . $must_writeable . $lang_i['error_is_writeable_2'] );
		}
	}
}
function clean_db ($name="",$prefix="")
{
	echo 1;
	$query = @mysql_list_tables ($name);
	while ($row = @mysql_fetch_array($query, MYSQL_NUM))
	{
		if (preg_match("/^" . $prefix . "/",$row[0]))
		{
			@mysql_query("DROP TABLE " . $row[0]);
		}
	}
}
/**
 * @subpackage install
 */
error_reporting(E_ALL ^ E_NOTICE);

global $config, $lang_i;

ob_start();

define('SETUP', 1);

define('BASE_DIR', str_replace("\\", "/", dirname(dirname(__FILE__))));

include(BASE_DIR . '/install/lang/ru.php');

if (!is_writable(BASE_DIR . '/cache/smarty/')) die($lang_i['templates_c_notwritable']);

include(BASE_DIR . '/inc/db.config.php');
include(BASE_DIR . '/inc/config.php');
include(BASE_DIR . '/functions/func.common.php');
include(BASE_DIR . '/class/class.template.php');

$AVE_Template = new AVE_Template(BASE_DIR . '/install/tpl/');

$ver = APP_NAME . ' ' . APP_VERSION;
$AVE_Template->assign('version_setup', $lang_i['install_name'] . ' ' . $ver);
$AVE_Template->assign('app_info', APP_INFO);
$AVE_Template->assign('la', $lang_i);

$db_connect = check_db_connect($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

if ($db_connect && $_REQUEST['step'] != 'finish' && check_installed($config['dbpref'])) {
	echo '<pre>' . $lang_i['installed'] . '</pre>';
	exit;
};

$error_is_required = array();

check_required();
check_writable();

$count_error = sizeof((array) $error_is_required);
if (1 == $count_error)
{
	$AVE_Template->assign('error_header', $lang_i['erroro']);
}
elseif ($count_error > 1)
{
	$AVE_Template->assign('error_header', $lang_i['erroro_more']);
}

if ($count_error > 0 && ! (isset($_REQUEST['force']) && 1 == $_REQUEST['force']))
{
	$AVE_Template->assign('error_is_required', $error_is_required);
	$AVE_Template->display('error.tpl');
	exit;
}

$_REQUEST['step'] = isset($_REQUEST['step']) ? $_REQUEST['step'] : '';

switch ($_REQUEST['step'])
{
	case '' :
	case '1' :
		$AVE_Template->display('step1.tpl');
		break;

	case '2' :
		if (false === $db_connect && !empty($_POST['dbname']) && !empty($_POST['dbprefix']))
		{
			$db_connect = check_db_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);
			if ($_REQUEST["clean_db"]) {
				clean_db($_POST['dbname'], $_POST['dbprefix']);
			}
			$check_installed = check_installed($_POST['dbprefix']);

			if (true === $db_connect && false === $check_installed)
			{
				if (! @is_writeable(BASE_DIR . '/inc/db.config.php'))
				{
					$AVE_Template->assign('config_isnt_writeable', 1);
					$AVE_Template->display('error.tpl');
					exit;
				}

				$fp = @fopen(BASE_DIR . '/inc/db.config.php', 'w+');
				@fwrite($fp, "<?php\n"
					. "\$config['dbhost'] = \"" . stripslashes(trim($_POST['dbhost']))   . "\";\n"
					. "\$config['dbuser'] = \"" . stripslashes(trim($_POST['dbuser']))   . "\";\n"
					. "\$config['dbpass'] = \"" . stripslashes(trim($_POST['dbpass']))   . "\";\n"
					. "\$config['dbname'] = \"" . stripslashes(trim($_POST['dbname']))   . "\";\n"
					. "\$config['dbpref'] = \"" . stripslashes(trim($_POST['dbprefix'])) . "\";\n"
					. "?>"
				);
				@fclose($fp);

				$AVE_Template->display('step3.tpl');
				exit;
			}
			elseif (true === $db_connect && true === $check_installed)
			{
				$AVE_Template->assign('installed_q', $lang_i['installed_q']);
			}
			else
			{
				$AVE_Template->assign('warnnodb', $lang_i['enoconn']);
			}

		}
		else
		{
			$dbpref = make_random_string(5, 'abcdefghijklmnopqrstuvwxyz0123456789');
			$AVE_Template->assign('dbpref', $dbpref);
		}

		$AVE_Template->display('step2.tpl');
		break;

	case '3' :
		if (true === $db_connect)
		{
			if (isset($_POST['demo']) && 1 == $_POST['demo'])
			{
				$filename = BASE_DIR . '/install/structure_demo.sql';
			}
			else
			{
				$filename = BASE_DIR . '/install/structure_base.sql';
				$_REQUEST['demo'] = '0';
			}
			$handle = fopen($filename, 'r');
			$db_structure = fread($handle, filesize($filename));
			fclose($handle);

			$db_structure = str_replace('%%PRFX%%', $config['dbpref'], $db_structure);

			$ar = explode('#inst#', $db_structure);

			foreach ($ar as $in)
			{
				@mysql_query($in);
			}

			$AVE_Template->display('step4.tpl');
			exit;
		}
		$AVE_Template->display('step3.tpl');
		break;

	case '4' :
		$_POST['email'] = chop($_POST['email']);
		$_POST['username'] = chop($_POST['username']);

		$regex_username = '/[^\w-]/';
		$regex_password = '/[^\x20-\xFF]/';
		$regex_email = '/^[\w.-]+@[a-z0-9.-]+\.(?:[a-z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)$/i';

		$errors = array();
		if ($_POST['email'] == '')                                                        array_push($errors, $lang_i['noemail']);
		if (! preg_match($regex_email, $_POST['email']))                                  array_push($errors, $lang_i['email_no_specialchars']);
		if (empty($_POST['pass']) || preg_match($regex_password, $_POST['pass']))         array_push($errors, $lang_i['check_pass']);
		if (strlen($_POST['pass']) < 5)                                                   array_push($errors, $lang_i['pass_too_small']);
		if (empty($_POST['username']) || preg_match($regex_username, $_POST['username'])) array_push($errors, $lang_i['check_username']);

		if (true === $db_connect && ! sizeof($errors))
		{
			if (isset($_POST['demo']) && 1 == $_POST['demo'])
			{
				$filename = BASE_DIR . '/install/data_demo.sql';
			}
			else
			{
				$filename = BASE_DIR . '/install/data_base.sql';
			}

			$handle = fopen($filename, 'r');
			$dbin = fread($handle, filesize($filename));
			fclose($handle);

			$salt = make_random_string();
			$hash = md5(md5($_POST['pass'] . $salt));

			$dbin = str_replace('%%SITENAME%%', $ver,                $dbin);
			$dbin = str_replace('%%PRFX%%',     $config['dbpref'],   $dbin);
			$dbin = str_replace('%%EMAIL%%',    $_POST['email'],     $dbin);
			$dbin = str_replace('%%SALT%%',     $salt,               $dbin);
			$dbin = str_replace('%%PASS%%',     $hash,               $dbin);
			$dbin = str_replace('%%ZEIT%%',     time(),              $dbin);
			$dbin = str_replace('%%VORNAME%%',  $_POST['firstname'], $dbin);
			$dbin = str_replace('%%NACHNAME%%', $_POST['lastname'],  $dbin);
			$dbin = str_replace('%%USERNAME%%', $_POST['username'],  $dbin);
			$dbin = str_replace('%%FON%%',      $_POST['fon'],       $dbin);
			$dbin = str_replace('%%FAX%%',      $_POST['fax'],       $dbin);
			$dbin = str_replace('%%PLZ%%',      $_POST['zip'],       $dbin);
			$dbin = str_replace('%%ORT%%',      $_POST['town'],      $dbin);
			$dbin = str_replace('%%STRASSE%%',  $_POST['street'],    $dbin);
			$dbin = str_replace('%%HNR%%',      $_POST['hnr'],       $dbin);

			$ar = explode('#inst#', $dbin);

			foreach ($ar as $in)
			{
				@mysql_query("SET NAMES 'utf8'");
				@mysql_query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
				mysql_query($in);
			}

			$auth = base64_encode(serialize(array('id'=>'1', 'hash'=>$hash)));
			@setcookie('auth', $auth);

			$AVE_Template->display('step5.tpl');
			exit;
		}

		$AVE_Template->display('step4.tpl');
		break;
}

?>