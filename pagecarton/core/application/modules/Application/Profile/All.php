<?php
/**
 * PageCarton
 * 
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
 * @package    Application_Profile_All
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_All extends Application_Article_ShowAll
{
	
    /**
     *
     * 
     * @var string
     */
	protected static $_itemName = 'Profile';	
	
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'profile_url' );
	
    /**
     * 
     * 
     * @var string
     */
	protected $_idColumn = 'profile_url';	

	
    /**
     * Module files directory namespace
     * 
     * @var string
     */
	protected static $_newPostUrl = '/widgets/Application_Profile_Creator';	
	
			
    /**
     * 
     */
	public static function sanitizeData( &$data )
    {
	//	var_export( $data );
//		$this->_parameter['add_a_new_post_link'] = $this->getParameter( 'add_a_new_post_link' ) ? : '/widgets/Application_Profile_Creator';
		$data = Application_Profile_Abstract::getProfileInfo( $data['profile_url'] );
	//	self::v( $data );
		if( ! empty( $data['posts']['all'] ) )
		{
		//	$data = false;
		//	return false;
		//	self::v( $data['posts']['all'] );
		}
		if( empty( $data['profile_url'] ) )
		{
			$data = false;
			return false;
		//	self::v( $data['posts']['all'] );
		}
	//	self::v( $data['profile_url'] );
		$data['not_real_post'] = true; 
		$data['display_name'] = trim( @$data['display_name'] ); 
		$data['article_title'] = @$data['display_name']; 
		$data['article_description'] = @$data['profile_description']; 
		$data['document_url'] = @$data['display_picture']; 
		$data['document_url_base64'] = @$data['display_picture_base64']; 
		$data['profile_url'] = is_array( $data['profile_url'] ) ? array_pop( $data['profile_url'] ) : $data['profile_url'];
	//	$data['display_picture'] = @$data['document_url'];     
		$data['document_url_base64'] = @$data['display_picture_base64']; 
		$data['article_modified_date'] = @$data['profile_modified_date']; 
		$data['article_creation_date'] = @$data['profile_creation_date']; 
		$data['article_url'] = '/' . @$data['profile_url'];   
		$data['publish'] = '1'; 
		$data['auth_level'] = '0';   
	//	self::v( $data['document_url'] );
	//	$data['allow_raw_data'] = true;    
	}
	// END OF CLASS
}
