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
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler.Template
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler.Template
 * @see        Syscp_Handler_Template
 */

interface Syscp_Handler_Template_Interface extends Syscp_Handler_Interface
{
    public function set($varName, $value);

    public function setTemplate($template);

    public function render();

    public function showError($error, $replacer = '');

    public function useTheme($theme);

    public function showQuestion($question, $params = array(), $replacer = '', $targetUrl = '');
}

