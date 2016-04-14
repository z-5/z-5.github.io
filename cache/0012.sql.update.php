<?
$check = $AVE_DB->Real_Query("
	SELECT mail_smtp_encrypt
	FROM " . PREFIX . "_settings
", false) -> _result;
if($check === false)
{
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_settings`
		ADD
			`mail_smtp_encrypt` varchar(255) NULL
		AFTER
			`mail_smtp_pass`
	");
}
?>