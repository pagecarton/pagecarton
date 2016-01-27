<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Email_Abstract
 */
 
require_once 'Application/User/Email/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Email_List extends Application_User_Email_Abstract
{
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		$this->setViewContent( $this->getList(), true );		
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList()
    {
		$list = new Ayoola_Paginator();
		$list->listTitle = 'Email accounts on this website';
		$list->pageName = $this->getObjectName();
		$list->setData( $this->getDbData() );
		$list->setListOptions( array( 'Settings' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/E-mail/" title="Advanced E-mail Settings">Advanced Settings</a>' ) );
		$this->setIdColumn( 'email_id' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No Email Accounnts' );
		$list->createList(  
			array(
				'email' => '<a title="Edit %FIELD%" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Email_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'X' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Email_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
//		var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
