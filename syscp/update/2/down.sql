ALTER TABLE `panel_ipsandports` DROP `default_vhostconf_domain`,
 DROP `ssl_key_file`,
 DROP `ssl_ca_file`,
 CHANGE `ssl_cert_file` `ssl_cert` TINYTEXT NULL DEFAULT NULL;
DELETE FROM `panel_settings` WHERE `settinggroup` = 'system' AND `varname` = 'ssl_key_file';
DELETE FROM `panel_settings` WHERE `settinggroup` = 'system' AND `varname` = 'ssl_ca_file';