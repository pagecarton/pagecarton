<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_Abstract
 */  
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_List extends Application_User_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Users'; 
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( $this->getList(), true );
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = $this->getParameter( 'title' ) ? : self::getObjectTitle();

		$list->showSearchBox = true;
		$data = $this->getDbData();
		krsort( $data );
		$list->setData( $data ); 
//		var_export( $data );
		$list->setListOptions
		( 
			array( 
				//		'Creator' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Creator/" title="Add a new user">+</a>',
						'Settings' => '<a rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/UserAccount/" title="User Account settings">User Account Settings</a>'
						) 
		);
	//	var_export( base64_encode( hash( 'sha512', 'tymyjope' ) ) );
	//	$this->setIdColumn( 'user_name' );
		$list->setKey( $this->getIdColumn() );
	//	$list->setKey( 'email' );
		$rowOptions = array( 
										'Delete' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Delete/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="">Delete Account</a>' ,
										'Options' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Editor/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="">Update Account</a>' ,
										'Password' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Help_ResetPassword/?' . $this->getIdColumn() . '=%KEY%\' );" title="">Reset Password</a>' ,
										'Impersonate' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Impersonate/?' . $this->getIdColumn() . '=%KEY%\' );" title="">Log on as User</a>' ,
										'VCard' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_DownloadContact/?' . $this->getIdColumn() . '=%KEY%\' );" title="">Download VCARD</a>' , 
									);
		if( is_array( $this->getParameter( 'row_options' ) ) )
		{
			$rowOptions = array_merge( $rowOptions, $this->getParameter( 'row_options' ));
		}
		$list->setRowOptions( $rowOptions );
	//	var_Export( $this->getParameter() );
	//	var_Export( $this->getParameter() );
		
	//	$list->setNoRecordMessage( 'There are no user accounts on this application' );
		$options = array(
				'email' => null, 
				'firstname' => null, 
				'lastname' => null, 
				'phone_number' => null, 
	//			'username' => '<a rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>',
			);
		$optionalFields = array(
				'firstname' => null, 
				'lastname' => null, 
				'phone_number' => null, 
		);
		if( is_array( $this->getParameter( 'other_fields' ) ) )
		{
			$optionalFields = $optionalFields + $this->getParameter( 'other_fields' );
		}

		
//	var_export( $data );
		if( ! $this->getParameter( 'show_all_columns' ) )
		{
			$testData = array_shift( $data );
			foreach( $optionalFields as $key => $each )
			{
				if( empty( $testData[$key] ) )
				{
					unset( $options[$key] );
				}
			}

		}
		$list->createList( $options );
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
