<?
$CheckSQL="SELECT id FROM ".PREFIX."_liveeditor";
$UpdateSQL=Array();

$UpdateSQL[]="CREATE TABLE IF NOT EXISTS `".PREFIX."_liveeditor` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `liveeditor_name` varchar(255) NOT NULL,
  `liveeditor_fields` mediumint(2) unsigned NOT NULL,
  `liveeditor_status` mediumint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";

$UpdateSQL[]="INSERT INTO `".PREFIX."_liveeditor` VALUES
  (1, 'Настройка редактора по умолчанию № 1', '1', '1'),
  (2, 'Настройка редактора по умолчанию № 2', '2', '1');
	";

$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
	foreach($UpdateSQL as $v)
		$AVE_DB->Real_Query($v,false);
}

?>