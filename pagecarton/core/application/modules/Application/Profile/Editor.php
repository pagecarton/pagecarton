<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_Editor extends Application_Profile_Abstract
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
		//	var_export( Application_HashTag_Abstract::get( 'profiles' ) );
			
		//	var_export( $data );
			//	Only the owner can edit or priviledged user can edit
			//	Check settings
			$profileSettings = Application_Profile_Settings::getSettings( 'Profiles' );
			if( ! self::isOwner( $data['username'] ) && ! self::hasPriviledge( $profileSettings['allowed_editors'] ) ){ return false; }
		//	var_export( $data );
			//			var_export( $data['quiz_correct_option'] );
			$this->createForm( 'Continue...', 'Edit "' . $data['display_name'] . '"', $data );
//			$this->setViewContent( '<script src="/js/objects/tinymce/tinymce.min.js"></script>' );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }

		//	self::v( $values );

			$access = new Ayoola_Access();
			if( $userInfo = $access->getUserInfo() )
			{
				@$data['profile_editor_username'] = is_array( @$data['profile_editor_username'] ) ? $data['profile_editor_username'] : array();
				array_push( $data['profile_editor_username'], $userInfo['username'] );
			}
			//	Old owner is still the new owner
			$values['username'] = $data['username'];
			$values['profile_modified_date'] = time();
			
			//	making options that have been disabled to still be active.
		//	$data['profile_data'] = array_merge( $data['profile_data'], $values['profile_data'] );  
		//	self::v( $data['profile_data'] );
		//	self::v( $values['profile_data'] );
			$values = array_merge( $data, $values );  
						
			self::saveProfile( $values );
			$fullUrl = Ayoola_Page::getHomePageUrl() . '/' . $values['profile_url'] . '';
			$this->setViewContent( '<div class="goodnews">Profile saved successfully. 
									<a href="' . Ayoola_Application::getUrlPrefix() . '/' . $values['profile_url'] . '" target="_blank">Preview</a> | 
									<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Share/?url=/' . $values['profile_url'] . '%&title=' . $values['display_name'] . '\' );" href="javascript:">Share</a>
									
									</div>', true );
	//		$this->setViewContent( '<div class="boxednews greynews" title="Share this new profile page with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' );  
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( '<div class="boxednews greynews"><a href="' . $_GET['previous_url'] . '"><img style="margin-right:0.5em;" alt="Edit" src="' . Ayoola_Application::getUrlPrefix() . '/open-iconic/png/arrow-circle-left-2x.png">Go Back</a></div>' );
			}
			$this->_objectData['profile_url'] = $values['profile_url'];  
						
			//	Notify Admin
			$mailInfo['subject'] = 'Profile Edited';
			$mailInfo['body'] = 'A new profile name "' . $values['display_name'] . '", has been edited with the profile module. 
			
			You can view the new profile by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '/' . $values['profile_url'] . '.';
			try
			{
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
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
