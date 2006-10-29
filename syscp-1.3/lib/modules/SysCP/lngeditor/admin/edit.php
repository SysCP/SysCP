<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Florian Aders <eleras@syscp.org>
 * @copyright  (c) 2006 Florian Aders
 * @package    Syscp.Modules
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 *
 */

if($this->User['change_serversettings'] == '1')
{
    if(isset($_GET['modulename'])
       && $_GET['modulename'] != ''
       && isset($_GET['translateto'])
       && $_GET['translateto'] != ''
       && isset($_GET['vendorname'])
       && $_GET['vendorname'] != '')
    {
        $vendor = $_GET['vendorname'];
        $module = $_GET['modulename'];
        $translateto = $_GET['translateto'];

        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            ksort($_POST);
            $lngFile = sprintf('%slng/%s/%s/lang.%s.php', SYSCP_PATH_LIB, $vendor, $module, strtolower($translateto));
            $lngString = '<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     The SysCP Team <team@syscp.org>
 * @copyright  (c) 2006 The SysCP Team
 * @package    Syscp.Translation
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 *
 */

/**
 * Normal strings
 */'."\n\n";
            $errorquestion = array();
            $search = array(
                "ä",
                "Ä",
                "ö",
                "Ö",
                "ü",
                "Ü",
                "\"",
                "'"
            );
            $replace = array(
                "&auml;",
                "&Auml;",
                "&ouml;",
                "&Ouml;",
                "&uuml;",
                "&Uuml;",
                "&quot;",
                "\'"
            );
            $this->ValidationHandler->addValidator('lngstring', new Syscp_Validator_Regex('/^[^\r\0]*$/'));
            foreach($_POST as $key => $value)
            {
                if(strpos($key, $vendor."-".$module."-") === 0)
                {
                    $key = str_replace($vendor."-".$module."-", '', $key);
                    $pos1 = strpos($key, "error-");
                    $pos2 = strpos($key, "question-");

                    if($pos1 === 0
                        || $pos2 === 0)
                    {
                        $errorquestion[$key] = $value;
                    }
                    else
                    {
                        if(!$this->ValidationHandler->getValidator('lngstring')->validate($value)
                            && $value != '')
                        {
                            $this->TemplateHandler->showError('SysCP.globallang.stringiswrong', $key);
                            return false;
                        }

                        $value = str_replace($search, $replace, $value);
                        if ($value != "")
                        {
                            $lngString.= '$lng[\''.$vendor.'\'][\''.$module.'\'][\''.$key.'\'] = \''.$value.'\';'."\n";
                        }
                    }
                }
            }

            $lngString.= '
/**
 * Errors & Questions
 */

';
            foreach($errorquestion as $key => $value)
            {
                $key = str_replace("-", "']['", $key);
                $value = str_replace($search, $replace, $value);

                if(!$this->ValidationHandler->getValidator('lngstring')->validate($value)
                   && $value != '')
                {
                    $this->TemplateHandler->showError('SysCP.globallang.stringiswrong', $key);
                    return false;
                }

                if ($value != "")
                {
                    $lngString.= '$lng[\''.$vendor.'\'][\''.$module.'\'][\''.$key.'\'] = \''.$value.'\';'."\n";
                }
            }

            $this->ValidationHandler->removeValidator('lngstring');

            if(Syscp::isWriteableFile($lngFile))
            {
                file_put_contents($lngFile, $lngString);
            }
            else
            {
                $this->TemplateHandler->set('lngFile', $lngFile);
                $this->TemplateHandler->set('lngString', nl2br(htmlspecialchars($lngString)));
                $this->TemplateHandler->setTemplate('SysCP/lngeditor/admin/error.tpl');
                $errorReplace = $this->TemplateHandler->fetch();
                $this->TemplateHandler->showError('SysCP.lngeditor.error.filecouldnotbewritten', $errorReplace);
                return false;
            }

            $this->redirectTo(array(
                'module' => 'lngeditor',
                'action' => 'list'
            ));
        }
        else
        {
            $translateToHandler = new Syscp_Handler_L10n();
            $params = array(
                'DatabaseHandler' => $this->DatabaseHandler,
                'defaultLanguage' => 'English',
                'languageFilelist' => $this->languageConfig
            );
            $translateToHandler->initialize($params);
            $translateToHandler->setLanguage($translateto);
            $translateFromHandler = new Syscp_Handler_L10n();
            $params = array(
                'DatabaseHandler' => $this->DatabaseHandler,
                'defaultLanguage' => 'English',
                'languageFilelist' => $this->languageConfig
            );
            $translateFromHandler->initialize($params);
            $translateFromHandler->setLanguage('English');
            $this->TemplateHandler->set('english', $translateFromHandler->getAllSubstrings($vendor.'.'.$module));
            $this->TemplateHandler->set('translate', $translateToHandler->getAllSubstrings($vendor.'.'.$module));
            $this->TemplateHandler->set('translateto', $translateto);
            $this->TemplateHandler->set('lngTo', $translateToHandler->get('SysCP.globallang.lang_name'));
            $this->TemplateHandler->set('lngFrom', $translateFromHandler->get('SysCP.globallang.lang_name'));
            $this->TemplateHandler->set('vendorname', $vendor);
            $this->TemplateHandler->set('modulename', $module);
            $this->TemplateHandler->setTemplate('SysCP/lngeditor/admin/edit.tpl');
        }
    }
    else
    {
        $this->redirectTo(array(
            'module' => 'lngeditor',
            'action' => 'list'
        ));
    }
}

