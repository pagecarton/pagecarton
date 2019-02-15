<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Redirect
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Redirect.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_Redirect
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Redirect extends Ayoola_Abstract_Table
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Redirect'; 
	
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
	protected static $_accessLevel = array( 0 );
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
	//	return null;    
		try
		{ 
			$url = trim( $_REQUEST['url'] );
			$pathInfo = parse_url( $url );
			var_export( $pathInfo );
			if( ! $url )
			{
				$this->setViewContent( '<div class="badnews">The link appears to be broken.</div>' ); 
			}
			elseif( empty( $pathInfo['scheme'] ) )
			{
				$url = 'http://' . $url;
			}
			$pathInfo = parse_url( $url );
	//		var_export( $pathInfo );
	//		var_export( gethostbyname( $pathInfo['host'] ) );
			if( empty( $pathInfo['host'] ) || ! strpos( $pathInfo['host'], '.' ) || gethostbyname( $pathInfo['host'] ) === gethostbyname( $_SERVER['SERVER_NAME'] ) )
			{
				$this->setViewContent( '<div class="badnews">The link appears to be broken.</div>' ); 
				return false;
			}
			header( 'Location: ' . $url );
		//	if( ! $info = $this->getIdentifierData() ){ return false; }
	//		$this->setViewContent( $this->getXml()->saveHTML() ); 
		}
		catch( Ayoola_Exception $e ){ return false; }
	}
	
	// END OF CLASS
}
