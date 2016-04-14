<?
$check = $AVE_DB->Real_Query("
	SELECT document_lang
	FROM " . PREFIX . "_documents
", false) -> _result;
if($check === false)
{
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_documents`
		ADD
			`document_tags` text NOT NULL
	");
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_documents`
		ADD
			`document_lang` varchar(5) NOT NULL DEFAULT 'ru'
	");
	$AVE_DB->Real_Query("
		ALTER TABLE `" . PREFIX . "_request`
		ADD
			`request_lang` enum('1','0') NOT NULL DEFAULT '0'
	");
	$AVE_DB->Real_Query("
		CREATE TABLE IF NOT EXISTS `".PREFIX."_settings_lang` (
		  `Id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
		  `lang_key` varchar(2) NOT NULL DEFAULT 'ru',
		  `lang_name` char(50) NOT NULL,
		  `lang_alias_pref` varchar(10) NOT NULL,
		  `lang_default` enum('1','0') NOT NULL DEFAULT '0',
		  `lang_status` enum('1','0') NOT NULL DEFAULT '0',
		  PRIMARY KEY (`Id`),
		  UNIQUE KEY `lang_key` (`lang_key`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;
	");


	$AVE_DB->Real_Query("
		INSERT INTO `".PREFIX."_settings_lang` (`Id`, `lang_key`, `lang_name`, `lang_alias_pref`, `lang_default`, `lang_status`) VALUES
		(1, 'ru', 'Русский', 'ru', '1', '1'),
		(2, 'en', 'English', 'en', '0', '0'),
		(3, 'ua', 'Ukraine', 'ua', '0', '0'),
		(4, 'de', 'Deutsch', 'de', '0', '0'),
		(5, 'it', 'Italian', 'it', '0', '0'),
		(6, 'fr', 'France', 'fr', '0', '0'),
		(7, 'sp', 'Spanish', 'sp', '0', '0'),
		(8, 'kz', 'Казахский', 'kz', '0', '0'),
		(9, 'by', 'Белорусский', 'by', '0', '0');

	");


	$AVE_DB->Real_Query("
		CREATE TABLE IF NOT EXISTS `".PREFIX."_document_tags` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `document_id` int(11) NOT NULL,
		  `tag` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `document_id` (`document_id`),
		  KEY `tag` (`tag`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=1 ;
		
	");
}
?>