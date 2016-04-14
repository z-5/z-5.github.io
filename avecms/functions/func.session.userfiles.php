<?php

ini_set('session.save_handler', 'user');

function sess_open($sess_save_path, $session_name){
  global $sess_save_path, $sess_session_name;
       
  $sess_save_path = BASE_DIR.'/session';
  $sess_session_name = $session_name;
  return(true);
}

function sess_close(){
  return(true);
}

function sess_folder(){
global $sess_session_id,$sess_save_path;
return $sess_save_path.'/'.mb_substr($sess_session_id,0,2);
}

function sess_read($id){
	global $sess_save_path, $sess_session_name, $sess_session_id;

	$sess_session_id=$id;
	$sess_dir=sess_folder();
	$sess_file = "$sess_dir/$id.sess";
	if(file_exists($sess_file) && (filemtime($sess_file)+SESSION_LIFETIME)<time()) sess_gc(SESSION_LIFETIME);
	if ($fp = @fopen($sess_file, "r")) {
		$sess_data = fread($fp, filesize($sess_file));
		return($sess_data);
	} else {
		return(""); // Здесь обязана возвращать "".
	}
}

function sess_write ($id, $sess_data) {
	global $sess_save_path, $sess_session_name, $sess_session_id;
	$sess_session_id=$id;
	$sess_dir=sess_folder();
	$sess_file = "$sess_dir/$id.sess";
	if(!file_exists($sess_dir))
		mkdir($sess_dir,0777,true);
	if ($fp = @fopen($sess_file, "w")) {
		return(fwrite($fp, $sess_data));
	} else {
		return(false);
	}
}

function sess_destroy ($id) {
	global $sess_save_path, $sess_session_name, $sess_session_id;

	$sess_session_id=$id;
	$sess_dir=sess_folder();
	$sess_file = "$sess_dir/$id.sess";
	return(@unlink($sess_file));
}

/*******************************************************************
 * ПРЕДУПРЕЖДЕНИЕ - Вам понадобится реализовать здесь какой-нибудь *
 * вариант утилиты уборки мусора. *
 *******************************************************************/
function sess_gc ($maxlifetime) {
	global $sess_save_path, $sess_session_id;

	$sess_dir=sess_folder();
	foreach (glob($sess_dir."/*.sess") as $filename) {
		if((filemtime($filename)+$maxlifetime)<time())unlink($filename);
	}
	return true;
}

session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");


// продолжить нормальное использование сессий

?>