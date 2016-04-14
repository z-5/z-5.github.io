<?php

/**
 * AVE.cms
 *
 * Класс, предназначенный для создания и восстановления дампов БД через Панель управления
 *
 * @package AVE.cms
 * @filesource
 */

class AVE_DB_Service
{

/**
 *	Свойства класса
 */

	/**
	 * Метка-разделитель SQL-запросов
	 *
	 * @var string
	 */
	var $_delimiter = '#####systemdump#####';

	/**
	 * Дамп базы данных
	 *
	 * @var string
	 */
	var $_database_dump = '';

/**
 *	Внутренние методы
 */

	/**
	 * Метод, предназначенный для формирования файла дампа базы данных
	 *
	 * @return boolean
	 */
	function _databaseDumpCreate()
	{
		global $AVE_DB;

		if (! (!empty($_REQUEST['ta']) && is_array($_REQUEST['ta']))) return false;

		$search  = array("\x00", "\x0a", "\x0d", "\x1a");
		$replace = array('\0', '\n', '\r', '\Z');

		$this->_database_dump = '';

		// Циклически обрабатываем каждую таблицу
        foreach ($_REQUEST['ta'] as $table)
		{
			// Если таблица имеет корректный префикс
            if (preg_match('/^' . preg_quote(PREFIX) . '_/', $table))
			{
				$row = $AVE_DB->Query("SHOW CREATE TABLE " . $table)->FetchArray();
				// Сохраняем CREATE и DROP запросы
                $this->_database_dump .= "DROP TABLE IF EXISTS `" . $table . "`;" . $this->_delimiter . "\n";
				$this->_database_dump .= $row[1] . ";" . $this->_delimiter . "\n\n";

				$nums = 0;

                // Получаем данные, которые в дальнейшем будут вставлены в INSERT запросы.
                $sql = $AVE_DB->Query('SELECT * FROM `' . $table . '`');
				while ($row = $sql->FetchArray())
				{
					if ($nums==0)
					{
						$nums = $sql->NumFields();

						$temp_array = array();
						for ($i=0; $i<$nums; $i++)
						{
							$temp_array[] = $sql->FieldName($i);
						}
						$table_list = '(`' . implode('`, `', $temp_array) . '`)';
					}

					$temp_array = array();
					for ($i=0; $i<$nums; $i++)
					{
						if (!isset($row[$i]))
						{
							$temp_array[] = 'NULL';
						}
						elseif ($row[$i] != '')
						{
							$temp_array[] = "'" . str_replace($search, $replace, addslashes($row[$i])) . "'";
						}
						else
						{
							$temp_array[] = "''";
						}
					}

                    // Сохряняем INSERT запросы
                    $this->_database_dump .= 'INSERT INTO `' . $table . '` ' . $table_list . ' VALUES (' . implode(', ', $temp_array) . ");" . $this->_delimiter . "\n";
				}
				$this->_database_dump .= "\n";

				$sql->Close();
			}
		}

		return !empty($this->_database_dump);
	}


