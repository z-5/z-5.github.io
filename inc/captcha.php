<?php
define('BASE_DIR', str_replace("\\", "/", dirname(dirname(__FILE__))));

require(BASE_DIR . '/inc/config.php');
require(BASE_DIR . '/inc/db.config.php');

if (!SESSION_SAVE_HANDLER)
{
	require(BASE_DIR . '/functions/func.session.userfiles.php');
}
else
{
	require(BASE_DIR . '/functions/func.session.php');
}
session_name('cp');
session_start();
unset($_SESSION['captcha_keystring']);

require(BASE_DIR . '/lib/kcaptcha/kcaptcha.php');

$captcha = new KCAPTCHA();

$_SESSION['captcha_keystring'] = $captcha->getKeyString();

?>