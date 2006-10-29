<?php

if(isset($_POST['send'])
   && $_POST['send'] == 'send')
{
    $_SESSION['theme'] = addslashes(htmlentities(html_entity_decode($_POST['new_theme'])));
    $this->redirectTo(array(
        'module' => 'index',
        'action' => 'index'
    ));
}
else
{
    $dh = opendir(SYSCP_PATH_LIB.'themes/');
    $themes = array();

    while(false !== ($dir = readdir($dh)))
    {
        if($dir != '.'
           && $dir != '..')
        {
            $themes[$dir] = $dir;
        }
    }

    closedir($dh);
    $this->TemplateHandler->set('theme_list', $themes);
    $this->TemplateHandler->set('theme_sel', $this->theme);
    $this->TemplateHandler->setTemplate('SysCP/index/customer/change_theme.tpl');
}