<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_HybridAuth
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: HybridAuth.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_HybridAuth
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_HybridAuth extends Hybrid_Auth
{

    /**
     * Default Server to use for authentication
     *
     * @var 
     * 
     */
    const DEFAULT_AUTH_SERVER = 'http://account.ayoo.la';

    /**
     * Constructor
     *
     * @param 
     * 
     */
    public function __construct()
    {
		parent::__construct( self::getConfig() );
    }
	
    /**
     * Returns the configs
     *
     * @param 
     * 
     */
    public static function getConfig()
    {
		$settings = Application_Settings_Abstract::getSettings( 'SocialMedia' );
		$config = array();
		$config['base_url'] = 'http://' . $_SERVER['HTTP_HOST'] . "/tools/classplayer/get/object_name/Ayoola_HybridAuth_EndPoint/";
		$providers = array
				( 
					// openid providers
 					"OpenID" => array(),
					"Yahoo" => array( "keys" => array( "key", "secret" ), ),
					"AOL"  => array(),
					"Google" => array( "keys" => array( "id", "secret" ), ),
					"Facebook" => array ( "keys" => array( "id", "secret" ), ),
					"Twitter" => array( "keys" => array( "key", "secret" ), ),
					"Live" => array( "keys" => array( "id", "secret" ), ),
					"MySpace" => array( "keys" => array( "key", "secret" ), ),
					"LinkedIn" => array( "keys" => array( "key", "secret" ), ),
					"Foursquare" => array( "keys" => array( "id", "secret" ), ),
 				);

		$config['providers'] = array();
		foreach( $providers as $key => $value )
		{
			$lowerKey = strtolower( $key );
			$consumerKey = $lowerKey . '_consumer_key';
			$consumerSecret = $lowerKey . '_consumer_secret';
			if( is_array( $providers[$key] ) && empty( $providers[$key] ) )
			{
				$config['providers'][$key] = array( 'enabled' => true );
				continue;
			}
			elseif( empty( $settings[$consumerKey] ) || empty( $settings[$consumerSecret] ) )
			{
				continue;
			}
			$config['providers'][$key] = array
											( 
												'enabled' => true,
												'keys' => array(
																	$providers[$key]['keys'][0] => $settings[$consumerKey],
																	$providers[$key]['keys'][1] => $settings[$consumerSecret],
																)
											);
		}
	//	var_export( $config );
		return $config;
    }
	
	// END OF CLASS
}
