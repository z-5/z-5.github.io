<?php

/**
 * AVE.cms
 *
 * Класс предназначен для создания обертки над mysql запросами к БД.
 *
 * @package AVE.cms
 * @filesource
 */

function array2object($array) {
 
    if (is_array($array)) {
        $obj = new StdClass();
 
        foreach ($array as $key => $val){
            $obj->$key = $val;
        }
    }
    else { $obj = $array; }
 
    return $obj;
}	

/**
 * Класс, предназначенный для работы с результатами выполнения MySQL-запроса
 */
class AVE_DB_Result
{

/**
 *	Свойства класса
 */

	/**
	 * Конечный результат выполнения запроса
	 *
	 * @var resource
	 * @access private
	 */
	var $_result;


/**
 *	Внешние методы класса
 */

	/**
	 * Конструктор, возвращает объект с указателем на результат выполнения SQL-запроса
	 *
	 * @param resource $result	указателем на результат выполнения SQL-запроса
	 * @return object AVE_DB_Result
	 * @access public
	 */
	function AVE_DB_Result($result)
	{
		$this->_result = $result;
	}

	/**
	 * Метод, предназначенный для обработки результата запроса.
	 * Возвращает как ассоциативный, так и численный массив.
	 *
	 * @return array
	 * @access public
	 */
	function FetchArray()
	{
		if(is_array($this->_result)) {
			$a=current($this->_result);
			next($this->_result);
			$b=array();
			if(!is_array($a))return false;
			foreach($a as $k=>$v)$b[]=$v;
			return array_merge($b,$a);
		}
		return @mysqli_fetch_array($this->_result);
	}

	/**
	 *  Метод, предназначенный для обработки результата запроса.
	 *  Возвращает только ассоциативный массив.
	 *
	 * @return array
	 * @access public
	 */
	function FetchAssocArray()
	{
		if(is_array($this->_result)){
			$a=current($this->_result);
			next($this->_result);
			return $a;
		}
		return @mysqli_fetch_assoc($this->_result);
	}

	/**
	 * Метод, предназначенный для обработки результата запроса, возвращая данные в виде объекта.
	 *
	 * @return object
	 * @access public
	 */
	function FetchRow()
	{
		if(is_array($this->_result)){
			$a=$this->FetchAssocArray();
			return array2object($a);
		}
		return @mysqli_fetch_object($this->_result);
	}

	/**
	 * Метод, предназначенный для возвращения данных результата запроса
	 *
	 * @return mixed
	 * @access public
	 */
	function GetCell()
	{		
		if(is_array($this->_result)){
			$a=current($this->_result);
			if(is_array($a)){return current($a);}else{return false;}
		}
		if ($this->NumRows())
		{
			$a=@mysqli_fetch_row($this->_result);
			return $a[0];
		}
		return false;
	}


	/**
	 * Метод, предназначенный для перемещения внутреннего указателя в результате запроса
	 *
	 * @param int $id - номер ряда результатов запроса
	 * @return bool
	 * @access public
	 */
	function DataSeek($id = 0)
	{
		if(is_array($this->_result)){
			//не нашел как переместить указатель в массиве на конкретный
			reset($this->_result);
			for($x=0;$x==$id;$x++)next($this->_result);
			return $id; //эээ а что вернуть то надо было?
		}
		
		return @mysqli_data_seek($this->_result, $id);
	}

	/**
	 * Метод, преднязначенный для получения количества рядов результата запроса
	 *
	 * @return int
	 * @access public
	 */
	function NumRows()
	{
		if(is_array($this->_result)){
			return count($this->_result);
		}
		return @mysqli_num_rows($this->_result);
	}


	/**
	 * Метод, преднязначенный для получения количества полей результата запроса
	 *
	 * @return int
	 * @access public
	 */
	function NumFields()
	{
		if(is_array($this->_result)){
			$a=current($this->_result);
			return count($a);
		}
		return mysqli_num_fields($this->_result);
	}

	/**
	 * Метод, преднязначенный для получения названия указанной колонки результата запроса
	 *
	 * @param int $i - индекс колонки
	 * @return string
	 * @access public
	 */
	function FieldName($i)
	{
		if(is_array($this->_result)){
			$a=current($this->_result);
			$b=array_keys($a);
			return($b[$i]);
		}
		mysqli_field_seek($this->_result, $i);
		$field = mysqli_fetch_field($this->_result);
		return $field->name;
	}

	/**
	 * Метод, преднязначенный для освобождения памяти от результата запроса
	 *
	 * @return bool
	 * @access public
	 */
	function Close()
	{
		if(is_array($this->_result)){
			unset($this);
			return true;
		}
		$r = @mysqli_free_result($this->_result);
		unset($this);
		return $r;
	}
}

