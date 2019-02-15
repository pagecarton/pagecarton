<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_NotificationMessage_Abstract
 */
 
require_once 'Application/User/NotificationMessage/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_NotificationMessage_List extends Application_User_NotificationMessage_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Bulk Mail'; 
	
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
		$list->listTitle = self::getObjectTitle();
		$list->pageName = $this->getObjectName();
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No notification message' );
		$list->createList(  
			array(
		//		'from' => null, 
		//		'to' => null,
				'subject' => '<a title="Edit %FIELD%" rel="changeElementId=' . $this->getObjectName() . '" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_NotificationMessage_Editor/?' . $this->getIdColumn() . '=%KEY%\' );">%FIELD%</a>', 
		//		'body' => null, 
		//		'mode_name' => null, 
			//	'send' => '<a rel="changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_NotificationMessage_Send/?' . $this->getIdColumn() . '=%KEY%">send</a>', 
				'send' => '<a rel="changeElementId=' . $this->getObjectName() . '" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_NotificationMessage_Send/?' . $this->getIdColumn() . '=%KEY%\' );">send</a>', 
		//		'X' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_NotificationMessage_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
				'X' => '<a rel="changeElementId=' . $this->getObjectName() . '" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_NotificationMessage_Delete/?' . $this->getIdColumn() . '=%KEY%\' );">X</a>', 
			)
		);
//		var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
