<?php

/**
 * Manage Taxrates (billing_taxrates.php)
 *
 * This file manages taxrates (list, add, delete, edit)
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
            'c.classname' => $lng['billing']['taxclass'],
            'r.taxrate' => $lng['billing']['taxrate'],
            'r.valid_from' => $lng['service']['valid_from'],
        );
        $paging = new paging($userinfo, $db, TABLE_BILLING_TAXRATES, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
        $customers = '';
        $result = $db->query("SELECT `r`.*, `c`.*  " . "FROM `" . TABLE_BILLING_TAXRATES . "` `r` LEFT JOIN `" . TABLE_BILLING_TAXCLASSES . "` `c` ON( `r`.`taxclass` = `c`.`classid` ) " . $paging->getSqlWhere() . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
        $paging->setEntries($db->num_rows($result));
        $sortcode = $paging->getHtmlSortCode($lng);
        $arrowcode = $paging->getHtmlArrowCode($filename . '?s=' . $s);
        $searchcode = $paging->getHtmlSearchCode($lng);
        $pagingcode = $paging->getHtmlPagingCode($filename . '?s=' . $s);
        $i = 0;
        $taxrates = '';

        while($row = $db->fetch_array($result))
        {
            if($paging->checkDisplay($i))
            {
                $row['taxrate_percent'] = $row['taxrate']*100;
                $row = htmlentities_array($row);
                eval("\$taxrates.=\"" . getTemplate("billing/taxrates_row") . "\";");
            }

            $i++;
        }

        eval("echo \"" . getTemplate("billing/taxrates") . "\";");
    }

    if($action == 'add')
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            if(isset($_POST['valid_from'])
               && $_POST['valid_from'] != '0'
               && $_POST['valid_from'] != '')
            {
                $valid_from = validate($_POST['valid_from'], html_entity_decode($lng['service']['valid_from']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
            }
            else
            {
                $valid_from = '0';
            }

            if(isset($_POST['taxrate']))
            {
                $taxrate = doubleval(str_replace(',', '.', $_POST['taxrate']));
            }
            elseif(isset($_POST['taxrate_percent']))
            {
                $taxrate = doubleval(str_replace(',', '.', $_POST['taxrate_percent']))/100;
            }
            else
            {
                $texrate = 0;
            }

            $taxclass = (isset($taxclasses[$_POST['taxclass']]) ? $_POST['taxclass'] : '1');
            $db->query('INSERT INTO `' . TABLE_BILLING_TAXRATES . '` (`taxclass`, `taxrate`, `valid_from`) VALUES( \'' . $db->escape($taxclass) . '\', \'' . $db->escape($taxrate) . '\', \'' . $db->escape($valid_from) . '\' ) ');
            redirectTo($filename, Array(
                's' => $s
            ));
        }
        else
        {
            $valid_from = date('Y-m-d');
            eval("echo \"" . getTemplate("billing/taxrates_add") . "\";");
        }
    }

    if($action == 'delete')
    {
        $result = $db->query_first('SELECT * FROM `' . TABLE_BILLING_TAXRATES . '` WHERE `taxid` = \'' . $id . '\' ');

        if($result['taxid'] == $id
           && $id != '0')
        {
            if(isset($_POST['send'])
               && $_POST['send'] == 'send')
            {
                $db->query('DELETE FROM `' . TABLE_BILLING_TAXRATES . '` WHERE `taxid` = \'' . $id . '\' ');
                redirectTo($filename, Array(
                    's' => $s
                ));
            }
            else
            {
                ask_yesno('billing_taxrate_reallydelete', $filename, array(
                    'id' => $id,
                    'action' => $action
                ), $taxclasses[$result['taxclass']] . ' - ' . $result['taxrate']);
            }
        }
    }

    if($action == 'edit')
    {
        $result = $db->query_first('SELECT * FROM `' . TABLE_BILLING_TAXRATES . '` WHERE `taxid` = \'' . $id . '\' ');

        if($result['taxid'] == $id
           && $id != '0')
        {
            if(isset($_POST['send'])
               && $_POST['send'] == 'send')
            {
                if(isset($_POST['valid_from'])
                   && $_POST['valid_from'] != '0'
                   && $_POST['valid_from'] != '')
                {
                    $valid_from = validate($_POST['valid_from'], html_entity_decode($lng['service']['valid_from']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
                }
                else
                {
                    $valid_from = '0';
                }

                if(isset($_POST['taxrate']))
                {
                    $taxrate = doubleval(str_replace(',', '.', $_POST['taxrate']));
                }
                elseif(isset($_POST['taxrate_percent']))
                {
                    $taxrate = doubleval(str_replace(',', '.', $_POST['taxrate_percent']))/100;
                }
                else
                {
                    $texrate = $result['taxrate'];
                }

                $taxclass = ((isset($_POST['taxclass']) && isset($taxclasses[$_POST['taxclass']])) ? $_POST['taxclass'] : '1');
                $db->query('UPDATE `' . TABLE_BILLING_TAXRATES . '` SET `taxclass` = \'' . $db->escape($taxclass) . '\', `taxrate` = \'' . $db->escape($taxrate) . '\', `valid_from` = \'' . $db->escape($valid_from) . '\' WHERE `taxid` = \'' . $id . '\' ');
                redirectTo($filename, Array(
                    's' => $s
                ));
            }
            else
            {
                $taxclasses_option = '';
                foreach($taxclasses as $classid => $classname)
                {
                    $taxclasses_option.= makeoption($classname, $classid, $result['taxclass']);
                }

                $result['taxrate_percent'] = $result['taxrate']*100;
                eval("echo \"" . getTemplate("billing/taxrates_edit") . "\";");
            }
        }
    }
}

?>