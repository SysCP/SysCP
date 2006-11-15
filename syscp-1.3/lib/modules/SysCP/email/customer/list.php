<?php

// The list email accounts action, rewritten martin@2006/06/01
// Init Vars

$tree = array();

// Query the email table for rows owned by the current User

$result = $this->DatabaseHandler->query('SELECT `'.TABLE_MAIL_VIRTUAL.'`.`id`, '.'       `'.TABLE_MAIL_VIRTUAL.'`.`domainid`, '.'       `'.TABLE_MAIL_VIRTUAL.'`.`email`, '.'       `'.TABLE_MAIL_VIRTUAL.'`.`email_full`, '.'       `'.TABLE_MAIL_VIRTUAL.'`.`iscatchall`, '.'       `'.TABLE_MAIL_VIRTUAL.'`.`destination`, '.'       `'.TABLE_MAIL_VIRTUAL.'`.`popaccountid`, '.'       `'.TABLE_PANEL_DOMAINS.'`.`domain` '.'FROM `'.TABLE_MAIL_VIRTUAL.'` '.'LEFT JOIN `'.TABLE_PANEL_DOMAINS.'` '.'  ON (`'.TABLE_MAIL_VIRTUAL.'`.`domainid` = `'.TABLE_PANEL_DOMAINS.'`.`id`)'.' WHERE `'.TABLE_MAIL_VIRTUAL.'`.`customerid`="'.$this->User['customerid'].'" '.' AND `'.TABLE_PANEL_DOMAINS.'`.`aliasdomain` IS NULL '.'ORDER BY `domainid`, `email` ASC');
$emailCount = 0;

while(false !== ($row = $this->DatabaseHandler->fetchArray($result)))
{
    $parentAliasDomains = '';
    $queryAliases  = 'SELECT `domain`, `aliasdomain` FROM `%s` WHERE `aliasdomain`=%s;';
    $queryAliases  = sprintf($queryAliases, TABLE_PANEL_DOMAINS, $row['domainid']);
    $resultAliases = $this->DatabaseHandler->query($queryAliases);

    while(false !== ($rowAliases = $this->DatabaseHandler->fetchArray($resultAliases)))
    {
        $parentAliasDomains .= $rowAliases['domain'];
    }

    // increment the counter

    $emailCount++;

    // lets start and decode some idna thingies

    $row['email'] = $this->IdnaHandler->decode($row['email']);
    $row['email_full'] = $this->IdnaHandler->decode($row['email_full']);
    $row['domain'] = $this->IdnaHandler->decode($row['domain']);
    if($parentAliasDomains)
    {
        $row['domain'] .= ' ('.$parentAliasDomains.')';
    }

    // idna decode on the destination takes some more efford

    $row['destination'] = explode(' ', $row['destination']);
    foreach($row['destination'] as $key => $value)
    {
        $row['destination'][$key] = $this->IdnaHandler->decode($value);

        // we need to check if the destination is equal to the email,
        // if it's the case, we remove the entry from the forwarder list
        // postfix requires a forwarder to the email itself.

        if($value == $row['email_full'])
        {
            unset($row['destination'][$key]);
        }
    }

    $row['destination'] = join(', ', $row['destination']);

    // lets build a tree arrayy('domainname' => array() )

    $tree[$row['domain']][] = $row;
}

// an email domain counter, counting the amount of domains with email
// capabilities the customer has

$emaildomainCount = 0;
$emaildomainCount = $this->DatabaseHandler->queryFirst('SELECT COUNT(`id`) AS `count` '.'FROM `'.TABLE_PANEL_DOMAINS.'` '.'WHERE `customerid`   = \''.$this->User['customerid'].'\' '.'  AND `isemaildomain`= \'1\' '.'ORDER BY `domain` ASC');
$emaildomainCount = $emaildomainCount['count'];
$this->TemplateHandler->set('emailTree', $tree);
$this->TemplateHandler->set('emaildomainCount', $emaildomainCount);
$this->TemplateHandler->set('emailCount', $emailCount);
$this->TemplateHandler->setTemplate('SysCP/email/customer/list.tpl');
