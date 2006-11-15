<?php

function reverseDomain($domain)
{
    $result = '';
    $result = split('\.', $domain);
    $result = array_reverse($result);
    $result = join('.', $result);
    return $result;
}

// This part has been rewriten completly at 2006/06/02 --martin
// query all domains from the domainlist owned by this user

$result = $this->DatabaseHandler->query("SELECT `d`.`id`, "."       `d`.`customerid`, "."       `d`.`domain`, "."       `d`.`documentroot`, "."       `d`.`isemaildomain`, "."       `d`.`caneditdomain`, "."       `d`.`iswildcarddomain`, "."       `d`.`parentdomainid`, "."		`ad`.`domain` AS `aliasdomain`, "."       `pd`.`domain` AS `parentdomain` "."FROM `".TABLE_PANEL_DOMAINS."` `d` "."LEFT JOIN `".TABLE_PANEL_DOMAINS."` `ad` ON `d`.`aliasdomain`=`ad`.`id` "."LEFT JOIN `".TABLE_PANEL_DOMAINS."` `pd` ON `d`.`parentdomainid`=`pd`.`id` "."WHERE `d`.`customerid`='".$this->User['customerid']."' "."  AND `d`.`id` <> ".$this->User['standardsubdomain'] . " AND `d`.`aliasdomain` IS NULL");
$domainList = array();

// counter for the amount of parentdomains

$parentDomainCount = 0;

while(false !== ($row = $this->DatabaseHandler->fetchArray($result)))
{
    // we put the domains into an array in the form of
    // array(parentdomain => array(domain => domaindata))
    // if the domain has no parentdomain the resulting array will be
    // array(domain => array(domain => domaindata)
    // please note the array keys domain and parentdomain are
    // used in reversed order, e.g. example.org get org.example
    // prepare some values in the row for displaying

    $row['domain'] = $this->IdnaHandler->decode($row['domain']);
    $row['parentdomain'] = $this->IdnaHandler->decode($row['parentdomain']);

    // we further need to remove the customerroot from the documentroot

    $row['documentroot'] = str_replace($this->User['homedir'], '', $row['documentroot']);

    // we now reverse the parentdomain to arpa form

    $parentdomain = reverseDomain($row['parentdomain']);
    $domain = reverseDomain($row['domain']);

    // and decide if this domain has a parent domain
    // if not, the domain itself is a parent domain

    if($parentdomain == '')
    {
        // set the parentdomain to this domain

        $parentdomain = $domain;

        // increase parentdomain counter

        $parentDomainCount++;
    }

    // we need to check if this domain has aliasdomains, we simply to it
    // using an additional query

    $checkQuery = 'SELECT COUNT(`id`) AS `count` FROM `%s` WHERE `aliasdomain`=\'%s\'';
    $checkQuery = sprintf($checkQuery, TABLE_PANEL_DOMAINS, $row['id']);
    $checkResult = $this->DatabaseHandler->queryFirst($checkQuery);

    if($checkResult['count'] > 0)
    {
        $row['hasAliasdomains'] = true;
    }
    else
    {
        $row['hasAliasdomains'] = false;
    }

    // put the row in the resulting array

    $domainList[$parentdomain][$domain] = $row;
}

// lets sort the domainList, we start sorting the first index, and after
// that, we sort the second index of each first index
// sort first level

ksort($domainList);

// sort second level

foreach($domainList as $key => $list)
{
    ksort($domainList[$key]);
}

// and reverse the indices (1st and 2nd level) of the resulting array

$finalDomainList = array();
foreach($domainList as $parentKey => $list)
{
    $parentAliasDomains = '';
    $queryAliases  = 'SELECT `domain`, `aliasdomain` FROM `%s` WHERE `aliasdomain`=%s;';
    $queryAliases  = sprintf($queryAliases, TABLE_PANEL_DOMAINS, $list[$parentKey]['id']);
    $resultAliases = $this->DatabaseHandler->query($queryAliases);

    while(false !== ($rowAliases = $this->DatabaseHandler->fetchArray($resultAliases)))
    {
        $parentAliasDomains .= $rowAliases['domain'];
    }

    foreach($list as $domainKey => $domainEntry)
    {
        $parentDomain = reverseDomain($parentKey);
        if($parentAliasDomains)
        {
            $parentDomain .= ' ('.$parentAliasDomains.')';
        }
        $domain = reverseDomain($domainKey);
        $finalDomainList[$parentDomain][$domain] = $domainEntry;
    }
}

// put the results int the template and initialize the template to use

$this->TemplateHandler->set('domainList', $finalDomainList);
$this->TemplateHandler->set('parentDomainCount', $parentDomainCount);
$this->TemplateHandler->setTemplate('SysCP/domains/customer/list.tpl');
