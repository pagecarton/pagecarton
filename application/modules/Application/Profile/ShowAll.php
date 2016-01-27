<?php
/**
 * AyStyle Developer Tool
 * 
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Profile_ShowAll
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $  
 */

/** 
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Profile_ShowAll
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Profile_ShowAll extends Application_Profile_Abstract
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
			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			@$userInfo['profiles'] = is_array( $userInfo['profiles'] ) ? $userInfo['profiles'] : array();
			$template = null;
			foreach( $userInfo['profiles'] as $url )
			{
				$filename = self::getProfilePath( $url );
				if( ! $values = @include $filename )
				{
					continue;
				}				
				if( $this->getParameter( 'auth_name' ) )
				{
					$table = new Ayoola_Access_AuthLevel();
					$authInfo = $table->selectOne( null, array( 'auth_level' => $values['access_level'] ) );
				//	var_export( $authInfo );
					$values +=  is_array( $authInfo ) ? $authInfo : array();
				}
				
				$values['full_profile_url'] = 'http://' . Ayoola_Page::getDefaultDomain() . '/' . $values['profile_url'] . '';
				$values['logon_url'] = Ayoola_Page::setPreviousUrl( '/object/name/Application_Profile_Logon/' ) . '&profile_url=' . $values['profile_url'];
				$values['edit_url'] = Ayoola_Page::setPreviousUrl( '/object/name/Application_Profile_Editor/' ) . '&profile_url=' . $values['profile_url'];
				$values['delete_url'] = Ayoola_Page::setPreviousUrl( '/object/name/Application_Profile_Delete/' ) . '&profile_url=' . $values['profile_url'];
				$values['edit_photo_url'] = Ayoola_Page::setPreviousUrl( '/object/name/Application_Profile_Photo/' ) . '&profile_url=' . $values['profile_url'];
				$this->setViewContent( '
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
			
					//	$templateToUse .= $this->getParameter( 'markup_template' );
					$template .= self::replacePlaceholders( $this->getParameter( 'markup_template' ), $values + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
		//	var_export( $values );
			}
		//	var_export( $template );
			$this->setViewContent( '<div style="clear:both"></div>' );
			$this->_parameter['markup_template'] = $template;
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
	// END OF CLASS
}
