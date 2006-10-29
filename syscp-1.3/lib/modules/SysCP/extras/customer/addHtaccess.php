<?php

if((isset($_POST['send']))
   && ($_POST['send'] == 'send'))
{
    $path = makeCorrectDir(addslashes($_POST['path']));
    $userpath = $path;
    $path = $this->User['homedir'].$path;
    $path_dupe_check = $this->DatabaseHandler->query_first("SELECT `id`, `path` FROM `".TABLE_PANEL_HTACCESS."` WHERE `path`='$path' AND `customerid`='".$this->User['customerid']."'");

    if(!$_POST['path'])
    {
        $this->TemplateHandler->showError('SysCP.extras.error.invalidpath');
        return false;
    }

    if(($_POST['error404path'] == '')
       || (preg_match('/^https?\:\/\//', $_POST['error404path'])))
    {
        $error404path = $_POST['error404path'];
    }
    else
    {
        $this->TemplateHandler->showError('SysCP.extras.error.mustbeurl');
        return false;
    }

    if(($_POST['error403path'] == '')
       || (preg_match('/^https?\:\/\//', $_POST['error403path'])))
    {
        $error403path = $_POST['error403path'];
    }
    else
    {
        $this->TemplateHandler->showError('SysCP.extras.error.mustbeurl');
        return false;
    }

    if(($_POST['error500path'] == '')
       || (preg_match('/^https?\:\/\//', $_POST['error500path'])))
    {
        $error500path = $_POST['error500path'];
    }
    else
    {
        $this->TemplateHandler->showError('SysCP.extras.error.mustbeurl');
        return false;
    }

    //					if (    ($_POST['error401path'] == '')
    //					     || (preg_match('/^https?\:\/\//', $_POST['error401path']) )
    //					   )
    //					{
    //						$error401path = $_POST['error401path'];
    //					}
    //					else
    //					{
    //						standard_error('mustbeurl');
    //					}

    if(!is_dir($path))
    {
        $this->TemplateHandler->showError('SysCP.extras.error.directorymustexist', $userpath);
        return false;
    }

    if($path_dupe_check['path'] == $path)
    {
        $this->TemplateHandler->showError('SysCP.extras.error.docpathdupe', $userpath);
        return false;
    }
    elseif($path == '')
    {
        $this->TemplateHandler->showError('SysCP.globallang.error.patherror');
        return false;
    }
    else
    {
        $this->DatabaseHandler->query('INSERT INTO `'.TABLE_PANEL_HTACCESS.'` '.'       (`customerid`, '.'        `path`, '.'        `options_indexes`, '.'        `error404path`, '.'        `error403path`, '.

        //						'        `error401path`, ' .

        '        `error500path` '.'       ) '.'VALUES ("'.$this->User['customerid'].'", '.'        "'.$path.'", '.'        "'.$_POST['options_indexes'].'", '.'        "'.$error404path.'", '.'        "'.$error403path.'", '.

        //						'        "'.$error401path.'", ' .

        '        "'.$error500path.'" '.'       )');
        $htaccessID = $this->DatabaseHandler->insert_id();
        $this->HookHandler->call('OnCreateHTAccess', array(
            'id' => $htaccessID,
            'path' => $path
        ));
        $this->redirectTo(array(
            'module' => 'extras',
            'action' => 'listHtaccess'
        ));
    }
}
else
{
    $pathSelect = makePathfield($this->User['homedir'], $this->User['guid'], $this->User['guid'], $this->ConfigHandler->get('panel.pathedit'));
    $options_indexes = array(
        1 => $this->L10nHandler->get('SysCP.globallang.yes'),
        0 => $this->L10nHandler->get('SysCP.globallang.no')
    );
    $options_indexes_sel = 1;
    $this->TemplateHandler->set('pathSelect', $pathSelect);
    $this->TemplateHandler->set('options_indexes', $options_indexes);
    $this->TemplateHandler->set('options_indexes_sel', $options_indexes_sel);
    $this->TemplateHandler->setTemplate('SysCP/extras/customer/htaccess_add.tpl');
}