	/**
	 * Метод, предназначенный для формирования файла дампа базы данных
	 *
	 * @return boolean
	 */
	function _databaseTopDumpCreate()
	{
		global $AVE_DB;

        $dbtables  = array();
        $sql = $AVE_DB->Query("SHOW TABLES LIKE '" . PREFIX . "_%'");
		while ($row = $sql->FetchArray())
		{
			array_push($dbtables, $row[0]);
		}

		$search  = array("\x00", "\x0a", "\x0d", "\x1a");
		$replace = array('\0', '\n', '\r', '\Z');

		$this->_database_dump = '';

		// Циклически обрабатываем каждую таблицу
        foreach ($dbtables as $table)
		{
			// Если таблица имеет корректный префикс
            if (preg_match('/^' . preg_quote(PREFIX) . '_/', $table))
			{
				$row = $AVE_DB->Query("SHOW CREATE TABLE " . $table)->FetchArray();
				// Сохраняем CREATE и DROP запросы
                $this->_database_dump .= "DROP TABLE IF EXISTS `" . $table . "`;" . $this->_delimiter . "\n";
				$this->_database_dump .= $row[1] . ";" . $this->_delimiter . "\n\n";

				$nums = 0;

                // Получаем данные, которые в дальнейшем будут вставлены в INSERT запросы.
                $sql = $AVE_DB->Query('SELECT * FROM `' . $table . '`');
				while ($row = $sql->FetchArray())
				{
					if ($nums==0)
					{
						$nums = $sql->NumFields();

						$temp_array = array();
						for ($i=0; $i<$nums; $i++)
						{
							$temp_array[] = $sql->FieldName($i);
						}
						$table_list = '(`' . implode('`, `', $temp_array) . '`)';
					}

					$temp_array = array();
					for ($i=0; $i<$nums; $i++)
					{
						if (!isset($row[$i]))
						{
							$temp_array[] = 'NULL';
						}
						elseif ($row[$i] != '')
						{
							$temp_array[] = "'" . str_replace($search, $replace, addslashes($row[$i])) . "'";
						}
						else
						{
							$temp_array[] = "''";
						}
					}

                    // Сохряняем INSERT запросы
                    $this->_database_dump .= 'INSERT INTO `' . $table . '` ' . $table_list . ' VALUES (' . implode(', ', $temp_array) . ");" . $this->_delimiter . "\n";
				}
				$this->_database_dump .= "\n";

				$sql->Close();
			}
		}

		return !empty($this->_database_dump);
	}


/**
 *	Внешние методы класса
 */

	/**
	 * Метод, предназначенный для сохранения файла дампа базы данных на жеский диск
	 *
	 */
	function databaseDumpExport($top=0)
	{
		// Если дамп не удалось создать, тогда завершаем работу
		if ($top)
		{
			if (!$this->_databaseTopDumpCreate()) exit;
		}
		else
		{
			if (!$this->_databaseDumpCreate()) exit;
		}

		// Готовим шаблон имени файла
		$file_name = preg_replace_ru(array("/%SERVER%/","/%DATE%/","/%TIME%/"),array($_SERVER['SERVER_NAME'],date('d.m.y'),date('H.i.s')),DB_EXPORT_TPL);

		$dump=(defined('GZ_DBDUMP')&&GZ_DBDUMP ? gzencode($this->_database_dump) : $this->_database_dump);
        // Формируем заголовок
		header('Content-Type: text/plain');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Disposition: attachment; filename=' . $file_name . '.sql'.(defined('GZ_DBDUMP')&&GZ_DBDUMP ? '.gz' : ''));
		header('Content-Length: ' . strlen($dump));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		// Выводим данные
        echo $dump;

		$this->_database_dump = '';

		// Выполняем запись системного сообщения в журнал
        reportLog($_SESSION['user_name'] . ' - выполнил резервное копирование базы данных.', 2, 2);

		exit;
	}

