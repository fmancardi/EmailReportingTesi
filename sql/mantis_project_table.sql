delimiter $$

CREATE TABLE `mantis_project_table` (
  `id` int(7) unsigned NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `status` int(2) NOT NULL default '10',
  `enabled` int(1) NOT NULL default '1',
  `view_state` int(2) NOT NULL default '10',
  `access_min` int(2) NOT NULL default '10',
  `file_path` varchar(250) NOT NULL default '',
  `description` text NOT NULL,
  `category_id` int(10) unsigned NOT NULL default '1',
  `inherit_global` int(10) unsigned NOT NULL default '0',
  `mail_tag` varchar(100) default NULL,
  `mail_substr` varchar(2000) default NULL,
  `mail_manager` tinyint(4) NOT NULL default '0',
  `mail_tag_exclude` varchar(1000) default NULL,
  `mail_reply_to` varchar(1000) default NULL,
  `mail_reply_body` varchar(1000) default NULL,
  `mail_reply_to_enabled` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `id` (`id`),
  KEY `view_state` (`view_state`)
) ENGINE=MyISAM AUTO_INCREMENT=545 DEFAULT CHARSET=latin1$$

