<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

ini_set('session.save_handler', 'user');

$sess_dblink = '';

$sess_lifetime = (defined('SESSION_LIFETIME') && is_numeric(SESSION_LIFETIME))
	? SESSION_LIFETIME
	: (get_cfg_var("session.gc_maxlifetime") < 1440 ? 1440 : get_cfg_var("session.gc_maxlifetime"));

function sess_open($save_path, $session_name)
{
	global $sess_dblink;

	@require(BASE_DIR . '/inc/db.config.php');

	if (! defined('PREFIX')) define('PREFIX', $config['dbpref']);

	if (! $sess_dblink = @mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']))
	{
		display_notice("Error connect MySQL database.");
		die;
	}

	if (! @mysql_select_db($config['dbname'], $sess_dblink))
	{
		display_notice("Error select MySQL database.");
		die;
	}

	@mysql_query("SET NAMES 'utf-8'", $sess_dblink);

	return true;
}

function sess_close()
{
	global $sess_dblink;

	$qid = @mysql_query("DELETE FROM ".PREFIX."_sessions WHERE expiry < '".time()."'", $sess_dblink);

	return true;
}

function sess_read($key)
{
	global $sess_dblink;

	$qid = @mysql_query("SELECT value, Ip FROM ".PREFIX."_sessions WHERE sesskey = '$key' AND expiry > '".time()."'", $sess_dblink);
	if ((list($value, $ip) = @mysql_fetch_row($qid)) && $ip == $_SERVER['REMOTE_ADDR']) return $value;

	return false;
}

function sess_write($key, $val)
{
	global $sess_dblink, $sess_lifetime;

	if (! $qid = @mysql_query("INSERT INTO ".PREFIX."_sessions VALUES ('$key', ".(time()+$sess_lifetime).", '".addslashes($val)."', '".$_SERVER['REMOTE_ADDR']."', FROM_UNIXTIME(expiry,'%d.%m.%Y, %H:%i:%s'))", $sess_dblink))
	{
		$qid = @mysql_query("UPDATE ".PREFIX."_sessions SET expiry = ".(time()+$sess_lifetime).", expire_datum = FROM_UNIXTIME(expiry,'%d.%m.%Y, %H:%i:%s'), value = '".addslashes($val)."', Ip = '".$_SERVER['REMOTE_ADDR']."' WHERE sesskey = '".$key."' AND expiry > '".time()."'", $sess_dblink);
	}

	return $qid;
}

function sess_destroy($key)
{
	global $sess_dblink;

	return @mysql_query("DELETE FROM ".PREFIX."_sessions WHERE sesskey = '$key'", $sess_dblink);
}

function sess_gc($maxlifetime)
{
	global $sess_dblink;

	$qid = @mysql_query("DELETE FROM ".PREFIX."_sessions WHERE expiry < '".time()."'", $sess_dblink);

	return @mysql_affected_rows($sess_dblink);
}

session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");

?>