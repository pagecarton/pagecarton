<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Upgrade
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Upgrade.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Upgrade
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Upgrade extends Ayoola_Access_Abstract
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
	protected static $_accessLevel = 1;
	
    /**
     * This method performs the class' essense.
     *
     * @param void
     * @return boolean
     */
    public function init()
    {
/* 		require_once 'Ayoola/Access.php'; 
		$auth = new Ayoola_Access();
		require_once 'Ayoola/Page.php'; 
		$userInfo = $auth->getUserInfo();
	//	var_export( $userInfo );
		
		
		//	Log
		if( ! $userInfo )
		{
			return false;
		}
		
		//	Check that we have a super user on ground
		$response = Ayoola_Api_UserList::send( array( 'access_level' => 99 ) );
		if( is_array( $response['data'] ) && count( $response['data'] ) > 0 )  
		{
			$upgradeTo = intval( $this->getParameter( 'upgrade_to_level' ) );
			switch( $upgradeTo )
			{
				case 99:
					throw new Ayoola_Access_Exception( 'YOU CANNOT UPGRADE TO A SUPERUSER' );
				break;
				case 1:
				case 0:
					throw new Ayoola_Access_Exception( 'YOU CANNOT UPGRADE TO THE NEW LEVEL: ' . $upgradeTo );
				break;
				default:
					if( $userInfo['access_level'] == $upgradeTo )
					{
						throw new Ayoola_Access_Exception( 'YOU UPGRADE TO THE SAME LEVEL: ' . $upgradeTo );
					}
				break;
			}
			if( $this->getParameter( 'upgrade_from_level' ) )
			{
					if( $userInfo['access_level'] != $this->getParameter( 'upgrade_from_level' ) )
					{
					//	var_export( $upgradeTo );
						throw new Ayoola_Access_Exception( 'YOU DO NOT MEET THE UPGRADE REQUIREMENT: ' . $this->getParameter( 'upgrade_from_level' ) );
					}
			}
			if( Ayoola_Abstract_Playable::hasPriviledge() )
			{
				throw new Ayoola_Access_Exception( 'YOU CANNOT CHANGE YOUR LEVEL AS THE SUPER USER' );
			}
			
			//	UPGRADE
			$userInfo['access_level'] = $upgradeTo;
			if( $response = Ayoola_Api_UserEditor::send( $userInfo ) )
			{
			//	var_export( $response );
				if( isset( $response['data'] ) )
				{
					//	Login with the new information
					//	SELF DESTRUCT THE INSTALLER
				//	@unlink( Ayoola_Application::$installer );
					Ayoola_Access_Login::login( $userInfo );
					return true;
				}
			}
		}
		return false;
 */		//	exit( 'wed3wd' );
    } 
	// END OF CLASS
}
