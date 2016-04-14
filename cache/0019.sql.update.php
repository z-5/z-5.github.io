<?
$CheckSQL=$AVE_DB->Query("SHOW FIELDS FROM ".PREFIX."_rubrics where Field='rubric_linked_rubric'")->FetchRow();
$res=$CheckSQL;
if($res->Type=="int(11)"){
	$AVE_DB->Query("ALTER TABLE  `".PREFIX."_rubrics` CHANGE  `rubric_linked_rubric`  `rubric_linked_rubric` VARCHAR( 255 ) NOT NULL DEFAULT  '0'");
}
?>