<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) the authors
 * @package    Syscp.Modules
 * @subpackage Traffic
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: class_idna_convert_wrapper.php 425 2006-03-18 09:48:20Z martin $
 */

/**
 * The traffic hook implementation
 *
 * @package    Syscp.Modules
 * @subpackage Traffic
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @version    1.0
 */
class Syscp_Hooks_Traffic extends Syscp_BaseHook
{
	/**
	 * Filename of the file this hook is implemented in.
	 * Consider this variable to be class specific constant.
	 *
	 * @var    string
	 */
	protected $FILE;  // CONST later

	/**
	 * Classname of this class
	 * Consider this variable to be class specific constant.
	 *
	 * @var    string
	 */
	protected $CLASS; // CONST later

	/**
	 * Class Constructor
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 *
	 * @since   1.0
	 * @access  public
	 *
	 * @return  Syscp_Hooks_Traffic
	 */
	public function __construct()
	{
		$this->FILE  = 'lib/classes/Syscp/Hooks/Traffic.class.php';
		$this->CLASS = __CLASS__;
	}

	/**
	 * This method will be called by the traffic cronscript.
	 *
	 * The traffic cronscript simply triggers the OncalcTrafficAtCron
	 * hook, and this is an implementation of the hook.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 *
	 * @since   1.0
	 * @access  public
	 *
	 * @param   array  $params  Parameters to be used in this hook call
	 *
	 * @return  void
	 */
	public function OnCalcTrafficAtCron( $params = array() )
	{
		// load the config and db vars from our attributes
		$config = $this->_config;
		$db     = $this->_db;
		$log    = $this->_log;

		$log->setUsername('Hook_Traffic');

		$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
		            '-- cronCalcTraffic: Checking if we need to calculate traffic and diskspace...');
		// check if there has already been one traffic run today
		//if($config->get('system.last_traffic_run') != date('dmy'))
		//{
			$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
			            '-- cronCalcTraffic: Starting traffic accounting' );

			// store the timestamp of yesterday
			$yesterday = time()-(60*60*24);

			$admin_traffic = array();

			// select all customers from the database
			$query = 'SELECT * FROM `%s` ORDER BY `customerid` ASC';
			$query = sprintf( $query, TABLE_PANEL_CUSTOMERS );
print $query."\n";
			$result = $db->query( $query );
