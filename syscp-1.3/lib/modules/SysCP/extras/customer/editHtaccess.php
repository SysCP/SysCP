<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    $result = $this->DatabaseHandler->query_first('SELECT * '.'FROM `'.TABLE_PANEL_HTACCESS.'` '.'WHERE `customerid` = "'.$this->User['customerid'].'" '.'  AND `id`         = "'.$this->ConfigHandler->get('env.id').'"');

    if((isset($result['customerid']))
       && ($result['customerid'] != '')
       && ($result['customerid'] == $this->User['customerid']))
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            $option_indexes = intval($_POST['options_indexes']);

            if($option_indexes != '1')
            {
                $option_indexes = '0';
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

            if(($option_indexes != $result['options_indexes'])
               || ($error404path != $result['error404path'])
               || ($error403path != $result['error403path'])

            //					     || ($error401path   != $result['error401path'])


               || ($error500path != $result['error500path']))
            {
                $this->DatabaseHandler->query('UPDATE `'.TABLE_PANEL_HTACCESS.'` '.'SET `options_indexes` = "'.$option_indexes.'",'.'    `error404path`    = "'.$error404path.'", '.'    `error403path`    = "'.$error403path.'", '.

                //							'    `error401path`    = "'.$error401path.'", ' .

                '    `error500path`    = "'.$error500path.'" '.'WHERE `customerid` = "'.$this->User['customerid'].'" '.'  AND `id` = "'.$this->ConfigHandler->get('env.id').'"');
                $this->HookHandler->call('OnUpdateHTAccess', array(
                    'id' => $this->ConfigHandler->get('env.id'),
                    'path' => $result['path']
                ));
            }

            $this->redirectTo(array(
                'module' => 'extras',
                'action' => 'listHtaccess'
            ));
        }
        else
        {
            $result['path'] = str_replace($this->User['homedir'], '', $result['path']);
            $result['error404path'] = $result['error404path'];
            $result['error403path'] = $result['error403path'];

            //					$result['error401path'] = $result['error401path'];

            $result['error500path'] = $result['error500path'];
            $options_indexes = array(
                1 => $this->L10nHandler->get('SysCP.globallang.yes'),
                0 => $this->L10nHandler->get('SysCP.globallang.no')
            );
            $this->TemplateHandler->set('options_indexes', $options_indexes);
            $this->TemplateHandler->set('result', $result);
            $this->TemplateHandler->setTemplate('SysCP/extras/customer/htaccess_edit.tpl');
        }
    }
}

