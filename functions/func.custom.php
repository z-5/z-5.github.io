<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

function UtfDate($data){
    $data = iconv('windows-1251', 'utf-8', $data);
	return $data;
}

function RusDate($data){
	$data = strtr($data, array('January'=>'Января', 'February'=>'Февраля', 'March'=>'Марта', 'April'=>'Апреля', 'May'=>'Мая', 'June'=>'Июня', 'July'=>'Июля', 'August'=>'Августа', 'September'=>'Сентября', 'October'=>'Октября', 'November'=>'Ноября', 'December'=>'Декабря', 'Sunday'=>'Воскресенье', 'Monday'=>'Понедельник', 'Tuesday'=>'Вторник', 'Wednesday'=>'Среда', 'Thursday'=>'Четверг', 'Friday'=>'Пятница', 'Saturday'=>'Суббота',));
	return $data;
}
//rubric_id=X
function GetGeoRubric($id){
	return "(SELECT Id FROM ".PREFIX."_documents WHERE rubric_id=3 AND (Id=".intval($id)." OR document_parent=".intval($id)."))";
}
?>