/**
 * Класс, предназначенный для работы непосредственно с MySQL БД
 */
class AVE_DB
{

/**
 *	Свойства класса
 */

	/**
	 * Идентификатор соединения с БД
	 *
	 * @var DbStateHandler
	 * @access private
	 */
	var $_handle;

	/**
	 * Список выполненных запросов
	 *
	 * @var array
	 * @access private
	 */
	var $_query_list;

	/**
	 * Метки времени до и после выполнения SQL-запроса
	 *
	 * @var array
	 * @access private
	 */
	var $_time_exec;


	/**
	 * Конструктор
	 *
	 * @param string $host	адрес сервера
	 * @param string $user	имя пользователя
	 * @param string $pass	пароль
	 * @param string $db	имя БД
	 * @return object		AVE_DB - объект
	 * @access public
	 */
	function AVE_DB($host, $user, $pass, $db)
	{
		// Пытаемся установить соединение с БД
		if (! $this->_handle = @mysqli_connect($host, $user, $pass))
		{
			$this->_error('connect');
			exit;
		}

		// Пытаемся выбрать БД
 		if (! @mysqli_select_db($this->_handle,$db))
		{
			$this->_error('select');
			exit;
		}

		// Устанавливаем кодировку
/*		if (function_exists('mysqli_set_charset'))
		{
			mysqli_set_charset($this->_handle, 'cp1251');
		}
		else*/
		{
			mysqli_query($this->_handle,"SET NAMES 'utf8'");
		}

		// Определяем профилирование
		if (defined('PROFILING') && PROFILING)
		{
//			mysqli_query($this->_handle, "QUERY_CACHE_TYPE = OFF");
//			mysqli_query($this->_handle, "FLUSH TABLES");
			if (mysqli_query($this->_handle, "SET PROFILING_HISTORY_SIZE = 100"))
			{
				mysqli_query($this->_handle,"SET PROFILING = 1");
			}
			else
			{
				define('SQL_PROFILING_DISABLE', 1);
			}
		}
	}

	/**
	 * Метод, предназначенный для получения функции из которой пришел запрос с ошибкой
	 *
	 * @return string
	 */
	function get_caller()
	{
		if (! function_exists('debug_backtrace')) return '';

		$stack = debug_backtrace();
		$stack = array_reverse($stack);

		$caller = array();
		foreach ((array)$stack as $call)
		{
			if (@$call['class'] == __CLASS__) continue;
			$function = $call['function'];
			if (isset($call['class']))
			{
				$function = $call['class'] . "->$function";
			}
			$caller[] = (isset($call['file']) ? '<strong>FILE:</strong> ' . $call['file'] . ' ' : '')
						. '<br /><strong>FUNCTION:</strong> ' . $function
						. (isset($call['line']) ? ' <br /><strong>LINE:</strong> ' . $call['line'] : '');
		}

		return implode(', ', $caller);
	}

	/**
	 * Метод, предназначенный для обработки ошибок
	 *
	 * @param string $type - тип ошибки (при подключении к БД или при выполнении SQL-запроса)
	 * @param string $query - текст SQL запроса вызвавшего ошибку
	 * @access private
	 */
	function _error($type, $query = '')
	{
		if ($type != 'query')
		{
			display_notice('Error ' . $type . ' MySQL database.');
		}
		else
		{
			$my_error = mysqli_error($this->_handle);

			reportLog('<strong class="code_red">SQL ERROR:</strong> ' . $my_error . PHP_EOL
					. "<br /><strong class=\"code\">QUERY:</strong> " . stripslashes($query) . PHP_EOL
					. "<br />"        . $this->get_caller() . PHP_EOL
					. "<br /><strong class=\"code\">URL:</strong> "   . HOST . $_SERVER['SCRIPT_NAME']
						            . '?' . $_SERVER['QUERY_STRING'] . PHP_EOL
			);

            // Если в настройках системы установлен параметр на отправку сообщений на e-mail, тогда
            if (SEND_SQL_ERROR)
			{
				// Формируем текст сообщения с ошибкой
                $mail_body = ('SQL ERROR: ' . $my_error . PHP_EOL
					. 'TIME: '  . date('d-m-Y, H:i:s') . PHP_EOL
					. 'URL: '   . HOST . $_SERVER['SCRIPT_NAME']
					            . '?' . $_SERVER['QUERY_STRING'] . PHP_EOL
					. $this->get_caller() . PHP_EOL
					. 'QUERY: ' . stripslashes($query) . PHP_EOL
				);

                // Отправляем сообщение
                send_mail(
					get_settings('mail_from'),
					$mail_body,
					'MySQL Error!',
					get_settings('mail_from'),
					get_settings('mail_from_name'),
					'text'
				);
			}
		}
	}

/**
 *	Внешние методы класса
 */

