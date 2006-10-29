<?php
class Syscp_ProFTP_Hooks extends Syscp_BaseHook
{
    /**
     * Filename of the file this hook is implemented in.
     * Consider this variable to be class specific constant.
     *
     * @var    string
     */

    protected $FILE;

    // CONST later

    /**
     * Classname of this class
     * Consider this variable to be class specific constant.
     *
     * @var    string
     */

    protected $CLASS;

    // CONST later

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
        $this->FILE = 'lib/modules/SysCP/ftp/lib/Hooks.class.php';
        $this->CLASS = __CLASS__;
    }

    /**
     * This method got called by OnCalcTrafficAtCron
     *
     * @param array $params
     */

    public function OnCalcTrafficAtCron($params = array())
    {
        $db = $this->_db;
        $log = $this->_log;
        $config = $this->_config;
        $today['d'] = date('d');
        $today['m'] = date('m');
        $today['y'] = date('Y');
        $isChecked = array();

        // this is an array of customerid's which have already
        // been checked if their traffic row exists.

        $query = 'SELECT * FROM `'.TABLE_FTP_USERS.'`';
        $result = $db->query($query);

        while(false !== ($row = $db->fetchArray($result)))
        {
            // We start with a check if the customer already has a
            // traffic row in the traffic table, if not, we will
            // create one. Anyway the result is cached to reduce the
            // amount of SELECT-queries to the database.

            if(!isset($isChecked[$row['customerid']]))
            {
                $query = 'SELECT * '.'FROM `'.TABLE_PANEL_TRAFFIC.'` '.'WHERE `customerid` = '.(int)$row['customerid'].' '.'  AND `day`   = '.(int)$today['d'].' '.'  AND `month` = '.(int)$today['m'].' '.'  AND `year`  = '.(int)$today['y'].' ';
                $subRow = $db->queryFirst($query);

                if(isset($subRow['customerid']))
                {
                    $isChecked[$row['customerid']] = true;
                }
                else
                {
                    $query = 'INSERT INTO `'.TABLE_PANEL_TRAFFIC.'` '.'SET `customerid` = '.(int)$row['customerid'].', '.'    `day`   = '.(int)$today['d'].', '.'    `month` = '.(int)$today['m'].', '.'    `year`  = '.(int)$today['y'].' ';
                    $db->query($query);
                    $isChecked[$row['customerid']] = true;
                }
            }

            // now lets update the traffic row

            $query = 'UPDATE `'.TABLE_PANEL_TRAFFIC.'` '.'SET `ftp_up`   = `ftp_up`   + '.$row['up_bytes'].', '.'    `ftp_down` = `ftp_down` + '.$row['down_bytes'].' '.'WHERE `customerid` = '.(int)$row['customerid'].' '.'  AND `day`   = '.(int)$today['d'].' '.'  AND `month` = '.(int)$today['m'].' '.'  AND `year`  = '.(int)$today['y'].' ';
            $db->query($query);

            // and delete the row in the ftp table

            $query = 'UPDATE `'.TABLE_FTP_USERS.'` '.'SET `up_bytes` = 0, '.'    `down_bytes` = 0 '.'WHERE `id` = '.$row['id'];
            $db->query($query);
        }
    }
}

