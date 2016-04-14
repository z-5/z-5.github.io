<?php

error_reporting(E_ALL); // Set E_ALL for debuging

if (function_exists('date_default_timezone_set')) {
	date_default_timezone_set('Europe/Moscow');
}

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
include_once '../../../../inc/config.php';
include_once '../../../../inc/config.inc.php';

function debug($o) {
	echo '<pre>';
	print_r($o);
}

// exit('{"api":"2.0","uplMaxSize":"16M","options":{"archives":{"create":[],"extract":[]},"copyOverwrite":true,"disabled":[],"path":"Home","separator":"/","tmbUrl":"/files/","url":"/files/tmb/"},"cwd":{"path":"Home","thumbUrl":"/files/","filesUrl":"/files/tmb/","dirs":1,"date":"14.07.2011","mime":"directory","hash":"SG9tZQ==","phash":"","name":"Home","read":1,"write":0,"rm":0},"files":[{"dirs":1,"date":"14.07.2011","mime":"directory","hash":"SG9tZQ==","phash":"","name":"Home","read":1,"write":0,"rm":0}]}');
/**
 * Simple logger function.
 * Demonstrate how to work with elFinder event api.
 *
 * @param  string        $cmd     command name
 * @param  object|array  $voumes  current volume or source/destination volumes list for command "paste"
 * @param  array         $return  command result
 * @return array
 * @author Dmitry (dio) Levashov
 **/
function logger($cmd, $voumes, $result) {
	$log = $cmd.': ['.date('d.m H:s').'] '.$voumes[0]->id().' ';
	
	if (isset($voumes[1])) {
		$log .= $voumes[1]->id().' ';
	}
	
	switch ($cmd) {
		case 'mkdir':
		case 'mkfile':
		case 'upload':
			$log .= $result['added'][0]['name'];
			break;
		case 'rename':
			$log .= 'from '.$result['removedDetails'][0]['name'].' to '.$result['added'][0]['name'];
			break;
		case 'duplicate':
			$log .= 'src: '.$result['src']['name'].' copy: '.$result['added'][0]['name'];
			break;
		case 'rm':
			$log .= $result['removedDetails'][0]['name'];
			break;

		default:
			$log = '';
	}
	if ($log && is_dir('../../../../cache/redactor') || @mkdir('../../../../cache/redactor')) {
		$fp = fopen('../../../../cache/redactor/log.txt', 'a');
		if ($fp) {
			fwrite($fp, $log."\n");
			fclose($fp);
		}
	}
	return $result;
}


/**
 * Simple logger function.
 * Demonstrate how to work with elFinder event api.
 *
 * @package elFinder
 * @author Dmitry (dio) Levashov
 **/
class elFinderSimpleLogger {
	
	/**
	 * Write log
	 *
	 * @param  string        $cmd     command name
	 * @param  object|array  $voumes  current volume or source/destination volumes list for command "paste"
	 * @param  array         $return  command result
	 * @return array
	 **/
	public function write($cmd, $voumes, $result) {
		$log = $cmd.': ['.date('d.m H:s').'] '.$voumes[0]->id().' ';

		if (isset($voumes[1])) {
			$log .= $voumes[1]->id().' ';
		}

		switch ($cmd) {
			case 'mkdir':
			case 'mkfile':
			case 'upload':
			case 'paste':
				$log .= $result['added'][0]['name'];
				break;
			case 'rename':
				$log .= 'from '.$result['removedDetails'][0]['name'].' to '.$result['added'][0]['name'];
				break;
			case 'duplicate':
				$log .= 'src: '.$result['src']['name'].' copy: '.$result['added'][0]['name'];
				break;
			case 'rm':
				$log .= $result['removedDetails'][0]['name'];
				break;

			default:
				$log = '';
		}
		if ($log && is_dir('../../../../cache/redactor') || @mkdir('../../../../cache/redactor')) {
			$fp = fopen('../../../../cache/redactor/log.txt', 'a');
			if ($fp) {
				fwrite($fp, $log."\n");
				fclose($fp);
			}
		}
		return $result;
		
	}
	
} // END class 


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path. Attention! This is path relative to volume root directory started with directory separator.
 * @return bool
 * @author Dmitry (dio) Levashov
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0
		? !($attr == 'read' || $attr == 'write')
		: $attr == 'read' || $attr == 'write';
}

/**
 * Access control example class
 *
 * @author Dmitry (dio) Levashov
 **/
class elFinderTestACL {
	
	/**
	 * make dotfiles not readable, not writable, hidden and locked
	 *
	 * @param  string  $attr  attribute name (read|write|locked|hidden)
	 * @param  string  $path  file path. Attention! This is path relative to volume root directory started with directory separator.
	 * @param  mixed   $data  data which seted in 'accessControlData' elFinder option
	 * @param  elFinderVolumeDriver  $volume  volume driver
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 **/
	public function fsAccess($attr, $path, $data, $volume) {
		
		if ($volume->name() == 'localfilesystem') {
			return strpos(basename($path), '.') === 0
				? !($attr == 'read' || $attr == 'write')
				: $attr == 'read' || $attr == 'write';
		}
		
		return true;
	}
	
} // END class 

$acl = new elFinderTestACL();

function validName($name) {
	return strpos($name, '.') !== 0;
}

$opts = array(
	'locale' => 'en_US.UTF-8',
	'bind' => array(
		'mkdir mkfile  rename duplicate upload rm paste' => array(new elFinderSimpleLogger(), 'write'), 
	),
	'debug' => true,

	'roots' => array(
		array(
			// 'id' => 'x5',
			'driver'  => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'    => '../../../../templates/'.DEFAULT_ADMIN_THEME_FOLDER,         // path to files (REQUIRED)
			'URL'     => '/'.DEFAULT_ADMIN_THEME_FOLDER.'/', // URL to files (REQUIRED)
			'alias'  => 'File system',
			'disabled' => array(),
			'acceptedName' => 'validName',
			'uploadAllow' => array('all'),
			'uploadDeny'  => array('all'),
			'uploadOrder' => 'deny,allow',
			'uploadOverwrite' => false,
			'uploadMaxSize' => '128m',
			'copyOverwrite' => false,
			'copyJoin' => true,
			'mimeDetect' => 'internal',
			'tmbCrop' => false,
			'imgLib' => 'gd',
			'utf8fix' => true,
			'attributes' => array(
				array(
					'pattern' => '/^\/\./',
					'read' => false,
					'write' => false,
					'hidden' => true,
					'locked' => true
				),
                array(
                    'pattern' => '/.tmb/',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => false
                ),
                array(
                    'pattern' => '/\.php$/',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => false
                ),
				array(
                    'pattern' => '/.quarantine/',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => false
                ),
                array(
                    'pattern' => '/\.htaccess$/',
                    'write' => false,
                    'locked' => false,
                    'hidden' => true
                ),
                array(
                    'pattern' => '/.uploader/',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => false
                ),
                array(
                    'pattern' => '/.temp/',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => false
                )
			),
		)
	)
	
);

header('Access-Control-Allow-Origin: *');
$connector = new elFinderConnector(new elFinder($opts), true);
$connector->run();