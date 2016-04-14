<?
$CheckSQL="SELECT count(*) FROM ".PREFIX."_view_count";
$UpdateSQL=Array();
$UpdateSQL[]="CREATE TABLE IF NOT EXISTS `".PREFIX."_view_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
 foreach($UpdateSQL as $v)
  $AVE_DB->Real_Query($v);
}
?>