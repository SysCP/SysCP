<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
 */

define('TABLE_BILLING_INVOICES', 'billing_invoices');
define('TABLE_BILLING_INVOICES_ADMINS', 'billing_invoices_admins');
define('TABLE_BILLING_INVOICE_CHANGES', 'billing_invoice_changes');
define('TABLE_BILLING_INVOICE_CHANGES_ADMINS', 'billing_invoice_changes_admins');
define('TABLE_BILLING_SERVICE_CATEGORIES', 'billing_service_categories');
define('TABLE_BILLING_SERVICE_CATEGORIES_ADMINS', 'billing_service_categories_admins');
define('TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES', 'billing_service_domains_templates');
define('TABLE_BILLING_SERVICE_OTHER', 'billing_service_other');
define('TABLE_BILLING_SERVICE_OTHER_TEMPLATES', 'billing_service_other_templates');
define('TABLE_BILLING_TAXCLASSES', 'billing_taxclasses');
define('TABLE_BILLING_TAXRATES', 'billing_taxrates');
define('TABLE_FTP_GROUPS', 'ftp_groups');
define('TABLE_FTP_USERS', 'ftp_users');
define('TABLE_MAIL_USERS', 'mail_users');
define('TABLE_MAIL_VIRTUAL', 'mail_virtual');
define('TABLE_PANEL_ADMINS', 'panel_admins');
define('TABLE_PANEL_CUSTOMERS', 'panel_customers');
define('TABLE_PANEL_DATABASES', 'panel_databases');
define('TABLE_PANEL_DOMAINS', 'panel_domains');
define('TABLE_PANEL_HTACCESS', 'panel_htaccess');
define('TABLE_PANEL_HTPASSWDS', 'panel_htpasswds');
define('TABLE_PANEL_SESSIONS', 'panel_sessions');
define('TABLE_PANEL_SETTINGS', 'panel_settings');
define('TABLE_PANEL_TASKS', 'panel_tasks');
define('TABLE_PANEL_TEMPLATES', 'panel_templates');
define('TABLE_PANEL_TRAFFIC', 'panel_traffic');
define('TABLE_PANEL_TRAFFIC_ADMINS', 'panel_traffic_admins');
define('TABLE_PANEL_DISKSPACE', 'panel_diskspace');
define('TABLE_PANEL_DISKSPACE_ADMINS', 'panel_diskspace_admins');
define('TABLE_PANEL_NAVIGATION', 'panel_navigation');
define('TABLE_PANEL_LANGUAGE', 'panel_languages');
define('TABLE_PANEL_CRONSCRIPT', 'panel_cronscript');
define('TABLE_PANEL_IPSANDPORTS', 'panel_ipsandports');
define('TABLE_PANEL_TICKETS', 'panel_tickets');
define('TABLE_PANEL_TICKET_CATS', 'panel_ticket_categories');
define('TABLE_PANEL_LOG', 'panel_syslog');
define('TABLE_MAIL_AUTORESPONDER', 'mail_autoresponder');
define('TABLE_PANEL_PHPCONFIGS', 'panel_phpconfigs');
define('TABLE_APS_PACKAGES', 'aps_packages');
define('TABLE_APS_INSTANCES', 'aps_instances');
define('TABLE_APS_SETTINGS', 'aps_settings');
define('TABLE_APS_TASKS', 'aps_tasks');
define('TABLE_APS_TEMP_SETTINGS', 'aps_temp_settings');

// Billing constants

define('CONST_BILLING_INVOICESTATE_INVOICED', '0');
define('CONST_BILLING_INVOICESTATE_SENT', '1');
define('CONST_BILLING_INVOICESTATE_PAID', '2');
define('CONST_BILLING_INVOICESTATE_CANCELLED_NO_REINVOICE', '3');
define('CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICE_WITHOUT_CREDIT_NOTE', '4');
define('CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICE_WITH_CREDIT_NOTE', '5');
define('CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICED', '6');
define('CONST_BILLING_PAYMENTMETHOD_BANKTRANSFER', '0');
define('CONST_BILLING_PAYMENTMETHOD_DEBITCARD', '1');
define('CONST_BILLING_INTERVALPAYMENT_PREPAID', '0');
define('CONST_BILLING_INTERVALPAYMENT_POSTPAID', '1');

// APS constants

define('TASK_INSTALL', 1);
define('TASK_REMOVE', 2);
define('TASK_RECONFIGURE', 3);
define('TASK_UPGRADE', 4);
define('TASK_SYSTEM_UPDATE', 5);
define('TASK_SYSTEM_DOWNLOAD', 6);
define('INSTANCE_INSTALL', 1);
define('INSTANCE_TASK_ACTIVE', 2);
define('INSTANCE_SUCCESS', 3);
define('INSTANCE_ERROR', 4);
define('INSTANCE_UNINSTALL', 5);
define('PACKAGE_LOCKED', 1);
define('PACKAGE_ENABLED', 2);
$version = '1.2.19-svn41';

?>
