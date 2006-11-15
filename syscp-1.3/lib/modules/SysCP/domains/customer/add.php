<?php

if($this->User['subdomains_used'] < $this->User['subdomains']
   || $this->User['subdomains'] == '-1')
{
    if(isset($_POST['send'])
       && $_POST['send'] == 'send')
    {
        $subdomain = $this->IdnaHandler->encode(preg_replace(Array(
            '/\:(\d)+$/',
            '/^https?\:\/\//'
        ), '', addslashes($_POST['subdomain'])));
        $domain = $this->IdnaHandler->encode(addslashes($_POST['domain']));
        $domain_check = $this->DatabaseHandler->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `ipandport`, `isemaildomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$domain' AND `customerid`='".$this->User['customerid']."' AND `parentdomainid`='0' AND `iswildcarddomain`='0' AND `caneditdomain`='1' ");
        $completedomain = $subdomain.'.'.$domain;
        $completedomain_check = $this->DatabaseHandler->query_first("SELECT `id`, `customerid`, `domain`, `ipandport`, `documentroot`, `isemaildomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$completedomain' AND `customerid`='".$this->User['customerid']."' AND `caneditdomain` = '1'");
        $aliasdomain = intval($_POST['alias']);
        $aliasdomain_check = array(
            'id' => 0
        );

        if($aliasdomain != 0)
        {
            $aliasdomain_check = $this->DatabaseHandler->query_first('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$this->User['customerid'].'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$this->User['customerid'].'\' AND `d`.`id`=\''.$aliasdomain.'\'');
        }

        if(!preg_match('/^https?\:\/\//', $_POST['path']))
        {
            // store the path the user provided for an error message, which
            // may occur

            $userpath = addslashes($_POST['path']);

            // retrieve the default documentroot prefix

            $finalPath = $this->ConfigHandler->get('system.documentroot_prefix');

            // we need to replace some variables here

            $finalPath = str_replace('{LOGIN}', $this->User['loginname'], $finalPath);
            $finalPath = str_replace('{DOMAIN}', $completedomain, $finalPath);
            $finalPath = str_replace('{USERHOME}', $this->User['homedir'], $finalPath);

            // build the finalPath

            $finalPath = $finalPath.'/'.$_POST['path'];

            // ensure a correct path and assign to path

            $path = Syscp::makeCorrectDir($finalPath);

            //						$path = $this->User['homedir'].$path;
            //if(!is_dir($path))
            //{
            //	$this->TemplateHandler->showError('directorymustexist',$userpath);
            //	return false;
            //}
        }

        $path = addslashes($path);

        if($path == '')
        {
            $this->TemplateHandler->showError('SysCP.globallang.error.patherror');
            return false;
        }
        elseif($subdomain == '')
        {
            $this->TemplateHandler->showError(
                'SysCP.globallang.error.stringisempty',
                'domainname'
            );
            return false;
        }
        elseif($subdomain == 'www')
        {
            $this->TemplateHandler->showError('SysCP.domains.error.wwwnotallowed');
            return false;
        }
        elseif(preg_match('/.*\..*/', $subdomain))
        {
            $this->TemplateHandler->showError('SysCP.domains.error.subdomainiswrong', $subdomain);
            return false;
        }
        elseif($domain == '')
        {
            $this->TemplateHandler->showError('SysCP.domains.error.domaincantbeempty');
            return false;
        }
        elseif($completedomain_check['domain'] == $completedomain)
        {
            $this->TemplateHandler->showError('SysCP.domains.error.domainexistalready', $completedomain);
            return false;
        }
        elseif($domain_check['domain'] != $domain)
        {
            $this->TemplateHandler->showError('SysCP.domains.error.maindomainnonexist', $domain);
            return false;
        }
        elseif($aliasdomain_check['id'] != $aliasdomain)
        {
            $this->TemplateHandler->showError('SysCP.domains.error.domainisaliasorothercustomer');
            return false;
        }
        else
        {
            // set the access logfile

            $accessLog = $this->ConfigHandler->get('system.apache_access_log');
            $accessLog = str_replace('{LOGIN}', $this->User['loginname'], $accessLog);
            $accessLog = str_replace('{USERHOME}', $this->User['homedir'], $accessLog);
            $accessLog = str_replace('{DOMAIN}', $completedomain, $accessLog);
            $errorLog = $this->ConfigHandler->get('system.apache_error_log');
            $errorLog = str_replace('{LOGIN}', $this->User['loginname'], $errorLog);
            $errorLog = str_replace('{USERHOME}', $this->User['homedir'], $errorLog);
            $errorLog = str_replace('{DOMAIN}', $completedomain, $errorLog);
            $result = $this->DatabaseHandler->query("INSERT INTO `".TABLE_PANEL_DOMAINS."` (`customerid`, `domain`, `documentroot`, `ipandport`, `aliasdomain`, `parentdomainid`, `isemaildomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`, `access_logfile`, `error_logfile`) VALUES ('".$this->User['customerid']."', '$completedomain', '$path', '".$domain_check['ipandport']."', ".(($aliasdomain != 0) ? "'".$aliasdomain."'" : "NULL").", '".$domain_check['id']."', '0', '".$domain_check['openbasedir']."', '".$domain_check['safemode']."', '".$domain_check['speciallogfile']."', '".$domain_check['specialsettings']."', '".$accessLog."', '".$errorLog."')");
            $domainid = $this->DatabaseHandler->insert_id();
            $result = $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used`=`subdomains_used`+1 WHERE `customerid`='".$this->User['customerid']."'");
            $this->HookHandler->call('OnCreateDomain', array(
                'id' => $domainid
            ));
            $this->redirectTo(array(
                'module' => 'domains',
                'action' => 'list'
            ));
        }
    }
    else
    {
        $result = $this->DatabaseHandler->query("SELECT `id`, `domain`, `documentroot`, `isemaildomain`, `aliasdomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$this->User['customerid']."' AND `parentdomainid`='0' AND `iswildcarddomain`='0' AND `caneditdomain`='1' AND `aliasdomain` IS NULL ORDER BY `domain` ASC");
        $domains = array();

        while($row = $this->DatabaseHandler->fetchArray($result))
        {
            $row['domain'] = $this->IdnaHandler->decode($row['domain']);
            $domains[$row['domain']] = $row['domain'];
        }

        $aliasdomains = array();
        $aliasdomains[0] = $this->L10nHandler->get('SysCP.domains.noaliasdomain');
        $result = $this->DatabaseHandler->query("SELECT `d`.`id`, `d`.`domain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`id` <> `c`.`standardsubdomain` AND `d`.`customerid`=`c`.`customerid` AND `d`.`customerid`=".$this->User['customerid']." ORDER BY `d`.`domain` ASC");

        while($row = $this->DatabaseHandler->fetchArray($result))
        {
            $row['domain'] = $this->IdnaHandler->decode($row['domain']);
            $aliasdomains[$row['id']] = $row['domain'];
        }

        if($this->ConfigHandler->get('panel.customerpathedit') == "Yes")
        {
            $pathSelect = makePathfield($this->User['homedir'], $this->User['guid'], $this->User['guid'], $this->ConfigHandler->get('panel.pathedit'));
        }
        else
        {
            $pathSelect = '<input type="hidden" name="path" value="" size="30" />';
        }
        $documentrootPrefix = $this->ConfigHandler->get('system.documentroot_prefix');
        $documentrootPrefix = str_replace('{LOGIN}', $this->User['loginname'], $documentrootPrefix);
        $documentrootPrefix = str_replace('{USERHOME}', $this->User['homedir'], $documentrootPrefix);
        $documentrootPrefix = str_replace('{DOMAIN}', '{domainname}', $documentrootPrefix);
        $documentrootPrefix = str_replace($this->User['homedir'], '', $documentrootPrefix);
        $documentrootPrefix = Syscp::makeCorrectDir($documentrootPrefix);
        $this->TemplateHandler->set('domains', $domains);
        $this->TemplateHandler->set('aliasdomains', $aliasdomains);
        $this->TemplateHandler->set('pathSelect', $pathSelect);
        $this->TemplateHandler->set('documentrootPrefix', $documentrootPrefix);
        $this->TemplateHandler->setTemplate('SysCP/domains/customer/add.tpl');
    }
}

