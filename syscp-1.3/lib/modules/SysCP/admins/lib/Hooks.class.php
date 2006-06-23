<?php

class Syscp_Admins_Hooks extends Syscp_BaseHook
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
		$this->FILE  = 'lib/modules/SysCP/admins/lib/Hooks.class.php';
		$this->CLASS = __CLASS__;
	}

	/**
	 * This method got called by OnCalcTrafficAtCron
	 *
	 * This method does sum up the customers traffic of an admin of
	 * today and yesterday and inserts the result into the traffic
	 * table of the admins.
	 *
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

		$yesterday['d'] = date('d', time()-(24*60*60));
		$yesterday['m'] = date('m', time()-(24*60*60));
		$yesterday['y'] = date('Y', time()-(24*60*60));

		// fetch all admins from the database
		$query  = 'SELECT * FROM `'.TABLE_PANEL_ADMINS.'`';
		$result = $db->query($query);
		while (false !== ($admin = $db->fetchArray($result)))
		{
			// generate list of customers owned by this admin
			$query  = 'SELECT `customerid` '
			        . 'FROM `'.TABLE_PANEL_CUSTOMERS.'` '
			        . 'WHERE `adminid` = '.$admin['adminid'];
			$subRes = $db->query($query);
			$customerList = array();
			while(false !== ($row = $db->fetchArray($subRes)))
			{
				$customerList[] = $row['customerid'];
			}
			// we now have the list of customerid'S in customerList
			// and can generate the query to get the complete traffic
			// of yesterday
			if (sizeof($customerList > 0))
			{
				$query = 'SELECT SUM(`http`)     AS `http_sum`, '
				       . '       SUM(`mail`)     AS `mail_sum`, '
				       . '       SUM(`ftp_up`)   AS `ftp_up_sum`, '
				       . '       SUM(`ftp_down`) AS `ftp_down_sum` '
				       . 'FROM `'.TABLE_PANEL_TRAFFIC.'` '
				       . 'WHERE `customerid` IN ('.join($customerList,', ').') '
				       . '  AND `day`   = '.(int)$today['d'].' '
				       . '  AND `month` = '.(int)$today['m'].' '
				       . '  AND `year`  = '.(int)$today['y'].' ';
				$todayTraffic = $db->queryFirst($query);
				$query = 'SELECT SUM(`http`)     AS `http_sum`, '
				       . '       SUM(`mail`)     AS `mail_sum`, '
				       . '       SUM(`ftp_up`)   AS `ftp_up_sum`, '
				       . '       SUM(`ftp_down`) AS `ftp_down_sum` '
				       . 'FROM `'.TABLE_PANEL_TRAFFIC.'` '
				       . 'WHERE `customerid` IN ('.join($customerList,', ').') '
				       . '  AND `day`   = '.(int)$yesterday['d'].' '
				       . '  AND `month` = '.(int)$yesterday['m'].' '
				       . '  AND `year`  = '.(int)$yesterday['y'].' ';
				$yesterdayTraffic = $db->queryFirst($query);
			}
			else
			{
				$todayTraffic['http_sum']     = 0;
				$todayTraffic['mail_sum']     = 0;
				$todayTraffic['ftp_up_sum']   = 0;
				$todayTraffic['ftp_down_sum'] = 0;
				$yesterdayTraffic['http_sum']     = 0;
				$yesterdayTraffic['mail_sum']     = 0;
				$yesterdayTraffic['ftp_up_sum']   = 0;
				$yesterdayTraffic['ftp_down_sum'] = 0;
			}
			// at this moment we have the traffic all customers of this admin
			// produced yesterday and today in the todayTraffic and yesterdayTraffic
			// we now only need to put them into the traffic table of the admins
			// but first, we need to decide if the admin already has a traffic
			// entry for the day
			$query = 'SELECT * '
			       . 'FROM `'.TABLE_PANEL_TRAFFIC_ADMINS.'` '
			       . 'WHERE `adminid` = '.(int)$admin['adminid'].' '
			       . '  AND `day`   = '.(int)$today['d'].' '
			       . '  AND `month` = '.(int)$today['m'].' '
			       . '  AND `year`  = '.(int)$today['y'].' ';
			$row = false;
			$row = $db->queryFirst($query);
			if (isset($row['adminid']))
			{
				// there is a row, we only need to update the values
				$query = 'UPDATE `'.TABLE_PANEL_TRAFFIC_ADMINS.'` '
				       . 'SET `http` = '.(int)$todayTraffic['http_sum'].', '
				       . '    `mail` = '.(int)$todayTraffic['mail_sum'].', '
				       . '    `ftp_up` = '.(int)$todayTraffic['ftp_up_sum'].', '
				       . '    `ftp_down` = '.(int)$todayTraffic['ftp_down_sum'].' '
				       . 'WHERE `adminid` = '.(int)$admin['adminid'].' '
				       . '  AND `day`   = '.(int)$today['d'].' '
				       . '  AND `month` = '.(int)$today['m'].' '
				       . '  AND `year`  = '.(int)$today['y'].' ';
			}
			else
			{
				// there is no row, we need to insert the values
				$query = 'INSERT INTO `'.TABLE_PANEL_TRAFFIC_ADMINS.'` '
				       . 'SET `http`     = '.(int)$todayTraffic['http_sum'].', '
				       . '    `mail`     = '.(int)$todayTraffic['mail_sum'].', '
				       . '    `ftp_up`   = '.(int)$todayTraffic['ftp_up_sum'].', '
				       . '    `ftp_down` = '.(int)$todayTraffic['ftp_down_sum'].', '
				       . '    `adminid`  = '.(int)$admin['adminid'].', '
				       . '    `day`      = '.(int)$today['d'].', '
				       . '    `month`    = '.(int)$today['m'].', '
				       . '    `year`     = '.(int)$today['y'].' ';
			}
			$db->query($query);
			// and now we do the same with the traffic values from the day before
			// this is needed, because we may not have done this already, we do here
			// in the sense of better double done than never.
			$query = 'SELECT * '
			       . 'FROM `'.TABLE_PANEL_TRAFFIC_ADMINS.'` '
			       . 'WHERE `adminid` = '.(int)$admin['adminid'].' '
			       . '  AND `day`   = '.(int)$yesterday['d'].' '
			       . '  AND `month` = '.(int)$yesterday['m'].' '
			       . '  AND `year`  = '.(int)$yesterday['y'].' ';
			$row = false;
			$row = $db->queryFirst($query);
			if (isset($row['adminid']))
			{
				// there is a row, we only need to update the values
				$query = 'UPDATE `'.TABLE_PANEL_TRAFFIC_ADMINS.'` '
				       . 'SET `http` = '.(int)$yesterdayTraffic['http_sum'].', '
				       . '    `mail` = '.(int)$yesterdayTraffic['mail_sum'].', '
				       . '    `ftp_up` = '.(int)$yesterdayTraffic['ftp_up_sum'].', '
				       . '    `ftp_down` = '.(int)$yesterdayTraffic['ftp_down_sum'].' '
				       . 'WHERE `adminid` = '.(int)$admin['adminid'].' '
				       . '  AND `day`   = '.(int)$yesterday['d'].' '
				       . '  AND `month` = '.(int)$yesterday['m'].' '
				       . '  AND `year`  = '.(int)$yesterday['y'].' ';
			}
			else
			{
				// there is no row, we need to insert the values
				$query = 'INSERT INTO `'.TABLE_PANEL_TRAFFIC_ADMINS.'` '
				       . 'SET `http`     = '.(int)$yesterdayTraffic['http_sum'].', '
				       . '    `mail`     = '.(int)$yesterdayTraffic['mail_sum'].', '
				       . '    `ftp_up`   = '.(int)$yesterdayTraffic['ftp_up_sum'].', '
				       . '    `ftp_down` = '.(int)$yesterdayTraffic['ftp_down_sum'].', '
				       . '    `adminid`  = '.(int)$admin['adminid'].', '
				       . '    `day`      = '.(int)$yesterday['d'].', '
				       . '    `month`    = '.(int)$yesterday['m'].', '
				       . '    `year`     = '.(int)$yesterday['y'].' ';
			}
			$db->query($query);
		}
	}
}