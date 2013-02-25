ADD MYSQL LINES ETC
===================
**//NOTES:
**//Please note that these names are optimized for my database.
**//Please change: 'webid_' to your $DBPrefix.
**//You can find your $DBPrefix in your config.inc.php (which is located in the included folder of your root folder)

**ADD THESE LINES FROM TOP TO BOTTOM

**GROUPS
ALTER TABLE `webid_groups`  ADD `excludeuser` varchar(30000) NOT NULL DEFAULT '' AFTER `auto_join`
**SETTINGS
ALTER TABLE `webid_settings`  ADD `development` INT(2) NOT NULL DEFAULT '0' AFTER `version`
ALTER TABLE `webid_settings`  ADD `developmentversion` varchar(6) NOT NULL DEFAULT '1.0.6' AFTER `development`