print $result."--\n";

			// iterate the customers
			while( false !== ($row = $db->fetchArray($result)) )
			{
				// generate HTTP traffic for the current customer
				$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
				            sprintf( '-- cronCalcTraffic: counting http traffic for %s',
				                     $row['loginname'] ) );

				// set http traffic to 0
				$httptraffic = 0;

				// call webalizer
				// $httptraffic = webalizer_hist($row['loginname'], $row['documentroot'].'/webalizer/', $row['loginname']);

				// search for speciallogfile domains of this customer
				//$query = 'SELECT `domain` ' .
				//         'FROM `%s` ' .
				//         'WHERE `customerid`     = \'%s\' ' .
				//         '  AND `parentdomainid` = 0 ' .
				//         '  AND `speciallogfile` = \'1\'';
				//$query = sprintf( $query, TABLE_PANEL_DOMAINS, $row['customerid'] );
				//$speciallogfiles_domains = $db->query( $query );

				// iterate the speciallogfile domains
				//while( true === ($speciallogfiles_domains_row = $db->fetch_array($speciallogfiles_domains)) )
				//{
				//	$httptraffic += webalizer_hist($row['loginname'].'-'.$speciallogfiles_domains_row['domain'], $row['documentroot'].'/webalizer/'.$speciallogfiles_domains_row['domain'].'/', $speciallogfiles_domains_row['domain']);
				//}



				// at this point the http traffic has been calculated and can be
				// found at $httptraffic

				// lets continue with the ftp traffic
				$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
				            sprintf( '-- cronCalcTraffic: counting ftp traffic for %s',
				                     $row['loginname'] ) );

				// init the ftp traffic variable
				$ftptraffic = array();

				// query the ftp table for current traffic values
				$query = 'SELECT SUM(`up_bytes`)   AS `up_bytes_sum`, ' .
				         '       SUM(`down_bytes`) AS `down_bytes_sum` ' .
				         'FROM `%s` ' .
				         'WHERE `customerid` = \'%s\'';
				$query = sprintf( $query, TABLE_FTP_USERS, $row['customerid'] );
				$ftptraffic = $db->query_first( $query );

				// we now have ftptraffic as an array with up_bytes_sum and down_bytes_sum
				// containing the full traffic generated

				// now the mailtraffic, which is not implemented, and therefor 0
				$mailtraffic = 0;

				// now we sum up the total traffic

				// check if yesterday was not the first of a month, so we can simply sum up
				if(date('d',$yesterday)!='01')
				{
					$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
					            sprintf( '--cronCalcTraffic: creating traffic sum for %s',
					                     $row['loginname'] ) );

					// query the old traffic the user currently has
					$query = 'SELECT SUM(`http`)     AS `http_sum`, ' .
					         '       SUM(`ftp_up`)   AS `ftp_up_sum`, ' .
					         '       SUM(`ftp_down`) AS `ftp_down_sum`, ' .
					         '       SUM(`mail`)     AS `mail_sum` ' .
					         'FROM `%s` ' .
					         'WHERE `year`       = \'%s\' ' .
					         '  AND `month`      = \'%s\' ' .
					         '  AND `day`        < \'%s\' ' .
					         '  AND `customerid` = \'%s\' ';
					$query = sprintf( $query, TABLE_PANEL_TRAFFIC,
					                          date('Y',$yesterday),
					                          date('m',$yesterday),
					                          date('d',$yesterday),
					                          $row['customerid'] );
					$oldtraffic = $db->query_first( $query );

					// lets calculate the new traffic
//					$new['http']     = $httptraffic + $oldtraffic['http_sum'];
					$new['ftp_up']   = ($ftptraffic['up_bytes_sum']/1024)-$oldtraffic['ftp_up_sum'];
					$new['ftp_down'] = ($ftptraffic['down_bytes_sum']/1024)-$oldtraffic['ftp_down_sum'];
					$new['mail']     = $mailtraffic-$oldtraffic['mail_sum'];
				}
				else
				{
					$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
					            sprintf( '--cronCalcTraffic: creating traffic (new month) sum for %s',
					                     $row['loginname'] ) );
