<?php

/**
 * AVE.cms
 *
 * Класс, предназначенный для управления журналом системных сообщений
 *
 * @package AVE.cms
 * @filesource
 */

class AVE_Logs
{

/**
 *	Свойства класса
 */

	/**
	 * Количество записей на странице
	 *
	 * @var int
	 */
	var $_404dir = '/cache/404.php';
	var $_logdir = '/cache/log.php';
/**
 *	Внутренние методы класса
 */


/**
 *	Внешние методы класса
 */

	/**
	 * Метод, предназначенный для отображения всех записей Журнала событий
	 *
	 */
	function logList()
	{
		global $AVE_DB, $AVE_Template;
		$logdata=array();
		$logfile = BASE_DIR.$this->_logdir;
		if(file_exists($logfile))
			@eval('?>'.file_get_contents($logfile).'<?');
		arsort($logdata);
		// Передаем данные в шаблон для вывода и отображаем страницу
        $AVE_Template->assign('logs', $logdata);
		$AVE_Template->assign('content', $AVE_Template->fetch('logs/logs.tpl'));
	}
	/**
	 * Метод, предназначенный для отображения всех записей Журнала событий 404
	 *
	 */
	function List404()
	{
		global $AVE_DB, $AVE_Template;
		$logdata=array();
		$logfile = BASE_DIR.$this->_404dir;
		if(file_exists($logfile))
			include($logfile);
		arsort($logdata);
		// Передаем данные в шаблон для вывода и отображаем страницу
        $AVE_Template->assign('logs', $logdata);
		$AVE_Template->assign('content', $AVE_Template->fetch('logs/404.tpl'));
	}

	/**
	 * Метод, предназначенный для удаление записей Журнала событий
	 *
	 */
	function logDelete()
	{
		global $AVE_DB, $AVE_Template;

		$logfile = BASE_DIR.$this->_logdir;
		if(file_exists($logfile))
			unlink($logfile);

		// Сохраняем системное сообщение в журнал
        reportLog($_SESSION['user_name'] . ' - ' .  $AVE_Template->get_config_vars('LOGS_CLEAN'), 2, 2);

        header('Location:index.php?do=logs&cp=' . SESSION);
		exit;
	}

	/**
	 * Метод, предназначенный для удаление записей Журнала событий 404
	 *
	 */
	function Delete404()
	{
		global $AVE_DB, $AVE_Template;

		$logfile = BASE_DIR.$this->_404dir;
		if(file_exists($logfile))
			unlink($logfile);

		// Сохраняем системное сообщение в журнал
        reportLog($_SESSION['user_name'] . ' - ' .  $AVE_Template->get_config_vars('LOGS_404_CLEAN'), 2, 2);

        header('Location:index.php?do=logs&action=log404&cp=' . SESSION);
		exit;
	}

	/**
	 * Метод, предназначенный для экспорта системных сообщений
	 *
	 */
	function logExport()
	{
		global $AVE_DB;

		// Определяем тип файла (CSV), формат имени файла, разделители и т.д.
        $datstring = '';
		$dattype = 'text/csv';
		$datname = 'system_log_' . date('dmyhis', time()) . '.csv';

		$separator = ';';
		$enclosed = '"';

        // Выполняем запрос к БД на получение списка всех системных сообщений
		$logdata=array();
		$logfile = BASE_DIR.$this->_logdir;
		if(file_exists($logfile))
			@eval('?>'.file_get_contents($logfile).'<?');
		arsort($logdata);
		$fieldcount = count($logdata[0]);
		
		foreach($logdata[0] as $k=>$v)
			$datstring .= $enclosed . $k . $enclosed . $separator;
		$datstring .= PHP_EOL;

		// Циклически обрабатываем данные и формируем CSV файл с учетом указаны выше параметров
		foreach($logdata as $k=>$v)
		{
			foreach ($v as $key => $val)
			{
				$val = ($key=='log_time') ? date('d-m-Y, H:i:s', $val) : $val;
				$datstring .= ($val == '') ? $separator : $enclosed . stripslashes($val) . $enclosed . $separator;
			}
			$datstring .= PHP_EOL;
		}

		// Определяем заголовки документа
		header('Content-Type: text/csv' . $dattype);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Disposition: attachment; filename="' . $datname . '"');
		header('Content-Length: ' . strlen($datstring));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		// Выводим данные
        echo $datstring;

		// Сохраняем системное сообщение в журнал
        reportLog($_SESSION['user_name'] . ' - ' .  $AVE_Template->get_config_vars('LOGS_EXPORT'), 2, 2);

		exit;
	}

	/**
	 * Метод, предназначенный для экспорта сообщений 404
	 *
	 */
	function Export404()
	{
		global $AVE_DB, $AVE_Template;

		// Определяем тип файла (CSV), формат имени файла, разделители и т.д.
        $datstring = '';
		$dattype = 'text/csv';
		$datname = 'system_log_' . date('dmyhis', time()) . '.csv';

		$separator = ';';
		$enclosed = '"';

        // Выполняем запрос к БД на получение списка всех системных сообщений
		$logdata=array();
		$logfile = BASE_DIR.$this->_404dir;
		if(file_exists($logfile))
			include($logfile);
		arsort($logdata);
		$fieldcount = count($logdata[0]);
		
		foreach($logdata[0] as $k=>$v)
			$datstring .= $enclosed . $k . $enclosed . $separator;
		$datstring .= PHP_EOL;

		// Циклически обрабатываем данные и формируем CSV файл с учетом указаны выше параметров
		foreach($logdata as $k=>$v)
		{
			foreach ($v as $key => $val)
			{
				$val = ($key=='log_time') ? date('d-m-Y, H:i:s', $val) : $val;
				$datstring .= ($val == '') ? $separator : $enclosed . stripslashes($val) . $enclosed . $separator;
			}
			$datstring .= PHP_EOL;
		}

		// Определяем заголовки документа
		header('Content-Type: text/csv' . $dattype);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Disposition: attachment; filename="' . $datname . '"');
		header('Content-Length: ' . strlen($datstring));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		// Выводим данные
        echo $datstring;

		// Сохраняем системное сообщение в журнал
        reportLog($_SESSION['user_name'] . ' - ' .  $AVE_Template->get_config_vars('LOGS_404_EXPORT'), 2, 2);

		exit;
	}
}

?>