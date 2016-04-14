<?
$CheckSQL="SELECT rubric_field_alias FROM ".PREFIX."_rubric_fields";
$UpdateSQL=Array();

$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubric_fields`
	ADD
		`rubric_field_alias`
	 varchar(20) NOT NULL default '' AFTER
		`rubric_id`
	";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
	foreach($UpdateSQL as $v)
		$AVE_DB->Real_Query($v,false);
}

$CheckSQL="SELECT rubric_teaser_template FROM ".PREFIX."_rubrics";
$UpdateSQL=Array();

$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_teaser_template`
	 text NOT NULL default '' AFTER
		`rubric_code_end`
	";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
	foreach($UpdateSQL as $v)
		$AVE_DB->Real_Query($v,false);
}


$CheckSQL="SELECT rubric_header_template FROM ".PREFIX."_rubrics";
$UpdateSQL=Array();

$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_header_template`
	 text NOT NULL default '' AFTER
		`rubric_teaser_template`
	";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
	foreach($UpdateSQL as $v)
		$AVE_DB->Real_Query($v,false);
}
?>