<?php

/**
 * Main Taxcontroller Class (class_billing_taxcontroller.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class manages the complete process of taxing invoice lines and calculating endprices
 * @package   Billing
 */

class taxController
{
    /**
     * Database handler
     * @var db
     */

    var $db = false;

    /**
     * ID of the default tax class
     * @var int
     */

    var $default_taxclass = 0;

    /**
     * Set to true if tax should be calculated and added, false otherwise
     * @var bool
     */

    var $calc_tax = true;

    /**
     * Cache of taxclasses
     * @var array
     */

    var $taxclasses = array();

    /**
     * Class constructor of diskspace. Gets reference for database connection.
     * Builds self::taxclasses from database values.
     *
     * @param db     Reference to database handler
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function __construct($db)
    {
        $this->db = $db;
        $default_taxclass_result = $this->db->query_first('SELECT `classid` FROM `' . TABLE_BILLING_TAXCLASSES . '` WHERE `default` = \'1\' LIMIT 0,1');
        $this->default_taxclass = $default_taxclass_result['classid'];
        $last_taxclass_validfrom = 0;
        $taxclasses_result = $this->db->query('SELECT `taxclass`, `taxrate`, `valid_from` FROM `' . TABLE_BILLING_TAXRATES . '` ORDER BY `valid_from` ASC');

        while($taxclasses_row = $this->db->fetch_array($taxclasses_result))
        {
            if(!isset($this->taxclasses[$taxclasses_row['taxclass']])
               || !is_array($this->taxclasses[$taxclasses_row['taxclass']]))
            {
                $this->taxclasses[$taxclasses_row['taxclass']] = array();
            }

            $this->taxclasses[$taxclasses_row['taxclass']][$taxclasses_row['valid_from']] = $taxclasses_row;

            if(isset($this->taxclasses[$taxclasses_row['taxclass']][$last_taxclass_validfrom])
               && is_array($this->taxclasses[$taxclasses_row['taxclass']][$last_taxclass_validfrom])
               && $taxclasses_row['valid_from'] != 0)
            {
                $this->taxclasses[$taxclasses_row['taxclass']][$last_taxclass_validfrom]['valid_to'] = $taxclasses_row['valid_from'];
            }

            $last_taxclass_validfrom = $taxclasses_row['valid_from'];
        }
    }

    /**
     * This method returns all taxes, which were valid in a certain interval.
     *
     * @param int    Taxclass
     * @param int    Beginning date of interval
     * @param int    Ending date of interval
     * @return array All taxclasses, array( [<VALID FROM DATE>] => <TAXRATE> )
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function getTaxChanges($taxclass, $begin = 0, $end = 0)
    {
        $returnval = array();

        if($this->calc_tax === true)
        {
            if(isset($this->taxclasses[$taxclass]))
            {
                reset($this->taxclasses[$taxclass]);
                foreach($this->taxclasses[$taxclass] as $valid_from => $taxrate)
                {
                    if(calculateDayDifference($valid_from, $begin) >= 0)
                    {
                        $returnval[0] = $taxrate;
                    }
                    elseif(calculateDayDifference($begin, $valid_from) > 0
                           && calculateDayDifference($valid_from, $end) > 0)
                    {
                        $returnval[$valid_from] = $taxrate;
                    }
                }
            }
        }
        else
        {
            $returnval[0] = '0.0';
        }

        return $returnval;
    }

    /**
     * This method adds taxrates to the invoice rows.
     *
     * @param array  The array containing all invoice rows
     * @return array The array containing (taxed) invoice rows
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function applyTaxRate($invoice)
    {
        $invoice_new = array();
        foreach($invoice as $rowid => $invoice_row)
        {
            // If we don't have a valid taxclass, use the default one.

            if(!isset($invoice_row['taxclass'])
               || !isset($this->taxclasses[$invoice_row['taxclass']]))
            {
                $invoice_row['taxclass'] = $this->default_taxclass;
            }

            if(isset($invoice_row['taxclass'])
               && isset($this->taxclasses[$invoice_row['taxclass']]))
            {
                // Once-fees are quite easy, just get the valid taxrate and add it to the row.

                if(isset($invoice_row['service_occurence'])
                   && $invoice_row['service_occurence'] == 'once')
                {
                    $taxchanges = $this->getTaxChanges($invoice_row['taxclass'], $invoice_row['service_date']);
                    $pricing = array(
                        'taxrate' => $taxchanges[0]['taxrate'],
                        'total_fee' => $invoice_row['setup_fee']
                    );
                    $invoice_new[] = array_merge($invoice_row, $pricing);
                }

                // Periodical fees are much more complicated:

                elseif (isset($invoice_row['service_occurence'])
                       && $invoice_row['service_occurence'] == 'period')
                {
                    // Get all tax changes in our service interval

                    $taxchanges = $this->getTaxChanges($invoice_row['taxclass'], $invoice_row['service_date_begin'], manipulateDate($invoice_row['service_date_end'], '-', 1, 'd'));

                    // In pricing we store all changes to our invoice row, will get merged lateron.

                    $pricing = array(
                        'taxrate' => $taxchanges[0]['taxrate'],
                        'total_fee' => 0
                    );

                    // number_days will store the days we already processed...

                    $number_days = 0;

                    // ... whereas days_diff contains the whole number of days of our service interval.

                    $days_diff = calculateDayDifference($invoice_row['service_date_begin'], $invoice_row['service_date_end']);
                    $service_date_begin_array = transferDateToArray($invoice_row['service_date_begin']);

                    // Now we walk through the interval, stepping is the interval length.

                    while($days_diff >= $number_days+$this->getDaysForInterval($invoice_row['interval_length'], $invoice_row['interval_type'], $service_date_begin_array))
                    {
                        // Whenever we happen to meet a tax change, remaining will reduced by the number of days to that tax change.

                        $remaining = $interval_days = $this->getDaysForInterval($invoice_row['interval_length'], $invoice_row['interval_type'], $service_date_begin_array);
                        $interval_begin = manipulateDate($invoice_row['service_date_begin'], '+', $number_days, 'd');
                        $interval_end = manipulateDate($invoice_row['service_date_begin'], '+', $number_days+$interval_days, 'd');

                        // Now get tax changes in the current interval.

                        $taxchanges = $this->getTaxChanges($invoice_row['taxclass'], $interval_begin, $interval_end);

                        // Maybe taxrate already changed on the first day of our interval.

                        if($pricing['taxrate'] != $taxchanges[0]['taxrate'])
                        {
                            $pricing['service_date_end'] = $taxchanges[0]['valid_to'];
                            $invoice_new[] = array_merge($invoice_row, $pricing);
                            $pricing['taxrate'] = $taxchanges[0]['taxrate'];
                            $pricing['total_fee'] = 0;
                        }

                        // Anyways, we don't need the current taxrate.

                        unset($taxchanges[0]);

                        // Walk through all taxchanges...

                        foreach($taxchanges as $valid_from => $taxchange)
                        {
                            $tax_days = calculateDayDifference($interval_begin, $valid_from);

                            // Subtract the days we are going to tax from the remaining days in our interval

                            $remaining-= $tax_days;

                            // total_fee is a fraction of the interval fee

                            $pricing['total_fee']+= $invoice_row['interval_fee']*($tax_days/$interval_days);

                            // Set ending day of row to day when tax changed

                            $pricing['service_date_end'] = $taxchange['valid_from'];

                            // And add a new row to invoice

                            $invoice_new[] = array_merge($invoice_row, $pricing);

                            // Next line begins with the day when tax changed

                            $interval_begin = $pricing['service_date_begin'] = $taxchange['valid_from'];
                            $pricing['taxrate'] = $taxchange['taxrate'];
                            $pricing['total_fee'] = 0;
                        }

                        // Incruse number_days (loop condition value)

                        $number_days+= $interval_days;

                        // also update service_date_begin_array, so self::getDaysForInterval returns a correct value in our loop condition

                        $service_date_begin_array[$invoice_row['interval_type']]+= $invoice_row['interval_length'];

                        // Finally add the remaining fraction to total_fee

                        $pricing['total_fee']+= $invoice_row['interval_fee']*($remaining/$interval_days);
                    }

                    // Last element, so use our real service_date_end

                    unset($pricing['service_date_end']);

                    // If there are still have some days left (e.g. when service was terminated during an interval)...

                    if($days_diff > $number_days)
                    {
                        // ... calculate last total_fee...

                        $pricing['total_fee']+= $invoice_row['interval_fee']*(($days_diff-$number_days)/$this->getDaysForInterval($invoice_row['interval_length'], $invoice_row['interval_type'], $service_date_begin_array));
                    }

                    // ... and finally add last line.

                    $invoice_new[] = array_merge($invoice_row, $pricing);
                }
            }
            else
            {
                $invoice_new[] = $invoice_row;
            }
        }

        return $invoice_new;
    }

    /**
     * Due to the weirdness in our calendar we need a special
     * method for gathering the number days in an interval.
     *
     * @param int    Length of interval
     * @param string Type of interval, might be 'd' for day,'m' for month or 'y' for year
     * @param array  Date when service began, in array form: array( 'd' => int, 'm' => int, 'y' => int )
     * @return int   Number of days in the interval
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function getDaysForInterval($interval_length, $interval_type, $service_date_begin_array)
    {
        $returnval = 0;

        switch($interval_type)
        {
        case 'y':
            for ($i = 1;$i <= $interval_length;$i++)
            {
                $returnval+= getDaysForYear((int)$service_date_begin_array['m'], (int)$service_date_begin_array['y']+($i-1));
            }

            break;
        case 'm':
            for ($i = 1;$i <= $interval_length;$i++)
            {
                $returnval+= getDaysForMonth((int)$service_date_begin_array['m']+($i-1), (int)$service_date_begin_array['y']);
            }

            break;
        case 'd':
        default:
            $returnval = $interval_length;
            break;
        }

        return $returnval;
    }
}

?>