	/**
	 * Метод, предназначенный для восстановления базы данных из дампа
	 *
	 * @param string $tempdir путь к папке в которую загружается файл дампа
	 */
	function databaseDumpImport($tempdir)
	{
		global $AVE_DB, $AVE_Template;

		$insert = false;

		// Если файл не пустой
        if ($_FILES['file']['size'] != 0)
		{
			// Получаем имя файла и его расширение (должно быть sql)
            $fupload_name = $_FILES['file']['name'];
			$gz = substr($fupload_name, -3)=='.gz';
			$end = substr($fupload_name, -3);

            // Если расширение sql, тогда
            if ($gz||$end == 'sql')
			{
				// Если файл не удалось загрузить, формируем сообщение с ошибкой
                if (!@move_uploaded_file($_FILES['file']['tmp_name'], $tempdir . $fupload_name)) die('Ошибка при загрузке файла!');

                // Устанавливаем права чтения, записи, выполнения на файл
                @chmod($fupload_name, 0777);

                // Определяем флаг готовности к записи данных в БД
                $insert = true;
			}
			else
			{
				// В противном случае, если расширение файла НЕ sql, формируем сообщение с ошибкой
                $AVE_Template->assign('msg', '<li class="highlight red"><strong>Ошибка:</strong> ' . $AVE_Template->get_config_vars('MAIN_SQL_FILE_ERROR') . '</li>');
			}
		}

		// Если флаг готовности записи установлен, тогда
        if ($insert)
		{
			// Еще раз провреяем наличие загруженного файла
            if ($fupload_name != '' && file_exists($tempdir . $fupload_name))
			{
				// Читаем данные из файла
                $handle = @fopen($tempdir . $fupload_name, 'r');
				$db_q = @fread($handle, filesize($tempdir . $fupload_name));
				fclose($handle);
				if($gz)$db_q=gzdecode($db_q);
				$m_ok = 0;
				$m_fail = 0;

				// Формируем массив запросов ориентируясь по разделителю указанному в свойстве _delimiter
                $querys = @explode($this->_delimiter, $db_q);

				// Циклически обрабатываем массив, выполняя каждый запрос
                foreach ($querys as $val)
				{
					if (chop($val) != '')
					{
						$q = str_replace("\n",'',$val);
						$q = $q . ';';
						if ($AVE_DB->Query($q))
						{
							$m_ok++;
						}
						else
						{
							$m_fail++;
						}
					}
				}

				// Удаляем файл дампа
                @unlink($tempdir . $fupload_name);

                // Формируем сопроводительные сообщения
                $msg =  '<li class="highlight green"><strong>' . $AVE_Template->get_config_vars('MAIN_RESTORE_OK') . '</strong><br /><br />'
					. $AVE_Template->get_config_vars('MAIN_TABLE_SUCC')
					. '<strong>' . $m_ok . '</strong><br/> '
					. $AVE_Template->get_config_vars('MAIN_TABLE_ERROR')
					. '<strong><span style="color:red">' . $m_fail . '</span></strong></li>';
				$AVE_Template->assign('msg', $msg);
			}
			else // В противном случае, если файл не найден, формируем сообщение с ошибкой
			{
				$AVE_Template->assign('msg', '<li class="highlight red"><strong>Ошибка:</strong> Импорт базы данных не выполнен, т.к. отсутсвует файл дампа или он поврежден.</li>');
			}
		}

        // Выполняем запись системного сообщения в журнал
		reportLog($_SESSION['user_name'] . ' - выполнил восстановление базы данных из резервной копии', 2, 2);
	}




    /**
	 * Метод, предназначенный для оптимизации таблиц базы данных
	 *
	 */
	function databaseTableOptimize()
	{
		global $AVE_DB;

		if (!empty($_POST['ta']) && is_array($_POST['ta']))
		{
			// Выполняем запрос на оптимизацию
            $AVE_DB->Query("OPTIMIZE TABLE `" . implode("`, `", $_POST['ta']) . "`");

			// Выполняем запись системного сообщения в журнал
            reportLog($_SESSION['user_name'] . ' - выполнил оптимизацию базы данных', 2, 2);
		}
	}

	/**
	 * Метод, предназначенный для восстановления повреждённых таблиц базы данных
	 *
	 */
	function databaseTableRepair()
	{
		global $AVE_DB;

		if (!empty($_POST['ta']) && is_array($_POST['ta']))
		{
			// Выполняем запрос на восстановление
            $AVE_DB->Query("REPAIR TABLE `" . implode("`, `", $_POST['ta']) . "`");

			// Выполняем запись системного сообщения в журнал
            reportLog($_SESSION['user_name'] . ' - выполнил востановление таблиц базы данных', 2, 2);
		}
	}

	/**
	 * Метод, предназначенный для формирования списка всех таблиц в БД
	 *
	 * @return string
	 */
	function databaseTableGet()
	{
		global $AVE_DB;

		$tables = '';

        // Получаем список всех таблиц, которые имею префикс, указанный в конфигурации системы
		$sql = $AVE_DB->Query("SHOW TABLES LIKE '" . PREFIX . "_%'");
		while ($row = $sql->FetchArray())
		{
			$tables .= '<option value="' . $row[0] . '" selected="selected">' . substr($row[0], 1+strlen(PREFIX)) . '</option>';
		}
		$sql->Close();

        // Возвращаем полученный список
		return $tables;
	}
}

?>