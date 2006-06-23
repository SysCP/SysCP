<?php

class Syscp_Apache2_Hooks extends Syscp_BaseHook
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
		$this->FILE  = 'lib/modules/SysCP/Apache2/lib/Hooks.class.php';
		$this->CLASS = __CLASS__;
	}

	/**
	 * This method got called by OnCalcTrafficAtCron
	 *
	 * @param array $params
	 */
	public function OnCalcTrafficAtCron($params = array())
	{
		$db     = $this->_db;
		$log    = $this->_log;
		$config = $this->_config;

		// first think we do is to mv the traffic logs to the cache dir (which also
		// serves as tmp) and do a apache restart.
		$logDir = SYSCP_PATH_BASE.'logs/apache2-traffic/';
		$tmpDir = SYSCP_PATH_BASE.'cache/apache2-traffic/';
		if (!Syscp::isReadableDir($tmpDir))
		{
			Syscp::exec('mkdir -p '.$tmpDir);
			Syscp::exec('mv '.$logDir.'* '.$tmpDir.'/');
			Syscp::exec($config->get('system.apachereload_command'));
		}

		// lets load all customers, only customers can directly produce http traffic
		$query  = 'SELECT * FROM `'.TABLE_PANEL_CUSTOMERS.'`';
		$result = $db->query($query);
		while (false !== ($customer = $db->fetchArray($result)))
		{
			$logFile = $tmpDir.'/'.$customer['loginname'].'.log';
			if(Syscp::isReadableFile($logFile))
			{
				$data    = file($logFile);
				$traffic = 0;
				foreach($data as $value)
				{
					$traffic += $value;
				}
				$today['d'] = date('d');
				$today['m'] = date('m');
				$today['y'] = date('Y');
				// we need to determine if there is a trafficrow
				$query = 'SELECT * '
				       . 'FROM `'.TABLE_PANEL_TRAFFIC.'` '
				       . 'WHERE `customerid` = '.(int)$customer['customerid'].' '
				       . '  AND `day`   = '.(int)$today['d'].' '
				       . '  AND `month` = '.(int)$today['m'].' '
				       . '  AND `year`  = '.(int)$today['y'].' ';
				$oldTraffic = $db->queryFirst($query);
print "Http Traffic: ".$traffic." bytes \n";
				if(isset($oldTraffic['http']))
				{
					$traffic += $oldTraffic['http'];
					$query   = 'UPDATE `'.TABLE_PANEL_TRAFFIC.'` '
					         . 'SET `http` = '.(int)$traffic.' '
					         . 'WHERE `customerid` = '.(int)$customer['customerid'].' '
					         . '  AND `day`   = '.(int)$today['d'].' '
					         . '  AND `month` = '.(int)$today['m'].' '
					         . '  AND `year`  = '.(int)$today['y'].' ';
				}
				else
				{
					$query   = 'INSERT INTO `'.TABLE_PANEL_TRAFFIC.'` '
					         . 'SET `http` = '.(int)$traffic.', '
					         . '    `customerid` = '.(int)$customer['customerid'].', '
					         . '    `day`   = '.(int)$today['d'].', '
					         . '    `month` = '.(int)$today['m'].', '
					         . '    `year`  = '.(int)$today['y'].' ';
				}
				$db->query($query);
			}
		}
		// now we need to remove the tmpdir
		Syscp::exec('rm -r '.$tmpDir);
	}
}