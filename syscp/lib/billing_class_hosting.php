<?php

/**
 * Service Category class for Hosting (billing_class_hosting.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class extends serviceCategory and processes hosting,
 * basically this is just setting the source of services correctly
 * @package   Billing
 */

class hosting extends serviceCategory
{
    /**
     * Class constructor of hosting. Gets reference for database connection,
     * admin mode and service name.
     *
     * @param db     Reference to database handler
     * @param int    For admin mode set 1, otherwise 0
     * @param string The name of the service
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function __construct($db, $mode = 0, $service_name = '')
    {
        $this->endServiceImmediately = true;
        $this->toInvoiceTableData = array(
            'table' => getModeDetails($mode, 'TABLE_PANEL_USERS', 'table'),
            'keyfield' => getModeDetails($mode, 'TABLE_PANEL_USERS', 'key'),
            'condfield' => getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')
        );

        if($service_name == '')
        {
            $service_name = 'hosting';
        }

        parent::__construct(&$db, $mode, $service_name);
    }

    /**
     * We are merging the loginname in the returned value of the parent.
     *
     * @param date   The date when the template should have been valid
     * @param array  All appropriate template keys
     * @return array The valid template
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function getServiceDescription($service_detail, $service_occurence)
    {
        return array_merge(parent::getServiceDescription($service_detail, $service_occurence), array(
            'loginname' => $service_detail['loginname']
        ));
    }
}

?>