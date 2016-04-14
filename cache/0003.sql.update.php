<?
$CheckSQL="SELECT rubric_code_start,rubric_code_end FROM ".PREFIX."_rubrics";
$UpdateSQL=Array();
$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_code_start`
	TEXT NOT NULL AFTER
		`rubric_docs_active`
  ";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_code_end`
	TEXT NOT NULL AFTER
		`rubric_code_start`
  ";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
 foreach($UpdateSQL as $v)
  $AVE_DB->Real_Query($v);
}
?>