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
 * @subpackage Syscp.FrontController
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * This class represents the basic FrontController implementation
 *
 * This class needs to be defined abstract because it only implements a subset of it's
 * own Interface.
 *
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.FrontController
 */
abstract class Syscp_FrontController implements Syscp_FrontController_Interface
{
	protected $moduleConfig     = array();
	protected $navigationConfig = array();
	protected $hookConfig       = array();
	protected $languageConfig   = array();

	/**
	 * @todo implement language lists here
	 */
	public function initModules()
	{
		$cacheFile = SYSCP_PATH_BASE.'cache/modules.config';
		if(SYSCP_CLEAR_CACHE && Syscp::isReadableFile($cacheFile))
		{
			unlink($cacheFile);
		}
		// init vars
		$moduleConfig     = array();
		$navigationConfig = array();
		$hookConfig = array();
		$languageConfig = array();

		if (Syscp::isReadableFile($cacheFile))
		{
			$data = file($cacheFile);
			$data = unserialize($data[0]);
			$navigationConfig = $data['navigation'];
			$moduleConfig     = $data['module'];
			$hookConfig       = $data['hook'];
			$languageConfig   = $data['language'];
		}
		else
		{
			// generate list of module.conf files
			// lets init some values
			$filelist = array();
			$stack    = array();
			// we start to push the startdir into the stack
			array_push($stack, SYSCP_PATH_LIB);
			// while there are values in the stack, we fetch the topmost value
			while (sizeof($stack) > 0)
			{
				// fetch the topmost dir from the stack
				$dirName = array_pop($stack);
				// open the dir
				$dir = new DirectoryIterator($dirName);
				// iterate the directory
				foreach($dir as $file)
				{
						// if the file is a readable directory but not . or ..
						if(!$file->isDot()     && $file->isDir() &&
						   $file->isReadable() && $file->isExecutable())
						{
						// push to stack
						array_push($stack, $file->getPathname());
					}
					// if the file is a module.conf
					elseif ($file->getFilename() == 'module.conf')
					{
						// we put the file into the filelist
						$filelist[] = $file->getPathname();
					}
				}
			}

			// iterate the filelist
			foreach($filelist as $file)
			{
				// parse the given file
				$result = array();
				$result = Syscp::parseConfig($file);
				// create some convenience shortcuts
				$vendor = $result['Module']['vendor'];
				$name   = $result['Module']['name'];

				// store the module part
				$moduleConfig[$vendor][$name] = $result['Module'];

				if($result['Module']['enabled'] == 'true')
				{
					// now store the navigation part by iterating the array
					if(!isset($result['Navigation'])) $result['Navigation'] = array();
					foreach($result['Navigation'] as $entry)
					{
						// convenience shortcuts
						$area      = $entry['area'];
						$parentURL = $entry['parent_url'];
						$url       = $entry['url'];

						// if the current entry doesn't have a parent and there
						// is none entry with this url, add to the navigation
						if($parentURL == '' && !isset($navigationConfig[$area][$url]))
						{
							$navigationConfig[$area][$url] = $entry;
						}
						// otherwise check if the parent of this entry is set,
						// if so, add to the parent's child list.
						elseif (isset($navigationConfig[$area][$parentURL]))
						{
							$navigationConfig[$area][$parentURL]['childs'][] = $entry;
						}
					}

					// the hooklist
					if(isset($result['Hook']))
					{
						foreach($result['Hook'] as $name => $data)
						{
							$tmp = array();
							$tmp['hook'] = $name;
							$tmp['class'] = $data['class'];
							$tmp['file'] = $data['file'];
							$tmp['method'] = $data['method'];
							$tmp['priority'] = $data['priority'];
							$hookConfig[] = $tmp;
						}
					}

					// the language files
					if (isset($result['Language']))
					{
						foreach ($result['Language'] as $langName => $langFile)
						{
							$languageConfig[$langName][] = $langFile;
						}
					}

				}
			}

			// put the data into the cache
			$cache               = array();
			$cache['navigation'] = $navigationConfig;
			$cache['module']     = $moduleConfig;
			$cache['hook']       = $hookConfig;
			$cache['language']   = $languageConfig;
			$cache = serialize($cache);
//			$fileHandler = fopen($cacheFile, 'w');
			file_put_contents($cacheFile, $cache);
		}

		// map local variables into object scope.
		$this->moduleConfig     = $moduleConfig;
		$this->navigationConfig = $navigationConfig;
		$this->hookConfig       = $hookConfig;
		$this->languageConfig   = $languageConfig;
	}

}