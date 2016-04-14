<?
$CheckSQL="SELECT * FROM ".PREFIX."_document_rev";
$UpdateSQL=Array();
$UpdateSQL[]="CREATE TABLE ".PREFIX."_document_rev (
	`Id` int(10) unsigned NOT NULL auto_increment,
	`doc_id` mediumint(5) unsigned NOT NULL default '0',
	`doc_revision` int(10) unsigned NOT NULL default '0',
	`doc_data` text NOT NULL,
	`user_id` int(11) NOT NULL default '0',
	PRIMARY KEY  (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
	foreach($UpdateSQL as $v)
		$AVE_DB->Real_Query($v,false);
}
?>