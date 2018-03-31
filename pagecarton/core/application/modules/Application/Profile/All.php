<?php
/**
 * PageCarton Content Management System
 * 
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Profile_All
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: All.php 5.11.2012 12.02am ayoola $  
 */

/** 
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Profile_All
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_All extends Application_Article_ShowAll
{
	
    /**
     * Module files directory namespace
     * 
     * @var string
     */
	protected $_postTable = 'Application_Profile_Table';	
	
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
		$data = Application_Profile_Abstract::getProfileInfo( $data['profile_url'] );
	//	var_export( $data );
		$data['article_title'] = @$data['display_name']; 
		$data['article_description'] = @$data['profile_description']; 
		$data['document_url'] = @$data['display_picture']; 
		$data['document_url_base64'] = @$data['display_picture_base64']; 
		$data['profile_url'] = is_array( $data['profile_url'] ) ? array_pop( $data['profile_url'] ) : $data['profile_url'];
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
	//	$data['allow_raw_data'] = true; 
	}
	// END OF CLASS
}
