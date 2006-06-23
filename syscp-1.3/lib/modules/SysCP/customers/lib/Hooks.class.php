<?php

class Syscp_Customers_Hooks extends Syscp_BaseHook
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
		$this->FILE  = 'lib/modules/SysCP/customers/lib/Hooks.class.php';
		$this->CLASS = __CLASS__;
	}

	/**
	 * This method got called by OnCalcTrafficAtCron
	 *
	 * This method reads the traffic data of a customer from his traffic
	 * table and puts the sums of the traffic directly into the customers
	 * account row.
	 *
	 * @param array $params
	 */
	public function OnCalcTrafficAtCron($params = array())
	{
		$db     = $this->_db;
		$log    = $this->_log;
		$config = $this->_config;

		$today['d'] = date('d');
		$today['m'] = date('m');
		$today['y'] = date('Y');

		$query  = 'SELECT * FROM `'.TABLE_PANEL_CUSTOMERS.'` ';
		$result = $db->query($query);
		while (false !== ($customer = $db->fetchArray($result)))
		{
			$query = 'SELECT SUM(`http`) AS `http_sum`, '
			       . '       SUM(`mail`) AS `mail_sum`, '
			       . '       SUM(`ftp_up`) AS `ftp_up_sum`, '
			       . '       SUM(`ftp_down`) AS `ftp_down_sum` '
			       . 'FROM `'.TABLE_PANEL_TRAFFIC.'` '
			       . 'WHERE `customerid` = '.(int)$customer['customerid'].' '
			       . '  AND `month` = '.(int)$today['m'].' '
			       . '  AND `year`  = '.(int)$today['y'];
			$traffic = $db->queryFirst($query);
			$traffic = $traffic['http_sum']   + $traffic['mail_sum']
			         + $traffic['ftp_up_sum'] + $traffic['ftp_down_sum'];
			// And now we put the values into the customers account row
			$query = 'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` '
			       . 'SET `traffic_used` = '.$traffic.' '
			       . 'WHERE `customerid` = '.(int)$customer['customerid'];
			$db->query($query);
		}
	}

	public function OnCalcDiskusageAtCron($params = array())
	{
		$db     = $this->_db;
		$log    = $this->_log;
		$config = $this->_config;

		// Some parts of our script need a root connection, sadly these
		// parts are in a while-loop, that's why we start a root session
		// here and close it at the end of this method
		$dsn = sprintf('mysql://%s:%s@%s',
 		               $config->get('sql.root.user'),
		               $config->get('sql.root.password'),
       	               $config->get('sql.host'));
		$db_root = new Syscp_Handler_Database();
		$db_root->initialize(array('dsn'=>$dsn));

		$diskspace = 0;
		$output    = array();

		$query  = 'SELECT * FROM `'.TABLE_PANEL_CUSTOMERS.'` ';
		$result = $db->query($query);
		while (false !== ($customer = $db->fetchArray($result)))
		{
			$cmd = 'du -S '.$customer['homedir'];
			$output = Syscp::exec($cmd);
			// now lets generate a real usage array
			$usage = array();
			foreach($output as $line)
			{
				list($size, $path) = split("\t", $line);
				$path  = trim($path);
				$size  = trim($size);
				$usage[$path] = $size*1024; // du returns kbyte
			}
			// now we need to generate usage on the maildir
			$maildir = $config->get('system.vmail_homedir');
			$maildir = str_replace('{USERHOME}', $customer['homedir'], $maildir);
			$maildir = str_replace('{LOGIN}', $customer['loginname'], $maildir);
			$maildir = makeCorrectDir($maildir);
			// we need to check if the maildir has already been accounted
			if(!isset($usage[$maildir]))
			{
				$output = Syscp::exec('du -S '.$maildir);
				foreach($output as $line)
				{
					list($size, $path) = split("\t", $line);
					$path  = trim($path);
					$size  = trim($size);
					$usage[$path] = $size*1024; // du returns kbyte
				}
			}
			// and now we need to ensure the logfiles are not accounted!
			$query     = 'SELECT * '
			           . 'FROM `'.TABLE_PANEL_DOMAINS.'` '
			           . 'WHERE `customerid` = '.(int)$customer['customerid'];
			$domResult = $db->query($query);
			while (false !== ($domain = $db->fetchArray($domResult)))
			{
				// add the documentroot
				$docRoot = $domain['documentroot'];
				$output  = Syscp::exec('du -S '.$docRoot);
				foreach ($output as $line)
				{
					list($size, $path) = split("\t", $line);
					$path  = trim($path);
					$size  = trim($size);
					if(!isset($usage[$path]))
					{
						$usage[$path] = $size*1024; // du returns kbyte
					}
				}
				// check the error log
				$log = $domain['error_logfile'];
				$log = dirname($log);
				if (isset($usage[$log]))
				{
					unset($usage[$log]);
				}
				// check the access log
				$log = $domain['access_logfile'];
				$log = dirname($log);
				if (isset($usage[$log]))
				{
					unset($usage[$log]);
				}
			}

			// and the usage of the mysql databases are missing
			$query      = 'SELECT `databasename` '
			            . 'FROM `%s` '
			            . 'WHERE `customerid` = \'%s\'';
			$query      = sprintf( $query, TABLE_PANEL_DATABASES, $customer['customerid'] );
			$dbResult   = $db->query( $query );
			$mysqlusage = 0;
			// iterate the resulting databases
			while (false !== ($dbRow = $db->fetchArray($dbResult)) )
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
			$usage['MYSQL'] = $mysqlusage;

			// now we have everything we need, we can sum up the values in $usage
			foreach($usage as $size)
			{
				$diskspace += $size;
			}
			$query = 'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` '
			       . 'SET `diskspace_used` = '.(int)$diskspace.' '
			       . 'WHERE `customerid` = '.(int)$customer['customerid'];
			$db->query($query);
		}
	}
}