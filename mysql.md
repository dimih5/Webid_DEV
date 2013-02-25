ADD MYSQL LINES ETC
===================
**ADD THIS FROM TOP TO BOTTOM
ALTER TABLE `webid_settings`  ADD `development` INT(2) NOT NULL DEFAULT '0' AFTER `version`
ALTER TABLE `webid_settings`  ADD `developmentversion` varchar(6) NOT NULL DEFAULT '1.0.6' AFTER `development`