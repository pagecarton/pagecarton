<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_Creator extends Application_Profile_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1, 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Create a profile'; 

	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function setConfirmationPage( $values )
    {
		if( ! static::isSubDomain() )
		{
			$fullUrl = Ayoola_Page::getHomePageUrl() . '/' . $values['profile_url'] . '';
		}
		else
		{
			$fullUrl = 'http://' . $values['profile_url'] . '.' . Ayoola_Application::getDomainName() . '';
		}
		$this->setViewContent( '<div class="goodnews">Profile saved successfully. 
						<a href="' . $fullUrl . '" target="_blank">Preview</a>							
						</div>', true );
//		$this->setViewContent( self::__( '<div class="" title="Share this new profile page with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' ) );  
		if( @$_GET['previous_url'] )
		{
			$this->setViewContent( self::__( '<div class="pc-info-notify"><a href="' . $_GET['previous_url'] . '"><img style="margin-right:0.5em;" alt="Edit" src="' . Ayoola_Application::getUrlPrefix() . '/open-iconic/png/arrow-circle-left-2x.png">Go Back</a></div>' ) );
		}
		$this->_objectData['profile_url'] = $values['profile_url']; 
	//	$this->setViewContent(  );

		
		//	Notify Admin
		$mailInfo['subject'] = 'New Profile Created';
		$mailInfo['body'] = 'A new profile name "' . $values['display_name'] . '", has been created with the profile module. 
		
		You can view the new profile by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '/' . $values['profile_url'] . '.
		';
		Application_Log_View_General::log( array( 'type' => 'New profile', 'info' => array( $mailInfo ) ) );
		try
		{
			@Ayoola_Application_Notification::mail( $mailInfo );
		}
		catch( Ayoola_Exception $e ){ null; }

		$mailInfo['to'] = Ayoola_Application::getUserInfo( 'email' );
		$mailInfo['subject'] = 'Your new profile';
		$mailInfo['body'] = 'A new public profile name , has been created for you.
		
		Display Name: "' . $values['display_name'] . '",
		Profile URL: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '/' . $values['profile_url'] . '
		Manage your profiles: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '/account';
		self::sendMail( $mailInfo );

	}
	
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
			$this->createForm( static::$_submitButton, '' );
			if( $this->getParameter( 'class_to_play_when_completed' ) )
			{
				$this->setViewContent( Ayoola_Object_Embed::viewInLine( array( 'editable' => $this->getParameter( 'class_to_play_when_completed' ) ) + $this->getParameter() ? : array() ) );
			}
			$this->setViewContent( $this->getForm()->view() );
 			
			if( ! $values = $this->getForm()->getValues() ){ return false; }

			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$values['username'] = $userInfo['username'];
			$values['user_id'] = $userInfo['user_id'];
			
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

			// we need to set this on the main site.
			$multisiteTable = new PageCarton_MultiSite_Table();
			$prefix = Ayoola_Application::getPathPrefix();
			if( $response = $multisiteTable->selectOne( null, array( 'directory' => $prefix ) ) )
			{
				Ayoola_Application::reset( array( 'path' => $response['parent_dir'] ) );
				Ayoola_Access_Localize::info( $userInfo );
				Ayoola_Application::reset( array( 'path' => $prefix ) );
			}
			
			$values['profile_creation_date'] = time();
			$values['profile_modified_date'] = time();
			$values['creation_time'] = time();
			$values['creation_ip'] = $_SERVER['REMOTE_ADDR'];
			
			//	write to file
			self::saveProfile( $values );
			$this->setConfirmationPage( $values );
			
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
