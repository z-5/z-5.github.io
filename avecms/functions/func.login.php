<?php

function user_login($login, $password, $attach_ip = 0, $keep_in = 0, $sleep = 0)
{
	global $AVE_DB, $cookie_domain;

	sleep($sleep);

	if (empty($login)) return 1;

	$row = $AVE_DB->Query("
		SELECT
			usr.Id,
			usr.user_group,
			usr.user_name,
			usr.firstname,
			usr.lastname,
			usr.email,
			usr.country,
			usr.password,
			usr.salt,
			usr.status,
			grp.user_group_permission
		FROM
			" . PREFIX . "_users AS usr
		LEFT JOIN
			" . PREFIX . "_user_groups AS grp
				ON grp.user_group = usr.user_group
		WHERE email = '" . $login . "'
		OR user_name = '" . $login . "'
		LIMIT 1
	")->FetchRow();


	if (! (isset($row->password) && $row->password == md5(md5($password . $row->salt)))) return 2;
	if ($row->status != '1') return 3;

	$salt = make_random_string();

	$hash = md5(md5($password . $salt));

	$time = time();

	$u_ip = ($attach_ip==1) ? "INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')" : 0;

	$AVE_DB->Query("
		UPDATE " . PREFIX . "_users
		SET
			last_visit = '" . $time . "',
			password   = '" . $hash . "',
			salt       = '" . $salt . "',
			user_ip    =  " . $u_ip . "
		WHERE
			Id = '" . $row->Id . "'
	");

	$_SESSION['user_id']       = $row->Id;
	$_SESSION['user_name']     = get_username($row->user_name, $row->firstname, $row->lastname);
	$_SESSION['user_pass']     = $hash;
	$_SESSION['user_group']    = $row->user_group;
	$_SESSION['user_email']    = $row->email;
	$_SESSION['user_country']  = strtoupper($row->country);
	$_SESSION['user_language'] = strtolower($row->country);
	$_SESSION['user_ip']       = addslashes($_SERVER['REMOTE_ADDR']);

	$user_group_permissions = explode('|', preg_replace('/\s+/', '', $row->user_group_permission));
	foreach ($user_group_permissions as $user_group_permission) $_SESSION[$user_group_permission] = 1;

//	$_SESSION['admin_theme'] = DEFAULT_ADMIN_THEME_FOLDER;
//	$_SESSION['admin_language']  = DEFAULT_LANGUAGE;
	if ($keep_in == 1)
	{
		$expire = $time + COOKIE_LIFETIME;
		$auth = base64_encode( serialize( array('id'=>$row->Id, 'hash'=>$hash)));
		@setcookie('auth', $auth, $expire, ABS_PATH, $cookie_domain);
	}

	return true;
}

function user_logout()
{
	global $cookie_domain;

	// уничтожаем куку
	@setcookie('auth', '', 0, ABS_PATH, $cookie_domain);

	// уничтожаем сессию
	@session_destroy();
	session_unset();
	$_SESSION = array();
}

function auth_sessions()
{
	global $AVE_DB;

	if (empty($_SESSION['user_id']) || empty($_SESSION['user_pass'])) return false;

	$referer = false;
	if (isset($_SERVER['HTTP_REFERER']))
	{
		$referer = parse_url($_SERVER['HTTP_REFERER']);
		$referer = (trim($referer['host']) === $_SERVER['SERVER_NAME']);
	}

	// Если не наш REFERER или изменился IP-адрес
	// сверяем данные сессии с данными базы данных
	if ($referer === false || $_SESSION['user_ip'] != $_SERVER['REMOTE_ADDR'])
	{
		$verified = $AVE_DB->Query("
			SELECT 1
			FROM " . PREFIX . "_users
			WHERE Id = '" . (int)$_SESSION['user_id'] . "'
			AND password = '" . addslashes($_SESSION['user_pass']) . "'
			LIMIT 1
		")->NumRows();

		if (!$verified) return false;

		$_SESSION['user_ip'] = addslashes($_SERVER['REMOTE_ADDR']);
	}

	define('UID',    $_SESSION['user_id']);
	define('UGROUP', $_SESSION['user_group']);
	define('UNAME',  $_SESSION['user_name']);

	return true;
}

function auth_cookie()
{
	global $AVE_DB, $cookie_domain;

	if (empty($_COOKIE['auth'])) return false;

	$auth = unserialize( base64_decode($_COOKIE['auth']));

	if (! (isset($auth['id']) && is_numeric($auth['id'])))
	{
		// уничтожаем куку
		@setcookie('auth', '', 0, ABS_PATH, $cookie_domain);

		return false;
	}

	$row = $AVE_DB->Query("
		SELECT
			usr.user_group,
			usr.user_name,
			usr.firstname,
			usr.lastname,
			usr.email,
			usr.country,
			usr.password,
			usr.status,
			INET_NTOA(usr.user_ip) AS ip,
			grp.user_group_permission
		FROM
			" . PREFIX . "_users AS usr
		LEFT JOIN
			" . PREFIX . "_user_groups AS grp
				ON grp.user_group = usr.user_group
		WHERE usr.Id = '" . $auth['id'] . "'
		LIMIT 1
	")->FetchRow();

	if (empty($row)) return false;
	if ( ($row->ip !== '0.0.0.0' && $row->ip !== $_SERVER['REMOTE_ADDR']) || !($row->status === '1' && $row->password === $auth['hash']) ) return false;

	$_SESSION['user_id']       = (int)$auth['id'];
	$_SESSION['user_name']     = get_username($row->user_name, $row->firstname, $row->lastname);
	$_SESSION['user_pass']     = $auth['hash'];
	$_SESSION['user_group']    = (int)$row->user_group;
	$_SESSION['user_email']    = $row->email;
	$_SESSION['user_country']  = strtoupper($row->country);
	$_SESSION['user_language'] = strtolower($row->country);
	$_SESSION['user_ip']       = addslashes($_SERVER['REMOTE_ADDR']);

	$user_group_permissions = explode('|', preg_replace('/\s+/', '', $row->user_group_permission));
	foreach ($user_group_permissions as $user_group_permission) $_SESSION[$user_group_permission] = 1;

//	$_SESSION['admin_theme'] = DEFAULT_ADMIN_THEME_FOLDER;
//	$_SESSION['admin_language']  = DEFAULT_LANGUAGE;

	define('UID',    $_SESSION['user_id']);
	define('UGROUP', $_SESSION['user_group']);
	define('UNAME',  $_SESSION['user_name']);

	return true;
}

/**
 * Удаление профиля пользователя на сайте и на форуме
 *
 * @param string $user_id идентификатор пользователя
 */
function user_delete($user_id)
{
	global $AVE_DB;

	$AVE_DB->Query("
		DELETE
		FROM " . PREFIX . "_users
		WHERE Id = '" . $user_id . "'
	");
}

?>