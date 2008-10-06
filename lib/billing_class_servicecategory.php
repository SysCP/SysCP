<?php

/**
 * Main serviceCategory Class (class_billing_servicecategory.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class provides a general solution for collection and building invoice lines of services, together with their templates.
 * All Service Categories are children of this class.
 * @package   Billing
 */

class serviceCategory
{
    /**
     * Database handler
     * @var db
     */

    var $db = false;

    /**
     * userIds to consider when processing service category
     * @var array
     */

    var $userIds = array();

    /**
     * Mode, 0 for customer, 1 for admin/reseller
     * @var int
     */

    var $mode = 0;

    /**
     * Name of service
     * @var string
     */

    var $service_name = '';

    /**
     * All details about the service, eg. complete row from panel_customer/panel_admin or panel/domains etc.
     * @var array
     */

    var $service_details = array();

    /**
     * All data required to find details of service
     * @var array
     */

    var $toInvoiceTableData = array();

    /**
     * All templates for the service.
     * @var array
     */

    var $service_templates = array();

    /**
     * All data required to find templates of service
     * @var array
     */

    var $serviceTemplateTableData = array();

    /**
     * Default values for the fields in service_details which trigger usage of a template
     * @var array
     */

    var $defaultvalues = array();

    /**
     * Set to true if this service could end immediately, means we could also partly invoice the interval around the ending date.
     * @var bool
     */

    var $endServiceImmediately = false;

    /**
     * Set to true if you want to allow lastinvoiced_date being past servicestart_date and not .
     * @var bool
     */

    var $allowLastInvoicedDatePastServiceStart = false;

