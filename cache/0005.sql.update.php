<?
$CheckSQL="SELECT rubric_docs_active FROM ".PREFIX."_rubrics";
$UpdateSQL=Array();
$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_docs_active`
	 int(1) NOT NULL default '1' AFTER
		`rubric_created`
	";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
 foreach($UpdateSQL as $v)
  $AVE_DB->Real_Query($v);
}
?>