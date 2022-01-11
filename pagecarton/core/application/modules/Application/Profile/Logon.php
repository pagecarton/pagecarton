<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_Logon
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Logon.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_Logon
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_Logon extends Application_Profile_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1, 98 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			
			$this->createConfirmationForm( 'Logon',  'Log on as : "'  . $data['display_name'] . '"' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			//	Only the owner can edit or priviledged user can edit
			//	Check settings
			$profileSettings = Application_Profile_Settings::getSettings( 'Profiles' );
			if( ! self::isOwner( $data['username'] ) && ! self::hasPriviledge( $profileSettings['allowed_editors'] ) ){ return false; }

			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			
			//	Log on as this user...
			@$userInfo['profiles'] = is_array( $userInfo['profiles'] ) ? $userInfo['profiles'] : array();
			array_unshift( $userInfo['profiles'], $data['profile_url'] );
			$userInfo['profiles'] = array_unique( $userInfo['profiles'] );
			$userInfo['profile_url'] = $data['profile_url'];
			if( intval( $data['access_level'] ) !== 99 && intval( $userInfo['access_level'] ) !== 99 )
			{
				$userInfo['access_level'] = $data['access_level'] ? : $userInfo['access_level'];
			}

			//	save the new settings as well
			Ayoola_Access_Login::login( $userInfo );
			Ayoola_Access_Localize::info( $userInfo );

			$fullUrl = 'http://' . Ayoola_Page::getDefaultDomain() . '/' . $data['profile_url'] . '';
			$this->setViewContent(  '' . self::__( '<div class="boxednews greynews">You have successfully logged on as "'  . $data['display_name'] . '"</div>' ) . '', true  );
			$this->setViewContent( self::__( '<div class="boxednews greynews" title="Share this new profile page with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' ) );  
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( self::__( '<div class="boxednews greynews"><a href="' . $_GET['previous_url'] . '"><img style="margin-right:0.5em;" alt="Edit" src="/open-iconic/png/arrow-circle-left-2x.png">Go Back</a></div>' ) );
			}
			$this->_objectData['profile_url'] = $data['profile_url'];  
		}
		catch( Application_Profile_Exception $e )
		{ 
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
