<?
$CheckSQL="SELECT request_order_by_nat FROM ".PREFIX."_request";
$UpdateSQL=Array();
$UpdateSQL[]="ALTER TABLE `".PREFIX."_request`
	ADD
		`request_order_by_nat`
	 int(10) NOT NULL default '0' AFTER
		`request_order_by`
	";
$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
 foreach($UpdateSQL as $v)
  $AVE_DB->Real_Query($v);
}
?>