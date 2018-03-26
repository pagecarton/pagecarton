<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
	protected static $_accessLevel = 1;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			
			//	Only the owner or priviledged users can delete
			$profileSettings = Application_Profile_Settings::getSettings( 'Profiles' );
			if( ! self::isOwner( $data['username'] ) && ! self::hasPriviledge( $profileSettings['allowed_editors'] ) ){ return false; }
			
			$this->createConfirmationForm( 'Delete',  'Delete information and files of this profile: "'  . $data['display_name'] . '"' );
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
		//		var_export( $data );
			self::getProfileTable()->delete( array( 'profile_url' => strtolower( $data['profile_url'] ) ) );

			unlink( self::getProfilePath( $data['profile_url'] ) );
			
			@Ayoola_Doc::removeDirectory( dirname( self::getFolder() . $data['profile_url'] ) );
			$this->setViewContent( '<div class="boxednews badnews">Profile deleted successfully</div>', true ); 
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( '<div class="boxednews greynews"><a href="' . $_GET['previous_url'] . '"><img style="margin-right:0.5em;" alt="Edit" src="/open-iconic/png/arrow-circle-left-2x.png">Go Back</a></div>' );
			}
						
			//	Notify Admin
			$mailInfo['subject'] = 'Profile Deleted';
			$mailInfo['body'] = 'A new profile name "' . $values['display_name'] . '", has been deleted with the profile module. 
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
