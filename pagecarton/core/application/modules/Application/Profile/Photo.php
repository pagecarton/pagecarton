<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Profile_Photo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Photo.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Profile_Photo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_Photo extends Application_Profile_Abstract
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
			//	$userInfo = Ayoola_Application::getUserInfo();
				//	Get information about the user access information
				if( ! $data = Ayoola_Access::getAccessInformation( Ayoola_Application::getUserInfo( 'username' ) ) )
				{
					return false; 
				}
			//	var_export( $data );
			}
		//	var_export( Application_HashTag_Abstract::get( 'profiles' ) );
			//	var_export( $data );
			$profileSettings = Application_Profile_Settings::getSettings( 'Profiles' );
			if( ! self::isOwner( $data['username'] ) && ! self::hasPriviledge( $profileSettings['allowed_editors'] ) ){ return false; }
			
			$this->createForm( 'Upload',  'Change profile picture for : "'  . $data['display_name'] . '"' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			
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
			$values = array_merge( $data, $values );  
						
			self::saveProfile( $values );

			
			

			$fullUrl = 'http://' . Ayoola_Page::getDefaultDomain() . '/' . $data['profile_url'] . '';
			$this->setViewContent( '<div class="boxednews greynews">Profile picture successfully saved for "'  . $data['display_name'] . '".</div>', true );
			$this->setViewContent( '<div class="boxednews greynews" title="Share this profile page with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' );  
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( '<div class="boxednews greynews"><a href="' . $_GET['previous_url'] . '"><img style="margin-right:0.5em;" alt="Edit" src="/open-iconic/png/arrow-circle-left-2x.png">Go Back</a></div>' );
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
	//	This is to implement the abstract method of the parent class. Not all inheriting classes needs a form
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => $this->getObjectName() ) );
	//	$form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
		$form->submitValue = $submitValue ; 
		$fieldset = new Ayoola_Form_Element;
		
	//	if( is_null( $values ) )
		{
			
			//	Profile picture
			$fieldset->addElement( array( 'name' => 'display_picture_base64', 'data-document_type' => 'image', 'data-allow_base64' => true, 'label' => 'Display Picture', 'type' => 'Document', 'value' => @$values['display_picture_base64'], ) ); 
			$fieldset->addRequirement( 'display_picture_base64', array( 'NotEmpty' => array( 'badnews' => 'Please select a valid file to upload...', ) ) );
		}
		$accessLevel = @$_REQUEST['access_level'] ? : $this->getGlobalValue( 'access_level' );
		$customForm = false;
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset ); 
		
		$this->setForm( $form );
	}
	// END OF CLASS
}
