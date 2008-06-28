ALTER TABLE `panel_admins` 
  ADD `firstname` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `title` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `company` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `street` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `zipcode` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `city` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `country` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `phone` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `fax` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `taxid` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `contract_date` DATE NOT NULL, 
  ADD `contract_number` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `contract_details` TEXT NOT NULL DEFAULT '', 
  ADD `included_domains_qty` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `included_domains_tld` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `additional_traffic_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `additional_traffic_unit` BIGINT( 30 ) NOT NULL DEFAULT '0', 
  ADD `additional_diskspace_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `additional_diskspace_unit` BIGINT( 30 ) NOT NULL DEFAULT '0', 
  ADD `taxclass` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `setup_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `interval_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `interval_length` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'm', 
  ADD `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0', 
  ADD `calc_tax` TINYINT( 1 ) NOT NULL DEFAULT '1', 
  ADD `term_of_payment` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `payment_every` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `payment_method` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `bankaccount_holder` TEXT NOT NULL DEFAULT '', 
  ADD `bankaccount_number` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `bankaccount_blz` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `bankaccount_bank` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `service_active` TINYINT( 1 ) NOT NULL DEFAULT '0', 
  ADD `servicestart_date` DATE NOT NULL,
  ADD `serviceend_date` DATE NOT NULL,
  ADD `lastinvoiced_date` DATE NOT NULL,
  ADD `lastinvoiced_date_traffic` DATE NOT NULL,
  ADD `lastinvoiced_date_diskspace` DATE NOT NULL,
  ADD `customer_categories_once` TEXT NOT NULL DEFAULT '', 
  ADD `customer_categories_period` TEXT NOT NULL DEFAULT '', 
  ADD `invoice_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_hosting` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_hosting_customers` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_domains` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_traffic` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_diskspace` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_other` DECIMAL( 10,2 ) NOT NULL DEFAULT '0'; 

ALTER TABLE `panel_customers` 
  ADD `taxid` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `title` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `country` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `additional_service_description` TEXT NOT NULL DEFAULT '', 
  ADD `contract_date` DATE NOT NULL, 
  ADD `contract_number` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `contract_details` TEXT NOT NULL DEFAULT '', 
  ADD `included_domains_qty` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `included_domains_tld` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `additional_traffic_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `additional_traffic_unit` BIGINT( 30 ) NOT NULL DEFAULT '0', 
  ADD `additional_diskspace_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `additional_diskspace_unit` BIGINT( 30 ) NOT NULL DEFAULT '0', 
  ADD `taxclass` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `setup_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `interval_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `interval_length` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'm', 
  ADD `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0', 
  ADD `calc_tax` TINYINT( 1 ) NOT NULL DEFAULT '1', 
  ADD `term_of_payment` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `payment_every` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `payment_method` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `bankaccount_holder` TEXT NOT NULL DEFAULT '', 
  ADD `bankaccount_number` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `bankaccount_blz` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `bankaccount_bank` VARCHAR( 255 ) NOT NULL DEFAULT '', 
  ADD `service_active` TINYINT( 1 ) NOT NULL DEFAULT '0', 
  ADD `servicestart_date` DATE NOT NULL,
  ADD `serviceend_date` DATE NOT NULL,
  ADD `lastinvoiced_date` DATE NOT NULL,
  ADD `lastinvoiced_date_traffic` DATE NOT NULL,
  ADD `lastinvoiced_date_diskspace` DATE NOT NULL,
  ADD `invoice_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_hosting` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_domains` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_traffic` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_diskspace` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `invoice_fee_other` DECIMAL( 10,2 ) NOT NULL DEFAULT '0'; 

ALTER TABLE `panel_domains` 
  ADD `add_date` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `registration_date` DATE NOT NULL, 
  ADD `taxclass` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `setup_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `interval_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
  ADD `interval_length` INT( 11 ) NOT NULL DEFAULT '0', 
  ADD `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'y', 
  ADD `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0', 
  ADD `service_active` TINYINT( 1 ) NOT NULL DEFAULT '0', 
  ADD `servicestart_date` DATE NOT NULL, 
  ADD `serviceend_date` DATE NOT NULL, 
  ADD `lastinvoiced_date` DATE NOT NULL;

