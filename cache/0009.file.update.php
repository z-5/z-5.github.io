<?
// Комментирует в config.inc.php строку, если путь к папке вложений неверный
// В результате этого назначается папка по умолчанию
if (!is_dir(BASE_DIR . "/" . ATTACH_DIR))
{
	$config = file_get_contents (BASE_DIR.'/inc/config.inc.php');
	$config = str_replace(
		"define('ATTACH_DIR'",
		"//неверный путь!!!\r\n//define('ATTACH_DIR'",
		$config
	);
	file_put_contents (BASE_DIR.'/inc/config.inc.php', $config);
}
?>