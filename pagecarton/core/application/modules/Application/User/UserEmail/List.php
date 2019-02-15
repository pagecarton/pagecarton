<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserEmail_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_UserEmail_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserEmail_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserEmail_List extends Application_User_UserEmail_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( '<h4>Personal Address Informations. <span class="goodnews"><a title="Add new Address" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserEmail_Creator/">+</a></span></h4>' );
		$this->setViewContent( $this->getList() );
    } 
	
    /**
     * creates the list of the available 
     * 
     */
	public function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->showPagination = false;
	//	$list->listTitle = 'Saved Bank Account Information';
	//	var_export( $this->getIdColumn() );
		$list->setData( $this->getDbData() );
		
	//	$this->setIdColumn( 'user_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no address information saved in your account' );
		$list->createList(  
			array(
				'email' => '<a title="Edit address Information" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserEmail_Editor/?' . $this->getIdColumn() . '=%KEY%&">%FIELD%</a>', 
				'X' => '<a title="Delete" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserEmail_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
