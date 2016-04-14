<?php
/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */
@define('APP_NAME', 'AVE.CMS 3.0RC2');
@define('APP_VERSION', '1.4');
@define('APP_INFO', '&copy; 2007-2013 <a target="_blank" href="http://www.ave-cms.ru/">Ave-Cms.Ru</a>');

$GLOBALS['CMS_CONFIG']['IDS_LIB'] = array('DESCR' =>'Использовать систему обнаружения вторжений IDS для параноиков<br/>(существенно снижает производительность)','default'=>false,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['REWRITE_MODE'] = array('DESCR' =>'Использовать ЧПУ Адреса вида index.php будут преобразованы в /home/','default'=>true,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['TRANSLIT_URL'] = array('DESCR' =>'Использовать транслит в ЧПУ адреса вида /страница/ поменяються на /page/','default'=>true,'TYPE'=>'bool','VARIANT'=>''); 
$GLOBALS['CMS_CONFIG']['URL_SUFF'] = array('DESCR' =>'Cуффикс ЧПУ','default'=>'','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['URL_YANDEX'] = array('DESCR' =>'Использовать для формирования ЧПУ API Яндекс Переводчика','default'=>false,'TYPE'=>'bool','VARIANT'=>'');

$themes = array();
foreach (glob(dirname(dirname(__FILE__))."/templates/*") as $filename) {
	if(is_dir($filename))$themes[]=basename($filename);
}
$GLOBALS['CMS_CONFIG']['DEFAULT_THEME_FOLDER'] = array('DESCR' =>'Тема публичной части','default'=>$themes[0],'TYPE'=>'dropdown','VARIANT'=>$themes);

$themes = array();
foreach (glob(dirname(dirname(__FILE__))."/admin/templates/*") as $filename) {
	if(is_dir($filename))$themes[]=basename($filename);
}
$GLOBALS['CMS_CONFIG']['DEFAULT_ADMIN_THEME_FOLDER'] = array('DESCR' =>'Тема панели администратора','default'=>$themes[0],'TYPE'=>'dropdown','VARIANT'=>$themes);

$GLOBALS['CMS_CONFIG']['DEFAULT_THEME_FOLDER_COLOR'] = array('DESCR' =>'Цвет панели администратора','default'=>'blue','TYPE'=>'dropdown','VARIANT'=>array('blue','green','grey','purple','red','orange'));
$GLOBALS['CMS_CONFIG']['ADMIN_MENU'] = array('DESCR' =>'Использовать плавующее боковое меню','default'=>true,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['ADMIN_FAVICON'] = array('DESCR' =>'Использовать для админки альтернативную admin.favicon.ico вместо favicon.ico','default'=>true,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['ADMIN_CAPTCHA'] = array('DESCR' =>'Использовать капчу при входе в админку','default'=>false,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['ADMIN_EDITMENU'] = array('DESCR' =>'Использовать всплывающие "Действия" в системе','default'=>true,'TYPE'=>'bool','VARIANT'=>'');

$GLOBALS['CMS_CONFIG']['ATTACH_DIR'] = array('DESCR' =>'Директория для хранения вложений','default'=>'cache/attachments','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['UPLOAD_DIR'] = array('DESCR' =>'Директория для хранения файлов','default'=>'uploads','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['UPLOAD_SHOP_DIR'] = array('DESCR' =>'Директория для хранения миниатюр Магазина','default'=>'uploads/shop','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['UPLOAD_GALLERY_DIR'] = array('DESCR' =>'Директория для хранения миниатюр Галерей','default'=>'uploads/gallery','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['THUMBNAIL_DIR'] = array('DESCR' =>'Директория для хранения миниатюр изображений','default'=>'thumbnail','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['GZ_DBDUMP'] = array('DESCR' =>'Создание резервной копии базы данных со сжатием','default'=>false,'TYPE'=>'bool','VARIANT'=>'');

$GLOBALS['CMS_CONFIG']['SESSION_SAVE_HANDLER'] = array('DESCR' =>'Хранить сессии в БД','default'=>true,'TYPE'=>'bool','VARIANT'=>'');

$GLOBALS['CMS_CONFIG']['SESSION_LIFETIME'] = array('DESCR' =>'Время жизни сессии (Значение по умолчанию 24 часа)','default'=>60*60*24,'TYPE'=>'integer','VARIANT'=>''); 
$GLOBALS['CMS_CONFIG']['COOKIE_LIFETIME'] = array('DESCR' =>'Время жизни cookie автологина (60*60*24*14 - 2 недели)','default'=>60*60*24*14,'TYPE'=>'integer','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['USERS_TIME_SHOW'] = array('DESCR' =>'Показывать кто был онлайн в течении: (Значение по умолчанию 24 часа)','default'=>60*60*24,'TYPE'=>'integer','VARIANT'=>''); 
$GLOBALS['CMS_CONFIG']['PROFILING'] = array('DESCR' =>'Вывод статистики и списка выполненых запросов','default'=>false,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['MEMORY_LIMIT_PANIC'] = array('DESCR' =>'Пытаться очистить память если выходит за пределы ("-1" выключенно) в Мегабайтах (увеличивается нагрузка на MySQL)','default'=>-1,'TYPE'=>'dropdown','VARIANT'=>array('-1','6','12','28','54','100'));
$GLOBALS['CMS_CONFIG']['SEND_SQL_ERROR'] = array('DESCR' =>'Отправка писем с ошибками MySQL','default'=>false,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['SMARTY_COMPILE_CHECK'] = array('DESCR' =>'Контролировать изменения tpl файлов После настройки сайта установить - false','default'=>true,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['PHP_DEBUGGING'] = array('DESCR' =>'Включить обработку ошибок PHP','default'=>false,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['SMARTY_DEBUGGING'] = array('DESCR' =>'Консоль отладки Smarty','default'=>false,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['SMARTY_USE_SUB_DIRS'] = array('DESCR' =>'Создание папок для кэширования Установите это в false если ваше окружение PHP не разрешает создание директорий от имени Smarty. Поддиректории более эффективны, так что используйте их, если можете.','default'=>true,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['CACHE_DOC_TPL'] = array('DESCR' =>'Кэширование скомпилированных шаблонов документов','default'=>true,'TYPE'=>'bool','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['CACHE_LIFETIME'] = array('DESCR' =>'Время жизни кэша (300 = 5 минут)','default'=>0,'TYPE'=>'integer','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['YANDEX_MAP_API_KEY'] = array('DESCR' =>'Yandex MAP API REY','default'=>'','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['GOOGLE_MAP_API_KEY'] = array('DESCR' =>'Google MAP API REY','default'=>'','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['Memcached_Server'] = array('DESCR' =>'Адрес Memcached сервера','default'=>'','TYPE'=>'string','VARIANT'=>'');
$GLOBALS['CMS_CONFIG']['Memcached_Port'] = array('DESCR' =>'Порт Memcached сервера','default'=>'','TYPE'=>'string','VARIANT'=>'');

$GLOBALS['CMS_CONFIG']['DB_EXPORT_TPL'] = array('DESCR' =>'Шаблон имени файла экспорта бд (%SERVER%,%DATE%,%TIME%)','default'=>'%SERVER%_DB_BackUP_%DATE%_%TIME%','TYPE'=>'string','VARIANT'=>'');

@include(dirname(dirname(__FILE__)).'/inc/config.inc.php');
foreach($GLOBALS['CMS_CONFIG'] as $k=>$v)
{
	if(!defined($k))
		define($k,$v['default']);
}
?>