CREATE TABLE  `billing_service_categories` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `category_name` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `category_classname` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_classfile` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_cachefield` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_rowcaption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_rowcaption_interval` VARCHAR( 255 ) NOT NULL DEFAULT ''
) TYPE = MYISAM ;

CREATE TABLE  `billing_service_categories_admins` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `category_name` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `category_mode` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `category_classname` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_classfile` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_cachefield` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_rowcaption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_rowcaption_interval` VARCHAR( 255 ) NOT NULL DEFAULT ''
) TYPE = MYISAM ;

CREATE TABLE  `billing_service_domains_templates` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `tld` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `valid_from` DATE NOT NULL,
 `valid_to` DATE NOT NULL,
 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `setup_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_length` INT( 11 ) NOT NULL DEFAULT '0',
 `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'y',
 `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0'
) TYPE = MYISAM ;

CREATE TABLE  `billing_service_other` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `customerid` INT( 11 ) NOT NULL DEFAULT '0',
 `templateid` INT( 11 ) NOT NULL DEFAULT '0',
 `service_type` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `caption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `caption_interval` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `quantity` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `setup_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_length` INT( 11 ) NOT NULL DEFAULT '0',
 `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'm',
 `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `service_active` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `servicestart_date` DATE NOT NULL,
 `serviceend_date` DATE NOT NULL,
 `lastinvoiced_date` DATE NOT NULL
) TYPE = MYISAM ;

CREATE TABLE  `billing_service_other_templates` (
 `templateid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `valid_from` DATE NOT NULL,
 `valid_to` DATE NOT NULL,
 `service_type` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `caption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `caption_interval` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `setup_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_length` INT( 11 ) NOT NULL DEFAULT '0',
 `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'm',
 `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0'
) TYPE = MYISAM ;

CREATE TABLE  `billing_taxclasses` (
 `classid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `classname` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `default` TINYINT( 1 ) NOT NULL DEFAULT '0'
) TYPE = MYISAM ;

