<?php
/**
 * filename: $Source$
 * begin: Friday, Feb 18, 2005
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Martin Burchert <eremit@syscp.org>
 * @copyright (C) the authors
 * @package org.syscp
 * @subpackage contrib.cli
 * @version $Id: htpasswd-htaccess-remover.php 152 2005-04-12 18:39:46Z flo $
 */

	// some configs
	$baseLanguage = 'english.lng.php';

	// Check if we're in the CLI
	if (    @php_sapi_name() != 'cli' 
	     && @php_sapi_name() != 'cgi' 
	     && @php_sapi_name() != 'cgi-fcgi' )
	{
		die('This script will only work in the shell.');
	}

	// Check argument count
	if ( sizeof( $argv ) != 2 )
	{
		print_help( $argv );
		exit;
	}
	
	// Load the contents of the given path
	$path  = $argv[1];
	$files = array();
	
	if ($dh = opendir( $path ) ) 
	{
		while ( false !== ( $file = readdir( $dh ) ) ) 
		{
	    	if (    $file != "." 
	    	     && $file != ".."
	    	     && !is_dir( $file ) 
	    	     && preg_match( '/(.+)\.lng\.php/i', $file ) ) 
	    	{
	        	$files[ $file ] = str_replace('//','/',$path . '/' .$file);
			}
		}
		closedir( $dh );
	}
	else 
	{	
		print "ERROR: The path you requested cannot be read! \n ";
		print "\n";
		print_help();
		exit;	
	}
	
	// check if there is the default language defined
	if ( !isset( $files[$baseLanguage] ) )
	{
		print "ERROR: The baselanguage cannot be found! \n";
		print "\n";
		print_help();
		exit;
	}
	// import the baselanguage
	$base = import( $files[ $baseLanguage ] );
	// and unset it in the files, because we don't need to compare base to base
	unset( $files[ $baseLanguage ] );
	
	// compare each language with the baselanguage
	foreach( $files as $key => $file )
	{
		$comp = import( $file );
		print "\n\nComparing ".$baseLanguage." to ".$key."\n";
		$result = compare( $base, $comp );
		if ( is_array($result) && sizeof( $result ) > 0 )
		{
			print "  found missing strings: \n";
			foreach( $result as $value )
			{
				print "    ".$value."\n";
			}
		}
		else 
		{
			print "   no missing strings found! \n ";
		}

		print "\nReverse Checking ".$key." to ".$baseLanguage."\n";
		$result = compare( $comp, $base );
		if ( is_array($result) && sizeof( $result ) > 0 )
		{
			print "  found strings not in basefile: \n";
			foreach( $result as $key => $value )
			{
				print "    ".$value."\n";
			}
		}
		else 
		{
			print "   There are no strings which are not in the basefile! \n ";
		}
		
	}
	
	
	//-----------------------------------------------------------------------------------------
	// FUNCTIONS
	//-----------------------------------------------------------------------------------------
	
	/**
	 * prints the help screen
	 *
	 * @param  array  $argv
	 */
	function print_help( $argv )
	{
		print "Usage: php " . $argv[0] . " /PATH/TO/LNG \n";
		print " \n ";
	}
	
	function import( $file )
	{
		$input = file( $file );
		$return = array();
		foreach( $input as $key => $value )
		{
			if ( !preg_match('/^\$/', $value ) )
			{
				unset( $input[$key] );
			}
			else 
			{
				// generate the key
				$key = preg_replace('/^\$lng\[\'(.*)=(.*)$/U', '\\1', $value );
				$key = str_replace( '[\'', '/', $key );
				$key = trim(str_replace( '\']', '', $key ));
				
				//generate the value
				$value = trim($value);

				// set the result
				$return[ $key ] = $value;
				
			}
		}
		return $return;
	}
	
	function compare( $array1, $array2 )
	{
		$result = array();
		foreach( $array1 as $key => $value )
		{
			if ( !isset($array2[$key]) )
			{
				$result[$key] = $value;
			}
		}
		return $result;
	}
	
?>