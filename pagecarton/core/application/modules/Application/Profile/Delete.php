<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_Delete extends Application_Profile_Abstract
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
			if( ! $data = self::getIdentifierData() )
			{ 
				//	lets be able to delete username
				$userDir = Application_Profile_Abstract::getProfileFilesDir( Ayoola_Application::getUserInfo( 'username' ) );
				if( is_dir( $userDir ) )
				{
					$data = array( 'profile_url' => strtolower( Ayoola_Application::getUserInfo( 'username' ) ) ) + Ayoola_Application::getUserInfo();
				}
				else
				{
					return false; 
				}
			}
		//	var_export( $data );
			
			//	Only the owner or priviledged users can delete
			$profileSettings = Application_Profile_Settings::getSettings( 'Profiles' );
			if( ! self::isOwner( $data['username'] ) && ! self::hasPriviledge( $profileSettings['allowed_editors'] ? : 98 ) ){ return false; }
			
			$this->createConfirmationForm( 'Delete forever',  'Delete information and files of this handle: "'  . $data['profile_url'] . '". This cannot be undone. You should create a backup of its content and have it saved elsewhere before you delete.' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	Only remove from DB if file deleted.
			if( is_readable( self::getFolder() . $data['profile_url'] ) )
			{
				// Save to server
			//	if( ! $response = Application_Profile_Api_Delete::send( $data ) ){ return false; }
			//	var_export( $response );
			//	if( true !== $response['data'] ){ throw new Application_Profile_Exception( $response ); }
			}
		//	$filename = self::getProfilePath( $url );
			self::getProfileTable()->delete( array( 'profile_url' => strtolower( $data['profile_url'] ) ) );

			unlink( self::getProfilePath( $data['profile_url'] ) );
        	$userDir = Application_Profile_Abstract::getProfileFilesDir( $data['profile_url'] ) . DS . 'application';
		//		var_export( $userDir );
			Ayoola_Doc::deleteDirectoryPlusContent( $userDir );
        	$backup = Application_Profile_Abstract::getProfileFilesDir( $data['profile_url'] ) . DS . 'backup';
			Ayoola_Doc::deleteDirectoryPlusContent( $backup );
			
			@Ayoola_Doc::removeDirectory( dirname( self::getFolder() . $data['profile_url'] ) );
			$this->setViewContent(  '' . self::__( '<div class="boxednews badnews">Profile deleted successfully</div>' ) . '', true  ); 
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( self::__( '<div class="boxednews greynews"><a href="' . $_GET['previous_url'] . '"><img style="margin-right:0.5em;" alt="Edit" src="/open-iconic/png/arrow-circle-left-2x.png">Go Back</a></div>' ) );
			}
						
			//	Notify Admin
			$mailInfo['subject'] = 'Profile Deleted';
            $mailInfo['body'] = 'A profile has been just been deleted. Here are the details of the profile
            
Profile name: ' . $data['display_name'] . '
Profile URL: ' . $data['display_name'] . '
Deleted by: ' . Ayoola_Application::getUserInfo( 'username' ) . ' (' . Ayoola_Application::getUserInfo( 'email' ) . ')


			';
			try
			{
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
		}
		catch( Application_Profile_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
