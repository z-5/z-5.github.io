<?php
/**
 * Creates directory
 *
 * @param  string  $path Path to create
 * @param  integer $mode Optional permissions
 * @return boolean Success
 */
function _mkdir($path, $mode = 0777)
{
	$old = umask(0);
	$res = @mkdir($path, $mode);
	umask($old);

	return $res;
}

/**
 * Creates directories recursively
 *
 * @param  string  $path Path to create
 * @param  integer $mode Optional permissions
 * @return boolean Success
 */
function rmkdir($path, $mode = 0777)
{
	return is_dir($path) || (mkdir(dirname($path), $mode) && _mkdir($path, $mode));
}
require(dirname(__FILE__).'/config.php');
define('MAX_SIZE', 600);

$allowedExt = array('jpg', 'jpeg', 'png', 'gif');

$imagefile=urldecode($_SERVER['REQUEST_URI']);

if($_REQUEST['thumb']){
	$imagefile='/'.
			rtrim(
				dirname($_REQUEST['thumb'])
				.'/'.THUMBNAIL_DIR.'/'
				.(str_replace(
					'.',
					(empty($_REQUEST['mode']) ? '-c' : '-'.$_REQUEST['mode']).((empty($_REQUEST['width'])&& empty($_REQUEST['height'])) ? '128' : intval(@$_REQUEST['width'])).'x'.((empty($_REQUEST['width'])&& empty($_REQUEST['height'])) ? '128' : intval(@$_REQUEST['height'])).'.',
					basename($_REQUEST['thumb'])
					)
				),
				'/');
}
$baseDir = str_replace("\\", "/", dirname(dirname(__FILE__)));
if(file_exists($baseDir.$imagefile)){
		$img_data = @getimagesize($baseDir.$imagefile);
		header('Content-Type:' . $img_data['mime'], true);
		header("Last-Modified: ".gmdate("D, d M Y H:i:s".filemtime($baseDir.$imagefile))." GMT");
		header("Content-Length: " . (string) filesize($baseDir.$imagefile), true);
		readfile($baseDir.$imagefile);
		exit;
	}
list(, $thumbPath) = explode('/' . UPLOAD_DIR . '/', dirname($imagefile), 2);
$lenThumbDir = strlen(THUMBNAIL_DIR);
if ($lenThumbDir && substr($thumbPath, -$lenThumbDir) != THUMBNAIL_DIR) exit(0);

$thumbPath = $baseDir . '/' . UPLOAD_DIR . '/' . $thumbPath;
$imagePath = $lenThumbDir ? dirname($thumbPath) : $thumbPath;

$thumbName = basename($imagefile);
$nameParts = explode('.', $thumbName);
$countParts = count($nameParts);

if ($countParts < 2 || !in_array(strtolower(end($nameParts)), $allowedExt)) exit(0);

$matches = array();
preg_match('/-(r|c|f|t)(\d+)x(\d+)(r)*$/i', $nameParts[$countParts-2], $matches);


if (!isset($matches[0]))
{
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
	exit(0);
}
if (isset($matches[4]))
{
	list($size, $method, $width, $height, $rotate) = $matches;
}
else
{
	list($size, $method, $width, $height) = $matches;
	$rotate = false;
}

$nameParts[$countParts-2] = substr($nameParts[$countParts-2], 0, -strlen($size));
$imageName = implode('.', $nameParts);

$save = true;
if (!file_exists("$imagePath/$imageName"))
{
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
	$imageName = 'noimage.gif';
	if (!file_exists("$imagePath/$imageName"))
	{
		$imagePath = $baseDir . '/' . UPLOAD_DIR . '/images';
	}
	if (!file_exists("$imagePath/$imageName")) exit(0);

	$save = false;
}

if ($width > MAX_SIZE) $width = MAX_SIZE;
if ($height > MAX_SIZE) $height = MAX_SIZE;

require $baseDir.'/class/class.thumbnail.php';

$thumb = new Image_Toolbox("$imagePath/$imageName");

switch ($method)
{
	case 'r':
		$thumb->newOutputSize((int)$width, (int)$height, 0, (boolean)$rotate);
		break;

	case 'c':
		$thumb->newOutputSize((int)$width, (int)$height, 1, (boolean)$rotate);
		break;

	case 'f':
		$thumb->newOutputSize((int)$width, (int)$height, 2, false, '#ffffff');
		break;
	case 't':
		$thumb->newOutputSize((int)$width, (int)$height, 3, false);
		break;
}

$thumb->output();

if ($save)
{
	if (!file_exists($thumbPath) && !mkdir($thumbPath, 0777)) exit(0);

	if ($thumb->save("$thumbPath/$thumbName"))
	{
		$old = umask(0);
		chmod("$thumbPath/$thumbName", 0777);
		umask($old);
	}
}

?>