<?php
/**
 * AyStyle Developer Tool
 * 
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Profile_All
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: All.php 5.11.2012 12.02am ayoola $  
 */

/** 
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Profile_All
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Profile_All extends Application_Article_ShowAll
{
	
    /**
     * Module files directory namespace
     * 
     * @var string
     */
	protected static $_moduleDir = 'profiles';	
			
    /**
     * 
     */
	public static function sanitizeData( &$data )
    {
		$data['article_title'] = @$data['display_name']; 
		$data['article_description'] = @$data['profile_description']; 
		$data['document_url'] = @$data['display_picture']; 
		$data['document_url_base64'] = @$data['display_picture_base64']; 
		if( @$data['document_url_base64'] )
		{
			$data['document_url'] = '/tools/classplayer/get/object_name/Application_Profile_PhotoViewer/profile_url/' . @$data['profile_url'] . '/time/' . filemtime( Application_Profile_Abstract::getProfilePath( @$data['profile_url'] ) );
		}
		$data['display_picture'] = @$data['document_url'];     
		$data['document_url_base64'] = @$data['display_picture_base64']; 
		$data['article_modified_date'] = @$data['profile_modified_date']; 
		$data['article_creation_date'] = @$data['profile_creation_date']; 
		$data['article_url'] = '/' . @$data['profile_url']; 
		$data['publish'] = '1'; 
		$data['auth_level'] = '0'; 
	}
	// END OF CLASS
}
