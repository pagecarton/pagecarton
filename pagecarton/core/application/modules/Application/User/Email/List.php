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
		try
		{ 
			$this->setViewContent( $this->getList(), true );		

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            $this->setViewContent( $e->getMessage(), true ); 
            return false; 
        }
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
//		var_export( $this->getDbData() );
		$list->setListOptions( array( 'Settings' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/E-mail/" title="Advanced E-mail Settings">Advanced Settings</a>' ) );
		$this->setIdColumn( 'email_id' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No Email Accounts' );  
		$list->createList(  
			array(
				'email' => '%FIELD% 
				
				<a title="check mail " target="_blank" href="http://mail.ComeRiver.com/?name=%FIELD%">check mail</a>', 
				'Edit' => '<a title="Edit" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Email_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_Email_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
//		var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
