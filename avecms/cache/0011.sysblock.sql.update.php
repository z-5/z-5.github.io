<?
$CheckSQL="SELECT * FROM ".PREFIX."_sysblocks";

$UpdateSQL=Array();
$UpdateSQL[]="CREATE TABLE ".PREFIX."_sysblocks (
  `id` mediumint(5) unsigned NOT NULL auto_increment,
  `sysblock_name` varchar(255) NOT NULL,
  `sysblock_text` longtext NOT NULL,
  `sysblock_active` enum('0','1') NOT NULL default '1',
  `sysblock_author_id` int(10) unsigned NOT NULL default '1',
  `sysblock_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$UpdateSQL[]="UPDATE ".PREFIX."_rubrics SET rubric_template=REPLACE(rubric_template,'[mod_sysblock:','[tag:sysblock:') WHERE rubric_template LIKE '%[mod_sysblock:%'"; 
$UpdateSQL[]="UPDATE ".PREFIX."_templates SET template_text=REPLACE(template_text,'[mod_sysblock:','[tag:sysblock:') WHERE template_text LIKE '%[mod_sysblock:%'"; 
$UpdateSQL[]="UPDATE ".PREFIX."_document_fields SET field_value=REPLACE(field_value,'[mod_sysblock:','[tag:sysblock:') WHERE field_value LIKE '%[mod_sysblock:%'"; 
$UpdateSQL[]="UPDATE ".PREFIX."_request SET request_template_item=REPLACE(request_template_item,'[mod_sysblock:','[tag:sysblock:') WHERE request_template_item LIKE '%[mod_sysblock:%'"; 
$UpdateSQL[]="UPDATE ".PREFIX."_request SET request_template_main=REPLACE(request_template_main,'[mod_sysblock:','[tag:sysblock:') WHERE request_template_main LIKE '%[mod_sysblock:%'"; 

$res=$AVE_DB->Real_Query($CheckSQL,false);
if($res->_result===false)
{
	foreach($UpdateSQL as $v)
		$AVE_DB->Real_Query($v);
	$res=$AVE_DB->Query("SELECT * FROM ".PREFIX."_modul_sysblock",false);
	if($res){
		while($row=$res->FetchRow()){
			$AVE_DB->Query("INSERT INTO ".PREFIX."_sysblocks 
				(
					id,
					sysblock_name,
					sysblock_text
				)VALUES(
					'".addslashes($row->id)."',
					'".addslashes($row->sysblock_name)."',
					'".addslashes($row->sysblock_text)."'
				)"
			);
		}
	}
}
?>