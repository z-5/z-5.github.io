<?php

/**
 * AVE.cms
 *
 * Выводит и кэширует миниатюры изображений с заданными размерами.
 * При первом запросе формируется миниатюра и записывается на диск
 * для вывода готовой миниатюры при последующих запросах.
 * <pre>
 * Использование:
 * Вывод миниатюры шириной 120px и пропорциональной высотой
 * index.php?thumb=путь_к_изображению_от корня_сайта
 *
 * Вывод миниатюры шириной 200px и пропорциональной высотой
 * index.php?thumb=путь_к_изображению_от корня_сайта&width=200
 *
 * Вывод миниатюры высотой 200px и пропорциональной высотой
 * index.php?thumb=путь_к_изображению_от корня_сайта&height=200
 *
 * Вывод миниатюры шириной 200px и высотой 150px
 * index.php?thumb=путь_к_изображению_от корня_сайта&width=200&height=150
 * </pre>
 * @package AVE.cms
 * @filesource
 */

if (!defined('BASE_DIR')) exit;

// Если высота не в диапазоне от 10 до 600 (ну его нафиг такие миниатюры) устанавливаем = 0
$height = (isset($_REQUEST['height']) && is_numeric($_REQUEST['height']) &&
		$_REQUEST['height'] > 10 && $_REQUEST['height'] <= 600) ? (int)$_REQUEST['height'] : 0;

// Если ширина не в диапазоне от 10 до 800 (ну его нафиг такие миниатюры) устанавливаем = 0 или 120 если высота = 0
$width = (isset($_REQUEST['width']) && is_numeric($_REQUEST['width']) &&
		$_REQUEST['width'] > 10 && $_REQUEST['width'] <= 800) ? (int)$_REQUEST['width'] : (0 == $height ? 120 : 0);

// Удаляем непечатаемые символы и ведущий слэш
if (! empty($_REQUEST['thumb'])) $filename = ltrim(preg_replace('/[^\x20-\xFF]/', '', $_REQUEST['thumb']), '/');

// Формируем полный путь к оригиналу изображения
$file = BASE_DIR . '/' . ltrim($filename, '/');

if( isset( $_REQUEST['nocrop'] ) && ( $width && $height ) ){
    $img_data = @getimagesize($file);
    $aspectRatioOriginal = $img_data[0] / $img_data[1];
    $aspectRatio = $width / $height;
    
    if( $aspectRatio>$aspectRatioOriginal )
        $width = 0;
    elseif( $aspectRatio<$aspectRatioOriginal )
        $height = 0;
}

if( isset( $_REQUEST['nocropmin'] ) && ( $width && $height ) ){
    $img_data = @getimagesize($file);
    $aspectRatioOriginal = $img_data[0] / $img_data[1];
    $aspectRatio = $width / $height;
    
    if( $aspectRatio<$aspectRatioOriginal )
        $width = 0;
    elseif( $aspectRatio>$aspectRatioOriginal )
        $height = 0;
}


// Проверяем наличие изображения
if (! empty($filename) && file_exists($file))
{
	$filename = basename($file);
	$file_dir = dirname($file);

	// Формируем путь к миниатюре с учетом того что миниатюры должны храниться в папке thumbnail,
	// а имя файла миниатюры содержит размеры миниатюры (для хранения миниатюр с разными размерами)
	$thumb_file = $file_dir . '/thumbnail/th'
		. ($width ? '-w' . $width : '') . ($height ? '-h' . $height : '') . '-' . $filename;

	// Проверяем наличие миниатюры с нужными размерами
	if (file_exists($thumb_file)&&filemtime($thumb_file)>filemtime($file))
	{
		$img_data = @getimagesize($file);
		header('Content-Type:' . $img_data['mime'], true);
		header("Last-Modified: ".gmdate("D, d M Y H:i:s".filemtime($thumb_file))." GMT");
		header("Content-Length: " . (string) filesize($thumb_file), true);
		readfile($thumb_file);
		exit;
	}

	// Проверяем наличие папки для миниатюр и если её нет - создаём
	if (! file_exists($file_dir . '/thumbnail'))
	{
		$oldumask = umask(0);
		@mkdir($file_dir . '/thumbnail', 0777);
		umask($oldumask);
	}
}
elseif (! $file = realpath(BASE_DIR . '/uploads/images/noimage.gif'))
{
	exit;
}

define('IMAGE_TOOLBOX_DEFAULT_JPEG_QUALITY', 75);

require(BASE_DIR . '/class/class.thumbnail.php');

$img = new Image_Toolbox($file);

// если указано, что не обрезать, а помещать в установленные размеры, и при этом эти размеры явно заданы...

$img->newOutputSize($width, $height, 1, false);

//$img->addText('AVE.cms 2.09', BASE_DIR . '/inc/fonts/ft16.ttf', 16, '#709536', 'right -10', 'bottom -10');

header('Content-Type:' . $img_data['mime'], true);
header("Last-Modified: ".gmdate("D, d M Y H:i:s".filemtime($thumb_file))." GMT");
header("Content-Length: " . (string) filesize($thumb_file), true);

if (! empty($thumb_file))
{
	$img->save($thumb_file);
    $oldumask = umask(0);
	chmod($thumb_file, 0644);
    umask($oldumask);
}
$img->output();
?>