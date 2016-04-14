<?
$mail_new_user = $AVE_DB->Real_Query("
	SELECT mail_new_user
	FROM " . PREFIX . "_settings
	WHERE id=1
") -> FetchRow() -> mail_new_user;

$mail_new_user = str_replace(
	array("%KENNWORT%","%EMAILFUSS%"),
	array("%PASSWORD%","%EMAILSIGNATURE%"),
	$mail_new_user
);

$AVE_DB->Real_Query("
	UPDATE " . PREFIX . "_settings
	SET
	mail_new_user = '" . $mail_new_user . "'
	WHERE id=1
");
?>