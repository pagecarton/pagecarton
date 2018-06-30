<?php
/**
 * PageCarton Content Management System
 * 
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Profile_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $  
 */

/** 
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Profile_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_ShowAll extends Application_Profile_Abstract
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'My Profiles';      

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
			$template = null;
			$data = array();
	//		var_export( $userInfo['profiles'] );
			foreach( self::getMyProfiles() as $url )
			{
				$values = self::getProfileInfo( $url );
	//		var_export( $url );
	//		var_export( $values );
				if( ! $values )
				{
					continue;
				}
				
				$values['full_profile_url'] = Ayoola_Page::getHomePageUrl() . '/' . $values['profile_url'] . '';
				$values['logon_url'] = Ayoola_Page::setPreviousUrl( '/object/name/Application_Profile_Logon/' ) . '&profile_url=' . $values['profile_url'];
				if( $url == $userInfo['profile_url'] )
				{
					$values['logon_link'] = 'Default';
				}
				else
				{
					$values['logon_link'] = '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_LogOn/?profile_url=%KEY%\', \'' . __CLASS__ . '\' );" href="javascript:">Set as Default</a>';
				}
				
				$values['edit_url'] = Ayoola_Page::setPreviousUrl( '/object/name/Application_Profile_Editor/' ) . '&profile_url=' . $values['profile_url'];
				$values['delete_url'] = Ayoola_Page::setPreviousUrl( '/object/name/Application_Profile_Delete/' ) . '&profile_url=' . $values['profile_url'];
				$values['edit_photo_url'] = Ayoola_Page::setPreviousUrl( '/object/name/Application_Profile_Photo/' ) . '&profile_url=' . $values['profile_url'];
/*				$this->setViewContent( '
										<div class="boxednews greynews" style="float:left;padding:1em; text-align:center;">
											<span class=""><a href="' . $fullUrl . '"><strong class="">' . $values['display_name'] . ' </strong></a></span> 
											<hr>
											<a title="Log on as this profile" style="padding:0.5em;" href="' . $values['logon_url'] . '"><img alt="Logon" src="/open-iconic/png/account-login-2x.png"></a>
											<a title="Edit Profile" style="padding:0.5em;" href="' . $values['edit_url'] . '"><img alt="Edit" src="/open-iconic/png/pencil-2x.png"></a>
											<a title="Edit Photo" style="padding:0.5em;" href="' . $values['edit_photo_url'] . '"><img alt="Photo" src="/open-iconic/png/person-2x.png"></a>
											<a title="Delete Profile" style="padding:0.5em;" href="' . $values['delete_url'] . '"><img alt="Delete" src="/open-iconic/png/x-2x.png"></a> 
											<hr>
											<span class="" title="Share this new profile page with your contacts...">' . self::getShareLinks( $fullUrl ) . '</span> 
										</div>
										
										' 
									); 
*/			
					//	$templateToUse .= $this->getParameter( 'markup_template' );
			//		$template .= self::replacePlaceholders( $this->getParameter( 'markup_template' ), $values + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
		//	var_export( $values );
				$data[] = $values;
			}
		//	$this->createList( $data );
			$this->setViewContent( $this->createList( $data ) );
		//	var_export( $template );
		//	$this->setViewContent( '<div style="clear:both"></div>' );
	//		$this->_parameter['markup_template'] = $template;
		//	var_export( $this->_parameter['markup_template'] );
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with profile package.</p>' ); 
		}
	//	var_export( $this->getDbData() );
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList( $data )
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'My Profiles';
		$list->setData( $data );
		$list->setListOptions( array( 
										'Creator' => '<a title="New Profile" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_Creator/\', \'' . $this->getObjectName() . '\' );" href="javascript:">Create a profile</a>' 
										) );
		$this->setIdColumn( 'profile_url' );
		$list->setKey( 'profile_url' );
		$list->setNoRecordMessage( 'No profile created yet.' );
		$list->createList(  
			array(
				'URL' => array( 'field' => 'profile_url', 'value' => '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/%FIELD%\' );" href="javascript:">/%FIELD%</a>' ), 
				'Type' => array( 'field' => 'auth_name', 'value' => '%FIELD%' ), 
				' ' => array( 'field' => 'logon_link', 'value' => '%FIELD%' ), 
		//		'   ' => array( 'field' => 'profile_url', 'value' => '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/%FIELD%\' );" href="javascript:">Preview</a>' ), 
	//			'  ' => array( 'field' => 'profile_url', 'value' => '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Share/?url=/%FIELD%&title=%FIELD%\' );" href="javascript:">Share</a>' ), 
				'     ' => array( 'field' => 'profile_url', 'value' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>' ), 
				'    ' => '<a title="Delete" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_Delete/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" href="javascript:">x</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
