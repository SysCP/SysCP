<?php
$result = $this->DatabaseHandler->query_first("SELECT `d`.`id`, `d`.`customerid`, `d`.`domain`, `d`.`documentroot`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`parentdomainid`, `d`.`aliasdomain`, `pd`.`subcanemaildomain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_DOMAINS."` `pd` WHERE `d`.`customerid`='".$this->User['customerid']."' AND `d`.`id`='".$this->ConfigHandler->get('env.id')."' AND ((`d`.`parentdomainid`!='0' AND `pd`.`id`=`d`.`parentdomainid`) OR (`d`.`parentdomainid`='0' AND `pd`.`id`=`d`.`id`)) AND `d`.`caneditdomain`='1'");
$alias_check = $this->DatabaseHandler->query_first('SELECT COUNT(`id`) AS count FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$result['id'].'\'');
$alias_check = $alias_check['count'];

if(isset($result['customerid'])
   && $result['customerid'] == $this->User['customerid'])
{
    if(isset($_POST['send'])
       && $_POST['send'] == 'send')
    {
        $path = addslashes($_POST['path']);
        $aliasdomain = intval($_POST['alias']);

        if(!preg_match('/^https?\:\/\//', $path))
        {
            // store the path the user provided for an error message, which
            // may occur

            $userpath = addslashes($_POST['path']);

            // retrieve the default documentroot prefix

            $finalPath = $this->ConfigHandler->get('system.documentroot_prefix');

            // we need to replace some variables here

            $finalPath = str_replace('{LOGIN}', $this->User['loginname'], $finalPath);
            $finalPath = str_replace('{DOMAIN}', $result['domain'], $finalPath);
            $finalPath = str_replace('{USERHOME}', $this->User['homedir'], $finalPath);

            // build the finalPath

            $finalPath = $finalPath.'/'.$_POST['path'];

            // ensure a correct path and assign to path

            $path = Syscp::makeCorrectDir($finalPath);

            //if(!is_dir($path))
            //{
            //	$this->TemplateHandler->showError('directorymustexist',$userpath);
            //	return false;
            //}
        }

        if(isset($_POST['iswildcarddomain'])
           && $_POST['iswildcarddomain'] == '1'
           && $result['parentdomainid'] == '0'
           && $this->User['subdomains'] != '0')
        {
            $wildcarddomaincheck = $this->DatabaseHandler->query("SELECT `id` FROM `".TABLE_PANEL_DOMAINS."` WHERE `parentdomainid` = '{$result['id']}'");

            if($this->DatabaseHandler->num_rows($wildcarddomaincheck) != '0')
            {
                $this->TemplateHandler->showError('SysCP.domains.error.firstdeleteallsubdomains');
                return false;
            }

            $iswildcarddomain = '1';
        }
        else
        {
            $iswildcarddomain = '0';
        }

        if($result['parentdomainid'] != '0'
           && $result['subcanemaildomain'] == '1'
           && isset($_POST['isemaildomain']))
        {
            $isemaildomain = intval($_POST['isemaildomain']);
        }
        else
        {
            $isemaildomain = $result['isemaildomain'];
        }

        $aliasdomain_check = array(
            'id' => 0
        );

        if($aliasdomain != 0)
        {
            $aliasdomain_check = $this->DatabaseHandler->query_first('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$result['customerid'].'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$result['customerid'].'\' AND `d`.`id`=\''.$aliasdomain.'\'');
        }

        if($aliasdomain_check['id'] != $aliasdomain)
        {
            $this->TemplateHandler->showError('SysCP.domains.error.domainisaliasorothercustomer');
            return false;
        }

        if($path == '')
        {
            $this->TemplateHandler->showError('SysCP.globallang.error.patherror');
            return false;
        }
        else
        {
            if(($result['isemaildomain'] == '1')
               && ($isemaildomain == '0'))
            {
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$this->User['customerid']."' AND `domainid`='".$this->ConfigHandler->get('env.id')."'");
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `domainid`='".$this->ConfigHandler->get('env.id')."'");
            }

            if($path != $result['documentroot']
               || $isemaildomain != $result['isemaildomain']
               || $iswildcarddomain != $result['iswildcarddomain']
               || $aliasdomain != $result['aliasdomain'])
            {
                $this->HookHandler->call('OnUpdateDomain', array(
                    'id' => $this->ConfigHandler->get('env.id')
                ));
                $result = $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `documentroot`='$path', `isemaildomain`='$isemaildomain', `iswildcarddomain`='$iswildcarddomain', `aliasdomain`=".(($aliasdomain != 0 && $alias_check == 0) ? '\''.$aliasdomain.'\'' : 'NULL')." WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
            }

            $this->HookHandler->call('OnUpdateDomain', array(
                'id' => $this->ConfigHandler->get('env.id')
            ));
            $this->redirectTo(array(
                'module' => 'domains',
                'action' => 'list'
            ));
        }
    }
    else
    {
        $result['domain'] = $this->IdnaHandler->decode($result['domain']);
        $domains = array();
        $domains[0] = $this->L10nHandler->get('SysCP.domains.noaliasdomain');
        $result_domains = $this->DatabaseHandler->query("SELECT `d`.`id`, `d`.`domain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`id`<>'".$result['id']."' AND `c`.`standardsubdomain`<>`d`.`id` AND `d`.`customerid`='".$this->User['customerid']."' AND `c`.`customerid`=`d`.`customerid` ORDER BY `d`.`domain` ASC");

        while($row_domain = $this->DatabaseHandler->fetch_array($result_domains))
        {
            $domains[$row_domain['id']] = $this->IdnaHandler->decode($row_domain['domain']);
        }

        //					$result['documentroot']=str_replace($this->User['documentroot'],'',$result['documentroot']);

        $iswildcarddomain = array(
            1 => $this->L10nHandler->get('SysCP.globallang.yes'),
            0 => $this->L10nHandler->get('SysCP.globallang.no')
        );
        $isemaildomain = array(
            1 => $this->L10nHandler->get('SysCP.globallang.yes'),
            0 => $this->L10nHandler->get('SysCP.globallang.no')
        );
        $documentrootPrefix = $this->ConfigHandler->get('system.documentroot_prefix');
        $documentrootPrefix = str_replace('{LOGIN}', $this->User['loginname'], $documentrootPrefix);
        $documentrootPrefix = str_replace('{USERHOME}', $this->User['homedir'], $documentrootPrefix);
        $documentrootPrefix = str_replace('{DOMAIN}', $result['domain'], $documentrootPrefix);
        $documentrootPrefix = str_replace($this->User['homedir'], '', $documentrootPrefix);
        $documentrootPrefix = Syscp::makeCorrectDir($documentrootPrefix);
        $result['documentroot'] = str_replace($this->User['homedir'], '', $result['documentroot']);
        $result['documentroot'] = str_replace($documentrootPrefix, '', $result['documentroot']);
        $pathSelect = makePathfield($documentrootPrefix, $this->User['guid'], $this->User['guid'], $this->ConfigHandler->get('panel.pathedit'), $result['documentroot']);
        $this->TemplateHandler->set('documentrootPrefix', $documentrootPrefix);
        $this->TemplateHandler->set('result', $result);
        $this->TemplateHandler->set('iswildcarddomain', $iswildcarddomain);
        $this->TemplateHandler->set('isemaildomain', $isemaildomain);
        $this->TemplateHandler->set('pathSelect', $pathSelect);
        $this->TemplateHandler->set('domains', $domains);
        $this->TemplateHandler->set('alias_check', $alias_check);
        $this->TemplateHandler->setTemplate('SysCP/domains/customer/edit.tpl');

    }
}
else
{
    standard_error('SysCP.domains.error.canteditdomain');
}