    /**
     * Class constructor of service_category. Gets reference for database connection,
     * admin mode and service name.
     *
     * @param db     Reference to database handler
     * @param int    For admin mode set 1, otherwise 0
     * @param string The name of the service
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function __construct($db, $mode, $service_name)
    {
        $this->db = $db;
        $this->service_name = $service_name;

        if($mode === 1)
        {
            $this->mode = 1;
        }
    }

    /**
     * This method fills the service_detail and service_templates.
     *
     * @param array The Ids to gather details for.
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function fetchData($userIds = array())
    {
        if(!is_array($userIds))
        {
            $this->userIds = array(
                intval($userIds)
            );
        }
        else
        {
            $this->userIds = $userIds;
        }

        unset($userIds);

        if(is_array($this->toInvoiceTableData)
           && isset($this->toInvoiceTableData['table'])
           && $this->toInvoiceTableData['table'] != ''
           && isset($this->toInvoiceTableData['keyfield'])
           && $this->toInvoiceTableData['keyfield'] != ''
           && isset($this->toInvoiceTableData['condfield'])
           && $this->toInvoiceTableData['condfield'] != '')
        {
            $toInvoiceTable_requiredFields = array(
                $this->toInvoiceTableData['keyfield'],
                $this->toInvoiceTableData['condfield'],
                'setup_fee',
                'interval_fee',
                'interval_length',
                'interval_type',
                'interval_payment',
                'service_active',
                'servicestart_date',
                'serviceend_date',
                'lastinvoiced_date'
            );
            $condition = '';

            if(is_array($this->userIds)
               && !empty($this->userIds))
            {
                $condition = ' WHERE `' . $this->toInvoiceTableData['condfield'] . '` IN ( ' . implode(', ', $this->userIds) . ' ) ';
            }

            $toInvoice_result = $this->db->query('SELECT * FROM `' . $this->toInvoiceTableData['table'] . '` ' . $condition . ' ORDER BY `servicestart_date` ASC, `serviceend_date` ASC ');

            while($toInvoice_row = $this->db->fetch_array($toInvoice_result))
            {
                $rowOk = true;
                reset($toInvoiceTable_requiredFields);
                foreach($toInvoiceTable_requiredFields as $fieldname)
                {
                    if(!isset($toInvoice_row[$fieldname]))
                    {
                        $rowOk = false;
                    }
                }

                if($rowOk
                   && $toInvoice_row[$this->toInvoiceTableData['keyfield']] != '')
                {
                    $this->service_details[$toInvoice_row[$this->toInvoiceTableData['keyfield']]] = $toInvoice_row;
                }
            }
        }

        if(is_array($this->serviceTemplateTableData)
           && isset($this->serviceTemplateTableData['table'])
           && $this->serviceTemplateTableData['table'] != ''
           && isset($this->serviceTemplateTableData['keyfield'])
           && $this->serviceTemplateTableData['keyfield'] != '')
        {
            $serviceTemplateTable_requiredFields = array(
                $this->serviceTemplateTableData['keyfield'],
                'setup_fee',
                'interval_fee',
                'interval_length',
                'interval_type',
                'interval_payment',
                'taxclass',
                'valid_from',
                'valid_to'
            );
            $serviceTemplate_result = $this->db->query('SELECT * FROM `' . $this->serviceTemplateTableData['table'] . '` ORDER BY `valid_from` ASC, `valid_to` ASC ');

            while($serviceTemplate_row = $this->db->fetch_array($serviceTemplate_result))
            {
                $rowOk = true;
                reset($serviceTemplateTable_requiredFields);
                foreach($serviceTemplateTable_requiredFields as $fieldname)
                {
                    if(!isset($serviceTemplate_row[$fieldname]))
                    {
                        $rowOk = false;
                    }
                }

                if($rowOk
                   && $serviceTemplate_row[$this->serviceTemplateTableData['keyfield']] != '')
                {
                    if(!isset($this->service_templates[$serviceTemplate_row[$this->serviceTemplateTableData['keyfield']]])
                       || !is_array($this->service_templates[$serviceTemplate_row[$this->serviceTemplateTableData['keyfield']]]))
                    {
                        $this->service_templates[$serviceTemplate_row[$this->serviceTemplateTableData['keyfield']]] = array();
                    }

                    $valid = $serviceTemplate_row['valid_from'] . ':' . $serviceTemplate_row['valid_to'];
                    $this->service_templates[$serviceTemplate_row[$this->serviceTemplateTableData['keyfield']]][$valid] = $serviceTemplate_row;
                }
            }
        }

        $this->defaultvalues = array(
            'interval_fee' => '0.00',
            'interval_length' => '0',
            'interval_type' => 'y',
            'interval_payment' => '0',
            'setup_fee' => '0.00',
            'taxclass' => '0',
            'service_type' => '',
            'caption_setup' => '',
            'caption_interval' => ''
        );
    }

    /**
     * This method collects the invoice rows.
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
        $invoice = array();
        reset($this->service_details);
        foreach($this->service_details as $serviceId => $service_detail)
        {
            if(checkDateArray(transferDateToArray($service_detail['servicestart_date'])) === true)
            {
                // Load template which is valid through our setup date

                $template = $this->findValidTemplate($service_detail['servicestart_date'], $this->selectAppropriateTemplateKey($service_detail));
                foreach($this->defaultvalues as $field => $value)
                {
                    // We are using $this->service_details[$serviceId] instead of $service_detail so we can see the original values, as the "working copy" ($service_detail) could have been changed...

                    if((!isset($this->service_details[$serviceId][$field]) || (isset($this->service_details[$serviceId][$field])) && $this->service_details[$serviceId][$field] == $value)
                       && isset($template[$field])
                       && $template[$field] != $value)
                    {
                        $service_detail[$field] = $template[$field];
                    }
                }

                // If quantity is not set, we do need a value 1, otherwise this doesn't make sense...

                if(!isset($service_detail['quantity']))
                {
                    $service_detail['quantity'] = 1;
                }

                // Add setup fee to invoice

                if(checkDateArray(transferDateToArray($service_detail['lastinvoiced_date'])) !== true
                   || ($this->allowLastInvoicedDatePastServiceStart === false && calculateDayDifference($service_detail['lastinvoiced_date'], $service_detail['servicestart_date']) > 0))
                {
                    if($include_setup_fee === true)
                    {
                        $invoice[] = $this->buildInvoiceRowSetupFee($service_detail, $this->getServiceDescription($service_detail, 'setup'));
                    }

                    $service_detail['lastinvoiced_date'] = $service_detail['servicestart_date'];
                }

                // If payment_every is not set, we do need a value 1, otherwise nextinvoiced_date wouldn't be calculated correctly and we'll get stuck in an infinite loop

                if(!isset($service_detail['payment_every']))
                {
                    $service_detail['payment_every'] = 1;
                }

                if((int)$service_detail['interval_length'] != 0
                   && (int)$service_detail['payment_every'] != 0
                   && in_array($service_detail['interval_type'], getIntervalTypes('array')))
                {
                    $original_date = $service_detail['lastinvoiced_date'];
                    $service_detail['nextinvoiced_date'] = manipulateDate($service_detail['lastinvoiced_date'], '+', ((int)$service_detail['interval_length']*(int)$service_detail['payment_every']), $service_detail['interval_type'], $original_date);

                    while(($service_detail['interval_payment'] == CONST_BILLING_INTERVALPAYMENT_PREPAID && calculateDayDifference($service_detail['lastinvoiced_date'], time()) >= 0 && !($service_detail['service_active'] == '0' && calculateDayDifference($service_detail['lastinvoiced_date'], $service_detail['serviceend_date']) <= 0))
                          || ($service_detail['interval_payment'] == CONST_BILLING_INTERVALPAYMENT_POSTPAID && (calculateDayDifference($service_detail['nextinvoiced_date'], time()) >= 0 || ($this->endServiceImmediately === true && $service_detail['service_active'] == '0' && calculateDayDifference($service_detail['lastinvoiced_date'], $service_detail['serviceend_date']) > 0 && calculateDayDifference($service_detail['serviceend_date'], $service_detail['nextinvoiced_date']) >= 0 && calculateDayDifference($service_detail['lastinvoiced_date'], time()) >= 0 && calculateDayDifference($service_detail['serviceend_date'], time()) >= 0))))
                    {
                        // Reload template which is valid through our current invoice period

                        reset($this->defaultvalues);
                        $template = $this->findValidTemplate($service_detail['lastinvoiced_date'], $this->selectAppropriateTemplateKey($service_detail));
                        foreach($this->defaultvalues as $field => $value)
                        {
                            // We are using $this->service_details[$serviceId] instead of $service_detail so we can see the original values, as the "working copy" ($service_detail) could have been changed...

                            if((!isset($this->service_details[$serviceId][$field]) || (isset($this->service_details[$serviceId][$field])) && $this->service_details[$serviceId][$field] == $value)
                               && isset($template[$field])
                               && $template[$field] != $value)
                            {
                                $service_detail[$field] = $template[$field];
                            }
                        }

                        if($this->endServiceImmediately === true
                           && $service_detail['service_active'] == '0'
                           && calculateDayDifference($service_detail['lastinvoiced_date'], $service_detail['serviceend_date']) > 0
                           && calculateDayDifference($service_detail['serviceend_date'], $service_detail['nextinvoiced_date']) >= 0
                           && calculateDayDifference($service_detail['lastinvoiced_date'], time()) >= 0
                           && calculateDayDifference($service_detail['serviceend_date'], time()) >= 0)
                        {
                            $service_detail['nextinvoiced_date'] = $service_detail['serviceend_date'];
                        }

                        // Sanity check, shouldn't be needed...

                        if(calculateDayDifference($service_detail['lastinvoiced_date'], $service_detail['nextinvoiced_date']) >= 0)
                        {
                            $service_detail['service_date_begin'] = $service_detail['lastinvoiced_date'];
                            $service_detail['service_date_end'] = $service_detail['nextinvoiced_date'];

                            if($include_interval_fee === true)
                            {
                                $invoice[] = $this->buildInvoiceRowIntervalFee($service_detail, $this->getServiceDescription($service_detail, 'interval'));
                            }
                        }

                        // Go on in loop, set lastinvoiced_date to nextinvoiced_date ...

                        $service_detail['lastinvoiced_date'] = $service_detail['nextinvoiced_date'];

                        // ... and recalculate nextinvoiced_date.

                        $service_detail['nextinvoiced_date'] = manipulateDate($service_detail['lastinvoiced_date'], '+', ((int)$service_detail['interval_length']*(int)$service_detail['payment_every']), $service_detail['interval_type'], $original_date);
                    }
                }

                if($fixInvoice === true)
                {
                    $this->setLastInvoiced($serviceId, $service_detail);
                }
            }
        }

        return $invoice;
    }

    /**
     * This method sets the date up to which the service has been invoiced.
     *
     * @param int   The serviceId
     * @param array Service details
     * @return bool The returncode of the sql query
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function setLastInvoiced($serviceId, $service_detail)
    {
        $query = 'UPDATE `' . $this->toInvoiceTableData['table'] . '` SET `lastinvoiced_date` = \'' . $service_detail['lastinvoiced_date'] . '\' ';

        if($this->endServiceImmediately === true
           && $service_detail['service_active'] == '0'
           && $service_detail['servicestart_date'] != '0'
           && $service_detail['serviceend_date'] != '0')
        {
            $query.= ', `servicestart_date` = \'0\' ';
        }

        $query.= ' WHERE `' . $this->toInvoiceTableData['keyfield'] . '` = \'' . $serviceId . '\' ';
        return $this->db->query($query);
    }

    /**
     * This method builds an invoice row for setup fee.
     *
     * @param array  Service details
     * @param array  Service description
     * @return array The invoice row
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function buildInvoiceRowSetupFee($service_detail, $service_description)
    {
        return $this->getInvoiceRow('once', array(
            'quantity' => $service_detail['quantity'],
            'setup_fee' => $service_detail['setup_fee'],
            'taxclass' => $service_detail['taxclass']
        ), array(
            'service_date' => $service_detail['servicestart_date']
        ), $service_description);
    }

    /**
     * This method builds an invoice row for invoice fee.
     *
     * @param array  Service details
     * @param array  Service description
     * @return array The invoice row
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function buildInvoiceRowIntervalFee($service_detail, $service_description)
    {
        return $this->getInvoiceRow('period', array(
            'quantity' => $service_detail['quantity'],
            'interval_fee' => $service_detail['interval_fee'],
            'interval_length' => $service_detail['interval_length'],
            'interval_type' => $service_detail['interval_type'],
            'taxclass' => $service_detail['taxclass']
        ), array(
            'service_date_begin' => $service_detail['service_date_begin'],
            'service_date_end' => $service_detail['service_date_end']
        ), $service_description);
    }

    /**
     * This method selects an appropriate template key.
     *
     * @param array  Service details
     * @return int   The id of the appropriate template
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function selectAppropriateTemplateKey($provider)
    {
        $returnval = 0;

        if(isset($provider['templateid'])
           && (int)$provider['templateid'] != 0
           && isset($this->service_templates[$provider['templateid']]))
        {
            $returnval = $provider['templateid'];
        }

        return $returnval;
    }

    /**
     * This method selects a template which has been valid at the given time from the given templatekeys.
     *
     * @param date   The date when the template should have been valid
     * @param array  All appropriate template keys
     * @return array The valid template
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function findValidTemplate($date, $templatekeys)
    {
        $returnval = array();

        if(!is_array($templatekeys))
        {
            $templatekey = $templatekeys;
            unset($templatekeys);
            $templatekeys = array(
                $templatekey
            );
            unset($templatekey);
        }

        foreach($templatekeys as $templatekey)
        {
            if(isset($this->service_templates[$templatekey]))
            {
                reset($this->service_templates[$templatekey]);
                foreach($this->service_templates[$templatekey] as $valid => $template)
                {
                    list($valid_from, $valid_to) = explode(':', $valid, 2);

                    if(calculateDayDifference($valid_from, $date) >= 0
                       && (calculateDayDifference($date, $valid_to) > 0 || (int)$valid_to == 0))
                    {
                        $returnval = $template;
                    }
                }
            }
        }

        return $returnval;
    }

    /**
     * This method selects a template which has been valid at the given time from the given templatekeys.
     *
     * @param date   The date when the template should have been valid
     * @param array  All appropriate template keys
     * @return array The valid template
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function getServiceDescription($service_detail, $service_occurence)
    {
        if(isset($service_detail['caption_' . $service_occurence])
           && $service_detail['caption_' . $service_occurence] != '')
        {
            $returnval = array(
                'caption' => $service_detail['caption_' . $service_occurence]
            );
        }
        elseif(isset($service_detail['caption'])
               && $service_detail['caption'] != '')
        {
            $returnval = array(
                'caption' => $service_detail['caption']
            );
        }
        else
        {
            $returnval = array();
        }

        return $returnval;
    }

    /**
     * This method returns a good invoice row.
     * It checks the given fee for either quantity and setup_fee (for 'once')
     * or quantity, interval_fee, interval_length and interval_type (for 'period').
     * It also checks the given date for existens of either service_date (for 'once')
     * or service_date_begin and service_date_end (for 'period')
     *
     * @param string Either 'once' or 'period'
     * @param array  The fees
     * @param array  The dates
     * @param array  Service description.
     * @return array The invoice row
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function getInvoiceRow($occurence, $fee, $date, $description = array())
    {
        $invoice = array();

        if($occurence != 'once'
           && $occurence != 'period')
        {
            $occurence = 'period';
        }

        $requred_fields_fee = array();
        $requred_fields_fee['once'] = array(
            'quantity',
            'setup_fee'
        );
        $requred_fields_fee['period'] = array(
            'quantity',
            'interval_fee',
            'interval_length',
            'interval_type'
        );
        $requred_fields_date = array();
        $requred_fields_date['once'] = array(
            'service_date'
        );
        $requred_fields_date['period'] = array(
            'service_date_begin',
            'service_date_end'
        );
        $fields_fee_ok = true;
        foreach($requred_fields_fee[$occurence] as $field_fee_name)
        {
            if(!isset($fee[$field_fee_name]))
            {
                $fields_fee_ok = false;
            }
        }

        $fields_date_ok = true;
        foreach($requred_fields_date[$occurence] as $field_date_name)
        {
            if(!isset($date[$field_date_name]))
            {
                $fields_date_ok = false;
            }
        }

        if($fields_fee_ok
           && $fields_date_ok)
        {
            if(!is_array($description))
            {
                $description = array(
                    $description
                );
            }

            // Let service_type in description overwrite default service_type

            if(isset($description['service_type'])
               && $description['service_type'] != '')
            {
                $invoice['service_type'] = $description['service_type'];
            }
            else
            {
                $invoice['service_type'] = $this->service_name;
            }

            $invoice['service_occurence'] = $occurence;
            $invoice = array_merge($invoice, $fee, $date);
            $invoice['description'] = $description;
        }

        return $invoice;
    }
}

?>