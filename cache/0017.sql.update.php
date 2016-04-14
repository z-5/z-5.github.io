<?
$CheckSQL="SELECT ModuleName FROM ".PREFIX."_module";
$UpdateSQL=Array();

$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` DROP INDEX ModulName;";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `ModulName` `ModuleName` varchar(50);";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `Status` `ModuleStatus` enum('1','0') NOT NULL default '1';";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `CpEngineTag` `ModuleAveTag` varchar(255);";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `CpPHPTag` `ModulePHPTag` varchar(255);";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `ModulFunktion` `ModuleFunction` varchar(255);";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `IstFunktion` `ModuleIsFunction` enum('1','0') NOT NULL default '1';";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `ModulPfad` `ModuleSysName` varchar(50);";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `Version` `ModuleVersion` varchar(20) NOT NULL default '1.0';";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `Template` `ModuleTemplate` smallint(3) unsigned NOT NULL default '1';";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` CHANGE `AdminEdit` `ModuleAdminEdit` enum('0','1') NOT NULL default '0';";
$UpdateSQL[]="ALTER TABLE `".PREFIX."_module` ADD UNIQUE (`ModuleName`);";
	
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
	foreach($UpdateSQL as $v)
		$AVE_DB->Real_Query($v,false);
}

$CheckSQL="SELECT rubric_position FROM ".PREFIX."_rubrics";
$UpdateSQL=Array();

$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_admin_teaser_template`
	 text NOT NULL default '' AFTER
		`rubric_teaser_template`
	";

$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_linked_rubric`
	 int(11) NOT NULL DEFAULT '0' AFTER
		`rubric_header_template`
	";

$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_description`
	 text NOT NULL default '' AFTER
		`rubric_linked_rubric`
	";

$UpdateSQL[]="ALTER TABLE `".PREFIX."_rubrics`
	ADD
		`rubric_position`
	 int(11) NOT NULL DEFAULT '100' AFTER
		`rubric_description`
	";

$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
	foreach($UpdateSQL as $v)
		$AVE_DB->Real_Query($v,false);
}

?>