	/**
	 * Метод, предназначенный для выполнения запроса к MySQL
	 *
	 * @param string $query - текст SQL-запроса
	 * @param bool $log - записать ошибки в лог? по умолчанию включено
	 * @return object - объект с указателем на результат выполнения запроса
	 * @access public
	 */
	function Real_Query($query,$log=true)
	{
		//$this->_time_exec[] = microtime();
		$res = @mysqli_query($this->_handle,$query);
		//$this->_time_exec[] = microtime();
		//$this->_query_list[] = $query;
		if (!$res && $log) $this->_error('query', $query);

		return new AVE_DB_Result($res);
	}
	
	//чистим кеш
	function clearcache($cacheid){
		$cacheid=(substr($cacheid,0,3)=='doc' ? 'doc/'.intval(floor((int)substr($cacheid,4))/1000).'/'.(int)substr($cacheid,4) : $cacheid);
		$cache_dir=BASE_DIR.'/cache/sql/'.(trim($cacheid)>'' ? trim($cacheid).'/' : '');
		return rrmdir($cache_dir);
	}	
	/**
	 * Метод, предназначенный для выполнения запроса к MySQL и возвращение результата в виде асоциативного массива с поддержкой кеша
	 *
	 * @param string $query - текст SQL-запроса
	 * @param integer $TTL - время жизни кеша (-1 безусловный кеш)
	 * @param bool $log - записать ошибки в лог? по умолчанию включено
	 * @return array - асоциативный массив с результом запроса
	 * @access public
	 */
	function Query($query,$TTL=null,$cacheid='',$log=true){
		if(substr($cacheid,0,3)=='doc'){
			$cacheid=(int)str_replace('doc_','',$cacheid);
			$cacheid='doc/'.(floor($cacheid/1000)).'/'.$cacheid;
		}
		$result=Array();
		$TTL=strtoupper(substr(trim($query),0,6))=='SELECT' ? $TTL : null;
		if (defined('ACP')) $TTL=null;
		if($TTL && $TTL != "nocache"){
			$cache_file=md5($query);
			$cache_dir=BASE_DIR.'/cache/sql/'.(trim($cacheid)>'' ? trim($cacheid).'/' : substr($cache_file,0,2).'/'.substr($cache_file,2,2).'/'.substr($cache_file,4,2).'/');
			if(!file_exists($cache_dir))mkdir($cache_dir,0777,true);
			if(!(file_exists($cache_dir.$cache_file) && ($TTL==-1 ? true : @time()-@filemtime($cache_dir.$cache_file)<$TTL))){
				$res=$this->Real_Query($query,$log);
				while ($mfa=$res->FetchAssocArray())$result[]=$mfa;
				file_put_contents($cache_dir.$cache_file,serialize($result));
			}
			else{
				$result=unserialize(file_get_contents($cache_dir.$cache_file));
			}
		return new AVE_DB_Result($result);
		}
		else return $this->Real_Query($query,$log);
	}
	
	 /**
	 * Метод, предназначенный для экранирования специальных символов в строках для использования в выражениях SQL
	 *
	 * @param mixed $value - обрабатываемое значение
	 * @return mixed
	 * @access public
	 */
	function Escape($value)
	{
		if (!is_numeric($value))
		{
			$value = function_exists('mysql_real_escape_string')
				? mysql_real_escape_string($value, $this->_handle)
				: mysql_escape_string($value);
		}

		return $value;
	}

	/**
	 * Метод, предназначенный для возвращения ID записи, сгенерированной при последнем INSERT-запросе
	 *
	 * @return int
	 * @access public
	 */
	function InsertId()
	{
		return mysqli_insert_id($this->_handle);
	}

