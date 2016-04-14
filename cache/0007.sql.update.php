<?
$sql = $AVE_DB->Real_Query("SELECT Id FROM " . PREFIX . "_rubric_fields WHERE rubric_field_type='docfromrubcheck'");
while ($row = $sql->FetchRow()) {
	$sql2 = $AVE_DB->Real_Query("SELECT Id,field_value FROM " . PREFIX . "_document_fields WHERE rubric_field_id=" . $row->Id);
	while ($row2 = $sql2->FetchRow()) {
		$value = $row2->field_value;
		if(substr($value,0,1) == "a") {
			$value = implode(",",unserialize($value));
			$AVE_DB->Real_Query("UPDATE " . PREFIX . "_document_fields SET field_value='" . $value . "' WHERE Id=" . $row2->Id);
		}
	}
}
?>