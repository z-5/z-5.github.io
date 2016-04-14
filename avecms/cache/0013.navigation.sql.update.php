<?
$AVE_DB->Real_Query("
	DELETE
	FROM " . PREFIX . "_module
	WHERE ModulPfad = 'navigation' AND ModulFunktion = 'mod_navigation'
",false);
$AVE_DB->Query("UPDATE ".PREFIX."_rubrics SET rubric_template=REPLACE(rubric_template,'[mod_navigation:','[tag:navigation:') WHERE rubric_template LIKE '%[mod_navigation:%'"); 
$AVE_DB->Query("UPDATE ".PREFIX."_templates SET template_text=REPLACE(template_text,'[mod_navigation:','[tag:navigation:') WHERE template_text LIKE '%[mod_navigation:%'"); 
$AVE_DB->Query("UPDATE ".PREFIX."_document_fields SET field_value=REPLACE(field_value,'[mod_navigation:','[tag:navigation:') WHERE field_value LIKE '%[mod_navigation:%'"); 

?>