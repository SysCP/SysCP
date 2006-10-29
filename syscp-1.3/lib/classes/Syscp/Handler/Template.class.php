<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, the authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

Syscp::uses('Smarty.libs.Smarty');

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 */

class Syscp_Handler_Template implements Syscp_Handler_Template_Interface
{
    private $compileDir = '';
    private $themeDir = '';
    private $defaultTheme = '';
    private $currentTheme = '';
    private $Smarty = null;
    private $L10nHandler = null;
    private $Controller = null;
    const ERROR_MISSING_PARAM = 'You need to specify the %s parameter.';
    const ERROR_PARAM_TYPE = 'The param %s needs to be an instance of %s.';
    const ERROR_COMPILE_DIR = 'Cache %s does not exists or is not a writeable directory.';

    public function initialize($params = array())
    {
        // we don't want to repeat ourselves

        $required = array(
            'Controller' => 'Syscp_FrontController_Interface',
            'L10nHandler' => 'Syscp_Handler_L10n_Interface',
            'defaultTheme' => '',
            'compileDir' => '',
            'themeDir' => ''
        );
        foreach($required as $index => $instanceOf)
        {
            if(isset($params[$index]))
            {
                if($instanceOf == ''
                   || $params[$index] instanceof $instanceOf)
                {
                    $this->$index = $params[$index];
                }
                else
                {
                    $error = sprintf(self::ERROR_PARAM_TYPE, $index, $instanceOf);
                    throw new Syscp_Handler_Template_Exception($error);
                }
            }
            else
            {
                $error = sprintf(self::ERROR_MISSING_PARAM, $index);
                throw new Syscp_Handler_Template_Exception($error);
            }
        }

        $this->Smarty = new Smarty();
        $this->Smarty->register_function('l10n', array(
            $this,
            'smarty_function_l10n'
        ));
        $this->Smarty->register_function('url', array(
            $this,
            'smarty_function_url'
        ));
        $this->Smarty->force_compile = true;
    }

    public function setTemplate($template)
    {
        $this->Smarty->assign('body_template', $template);
    }

    public function set($varName, $value)
    {
        $this->Smarty->assign($varName, $value);
    }

    public function render()
    {
        if($this->currentTheme != '')
        {
            $this->useTheme($this->currentTheme);
        }
        else
        {
            $this->useTheme($this->defaultTheme);
        }

        $this->Smarty->display('main.tpl');
    }

    public function fetch()
    {
        if($this->currentTheme != '')
        {
            $this->useTheme($this->currentTheme);
        }
        else
        {
            $this->useTheme($this->defaultTheme);
        }

        $template = $this->Smarty->get_template_vars('body_template');
        $result = $this->Smarty->fetch($template);
        return $result;
    }

    public function useTheme($theme)
    {
        $this->currentTheme = $theme;
        $this->Smarty->template_dir = $this->themeDir.'/'.$this->currentTheme;
        $this->Smarty->compile_dir = $this->compileDir.'/'.$this->currentTheme;
        $compileDir = $this->Smarty->compile_dir;

        if(!file_exists($compileDir))
        {
            // try to create

            mkdir($compileDir, 0700, true);
        }
        elseif(!Syscp::isReadableDir($compileDir))
        {
            $error = sprintf(self::ERROR_COMPILE_DIR, $compileDir);
            throw new Syscp_Handler_Template_Exception($error);
        }

        // populate the imagedir used by the theme

        $this->Smarty->assign('imagedir', 'themes/'.$theme.'/');
    }

    public function showError($error, $replacer = '')
    {
        // Lets generate the full error message

        if($this->L10nHandler->exists($error))
        {
            $error = $this->L10nHandler->get($error);
        }
        else
        {
            $error = 'Unknown Error';
        }

        $error = sprintf($error, $replacer);
        $this->set('error', $error);
        $this->setTemplate('error.tpl');
    }

    public function showQuestion($question, $params = array(), $replacer = '', $targetUrl = '')
    {
        // retrieve the question from the translator

        $question = $this->L10nHandler->get($question);
        $question = sprintf($question, $replacer);
        $this->set('question', $question);
        $this->set('params', $params);

        if($targetUrl != '')
        {
            $this->set('url', $targetUrl);
        }
        else
        {
            $this->set('url', $this->Controller->createLink(array()));
        }

        $this->setTemplate('question_yn.tpl');
    }

    public function smarty_function_l10n($params, &$smarty)
    {
        return $this->L10nHandler->get($params['get']);
    }

    public function smarty_function_url($params, &$smarty)
    {
        return $this->Controller->createLink($params);
    }
}

