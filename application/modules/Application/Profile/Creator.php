<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Profile_Creator
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Profile_Creator
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Profile_Creator extends Application_Profile_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			

			//	Check settings
			$profileSettings = Application_Article_Settings::getSettings( 'Articles' );  
			$this->createForm( 'Create profile', 'Create a new profile....' );
			if( $this->getParameter( 'class_to_play_when_completed' ) )
			{
				$this->setViewContent( Ayoola_Object_Embed::viewInLine( array( 'editable' => $this->getParameter( 'class_to_play_when_completed' ) ) + $this->getParameter() ? : array() ) );
			}
			$this->setViewContent( $this->getForm()->view() );
 			
			if( ! $values = $this->getForm()->getValues() ){ return false; }

			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$values['username'] = $userInfo['username'];
			
			//	Save this information locally for easier lookup
			@$userInfo['profiles'] = is_array( $userInfo['profiles'] ) ? $userInfo['profiles'] : array();
			$userInfo['profiles'][] = $values['profile_url'];
			$userInfo['profiles'] = array_unique( $userInfo['profiles'] );
			$userInfo['profile_url'] = @$userInfo['profile_url'] ? : $values['profile_url'];
			if( intval( $values['access_level'] ) !== 99 && intval( $userInfo['access_level'] ) !== 99 )
			{
				$userInfo['access_level'] = $values['access_level'] ? : $userInfo['access_level'];
			}
			
			//	save the new settings as well
			Ayoola_Access_Login::login( $userInfo );
			Ayoola_Access_Localize::info( $userInfo );
			
			$values['profile_creation_date'] = time();
			$values['profile_modified_date'] = time();
			
			//	write to file
			self::saveProfile( $values );
			$fullUrl = 'http://' . Ayoola_Page::getDefaultDomain() . '/' . $values['profile_url'] . '';
			$this->setViewContent( '<div class="boxednews greynews">Profile saved successfully.</div> <div class="boxednews greynews"><a href="' . $fullUrl . '">View Profile.</a></div>', true );
			$this->setViewContent( '<div class="boxednews greynews" title="Share this new profile page with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' );  
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( '<div class="boxednews greynews"><a href="' . $_GET['previous_url'] . '"><img style="margin-right:0.5em;" alt="Edit" src="/open-iconic/png/arrow-circle-left-2x.png">Go Back</a></div>' );
			}
			$this->_objectData['profile_url'] = $values['profile_url']; 
		//	$this->setViewContent(  );
						
			//	Notify Admin
			$mailInfo['subject'] = 'New Profile Created';
			$mailInfo['body'] = 'A new profile name "' . $values['display_name'] . '", has been created with the profile module. 
			
			You can view the new profile by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '/' . $values['profile_url'] . '.
			';
			Application_Log_View_General::log( array( 'type' => 'New profile', 'info' => array( $mailInfo ) ) );
			try
			{
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
			
			//	Do something after creating an profile
		//	self::v( $this->getParameter( 'class_to_play_when_completed' )  );
			if( $this->getParameter( 'class_to_play_when_completed' ) )
			{
				$this->setViewContent( Ayoola_Object_Embed::viewInLine( array( 'editable' => $this->getParameter( 'class_to_play_when_completed' ) ) + $this->getParameter() ? : array() ) );
			}
			
			
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
