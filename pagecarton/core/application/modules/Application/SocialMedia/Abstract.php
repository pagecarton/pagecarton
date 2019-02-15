<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_SocialMedia_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_SocialMedia_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/SocialMedia/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_SocialMedia_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_SocialMedia_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * SocialMedia Settings
     * 
     * @var array
     */
	protected static $_settings;
	
    /**
     * Switch to true when the required sdk has been loaded
     * 
     * @var boolean
     */
	protected static $_loaded = false;
	
    /**
     * Sets and Returns the SocialMedia setting
     * 
     */
	public static function getSettings( $key = null )
    {
		if( is_null( static::$_settings ) )
		{
			$settings = new Application_Settings();
			$settings = $settings->selectOne( null, array( 'settingsname_name' => 'SocialMedia' ) );
			static::$_settings = @unserialize( htmlspecialchars_decode( $settings['settings'] ) ) ? : array();
		}
	//	var_export( static::$_settings[$key] );
		if( is_null( $key ) ){ $key = 'wsfr3w4rwqedrwer'; } //	workarround
		return array_key_exists( $key, static::$_settings ) ? static::$_settings[$key] : static::$_settings;
    } 
	
    /**
     * loads the SocialMedia sdk
     * 
     */
	public function getUrl()
    {
		//	If page parameter is not set, we use the current page
		$page = null;
		if( ! $page = $this->getParameter( 'url' ) ) 
		{
			$page = null;
		}
		$page = Ayoola_Page::getCanonicalUrl( $page );
	//	var_export( $page );
		return $page;
    } 
	
    /**
     * loads the SocialMedia sdk
     * 
     */
	public static function load()
    {
    } 
	// END OF CLASS
}
