<?php

/**
 * Service Category class for Domains (billing_class_domains.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class extends serviceCategory and processes domains, managing prices for included domains etc.
 * @package   Billing
 */

class domains extends serviceCategory
{
    /**
     * Holds information about included domains for each user.
     * @var array
     */

    var $included_domains = array(
        0 => array(
            'included_domains_qty' => 0,
            'included_domains_tld' => ''
        )
    );

    /**
     * Holds information of how many included domains a user already used.
     * @var array
     */

    var $included_domains_used = array();

    /**
     * Reference to the idna class
     * @var idna_convert_wrapper
     */

    var $idna = false;

    /**
     * Class constructor of domains. Gets reference for database connection,
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
        $this->toInvoiceTableData = array(
            'table' => TABLE_PANEL_DOMAINS,
            'keyfield' => 'id',
            'condfield' => 'customerid'
        );
        $this->serviceTemplateTableData = array(
            'table' => TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES,
            'keyfield' => 'tld'
        );

        if($service_name == '')
        {
            $service_name = 'domains';
        }

        parent::__construct(&$db, $mode, $service_name);
    }

    /**
     * We first fill self::included_domains. Then we check for every domain of that user,
     * if it is literally mentioned in the list of included domains. Lastly we again walk
     * through the list of domains and test with self::_checkIncludedDomain if it is an
     * included domain. Afterwards parent::collect is called.
     *
     * @param bool   Should we fix invoice (means we call self::setLastInvoiced to latest invoiced date).
     * @param bool   Should we include the setup fee?
     * @param bool   Should we include the interval fees?
     * @return array All invoice rows
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function collect($fixInvoice = false, $include_setup_fee = false, $include_interval_fee = false)
    {
        $this->idna = new idna_convert_wrapper();
        $included_domains_result = $this->db->query('SELECT `customerid`, `included_domains_qty`, `included_domains_tld` FROM `' . TABLE_PANEL_CUSTOMERS . '` WHERE `customerid` IN ( ' . implode(', ', $this->userIds) . ' ) ');

        while($included_domains_row = $this->db->fetch_array($included_domains_result))
        {
            $this->included_domains[$included_domains_row['customerid']] = $included_domains_row;
            unset($this->included_domains[$included_domains_row['customerid']]['customerid']);
        }

        // Get all domains owned by the customer (which have already been started) to verify if they are included domains

        $check_domains = array();
        $check_domains_result = $this->db->query('SELECT `customerid`, `domain` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `customerid` IN ( ' . implode(', ', $this->userIds) . ' ) AND `servicestart_date` != \'0000-00-00\' ORDER BY `add_date` ASC ');

        while($check_domains_row = $this->db->fetch_array($check_domains_result))
        {
            if(!isset($check_domains[$check_domains_row['customerid']]))
            {
                $check_domains[$check_domains_row['customerid']] = array();
            }

            $check_domains[$check_domains_row['customerid']][] = $check_domains_row['domain'];
        }

        foreach($this->included_domains as $customerid => $included_domains)
        {
            $included_domains_tld = explode(' ', $included_domains['included_domains_tld']);
            $included_domains_tld_length = array();
            foreach($included_domains_tld as $included_domain_tld)
            {
                $number_parts = count(explode('.', $included_domain_tld));

                if(!isset($included_domains_tld_length[$number_parts])
                   || !is_array($included_domains_tld_length[$number_parts]))
                {
                    $included_domains_tld_length[$number_parts] = array();
                }

                $included_domains_tld_length[$number_parts][] = $included_domain_tld;
            }

            krsort($included_domains_tld_length);
            $this->included_domains[$customerid]['included_domains_tld'] = '';
            foreach($included_domains_tld_length as $included_domains_tld)
            {
                if($this->included_domains[$customerid]['included_domains_tld'] != '')
                {
                    $this->included_domains[$customerid]['included_domains_tld'].= ' ';
                }

                $this->included_domains[$customerid]['included_domains_tld'].= implode(' ', $included_domains_tld);
            }

            $included_domains_tld = explode(' ', $this->included_domains[$customerid]['included_domains_tld']);

            if(isset($check_domains[$customerid])
               && is_array($check_domains[$customerid]))
            {
                reset($check_domains[$customerid]);
                foreach($check_domains[$customerid] as $check_domain)
                {
                    if(in_array($check_domain, $included_domains_tld))
                    {
                        $this->_checkIncludedDomain($check_domain, $customerid);
                    }
                }

                // Now cycle through the list of domains and check them

                reset($check_domains[$customerid]);
                foreach($check_domains[$customerid] as $check_domain)
                {
                    $this->_checkIncludedDomain($check_domain, $customerid);
                }
            }
        }

        return parent::collect($fixInvoice, $include_setup_fee, $include_interval_fee);
    }

    /**
     * We return all parts from the domain as appropriate template keys,
     * e.g. the domain is top.level.domain.com then this results in the
     * keys: com, domain.com, level.domain.com and top.level.domain.com
     *
     * @param array  Service details
     * @return int   The id of the appropriate template
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function selectAppropriateTemplateKey($service_detail)
    {
        $returnval = array();
        $domain_parts = array_reverse(explode('.', $service_detail['domain']));
        foreach($domain_parts as $domain_part)
        {
            if(!isset($domain))
            {
                $domain = $domain_part;
            }
            else
            {
                $domain = $domain_part . '.' . $domain;
            }

            $returnval[] = $domain;
        }

        return $returnval;
    }

    /**
     * If the check for included domain is positive we change the caption class and call the parent method afterwards.
     *
     * @param date   The date when the template should have been valid
     * @param array  All appropriate template keys
     * @return array The valid template
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function getServiceDescription($service_detail, $service_occurence)
    {
        $service_description = array(
            'domain' => $this->idna->decode($service_detail['domain'])
        );

        // Check for included domains

        if($this->_checkIncludedDomain($service_detail['domain'], $service_detail['customerid']) === true)
        {
            $service_description['caption_class'] = 'included_domain';
        }

        return array_merge(parent::getServiceDescription($service_detail, $service_occurence), $service_description);
    }

    /**
     * If the check for included domain is positive we override setup_fee to 0.00 and call the parent method afterwards.
     *
     * @param array  Service details
     * @param array  Service description
     * @return array The invoice row
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function buildInvoiceRowSetupFee($service_detail, $service_description)
    {
        // Check for included domains

        if($this->_checkIncludedDomain($service_detail['domain'], $service_detail['customerid']) === true)
        {
            $service_detail['setup_fee'] = '0.00';
        }

        return parent::buildInvoiceRowSetupFee($service_detail, $service_description);
    }

    /**
     * If the check for included domain is positive we override interval_fee to 0.00 and call the parent method afterwards.
     *
     * @param array  Service details
     * @param array  Service description
     * @return array The invoice row
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function buildInvoiceRowIntervalFee($service_detail, $service_description)
    {
        // Check for included domains

        if($this->_checkIncludedDomain($service_detail['domain'], $service_detail['customerid']) === true)
        {
            $service_detail['interval_fee'] = '0.00';
        }

        return parent::buildInvoiceRowIntervalFee($service_detail, $service_description);
    }

    /**
     * Checks if the given domain is an included domain for the given userid.
     *
     * @param string The domain
     * @param int    The userid
     * @return bool  True if domain is included domain, false otherwise
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function _checkIncludedDomain($domain, $customerid)
    {
        $returnval = false;

        if(isset($this->included_domains[$customerid])
           && is_array($this->included_domains[$customerid]))
        {
            if(!isset($this->included_domains_used[$customerid])
               || !is_array($this->included_domains_used[$customerid]))
            {
                $this->included_domains_used[$customerid] = array();
            }

            $included_domains_tld = explode(' ', $this->included_domains[$customerid]['included_domains_tld']);

            if(is_array($this->included_domains)
               && isset($this->included_domains[$customerid]['included_domains_qty'])
               && ((count($this->included_domains_used[$customerid]) < (int)$this->included_domains[$customerid]['included_domains_qty']) || (in_array($domain, $this->included_domains_used[$customerid])))
               && isset($this->included_domains[$customerid]['included_domains_tld'])

            // I'm abusing selectAppropriateTemplateKey here to get valid tld of domain ;-)

            
               && one_in_array($this->selectAppropriateTemplateKey(array(
                'domain' => $domain
            )), $included_domains_tld))
            {
                if(!in_array($domain, $this->included_domains_used[$customerid]))
                {
                    $this->included_domains_used[$customerid][] = $domain;
                }

                $returnval = true;
            }
        }

        return $returnval;
    }
}

?>