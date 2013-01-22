ALTER TABLE `mantis_dev`.`mantis_project_table` ADD COLUMN `mail_tag` VARCHAR(100) NULL  AFTER `inherit_global` ;
ALTER TABLE `mantis_dev`.`mantis_project_table` ADD COLUMN `mail_reply_to_cc` VARCHAR(1000) NULL  AFTER `mail_get_by_context` ;
