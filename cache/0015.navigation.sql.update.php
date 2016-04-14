<?
$check = $AVE_DB->Real_Query("
	SELECT navi_expand_ext
	FROM " . PREFIX . "_navigation
",false) -> _result;

if($check === false)
{
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_navigation`
		ADD
			`navi_expand_ext` enum('0','1','2') default '1'
		AFTER
			`navi_expand`
	");
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_navigation_items`
		ADD
			`navi_item_Img` varchar(255) NOT NULL
	");
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_navigation_items`
		ADD
			`navi_item_desc` text NOT NULL
	");
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_navigation_items`
		ADD
			`navi_item_Img_id` varchar(50) NOT NULL
	");
	$sql = $AVE_DB->Real_Query("
		SELECT id, navi_expand
		FROM " . PREFIX . "_navigation
	");
	while ($row = $sql->FetchRow())
	{
		$AVE_DB->Real_Query("
			UPDATE " . PREFIX . "_navigation
			SET
				navi_expand_ext = " . $row->navi_expand . "
			WHERE
				id = " . $row->id . "
		");
	}
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_navigation`
		DROP COLUMN `navi_expand`
	");
}
?>