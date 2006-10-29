<?php

if(isset($_POST['subjectid']))
{
    $subjectid = intval($_POST['subjectid']);
    $mailbodyid = intval($_POST['mailbodyid']);
}
elseif(isset($_GET['subjectid']))
{
    $subjectid = intval($_GET['subjectid']);
    $mailbodyid = intval($_GET['mailbodyid']);
}

$available_templates = array(
    'createcustomer',
    'createemailaccount'
);

if(isset($_POST['prepare'])
   && $_POST['prepare'] == 'prepare')
{
    $lang = addslashes(htmlentities(html_entity_decode($_POST['language'])));
    $templates = array();
    $result = $this->DatabaseHandler->query('SELECT `varname` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$this->User['adminid'].'\' AND `language`=\''.$lang.'\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');

    while(($row = $this->DatabaseHandler->fetch_array($result)) != false)
    {
        $templates[] = str_replace('_subject', '', $row['varname']);
    }

    $templates = array_diff($available_templates, $templates);
    $template_options = array();
    foreach($templates as $tpl)
    {
        $template_options[$tpl] = $this->L10nHandler->get('SysCP.templates.'.$tpl);
    }

    //				foreach($templates as $template) {
    //				$template_options.=makeoption($lng['admin']['templates'][$template],$template);
    //		}

    $this->TemplateHandler->set('template_options', $template_options);
    $this->TemplateHandler->set('lang', $lang);
    $this->TemplateHandler->setTemplate('SysCP/templates/admin/add2.tpl');

    //eval("echo \"".getTemplate("templates/templates_add_2")."\";");
}
else

if(isset($_POST['send'])
   && $_POST['send'] == 'send')
{
    $language = addslashes(htmlentities(html_entity_decode($_POST['language'])));
    $template = addslashes($_POST['template']);
    $subject = htmlentities(addslashes($_POST['subject']));
    $mailbody = htmlentities(addslashes($_POST['mailbody']));
    $templates = array();
    $result = $this->DatabaseHandler->query('SELECT `varname` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$this->User['adminid'].'\' AND `language`=\''.$language.'\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');

    while(($row = $this->DatabaseHandler->fetch_array($result)) != false)
    {
        $templates[] = str_replace('_subject', '', $row['varname']);
    }

    $templates = array_diff($available_templates, $templates);

    if($language == '')
    {
        $this->TemplateHandler->showError('SysCP.templates.error.nolangselected');
        return false;
    }
    elseif($subject == '')
    {
        $this->TemplateHandler->showError('SysCP.templates.error.nosubjectdefined');
        return false;
    }
    elseif($mailbody == '')
    {
        $this->TemplateHandler->showError('SysCP.templates.error.nomailbodydefined');
        return false;
    }
    elseif(array_search($template, $templates) === false)
    {
        $this->TemplateHandler->showError('SysCP.templates.error.templatenotfound');
        return false;
    }
    else
    {
        $result = $this->DatabaseHandler->query("INSERT INTO `".TABLE_PANEL_TEMPLATES."` (`adminid`, `language`, `templategroup`, `varname`, `value`)
					                   VALUES ('{$this->User['adminid']}', '$language', 'mails', '".$template."_subject','$subject')");
        $result = $this->DatabaseHandler->query("INSERT INTO `".TABLE_PANEL_TEMPLATES."` (`adminid`, `language`, `templategroup`, `varname`, `value`)
					                   VALUES ('{$this->User['adminid']}', '$language', 'mails', '".$template."_mailbody','$mailbody')");
        $this->redirectTo(array(
            'module' => 'templates',
            'action' => 'list'
        ));
    }
}
else
{
    $add = false;
    $language_options = '';
    $languages = $this->L10nHandler->getList();

    while(list($language_file, $language_name) = each($languages))
    {
        $templates = array();
        $result = $this->DatabaseHandler->query('SELECT `varname` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$this->User['adminid'].'\' AND `language`=\''.$language_name.'\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');

        while(($row = $this->DatabaseHandler->fetch_array($result)) != false)
        {
            $templates[] = str_replace('_subject', '', $row['varname']);
        }

        if(count(array_diff($available_templates, $templates)) > 0)
        {
            $add = true;
            $lang_list[$language_file] = $language_name;
        }
    }

    if($add)
    {
        $this->TemplateHandler->set('lang_list', $lang_list);
        $this->TemplateHandler->setTemplate('SysCP/templates/admin/add1.tpl');

        //eval("echo \"".getTemplate("templates/templates_add_1")."\";");
    }
    else
    {
        $this->TemplateHandler->showError('SysCP.templates.error.alltemplatesdefined');
        return false;
    }
}

