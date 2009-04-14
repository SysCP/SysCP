ALTER TABLE `panel_ipsandports` CHANGE `ssl_cert` `ssl_cert_file` VARCHAR( 255 ) NOT NULL,
 ADD `ssl_key_file` VARCHAR( 255 ) NOT NULL,
 ADD `ssl_ca_file` VARCHAR( 255 ) NOT NULL,
 ADD `default_vhostconf_domain` TEXT NOT NULL;
INSERT INTO `panel_settings` SET `settinggroup` = 'system', `varname` = 'ssl_key_file', `value` = '';
INSERT INTO `panel_settings` SET `settinggroup` = 'system', `varname` = 'ssl_ca_file', `value` = '';