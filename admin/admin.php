<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @subpackage admin
 * @filesource
 */

@date_default_timezone_set('Europe/Moscow');

define('ACP', 1);
define('ACPL', 1);
define('BASE_DIR', str_replace("\\", "/", dirname(dirname(__FILE__))));

if (! @filesize(BASE_DIR . '/inc/db.config.php')) { header('Location:/install'); exit; }

require(BASE_DIR . '/admin/init.php');

unset ($captcha_ok);

if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'logout')
{
	// Завершение работы в админке
	reportLog($_SESSION['user_name'] . ' - закончил сеанс в Панели управления', 2, 2);
	user_logout();
	header('Location:admin.php');
}

//// Если в сессии нет темы оформления или языка
//// и в запросе нет действия - отправляем на форму авторизации
//if  (!isset($_REQUEST['action']) &&
//	(!isset($_SESSION['admin_theme']) || !isset($_SESSION['admin_language'])))
//{
//	$AVE_Template->display('login.tpl');
//	exit;
//}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'login')
{
	// Авторизация
	if (!empty($_POST['user_login']) && !empty($_POST['user_pass']))
	{	
		if (ADMIN_CAPTCHA)
		{
			if (isset($_SESSION['captcha_keystring']) && isset($_POST['securecode']) && $_SESSION['captcha_keystring'] == $_POST['securecode']) $captcha_ok = 1;
			else
			{
				unset($_SESSION['user_id'], $_SESSION['user_pass']);
				unset($_SESSION['captcha_keystring']);
				$error = "<strong>Ошибка:</strong><br />Неправильный защитный код";
				$AVE_Template->assign('error', $error);
			}
		}
		else $captcha_ok = 1;
		
		if ($captcha_ok)
		{
			if (true === user_login($_POST['user_login'], $_POST['user_pass'], 1,(int)(isset($_POST['SaveLogin']) && $_POST['SaveLogin'] == '1')))
			{
			
	            if (!empty($_SESSION['redirectlink']))
	            {
	                header('Location:' . $_SESSION['redirectlink']);
	                unset($_SESSION['redirectlink']);
	                exit;
	            }
	
	            reportLog($_SESSION['user_name']
	            			. ' - начал сеанс в Панели управления', 2, 2);
	
	            header('Location:index.php');
	            exit;
			}
			else{
		        reportLog('Ошибка при входе в Панель управления - '
		        			. stripslashes($_POST['user_login']) . ' / '
		        			. stripslashes($_POST['user_pass']), 2, 2);
			
       			unset($_SESSION['user_id'], $_SESSION['user_pass']);
       			unset($_SESSION['captcha_keystring']);
				$error = "<strong>Ошибка:</strong><br />Имя пользователя или пароль не правильные!";
				$AVE_Template->assign('error', $error);
			}
			
		}

	}
}

$AVE_Template->assign('admin_favicon', ADMIN_FAVICON);
$AVE_Template->assign('captcha',ADMIN_CAPTCHA);
$AVE_Template->display('login.tpl');

?>