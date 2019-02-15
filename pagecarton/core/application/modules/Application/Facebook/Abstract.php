<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Facebook_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Facebook_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Facebook/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Facebook_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Facebook_Abstract extends Application_SocialMedia_Abstract
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
	protected static $_accessLevel = 0;
	
    /**
     * Facebook Settings
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
     * Sets and Returns the facebook setting
     * 
     */
	public static function getSettings( $key = null )
    {
		if( is_null( self::$_settings ) )
		{
			$settings = new Application_Settings();
			$settings = $settings->selectOne( null, array( 'settingsname_name' => 'Facebook' ) );
			@self::$_settings = unserialize( $settings['settings'] );
		}
	//	var_export( self::$_settings );
		if( is_null( $key ) ){ $key = 'wsfr3w4rwqedrwer'; } //	workarround
		return @array_key_exists( $key, self::$_settings ) ? self::$_settings[$key] : self::$_settings;
    } 
	
    /**
     * loads the Facebook sdk
     * 
     */
	public static function load()
    {
	//	if( self::$_loaded ){ return; }
		$appId = self::getSettings();
	//	var_export( $appId );
	//	if( empty( $appId['app_id'] ) ){ return; }
		$appId = @$appId['app_id'];
		$code = '(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;   js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=' . $appId . '";  fjs.parentNode.insertBefore(js, fjs); }(document, "script", "facebook-jssdk"));';
		Application_Javascript::addCode( 'ayoola.events.add( window, "load", function(){ var element = document.createElement( "div" ); element.id = "fb-root"; var parent = document.getElementsByTagName( "body" )[0] || document.firstChild; parent.appendChild( element ); }  );' );
		Application_Javascript::addCode( $code );
		self::$_loaded = true;
    } 
	// END OF CLASS
}
