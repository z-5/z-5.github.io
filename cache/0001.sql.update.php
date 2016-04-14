<?
$CheckSQL="SELECT count(*) FROM ".PREFIX."_document_keywords";
$UpdateSQL=Array();
$UpdateSQL[]="CREATE TABLE IF NOT EXISTS `".PREFIX."_document_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
 foreach($UpdateSQL as $v)
  $AVE_DB->Real_Query($v);
}
?>