<?
$CheckSQL="SELECT document_breadcrum_title FROM ".PREFIX."_documents";
$UpdateSQL=Array();
$UpdateSQL[]="ALTER TABLE `".PREFIX."_documents`
	ADD
		`document_breadcrum_title`
	 VARCHAR(255) NOT NULL AFTER
		`document_title`
	";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
 foreach($UpdateSQL as $v)
  $AVE_DB->Real_Query($v);
}
?>