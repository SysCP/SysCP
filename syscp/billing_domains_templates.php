<?php

/**
 * Manage Domain Templates (billing_domains_templates.php)
 *
 * This file manages domain templates (list, add, delete, edit)
 *
 * @author Florian Lippert <flo@syscp.org>
 * @version 1.0
 */

define('AREA', 'admin');

/**
 * Include our init.php, which manages Sessions, Language etc.
 */

require ("./lib/init.php");

if(isset($_POST['id']))
{
    $id = intval($_POST['id']);
}
elseif(isset($_GET['id']))
{
    $id = intval($_GET['id']);
}

if($userinfo['customers_see_all'] == '1')
{
    $taxclasses = array();
    $taxclasses_option = '';
    $taxclasses_result = $db->query('SELECT `classid`, `classname` FROM `' . TABLE_BILLING_TAXCLASSES . '` ');

    while($taxclasses_row = $db->fetch_array($taxclasses_result))
    {
        $taxclasses[$taxclasses_row['classid']] = $taxclasses_row['classname'];
        $taxclasses_option.= makeoption($taxclasses_row['classname'], $taxclasses_row['classid']);
    }

    if($action == '')
    {
        $fields = array(
            'tld' => $lng['domains']['topleveldomain'],
            'valid_from' => $lng['service']['valid_from'],
            'valid_to' => $lng['service']['valid_to'],
            'interval_fee' => $lng['service']['interval_fee'],
            'interval_length' => $lng['service']['interval_length'],
            'setup_fee' => $lng['service']['setup_fee'],
        );
        $paging = new paging($userinfo, $db, TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
        $customers = '';
        $result = $db->query("SELECT *  " . "FROM `" . TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES . "` " . $paging->getSqlWhere() . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
        $paging->setEntries($db->num_rows($result));
        $sortcode = $paging->getHtmlSortCode($lng);
        $arrowcode = $paging->getHtmlArrowCode($filename . '?s=' . $s);
        $searchcode = $paging->getHtmlSearchCode($lng);
        $pagingcode = $paging->getHtmlPagingCode($filename . '?s=' . $s);
        $i = 0;
        $domainstemplates = '';

        while($row = $db->fetch_array($result))
        {
            if($paging->checkDisplay($i))
            {
                $row = htmlentities_array($row);
                eval("\$domainstemplates.=\"" . getTemplate("billing/domains_templates_row") . "\";");
            }

            $i++;
        }

        eval("echo \"" . getTemplate("billing/domains_templates") . "\";");
    }

    if($action == 'add')
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            $tld = validate($_POST['tld'], $lng['domains']['topleveldomain']);

            if($tld == '')
            {
                standard_error('notallreqfieldsorerrors');
                exit;
            }

            if($_POST['valid_from'] != '0')
            {
                $valid_from = validate($_POST['valid_from'], $lng['service']['valid_from'], '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
            }
            else
            {
                $valid_from = '0';
            }

            if($_POST['valid_to'] != '0')
            {
                $valid_to = validate($_POST['valid_to'], $lng['service']['valid_to'], '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
            }
            else
            {
                $valid_to = '0';
            }

            if(isset($taxclasses[$_POST['taxclass']]))
            {
                $taxclass = $_POST['taxclass'];
            }
            else
            {
                $taxclass_keys = array_keys($taxclasses);
                $taxclass = $taxclass_keys[0];
                unset($taxclass_keys);
            }

            $interval_fee = doubleval(str_replace(',', '.', $_POST['interval_fee']));
            $interval_length = intval($_POST['interval_length']);
            $interval_type = (in_array($_POST['interval_type'], getIntervalTypes('array')) ? $_POST['interval_type'] : 'm');
            $interval_payment = intval($_POST['interval_payment']);
            $setup_fee = doubleval(str_replace(',', '.', $_POST['setup_fee']));

            if($interval_payment != '1')
            {
                $interval_payment = '0';
            }

            $db->query('INSERT INTO `' . TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES . '` (`tld`, `valid_from`, `valid_to`, `interval_fee` , `interval_length` , `interval_type` , `interval_payment` , `setup_fee`, `taxclass`) VALUES( \'' . $db->escape($tld) . '\', \'' . $db->escape($valid_from) . '\', \'' . $db->escape($valid_to) . '\', \'' . $db->escape($interval_fee) . '\', \'' . $db->escape($interval_length) . '\', \'' . $db->escape($interval_type) . '\', \'' . $db->escape($interval_payment) . '\', \'' . $db->escape($setup_fee) . '\', \'' . $db->escape($taxclass) . '\' ) ');
            redirectTo($filename, Array(
                's' => $s
            ));
        }
        else
        {
            $valid_from = date('Y-m-d');
            $valid_to = date('Y-m-d');
            $interval_type = getIntervalTypes('option');
            $interval_payment = makeoption($lng['service']['interval_payment_prepaid'], '0', '0', true) . makeoption($lng['service']['interval_payment_postpaid'], '1', '0', true);
            eval("echo \"" . getTemplate("billing/domains_templates_add") . "\";");
        }
    }

    if($action == 'delete')
    {
        $result = $db->query_first('SELECT * FROM `' . TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES . '` WHERE `id` = \'' . $id . '\' ');

        if($result['id'] == $id
           && $id != '0')
        {
            if(isset($_POST['send'])
               && $_POST['send'] == 'send')
            {
                $db->query('DELETE FROM `' . TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES . '` WHERE `id` = \'' . $id . '\' ');
                redirectTo($filename, Array(
                    's' => $s
                ));
            }
            else
            {
                ask_yesno('billing_domains_template_reallydelete', $filename, array(
                    'id' => $id,
                    'action' => $action
                ), $result['tld'] . ' (' . $result['valid_from'] . ' - ' . $result['valid_to'] . ')');
            }
        }
    }

    if($action == 'edit')
    {
        $result = $db->query_first('SELECT * FROM `' . TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES . '` WHERE `id` = \'' . $id . '\' ');

        if($result['id'] == $id
           && $id != '0')
        {
            if(isset($_POST['send'])
               && $_POST['send'] == 'send')
            {
                if($_POST['valid_from'] != '0')
                {
                    $valid_from = validate($_POST['valid_from'], $lng['service']['valid_from'], '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
                }
                else
                {
                    $valid_from = '0';
                }

                if($_POST['valid_to'] != '0')
                {
                    $valid_to = validate($_POST['valid_to'], $lng['service']['valid_to'], '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
                }
                else
                {
                    $valid_to = '0';
                }

                if(isset($taxclasses[$_POST['taxclass']]))
                {
                    $taxclass = $_POST['taxclass'];
                }
                else
                {
                    $taxclass_keys = array_keys($taxclasses);
                    $taxclass = $taxclass_keys[0];
                    unset($taxclass_keys);
                }

                $interval_fee = doubleval(str_replace(',', '.', $_POST['interval_fee']));
                $interval_length = intval($_POST['interval_length']);
                $interval_type = (in_array($_POST['interval_type'], getIntervalTypes('array')) ? $_POST['interval_type'] : 'm');
                $interval_payment = intval($_POST['interval_payment']);
                $setup_fee = doubleval(str_replace(',', '.', $_POST['setup_fee']));

                if($interval_payment != '1')
                {
                    $interval_payment = '0';
                }

                $db->query('UPDATE `' . TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES . '` SET `valid_from` = \'' . $db->escape($valid_from) . '\', `valid_to` = \'' . $db->escape($valid_to) . '\', `interval_fee` = \'' . $db->escape($interval_fee) . '\', `interval_length` = \'' . $db->escape($interval_length) . '\', `interval_type` = \'' . $db->escape($interval_type) . '\', `interval_payment` = \'' . $db->escape($interval_payment) . '\', `setup_fee` = \'' . $db->escape($setup_fee) . '\', `taxclass` = \'' . $db->escape($taxclass) . '\' WHERE `id` = \'' . $id . '\' ');
                redirectTo($filename, Array(
                    's' => $s
                ));
            }
            else
            {
                $interval_type = getIntervalTypes('option', $result['interval_type']);
                $interval_payment = makeoption($lng['service']['interval_payment_prepaid'], '0', $result['interval_payment'], true) . makeoption($lng['service']['interval_payment_postpaid'], '1', $result['interval_payment'], true);
                $taxclasses_option = '';
                foreach($taxclasses as $classid => $classname)
                {
                    $taxclasses_option.= makeoption($classname, $classid, $result['taxclass']);
                }

                eval("echo \"" . getTemplate("billing/domains_templates_edit") . "\";");
            }
        }
    }
}

?>