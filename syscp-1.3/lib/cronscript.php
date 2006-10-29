<?php

// We first need to check if the cronscript has really been executed in
// the CLI, if that's not the case, we will simply die with an error message
// displayed to the screen.

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
    die('This script will only work in the shell.');
}

// Now we need to create a lockfile, since only one execution of this script is
// allowed at a time and all previous executions must have been executed
// completly without an error.

$instanceID = md5(serialize($argv));

/**
 * Define basic name of the lockfile
 */

define('SYSCP_LOCK_NAME', '/var/run/syscp_cron.lock');

/**
 * Define actual lockfile name for this instance
 */

define('SYSCP_LOCK_FILE', sprintf('%s-%s-%s', SYSCP_LOCK_NAME, $instanceID, time()));

// create current lockfile

touch(SYSCP_LOCK_FILE);

// we have created the current lockfile, now we need to check
// if there is a previous lockfile. We do this by iterating the
// directory of the lock file and checking if there is a file
// with the same name, but without the timestamp.

$dir = new DirectoryIterator(dirname(SYSCP_LOCK_FILE));
foreach($dir as $file)
{
    // get the filename part of the base lockfile

    $lockBasename = basename(SYSCP_LOCK_NAME);

    // now check if the filename and the lockfilename have
    // the same basename part, but are not exactly the same.

    if(preg_match('/'.$lockBasename.'-'.$instanceID.'/', $file->getFilename())
       && basename(SYSCP_LOCK_FILE) != $file->getFilename())
    {
        // we have found a lockfile, lets remove our current lockfile

        unlink(SYSCP_LOCK_FILE);

        // and die with an error

        die('There is already a lockfile. Exiting...'."\n");
    }
}

/**#@+
 * Development Part
 * @todo Consider how to change this settings by using config files
 */
//define('SYSCP_CLEAR_CACHE', true);

error_reporting(E_ALL|E_STRICT);

/**#@-*/

/**
 * Load the SysCP basic class, which provides some core functions
 */

require_once dirname(__FILE__).'/../lib/classes/Syscp.class.php';

// Load the FrontController with his dependant interfaces and exceptions

Syscp::uses('Syscp.FrontController.Interface');
Syscp::uses('Syscp.FrontController.Cli');

// parse the argv

$params = array();
foreach($argv as $key => $value)
{
    if($key > 0)
    {
        list($argKey, $argValue) = split('=', $value);
        $argKey = trim($argKey, '--');
        $params[$argKey] = $argValue;
    }
}

/**
 * Instanciate and Dispatch a new Controller
 */

$ctrl = new Syscp_FrontController_Cli();
$ctrl->initialize($params);
$ctrl->dispatch();

// If we got here, without an error, we can safely remove the lockfile

unlink(SYSCP_LOCK_FILE);

?>