//					$new['http']     = $httptraffic;
					$new['ftp_up']   = ($ftptraffic['up_bytes_sum']/1024);
					$new['ftp_down'] = ($ftptraffic['down_bytes_sum']/1024);
					$new['mail']     = $mailtraffic;
				}

				// at this point the traffic which has not yet been accounted is stored
				// in $new

				// now we need to add the new traffic to the reseller, this user belongs to
				// and initialize the value if it's currently not set
				if( !isset( $admin_traffic[$row['adminid']] ) )
				{
					$admin_traffic[$row['adminid']]['http']     = 0 ;
					$admin_traffic[$row['adminid']]['ftp_up']   = 0 ;
					$admin_traffic[$row['adminid']]['ftp_down'] = 0 ;
					$admin_traffic[$row['adminid']]['mail']     = 0 ;
				}

				// calculate the sum of all traffic the user has
				$new['all'] = $httptraffic
				            + ($ftptraffic['up_bytes_sum']/1024)
				            + ($ftptraffic['down_bytes_sum']/1024)
				            + $mailtraffic;
				// insert the new sum of traffic in the traffic table
				$query = 'REPLACE INTO `%s` ' .
				         '      ( `customerid`, `year`,   `month`,    `day`, ' .
				         '               `ftp_up`, `ftp_down`, `mail`) ' .
				         'VALUES( \'%s\',       \'%s\',   \'%s\',     \'%s\', ' .
				         '              \'%s\',   \'%s\',     \'%s\')';
				$query = sprintf( $query, TABLE_PANEL_TRAFFIC,
				                          $row['customerid'],
				                          date('Y',$yesterday),
				                          date('m',$yesterday),
				                          date('d',$yesterday),
				                          $new['ftp_up'],
				                          $new['ftp_down'],
				                          $new['mail'] );
				$db->query( $query );

				// add the users traffic to the resellers traffic
				$admin_traffic[$row['adminid']]['http']     += $httptraffic;
				$admin_traffic[$row['adminid']]['ftp_up']   += $ftptraffic['up_bytes_sum']/1024;
				$admin_traffic[$row['adminid']]['ftp_down'] += $ftptraffic['down_bytes_sum']/1024;
				$admin_traffic[$row['adminid']]['mail']     += $mailtraffic;

				// create a new traffic entry if we have the 1st of a month
				if(date('d')=='01')
				{
					$query = 'UPDATE `%s` ' .
					         'SET `up_bytes`   = \'0\', ' .
					         '    `down_bytes` = \'0\'  ' .
					         'WHERE `customerid` = \'%s\'';
					$query = sprintf( $query, TABLE_FTP_USERS, $row['customerid'] );
					$db->query( $query );
				}

				// we are now finished with the customer specific traffic, and have added
				// the customer traffic to an internal reseller traffic specific storage
				// array

				// we continue with the diskspace usage
				$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
				            sprintf( '-- cronCalcTraffic: counting diskspace for webs of %s',
				                     $row['loginname'] ) );

				// init the diskspace usage with zero
				$webspaceusage = 0;
				// execute du and catch the result
				$back = Syscp::exec('du -s "'.$row['homedir'].'"');
				// iterate the result
				foreach($back as $backrow)
				{
					// ... and convert the result row into an array
					$webspaceusage = explode(' ',$backrow);
				}
				// get the diskspace usage, and discard the rest of the output
				$webspaceusage = intval($webspaceusage['0']);
				// unset no longer needed variables
				unset($back);
				unset($backrow);


				// we continue with the diskspace usage regarding mail accounts
				$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
				            sprintf( '-- cronCalcTraffic: counting diskspace for mails of %s',
				                     $row['loginname'] ) );

				// init the mailspace usage with zero
				$emailusage = 0;
				// execute du and catch the result
				$path = $config->get('system.vmail_homedir');
				$path = str_replace('{LOGIN}', $row['loginname'], $path);
				$path = str_replace('{USERHOME}', $row['homedir'], $path);
				$path = makeCorrectDir($path);
				$back = Syscp::exec('du -s "'.$path.'"');
				// iterate the result
				foreach($back as $backrow)
				{
					// ... and convert the result rows into arrays
					$emailusage = explode(' ',$backrow);
				}
				// get the diskspace usage, and discard the rest of the output
				$emailusage = intval($emailusage['0']);
				// unset no longer needed variables
				unset($back);
				unset($backrow);

				// we continue as last with the diskspace usage regarding the mysql db's
				$log->info( Syscp_Handler_Log_Interface::FACILITY_USER,
				            sprintf( '-- cronCalcTraffic: counting diskspace for databases of %s',
				                     $row['loginname'] ) );

				// init the databasespace usage with zero
				$mysqlusage = 0;

				// select all databases for this customer
				$query = 'SELECT `databasename` ' .
				         'FROM `%s` ' .
				         'WHERE `customerid` = \'%s\'';
				$query = sprintf( $query, TABLE_PANEL_DATABASES, $row['customerid'] );
				$databases_result = $db->query( $query );

				// iterate the resulting databases
				while(true === ($database_row = $db->fetch_array($databases_result)) )
				{
					// get the database information regarding a specific customer database
					$query = 'SHOW TABLE STATUS FROM `%s`';
					$query = sprintf( $query, $database_row['databasename'] );
					$mysql_usage_result = $db_root->query( $query );

					// iterate the results and sum the diskspace up
					while(true === ($mysql_usage_row = $db_root->fetch_array($mysql_usage_result)) )
					{
						$mysqlusage += $mysql_usage_row['Data_length']
						            +  $mysql_usage_row['Index_length'];
					}
				}
				$mysqlusage=$mysqlusage/1024;

				// at this point we have the diskspace usage of mysql, mails and webs,
				// now we only need to store them in the customer record

				// generate total diskspace usage
				$diskusage = $webspaceusage
				           + $emailusage
				           + $mysqlusage;
				// ... and update the customer record
				$query = 'UPDATE `%s` ' .
				         'SET `diskspace_used` = \'%s\', ' .
				         '    `traffic_used`   = \'%s\' ' .
				         'WHERE `customerid` = \'%s\' ';
				$query = sprintf( $query, TABLE_PANEL_CUSTOMERS,
				                          $diskusage,
				                          $new['all'],
				                          $row['customerid'] );
				$db->query( $query );
			}

			// at this point we are finished with the customers traffic, and have the
			// $admin_traffic array filled with the sum of the customers traffic, so
			// we can update the admins/resellers and we are done.

			// we fetch all admins from the database
			$query  = 'SELECT `adminid` FROM `%s` ORDER BY `adminid` ASC';
			$query  = sprintf( $query, TABLE_PANEL_ADMINS );
			$result = $db->query( $query );

			// and iterate the admins we got as result
			while(true === ($row = $db->fetch_array($result)) )
			{
				// lets check if we have a traffic record for the current admin
				if( isset( $admin_traffic[$row['adminid']] ) )
				{
					// we have lets generate the sum of all traffic types for this
					// admin
					$admin_traffic[$row['adminid']]['all'] = $admin_traffic[$row['adminid']]['http']
					                                       + $admin_traffic[$row['adminid']]['ftp_up']
					                                       + $admin_traffic[$row['adminid']]['ftp_down']
					                                       + $admin_traffic[$row['adminid']]['mail'];
					// and put the results into the admin traffic table
					$query = 'REPLACE INTO `%s` ' .
					         '       ( `adminid`, `year`, `month`, `day`, ' .
					         '         `http`, `ftp_up`, `ftp_down`, `mail`) ' .
					         'VALUES ( \'%s\', \'%s\', \'%s\', \'%s\', ' .
					         '         \'%s\', \'%s\', \'%s\', \'%s\' )';
					$query = sprintf( $query, TABLE_PANEL_TRAFFIC_ADMINS,
					                          $row['adminid'],
					                          date('Y',$yesterday),
					                          date('m',$yesterday),
					                          date('d',$yesterday),
					                          $admin_traffic[$row['adminid']]['http'],
					                          $admin_traffic[$row['adminid']]['ftp_up'],
					                          $admin_traffic[$row['adminid']]['ftp_down'],
					                          $admin_traffic[$row['adminid']]['mail'] );
					$db->query( $query );

					// additionally put the results in the admin/reseller user record
					$query = 'UPDATE `%s` ' .
					         'SET `traffic_used` = \'%s\' ' .
					         'WHERE `adminid` = \'%s\' ';
					$query = sprintf( $query, TABLE_PANEL_ADMINS,
					                          $admin_traffic[$row['adminid']]['all'],
					                          $row['adminid'] );
					$db->query( $query );
				}
			}
			// ... and we need to store the successfull traffic run in the settings
			$query = 'UPDATE `%s` ' .
			         'SET `value` = \'%s\' ' .
			         'WHERE `settinggroup` = \'system\' ' .
			         '  AND `varname`      = \'last_traffic_run\' ';
			$query = sprintf( $query, TABLE_PANEL_SETTINGS, date('dmy') );
			$db->query( $query );
		}
	//}
}

?>