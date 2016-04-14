CREATE TABLE `%%PRFX%%_countries` (
  `Id` mediumint(5) unsigned NOT NULL auto_increment,
  `country_code` char(2) NOT NULL default 'RU',
  `country_name` char(50) NOT NULL,
  `country_status` enum('1','2') NOT NULL default '2',
  `country_eu` enum('1','2') NOT NULL default '2',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#inst#

CREATE TABLE `%%PRFX%%_document_fields` (
  `Id` int(10) unsigned NOT NULL auto_increment,
  `rubric_field_id` mediumint(5) unsigned NOT NULL default '0',
  `document_id` int(10) unsigned NOT NULL default '0',
  `field_number_value` int(11) NOT NULL DEFAULT '0',
  `field_value` longtext NOT NULL,
  `document_in_search` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`Id`),
  KEY `document_id` (`document_id`),
  KEY `rubric_field_id` (`rubric_field_id`,`document_in_search`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_document_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_document_remarks` (
  `Id` int(10) unsigned NOT NULL auto_increment,
  `document_id` int(10) unsigned NOT NULL default '0',
  `remark_first` enum('0','1') NOT NULL default '0',
  `remark_title` varchar(255) NOT NULL,
  `remark_text` text NOT NULL,
  `remark_author_id` int(10) unsigned NOT NULL default '1',
  `remark_published` int(10) unsigned NOT NULL default '0',
  `remark_status` enum('1','0') NOT NULL default '1',
  `remark_author_email` varchar(255) NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_document_rev` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `doc_revision` int(10) unsigned NOT NULL DEFAULT '0',
  `doc_data` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_documents` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rubric_id` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `document_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `document_alias` varchar(255) NOT NULL,
  `document_title` varchar(255) NOT NULL,
  `document_breadcrum_title` varchar(255) NOT NULL,
  `document_published` int(10) unsigned NOT NULL DEFAULT '0',
  `document_expire` int(10) unsigned NOT NULL DEFAULT '0',
  `document_changed` int(10) unsigned NOT NULL DEFAULT '0',
  `document_author_id` mediumint(5) unsigned NOT NULL DEFAULT '1',
  `document_in_search` enum('1','0') NOT NULL DEFAULT '1',
  `document_meta_keywords` text NOT NULL,
  `document_meta_description` text NOT NULL,
  `document_meta_robots` enum('index,follow','index,nofollow','noindex,nofollow') NOT NULL DEFAULT 'index,follow',
  `document_status` enum('1','0') NOT NULL DEFAULT '1',
  `document_deleted` enum('0','1') NOT NULL DEFAULT '0',
  `document_count_print` int(10) unsigned NOT NULL DEFAULT '0',
  `document_count_view` int(10) unsigned NOT NULL DEFAULT '0',
  `document_linked_navi_id` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `document_teaser` text NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `document_alias` (`document_alias`),
  KEY `rubric_id` (`rubric_id`),
  KEY `document_status` (`document_status`),
  KEY `document_published` (`document_published`),
  KEY `document_expire` (`document_expire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_module` (
  `Id` smallint(3) unsigned NOT NULL auto_increment,
  `ModuleName` char(50) NOT NULL,
  `ModuleStatus` enum('1','0') NOT NULL default '1',
  `ModuleAveTag` char(255) NOT NULL,
  `ModulePHPTag` char(255) NOT NULL,
  `ModuleFunction` char(255) NOT NULL,
  `ModuleIsFunction` enum('1','0') NOT NULL default '1',
  `ModuleSysName` char(50) NOT NULL,
  `ModuleVersion` char(20) NOT NULL default '1.0',
  `ModuleTemplate` smallint(3) unsigned NOT NULL default '1',
  `ModuleAdminEdit` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  UNIQUE KEY `ModuleName` (`ModuleName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_navigation` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `navi_titel` varchar(255) NOT NULL,
  `navi_level1` text NOT NULL,
  `navi_level2` text NOT NULL,
  `navi_level3` text NOT NULL,
  `navi_level1active` text NOT NULL,
  `navi_level2active` text NOT NULL,
  `navi_level3active` text NOT NULL,
  `navi_level1begin` text NOT NULL,
  `navi_level1end` text NOT NULL,
  `navi_level2begin` text NOT NULL,
  `navi_level2end` text NOT NULL,
  `navi_level3begin` text NOT NULL,
  `navi_level3end` text NOT NULL,
  `navi_begin` text NOT NULL,
  `navi_end` text NOT NULL,
  `navi_user_group` text NOT NULL,
  `navi_expand_ext` enum('0','1','2') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_navigation_items` (
  `Id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(255) NOT NULL,
  `parent_id` mediumint(5) unsigned NOT NULL,
  `navi_item_link` char(255) NOT NULL,
  `navi_item_target` enum('_blank','_self','_parent','_top') NOT NULL DEFAULT '_self',
  `navi_item_level` enum('1','2','3') NOT NULL DEFAULT '1',
  `navi_item_position` smallint(3) unsigned NOT NULL DEFAULT '1',
  `navi_id` smallint(3) unsigned NOT NULL DEFAULT '0',
  `navi_item_status` enum('1','0') NOT NULL DEFAULT '1',
  `document_alias` char(255) NOT NULL,
  `navi_item_Img` varchar(255) NOT NULL,
  `navi_item_desc` text NOT NULL,
  `navi_item_Img_id` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `navi_id` (`navi_id`),
  KEY `document_alias` (`document_alias`),
  KEY `navi_item_status` (`navi_item_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_request` (
  `Id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rubric_id` smallint(3) unsigned NOT NULL,
  `request_items_per_page` smallint(3) unsigned NOT NULL,
  `request_title` varchar(255) NOT NULL,
  `request_template_item` text NOT NULL,
  `request_template_main` text NOT NULL,
  `request_order_by` varchar(255) NOT NULL,
  `request_order_by_nat` int(10) NOT NULL DEFAULT '0',
  `request_author_id` int(10) unsigned NOT NULL DEFAULT '1',
  `request_created` int(10) unsigned NOT NULL,
  `request_description` tinytext NOT NULL,
  `request_asc_desc` enum('ASC','DESC') NOT NULL DEFAULT 'DESC',
  `request_show_pagination` enum('0','1') NOT NULL DEFAULT '0',
  `request_where_cond` text NOT NULL,
  `request_cache_lifetime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_request_conditions` (
  `Id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` smallint(3) unsigned NOT NULL,
  `condition_compare` char(30) NOT NULL,
  `condition_field_id` int(10) NOT NULL,
  `condition_value` char(255) NOT NULL,
  `condition_join` enum('OR','AND') NOT NULL DEFAULT 'OR',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_rubric_fields` (
  `Id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `rubric_id` smallint(3) unsigned NOT NULL,
  `rubric_field_alias` varchar(20) NOT NULL,
  `rubric_field_title` varchar(255) NOT NULL,
  `rubric_field_type` varchar(75) NOT NULL,
  `rubric_field_position` smallint(3) unsigned NOT NULL DEFAULT '1',
  `rubric_field_default` text NOT NULL,
  `rubric_field_template` text NOT NULL,
  `rubric_field_template_request` text NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `rubric_id` (`rubric_id`),
  KEY `rubric_field_type` (`rubric_field_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_rubric_permissions` (
  `Id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `rubric_id` smallint(3) unsigned NOT NULL,
  `user_group_id` smallint(3) unsigned NOT NULL,
  `rubric_permission` char(255) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `rubric_id` (`rubric_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_rubric_template_cache` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `hash` char(32) NOT NULL,
  `rub_id` smallint(3) NOT NULL,
  `grp_id` smallint(3) NOT NULL DEFAULT '2',
  `doc_id` int(10) NOT NULL,
  `wysiwyg` enum('0','1') NOT NULL DEFAULT '0',
  `expire` int(10) unsigned DEFAULT '0',
  `compiled` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rubric_id` (`rub_id`,`doc_id`,`wysiwyg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_rubrics` (
  `Id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rubric_title` varchar(255) NOT NULL,
  `rubric_alias` varchar(255) NOT NULL,
  `rubric_template` text NOT NULL,
  `rubric_template_id` smallint(3) unsigned NOT NULL DEFAULT '1',
  `rubric_author_id` int(10) unsigned NOT NULL DEFAULT '1',
  `rubric_created` int(10) unsigned NOT NULL DEFAULT '0',
  `rubric_docs_active` int(1) unsigned NOT NULL DEFAULT '1',
  `rubric_code_start` text NOT NULL,
  `rubric_code_end` text NOT NULL,
  `rubric_teaser_template` text NOT NULL,
  `rubric_admin_teaser_template` text NOT NULL,
  `rubric_header_template` text NOT NULL,
  `rubric_linked_rubric` varchar(255) NOT NULL DEFAULT '0',
  `rubric_description` text NOT NULL,
  `rubric_position` int(11) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`Id`),
  KEY `rubric_template_id` (`rubric_template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_sessions` (
  `sesskey` varchar(32) NOT NULL,
  `expiry` int(10) unsigned NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  `Ip` varchar(35) NOT NULL,
  `expire_datum` varchar(25) NOT NULL,
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`),
  KEY `expire_datum` (`expire_datum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#inst#

CREATE TABLE `%%PRFX%%_settings` (
  `Id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) NOT NULL,
  `mail_type` enum('mail','smtp','sendmail') NOT NULL DEFAULT 'mail',
  `mail_content_type` enum('text/plain','text/html') NOT NULL DEFAULT 'text/plain',
  `mail_port` smallint(3) unsigned NOT NULL DEFAULT '25',
  `mail_host` varchar(255) NOT NULL,
  `mail_smtp_login` varchar(255) NOT NULL,
  `mail_smtp_pass` varchar(255) NOT NULL,
  `mail_smtp_encrypt` varchar(255) DEFAULT NULL,
  `mail_sendmail_path` varchar(255) NOT NULL DEFAULT '/usr/sbin/sendmail',
  `mail_word_wrap` smallint(3) NOT NULL DEFAULT '50',
  `mail_from` varchar(255) NOT NULL,
  `mail_from_name` varchar(255) NOT NULL,
  `mail_new_user` text NOT NULL,
  `mail_signature` text NOT NULL,
  `page_not_found_id` int(10) unsigned NOT NULL DEFAULT '2',
  `message_forbidden` text NOT NULL,
  `navi_box` varchar(255) NOT NULL,
  `start_label` varchar(255) NOT NULL,
  `end_label` varchar(255) NOT NULL,
  `separator_label` varchar(255) NOT NULL,
  `next_label` varchar(255) NOT NULL,
  `prev_label` varchar(255) NOT NULL,
  `total_label` varchar(255) NOT NULL,
  `date_format` varchar(25) NOT NULL DEFAULT '%d.%m.%Y',
  `time_format` varchar(25) NOT NULL DEFAULT '%d.%m.%Y, %H:%M',
  `default_country` char(2) NOT NULL DEFAULT 'RU',
  `use_editor` int(1) unsigned NOT NULL DEFAULT '0',
  `use_doctime` enum('1','0') NOT NULL DEFAULT '1',
  `hidden_text` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#inst#

CREATE TABLE `%%PRFX%%_sysblocks` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `sysblock_name` varchar(255) NOT NULL,
  `sysblock_text` longtext NOT NULL,
  `sysblock_active` enum('0','1') NOT NULL DEFAULT '1',
  `sysblock_author_id` int(10) unsigned NOT NULL DEFAULT '1',
  `sysblock_created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#inst#

CREATE TABLE `%%PRFX%%_liveeditor` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `liveeditor_name` varchar(255) NOT NULL,
  `liveeditor_fields` mediumint(2) unsigned NOT NULL,
  `liveeditor_status` mediumint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#inst#

CREATE TABLE `%%PRFX%%_templates` (
  `Id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `template_title` varchar(255) NOT NULL,
  `template_text` longtext NOT NULL,
  `template_author_id` int(10) unsigned NOT NULL DEFAULT '1',
  `template_created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_user_groups` (
  `user_group` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `user_group_name` char(50) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `set_default_avatar` enum('1','0') NOT NULL DEFAULT '0',
  `default_avatar` char(255) NOT NULL,
  `user_group_permission` longtext NOT NULL,
  PRIMARY KEY (`user_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#inst#

CREATE TABLE `%%PRFX%%_users` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` char(32) NOT NULL,
  `email` char(100) NOT NULL,
  `street` char(100) NOT NULL,
  `street_nr` char(10) NOT NULL,
  `zipcode` char(15) NOT NULL,
  `city` char(100) NOT NULL,
  `phone` char(35) NOT NULL,
  `telefax` char(35) NOT NULL,
  `description` char(255) NOT NULL,
  `firstname` char(50) NOT NULL,
  `lastname` char(50) NOT NULL,
  `user_name` char(50) NOT NULL,
  `user_group` smallint(3) unsigned NOT NULL DEFAULT '4',
  `user_group_extra` char(255) NOT NULL,
  `reg_time` int(10) unsigned NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `last_visit` int(10) unsigned NOT NULL,
  `country` char(2) NOT NULL DEFAULT 'RU',
  `birthday` char(10) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `del_time` int(10) unsigned NOT NULL,
  `emc` char(32) NOT NULL,
  `reg_ip` char(20) NOT NULL,
  `new_pass` char(32) NOT NULL,
  `company` char(255) NOT NULL,
  `taxpay` enum('0','1') NOT NULL DEFAULT '0',
  `salt` char(16) NOT NULL,
  `new_salt` char(16) NOT NULL,
  `user_ip` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `user_group` (`user_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#

CREATE TABLE `%%PRFX%%_view_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;#inst#