	/**
	 * Метод, предназначенный для формирования статистики выполнения SQL-запросов.
	 *
	 *
	 * @param string $type - тип запрашиваемой статистики
	 * <pre>
	 * Возможные значения:
	 *     list  - список выполненых зпаросов
	 *     time  - время исполнения зпросов
	 *     count - количество выполненных запросов
	 * </pre>
	 * @return mixed
	 * @access public
	 */
	function DBStatisticGet($type = '')
	{
		switch ($type)
		{
			case 'list':
				list($s_dec, $s_sec) = explode(' ', $GLOBALS['start_time']);
				$query_list = '';
				$nq = 0;
				$time_exec = 0;
				$arr = $this->_time_exec;
				$co = sizeof($arr);
				for ($it=0;$it<$co;)
				{
					list($a_dec, $a_sec) = explode(' ', $arr[$it++]);
					list($b_dec, $b_sec) = explode(' ', $arr[$it++]);
					$time_main = ($a_sec - $s_sec + $a_dec - $s_dec)*1000;
					$time_exec = ($b_sec - $a_sec + $b_dec - $a_dec)*1000;
					$query = sizeof(array_keys($this->_query_list, $this->_query_list[$nq])) > 1
						? "<span style=\"background-color:#ff9;\">" . $this->_query_list[$nq++] . "</span>"
						: $this->_query_list[$nq++];
					$query_list .= (($time_exec > 1) ? "<li style=\"color:#c00\">(" : "<li>(")
						. round($time_main) . " ms) " . $time_exec . " ms " . $query . "</li>\n";
				}

				return $query_list;
				break;

			case 'time':
				$arr = $this->_time_exec;
				$time_exec = 0;
				$co = sizeof($arr);
				for ($it=0;$it<$co;) {
					list($a_dec, $a_sec) = explode(" ", $arr[$it++]);
					list($b_dec, $b_sec) = explode(" ", $arr[$it++]);
					$time_exec += $b_sec - $a_sec + $b_dec - $a_dec;
				}

				return $time_exec;
				break;

			case 'count':
				return sizeof($this->_query_list);
				break;

			default:
				return '';
				break;
		}
	}

	/**
	 * Метод, предназначенный для формирования статистики выполнения SQL-запросов.
	 *
	 * @param string $type - тип запрашиваемой статистики
	 * <pre>
	 * Возможные значения:
	 *     list  - список выполненых зпаросов
	 *     time  - время исполнения зпросов
	 *     count - количество выполненных запросов
	 * </pre>
	 * @return mixed
	 * @access public
	 */
	function DBProfilesGet($type = '')
	{
		static $result, $list, $time, $count;

		if (!(defined('PROFILING') && PROFILING) || defined('SQL_PROFILING_DISABLE')) return false;

		if (!$result)
		{
			$list = "<table width=\"100%\">"
				. "\n\t<col width=\"20\">\n\t<col width=\"70\">";
			$result = mysqli_query($this->_handle, "SHOW PROFILES");
			while (list($qid, $qtime, $qstring) = @mysqli_fetch_row($result))
			{
				$time += $qtime;
			    $list .= "\n\t<tr>\n\t\t<td><strong>"
			    	. $qid
			    	. "</strong></td>\n\t\t<td><strong>"
			    	. number_format($qtime * 1, 6, ',', '')
			    	. "</strong></td>\n\t\t<td><strong>"
			    	. $qstring
			    	. "</strong></td>\n\t</tr>";
			    $res = mysqli_query($this->_handle, "
			    	SELECT STATE, FORMAT(DURATION, 6) AS DURATION
			    	FROM INFORMATION_SCHEMA.PROFILING
			    	WHERE QUERY_ID = " . $qid
			    );
				while (list($state, $duration) = @mysqli_fetch_row($res))
				{
				    $list .= "\n\t<tr>\n\t\t<td>&nbsp;</td><td>"
				    	. number_format($duration * 1, 6, ',', '')
				    	. "</td>\n\t\t<td>" . $state . "</td>\n\t</tr>";
				}
			}
			$time = number_format($time * 1, 6, ',', '');
			$list .= "\n</table>";
			$count = @mysqli_num_rows($result);
		}

		switch ($type)
		{
			case 'list':  return $list;  break;
			case 'time':  return $time;  break;
			case 'count': return $count; break;
		}

		return false;
	}

	/**
	 * Метод, предназначенный для получения информации о сервере MySQL
	 *
	 * @return string
	 * @access public
	 */
	function mysql_version()
	{
		return  mysqli_get_server_info($this->_handle);
	}
}

global $AVE_DB;	

// Еслине существует объекта по работе с БД
if (! isset($AVE_DB))
{
	// Подключаем конфигурационный файл с параметрами подключения
	require(BASE_DIR . '/inc/db.config.php');

	// Если параметры не указаны, прерываем работу
	if (! isset($config)) exit;

	// Если константа префикса таблиц не задана, принудительно определяем ее на основании параметров в файле db.config.php
	if (! defined('PREFIX')) define('PREFIX', $config['dbpref']);

	// Создаем объект для работы с БД
	$AVE_DB = new AVE_DB($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
	if($AVE_DB)
	{
		$updaters = (glob(BASE_DIR."/cache/*.update.php"));
		if ($updaters)
		{
			sort($updaters);
			foreach ($updaters as $v) {
				@eval('?>'.file_get_contents($v).'<?');
				@unlink($v);
				@reportLog($_SESSION['user_name'] . ' - Установил обновления (' . $v . ')', 2, 2);
			}
		}
	}
	unset($config);
}

?>