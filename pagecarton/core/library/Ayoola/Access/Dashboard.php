<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
		$userInfo = Ayoola_Application::getUserInfo();
	 //	var_export( $userInfo ); 

		$style = 'min-width:25%;max-width:50%;line-height:2em;display:inline-block;';
		$header = $style . 'font-weight:bold;';
		
		//	Account Information
/* 
		<img src="{{{display_picture}}}" style="float:right;max-height:160px;" >
		<h2>Account Summary</h2> 
		<p><strong>{{{auth_name}}}</strong></p> 
		<p>{{{auth_description}}}</p> 
		<p>Account has been in existence since {{{creation_date}}}.</p>
		<div style="clear:both;"></div>
		
		<br>
		<span style="float:right;">
		<h3>Wallet Balance</h3>
		<h2>{{{wallet_balance}}}</h2>
		</span>
 */		//	Account Settings
		$this->setViewContent( "<span style='{$header}'>Access Level: </span>" );
		
		//	Get information about the user access information
		$options = self::getAccessInformation( $userInfo['username'] );
	//	var_export( $options );  
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
		
		
	//	$userInfo['access_level'] ?   
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
	//	var_export( $this->_objectTemplateValues );  
	//	self::v( $this->_objectTemplateValues );  
		
		// Set Default
	//	self::v( $this->_objectTemplateValues );
    } 
	// END OF CLASS
}
