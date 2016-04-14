<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @subpackage admin
 * @filesource
 */

ob_start();
ob_implicit_flush(0);

define('BASE_DIR', str_replace("\\", "/", dirname(dirname(__FILE__))));

require(BASE_DIR . '/inc/init.php');

if (!isset($_SESSION['user_id']))
{
	header('Location:index.php');
	exit;
}
$max_size = 128; // максимальный размер миниатюры
$thumb_size = '-t' . $max_size . 'x' . $max_size; // формат миниатюр
$images_ext =  array('jpg', 'jpeg', 'png', 'gif');

$upload_path = BASE_DIR . '/' . UPLOAD_DIR;

$theme = empty($_SESSION['admin_theme']) ? DEFAULT_ADMIN_THEME_FOLDER : $_SESSION['admin_theme'];
$lang = empty($_SESSION['admin_language']) ? 'ru' : $_SESSION['admin_language'];

$AVE_Template = new AVE_Template(BASE_DIR . '/admin/templates/' . $theme . '/browser');
$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $lang . '/main.txt');
$AVE_Template->assign('tpl_dir', 'templates/' . $theme);
$AVE_Template->assign('ABS_PATH', '../');


if (!isset($_REQUEST['action'])) $_REQUEST['action'] = '';

switch ($_REQUEST['action'])
{
	case 'list':
		$dir = (empty($_REQUEST['dir'])
			|| strpos($_REQUEST['dir'], '..') !== false
			|| strpos($_REQUEST['dir'], '//') !== false) ? '/' : $_REQUEST['dir'];

		$path = $upload_path . (is_dir($upload_path . $dir) ? $dir : '/');

		$new_dir = $path . (isset($_REQUEST['newdir']) ? $_REQUEST['newdir'] : '');
		$new_dir_rezult = (!is_dir($new_dir) && !mkdir($new_dir, 0777));

		$skip_entry = array(THUMBNAIL_DIR, 'index.php');

		$dirs = array();
		$files = array();

		$d = @dir($path);
		while (false !== ($entry = @$d->read()))
		{
			if (in_array($entry, $skip_entry) || $entry{0} === '.') continue;

			if (is_dir($path . $entry))
			{
				$dirs[$entry] = 'browser.php?typ=' . $_REQUEST['typ']
					. '&amp;action=list&amp;dir=' . $dir . $entry . '/';
			}
			else
			{
				$nameParts = explode('.', $entry);
				$ext = strtolower(end($nameParts));

				$file['icon'] = file_exists("templates/{$theme}/images/mediapool/{$ext}.gif") ? $ext : 'attach';
				$file['filesize'] = @round(@filesize($path . $entry)/1024, 2);
				$file['moddate'] = date("d.m.y, H:i", @filemtime($path . $entry));

				if (in_array($ext, $images_ext))
				{
					$nameParts[count($nameParts)-2] .= $thumb_size;
					$file['bild'] = '../' . UPLOAD_DIR . $dir . THUMBNAIL_DIR . '/' . implode('.', $nameParts);
				}
				else
				{
					$file['bild'] = 'templates/' . $theme . '/images/file.gif';
				}

				$files[$entry] = $file;
			}
		}
		$d->close();

		ksort($dirs);
		ksort($files);

		$AVE_Template->assign('new_dir_rezult', $new_dir_rezult);
		$AVE_Template->assign('recycled', strpos($dir, '/recycled/') === 0);
		$AVE_Template->assign('dirs', $dirs);
		$AVE_Template->assign('files', $files);
		$AVE_Template->assign('max_size', $max_size);
		$AVE_Template->assign('dir', $dir);
		$AVE_Template->assign('dirup', rtrim(dirname($dir), '\\/') . '/');
		$AVE_Template->assign('mediapath', UPLOAD_DIR);

		$AVE_Template->display('browser.tpl');
		break;

	case 'upload':
		$AVE_Template->display('browser_upload.tpl');
		break;

	case 'upload2':
		/*
		$img_types = array('image/jpeg', 'image/png', 'image/gif');

		$w = (int)$_REQUEST['w'];
		$h = (int)$_REQUEST['h'];

		$resize = ($w != 0 || $h != 0);

		if ($resize) include(BASE_DIR . '/class/class.thumbnail.php');

		$dst_path = $upload_path . $_REQUEST['pfad'];

		for ($i=0;$i<count($_FILES['upfile']['tmp_name']);$i++)
		{
			$d_temp = $_FILES['upfile']['tmp_name'][$i];
			$d_name = strtolower(trim($_FILES['upfile']['name'][$i]));
			$nameParts = explode('.', $d_name);
			$ext = array_pop($nameParts);
			$d_name = implode('.', array(prepare_fname(implode('.', $nameParts)), $ext));

			while (file_exists($dst_path . $d_name ))
			{
				$nameParts = explode('.', $d_name);
				$nameParts[count($nameParts)-2] .= '-' . uniqid(rand());
				$d_name = implode('.', $nameParts);
			}

			if (! @move_uploaded_file($d_temp, $dst_path . $d_name)) continue;

			$old = umask(0);
			$rez = @chmod($dst_path . $d_name, 0777);
			umask($old);

			if ($resize && $rez && in_array($_FILES['upfile']['type'][$i], $img_types))
			{
				$image = new Image_Toolbox($dst_path . $d_name);

				$image->newOutputSize(max($w, $h), 0, 0, true);

				if ($image->save($dst_path . $d_name))
				{
					$old = umask(0);
					@chmod($dst_path . $d_name, 0777);
					umask($old);
				}
			}

			reportLog($_SESSION['user_name'] . ' - загрузил файл ('
				. UPLOAD_DIR . stripslashes($_REQUEST['pfad']) . $d_name. ')');
		}*/

		echo '<script type="text/javascript">
window.opener.parent.frames[\'zf\'].location.href = window.opener.parent.frames[\'zf\'].location.href;
window.close();
</script>';
		break;

	case 'delfile':
		if (check_permission('mediapool_del'))
		{
			if (empty($_REQUEST['file']) || empty($_REQUEST['dir'])) exit(0);

			$file_name = basename($_REQUEST['file']);

			$del_file = $upload_path . $_REQUEST['dir'] . $file_name;
			if (strpos($del_file, '..') !== false || !is_file($del_file)) exit(0);

			$recycled_path = $upload_path . '/recycled/';
			if (!is_dir($recycled_path) && !mkdir($recycled_path)) exit(0);

			do {$nameParts = explode('.', $file_name);
				$nameParts[count($nameParts)-2] .= '-' . uniqid(rand());
				$recycled_file_name = implode('.', $nameParts);
			} while (file_exists($recycled_path . $recycled_file_name));

			@copy($del_file, $recycled_path . $recycled_file_name);

			if (@unlink($del_file))
			{
				$nameParts = explode('.', $file_name);
				$ext = strtolower(end($nameParts));
				if (in_array($ext, $images_ext))
				{
					$nameParts[count($nameParts)-2] .= $thumb_size;
					@unlink($upload_path . $_REQUEST['dir'] . THUMBNAIL_DIR . '/' . implode('.', $nameParts));
				}

				reportLog($_SESSION['user_name'] . ' - удалил файл ('
					. UPLOAD_DIR . $_REQUEST['dir'] . $file_name  . ')');
			}
		}

		echo '<script type="text/javascript">
parent.frames[\'zf\'].location.href="browser.php?typ=', $_REQUEST['typ'], '&action=list&dir=', $_REQUEST['dir'], '";
</script>';
		break;

	default:
		@list($target, $target_id) = explode('__', $_REQUEST['target']);
		$tval = '/';
		if (!empty($_REQUEST['tval']) && 0 === strpos($_REQUEST['tval'], UPLOAD_DIR . '/'))
		{
			if (realpath(BASE_DIR . '/' . $_REQUEST['tval']))
			{
				$tval = rtrim(dirname(substr($_REQUEST['tval'], strlen(UPLOAD_DIR))), '\\/') . '/';
			}
		}

		$AVE_Template->assign('dir', $tval);
		$AVE_Template->assign('target', $target);
		$AVE_Template->assign('target_id', $target_id);
		$AVE_Template->assign('cppath', substr($_SERVER['PHP_SELF'], 0, -18));
		$AVE_Template->assign('mediapath', UPLOAD_DIR);

		$AVE_Template->display('browser_2frames.tpl');
		break;
}
?>
