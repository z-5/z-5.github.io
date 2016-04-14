<?php

/**
 * AVE.cms - Системные блоки
 *
 * @package AVE.cms
 * @filesource
 */

/**
 * Обработка тега системного блока
 *
 * @param int $id идентификатор системного блока
 */
function parse_sysblock($id)
{
	global $AVE_DB, $AVE_Core;
	$gen_time = microtime();

	if (is_array($id)) $id = $id[1];

	if (is_numeric($id))
	{
		$eval_sysblock=false;
		if($id<0){
			$id=abs($id);
			$eval_sysblock=true;
		}
        $cache_file=BASE_DIR.'/cache/sql/sysblock-'.$id.'.cache';
        if(!file_exists(dirname($cache_file))) mkdir(dirname($cache_file),0766,true);
        if(file_exists($cache_file)) {
            $return=file_get_contents($cache_file);
       } else {
            $return = $AVE_DB->Query("
                SELECT sysblock_text
                FROM " . PREFIX . "_sysblocks
                WHERE id = '" . $id . "'
                LIMIT 1
            ")->GetCell();
            file_put_contents($cache_file,$return);
        }

		// парсим остальные теги основного шаблона
		$search = array(
			'[tag:mediapath]',
			'[tag:path]',
			'[tag:home]',
			'[tag:docid]',
			'[tag:breadcrumb]'
		);
		$replace = array(
			ABS_PATH . 'templates/' . THEME_FOLDER . '/',
			ABS_PATH,
			get_home_link(),
			get_current_document_id(),
			get_breadcrumb()
		);
		$return = str_replace($search, $replace, $return);
		$return = preg_replace_callback('/\[tag:request:(\d+)\]/', 'request_parse', $return);

		if($eval_sysblock)$return = eval2var('?'.'>' . $return . '<'.'?');

		$gen_time = microtime()-$gen_time;
		$GLOBALS['block_generate'][] = array('SYSBLOCK_'.$id=>$gen_time);

		return $return;
	}
}
?>