CREATE TABLE  `billing_taxrates` (
 `taxid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `taxrate` DECIMAL( 4, 4 ) NOT NULL ,
 `valid_from` DATE NOT NULL
) TYPE = MYISAM ;

CREATE TABLE  `billing_invoices` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `customerid` INT( 11 ) NOT NULL DEFAULT '0',
 `xml` LONGTEXT NOT NULL DEFAULT '',
 `invoice_date` DATE NOT NULL,
 `state` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `state_change` INT( 11 ) NOT NULL DEFAULT '0',
 `invoice_number` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `total_fee_taxed` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00'
) TYPE = MYISAM ;

CREATE TABLE  `billing_invoices_admins` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `adminid` INT( 11 ) NOT NULL DEFAULT '0',
 `xml` LONGTEXT NOT NULL DEFAULT '',
 `invoice_date` DATE NOT NULL,
 `state` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `state_change` INT( 11 ) NOT NULL DEFAULT '0',
 `invoice_number` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `total_fee_taxed` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00'
) TYPE = MYISAM ;

CREATE TABLE  `billing_invoice_changes` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `customerid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `userid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `timestamp` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `key` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `action` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `interval` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `quantity` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `taxrate` DECIMAL( 4, 4 ) NOT NULL
) TYPE = MYISAM ;

CREATE TABLE  `billing_invoice_changes_admins` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `adminid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `userid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `timestamp` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `key` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `action` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `interval` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `quantity` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `taxrate` DECIMAL( 4, 4 ) NOT NULL
) TYPE = MYISAM ;

CREATE TABLE `panel_diskspace` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `customerid` int(11) unsigned NOT NULL default '0',
  `year` int(4) unsigned zerofill NOT NULL default '0000',
  `month` int(2) unsigned zerofill NOT NULL default '00',
  `day` int(2) unsigned zerofill NOT NULL default '00',
  `stamp` int(11) unsigned NOT NULL default '0',
  `webspace` bigint(30) unsigned NOT NULL default '0',
  `mail` bigint(30) unsigned NOT NULL default '0',
  `mysql` bigint(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `customerid` (`customerid`)
) TYPE=MyISAM ;

CREATE TABLE `panel_diskspace_admins` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `adminid` int(11) unsigned NOT NULL default '0',
  `year` int(4) unsigned zerofill NOT NULL default '0000',
  `month` int(2) unsigned zerofill NOT NULL default '00',
  `day` int(2) unsigned zerofill NOT NULL default '00',
  `stamp` int(11) unsigned NOT NULL default '0',
  `webspace` bigint(30) unsigned NOT NULL default '0',
  `mail` bigint(30) unsigned NOT NULL default '0',
  `mysql` bigint(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `adminid` (`adminid`)
) TYPE=MyISAM ;


INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (1, 'hosting', 10, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting', 'hosting_caption', 'hosting_rowcaption_setup', 'hosting_rowcaption_interval');
INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (2, 'domains', 20, 'domains', 'lib/billing_class_domains.php', 'invoice_fee_domains', 'domains_caption', 'domains_rowcaption_setup', 'domains_rowcaption_interval');
INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (3, 'traffic', 30, 'traffic', 'lib/billing_class_traffic.php', 'invoice_fee_traffic', 'traffic_caption', 'traffic_rowcaption_setup', 'traffic_rowcaption_interval');
INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (4, 'diskspace', 40, 'diskspace', 'lib/billing_class_diskspace.php', 'invoice_fee_diskspace', 'diskspace_caption', 'diskspace_rowcaption_setup', 'diskspace_rowcaption_interval');
INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (5, 'other', 50, 'other', 'lib/billing_class_other.php', 'invoice_fee_other', 'other_caption', 'other_rowcaption_setup', 'other_rowcaption_interval');

INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (1, 'hosting', 10, 0, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting', 'hosting_caption', 'hosting_rowcaption_setup', 'hosting_rowcaption_interval');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (2, 'hosting_customers', 20, 1, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting_customers', 'hosting_caption', 'hosting_rowcaption_setup_withloginname', 'hosting_rowcaption_interval_withloginname');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (3, 'domains', 30, 1, 'domains', 'lib/billing_class_domains.php', 'invoice_fee_domains', 'domains_caption', 'domains_rowcaption_setup', 'domains_rowcaption_interval');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (4, 'traffic', 40, 0, 'traffic', 'lib/billing_class_traffic.php', 'invoice_fee_traffic', 'traffic_caption', 'traffic_rowcaption_setup', 'traffic_rowcaption_interval');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (5, 'diskspace', 50, 0, 'diskspace', 'lib/billing_class_diskspace.php', 'invoice_fee_diskspace', 'diskspace_caption', 'diskspace_rowcaption_setup', 'diskspace_rowcaption_interval');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (6, 'other', 60, 1, 'other', 'lib/billing_class_other.php', 'invoice_fee_other', 'other_caption', 'other_rowcaption_setup', 'other_rowcaption_interval');

INSERT INTO `billing_taxclasses` (`classid`, `classname`, `default`) VALUES ( NULL, 'MwSt Deutschland', '1' );
INSERT INTO `billing_taxclasses` (`classid`, `classname`, `default`) VALUES ( NULL, 'MwSt Deutschland (reduziert)', '0' );
INSERT INTO `billing_taxrates` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 1, 0.1600, '0' );
INSERT INTO `billing_taxrates` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 1, 0.1900, '2007-01-01' );
INSERT INTO `billing_taxrates` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 2, 0.0700, '0' );

INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', '', 'billing;billing', 'billing.nourl', '100', 'customers_see_all', '0');
INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;openinvoices', 'billing_openinvoices.php', '110', 'customers_see_all', '0');
INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;openinvoices_admin', 'billing_openinvoices.php?mode=1', '115', 'customers_see_all', '0');
INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;invoices', 'billing_invoices.php', '120', 'customers_see_all', '0');
INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;invoices_admin', 'billing_invoices.php?mode=1', '125', 'customers_see_all', '0');
INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;other', 'billing_other.php', '130', 'customers_see_all', '0');
INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;taxclassesnrates', 'billing_taxrates.php', '140', 'customers_see_all', '0');
INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;domains_templates', 'billing_domains_templates.php', '150', 'customers_see_all', '0');
INSERT INTO `panel_navigation` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;other_templates', 'billing_other_templates.php', '160', 'customers_see_all', '0');

INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (NULL, 'billing', 'invoicenumber_count', '0');

