<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Script
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Script.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Script
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Abstract_Script extends Ayoola_Abstract_Table
{
	
    /**
     * Placeholder for type attribute
     * 
     * @var string
     */
	protected static $_type = 'text/javascript';
		
    /**
     * Redirect Requests
     * 
     * @var array
     */
	protected static $_redirectRequests = array();
	
    /**
     * Placeholder for content
     * 
     * @var string
     */
	const CONTENT_PLACEHOLDER = '@@CONTENT@@';
	
    /**
     * Placeholder for content
     * 
     * @var string
     */
	const TYPE_PLACEHOLDER = '@@TYPE@@';
	
    /**
     * html markup with placeholder
     * 
     * @var array
     */
	protected static $_markup = array( 'file' => "\n<script type='@@TYPE@@' src='@@CONTENT@@'></script>\n", 'code' => "\n<script type='@@TYPE@@'>\n<!--\n@@CONTENT@@\n//-->\n</script>\n" );
	
    /**
     * All the Script files to include in the safe
     * 
     * @var array
     */
	protected static $_files = array();
	
    /**
     * All the Script code lines to write to script
     * 
     * @var array
     */
	protected static $_codes = array();
	
    /**
     * Add a file to the $_files array
     * 
     * @param string The src attribute
     */
	public static function addFile( $file, array $settings = null )
    {
		$key = 'A' . sha1( $file );
		if( @$settings['js_mode'] )
		{
			static::$_jsMode[$key] = $file;
		}
		elseif( @$settings['to_head'] )
		{
			static::$_filesToHead[$key] = $file;
		}
		elseif( @$settings['onload'] )
		{
			static::$_filesOnLoad[$key] = $file;
		}
		else
		{
			static::$_files[$key] = $file;
		}
	}
	
    /**
     * Add a file to the $_files array
     * 
     * @param string The src attribute
     */
	public static function addCode( $code, array $settings = null )
    {
		$key = 'A' . sha1( $code );
		if( @$settings['js_mode'] )
		{
			static::$_jsMode[$key] = $code;
		}
		elseif( @$settings['to_head'] )
		{
			static::$_codesToHead[$key] = $code;
		}
		elseif( @$settings['onload'] )
		{
			static::$_codesOnLoad[$key] = $code;
		}
		else
		{
			static::$_codes[$key] = $code;
		}
	//	self::$_codes[] = $code;
	}
	
    /**
     * Returns the Script files markup
     * 
     */
	public static function getFiles( array $settings = null )
    {
		$files = null;
		//	make them full domains by default
		$i = 0;
		$j = null;
		static::$_files = $settings['to_head'] ? static::$_filesToHead : array_merge( static::$_files, static::$_filesToHead );
		foreach( static::$_files as $file )
		{

			$file = Ayoola_Doc::uriToDedicatedUrl( $file );
			$files .= str_ireplace( array( self::CONTENT_PLACEHOLDER, self::TYPE_PLACEHOLDER ),
									array( $file, static::$_type ),
									static::$_markup['file'] 
								);
		}
		
		//	Deal with the onload files
		if( static::$_filesOnLoad )
		{
			$files = "<script>\r\n";
			foreach( static::$_filesOnLoad as $file )
			{

				$file = Ayoola_Doc::uriToDedicatedUrl( $file );
				$files .= "ayoola.js.addFileOnLoad( '{$file}' );\r\n";
			}
			$files .= "</script>\r\n";
		}
		
		//	Deal with the onload files
		if( static::$_jsMode )
		{
			$files = "<script>\r\n";
			foreach( static::$_jsMode as $file )
			{

				$file = Ayoola_Doc::uriToDedicatedUrl( $file );
				$files .= "ayoola.js.addFile( '{$file}' );\r\n";
			}
			$files .= "</script>\r\n";
		}
		
		static::$_files = $settings['to_head'] ? static::$_files : array();	//	Reset it.
		static::$_filesOnLoad = array();	//	Reset it.
		static::$_jsMode = array();	//	Reset it.
		static::$_filesToHead = array();	//	Reset it.
		return $files . @$onLoadFiles;
	}
	
    /**
     * Returns the Script code lines
     * 
     */
	public static function getCodes()
    {
		$codes = null;
	//		var_export( self::$_codes );
		static::$_codes = array_merge( static::$_codes, static::$_codesToHead );
		foreach( static::$_codes as $code )
		{
			$codes .= str_ireplace( array( self::CONTENT_PLACEHOLDER, self::TYPE_PLACEHOLDER ),
									array( $code, static::$_type ),
									static::$_markup['code'] 
								);
		}
		
		//	Deal with the onload files
		$onLoadCodes = "<script>\r\n";
		foreach( static::$_codesOnLoad as $key => $code )
		{
			$onLoadCodes .= "var {$key} = function(){ {$code} }\r\n";
			$onLoadCodes .= "ayoola.events.add( window, 'load', {$key} );\r\n";
		}
		$onLoadCodes .= "</script>\r\n";
		static::$_codes = array();	//	Reset it.
		static::$_codesOnLoad = array();	//	Reset it.
		static::$_codesToHead = array();	//	Reset it.
		return $codes . $onLoadCodes;
	}
	
    /**
     * Returns all Script markups
     * 
     */
	public static function getAll()
    {
		return self::getFiles() . self::getCodes();
		
	}
	
    /**
     * perform a php header if this is not a java request
     * 
     * @param string URL
     * 
     */
	public static function redirect( $urlToGo )
    {
		if( array_key_exists( $urlToGo, self::$_redirectRequests ) )
		{
			if( ! Ayoola_Application::isXmlHttpRequest() && ! Ayoola_Application::isClassPlayer() )
			{			
				header( 'Location: ' . $urlToGo );
				exit();
			}
		
		}
		else
		{
		//	echo '<div id="ayoola-js-redirect-whole-page"></div>';
			self::header( $urlToGo );
		}
	}
	
    /**
     * perform a JS version of header()
     * 
     * @param string URL
     * @param string Specify the object name, the absence of which to lookout for.
     * 
     */
	public static function header( $urlToGo, $objectName = null )
    {
		self::$_redirectRequests[$urlToGo] = $urlToGo;
		$location = Ayoola_Application::isClassPlayer() ? 'parent.location' : 'location';
		self::addCode
		(
			
			'
			var callback = function()
			{
				var watchOutForThis = "' . $objectName . '";
				var urlToGo = "' . $urlToGo . '";
			//	if( ! document.getElementById( watchOutForThis ) ){ location = urlToGo; }
				if( watchOutForThis )
				{
					if( ! document.getElementById( watchOutForThis ) ){  parent.parent.parent.location = urlToGo; }
				}
				else
				{
					if( document.getElementById( "ayoola-js-redirect-frame-only" ) ){ location = urlToGo; }
					if( document.getElementById( "ayoola-js-redirect-whole-page" ) ){  parent.parent.parent.location = urlToGo; }
				}
			}

			ayoola.events.add
			(
				window, "load", function(){ ayoola.xmlHttp.setAfterStateChangeCallback( callback );	}
			);
			ayoola.events.add( window, "load", callback );' 
		);
		
	}
	// END OF CLASS
}
