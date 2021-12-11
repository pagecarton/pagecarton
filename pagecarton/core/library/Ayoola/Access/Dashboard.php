<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_Dashboard
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Dashboard.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_Dashboard
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Dashboard extends Ayoola_Access_Abstract
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
	protected static $_accessLevel = array( 1, 98 );
	
    /**
     * This method performs the class' essense.
     *
     * @param void
     * @return boolean
     */
    public function init()
    {
		if( ! $userInfo = Ayoola_Application::getUserInfo() )
		{
			return false;
		}

		$style = 'min-width:25%;max-width:50%;line-height:2em;display:inline-block;';
		$header = $style . 'font-weight:bold;';
		
		//	Account Information
		//	Account Settings
		$this->setViewContent( "<span style='{$header}'>Access Level: </span>" );
		
		//	Get information about the user access information
		$options = self::getAccessInformation( $userInfo['username'] );
		if( empty( $options['display_name'] ) )
		{
			if( ! empty( $options['firstname'] ) )
			{
				$options['display_name'] = ( ucfirst( $userInfo['firstname'] ) . ' ' . strtoupper( $userInfo['lastname'] ) );
			}
			if( ! empty( $options['username'] ) )
			{
				$options['display_name'] = '@' . $userInfo['username'];
			}
			
		}
		@$options['domain'] = Ayoola_Page::getDefaultDomain();
		switch( $userInfo['access_level'] )
		{
			case 1:
				$options['auth_name'] = 'FREE Account';
			break;
		}
		$this->_objectTemplateValues = array_merge( $options ? : array(), $this->_objectTemplateValues ? : array() );   
		
		
		@$userInfo['wallet_balance'] = $userInfo['wallet_balance'] ? $userInfo['wallet_balance'] : '0.00';
		@$this->setViewContent( "<span style='{$style}'>{$options['auth_name']}</span>" );
		
		$options = array( 'enabled', 'verified', 'approved' );
		foreach( $options as $each )
		{
			$this->setViewContent( "<span style='{$header}'>Account {$each}?:</span>" );
			if( empty( $userInfo[$each] ) ){ $userInfo[$each] = 'No'; }
			else{ $userInfo[$each] = 'Yes'; }
			$this->setViewContent( "<span style='{$style}'>{$userInfo[$each]}</span>" );
		}
		$this->_objectTemplateValues = array_merge( $userInfo ? : array(), $this->_objectTemplateValues ? : array() );
    } 
	// END OF CLASS
}
