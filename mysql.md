ADD MYSQL LINES ETC
===================
**//NOTES:
**//Please note that these names are optimized for my database.
**//Please change: 'webid_' to your $DBPrefix.
**//You can find your $DBPrefix in your config.inc.php (which is located in the included folder of your root directory)

**ADD THESE LINES FROM TOP TO BOTTOM

**SETTINGS(For Auc_Create_admin_mail)
ALTER TABLE  `webid_settings` ADD  `VIPemail` VARCHAR( 300 ) NOT NULL
ALTER TABLE  `webid_settings` ADD  `VIPemailStatuas` ENUM('y',  'n') NOT NULL DEFAULT  'y'
**SETTINGS(for realtimedata)
ALTER TABLE  `webid_settings` ADD  `realtimedata` ENUM('y',  'n') NOT NULL DEFAULT  'y' AFTER  `contractsmap`
**SETTINGS(for contract uploading)
ALTER TABLE `webid_settings`  ADD `contractsmap` ENUM('y',  'n') NOT NULL DEFAULT 'y' AFTER `maxpictures`
**GROUPS (for group email notifications)
ALTER TABLE `webid_groups`  ADD `excludeuser` varchar(30000) NOT NULL DEFAULT '' AFTER `auto_join`
**SETTINGS (for development versions)
ALTER TABLE `webid_settings`  ADD `development` INT(2) NOT NULL DEFAULT '0' AFTER `version`
ALTER TABLE `webid_settings`  ADD `developmentversion` varchar(6) NOT NULL DEFAULT '1.0.6' AFTER `development`