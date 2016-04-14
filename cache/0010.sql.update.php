<?
$AVE_DB->Real_Query("
	UPDATE " . PREFIX . "_documents
	SET
	document_alias = '/'
	WHERE id=1
");
?>