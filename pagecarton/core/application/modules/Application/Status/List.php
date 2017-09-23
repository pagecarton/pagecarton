<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Status   Ayoola
 * @package    Application_Status_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Status_Abstract
 */
 
require_once 'Application/Status/Abstract.php';


/**
 * @Status   Ayoola
 * @package    Application_Status_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Status_List extends Application_Status_Abstract
{
		
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
		$list->listTitle = 'Status Updates on this website.';
		$list->showSearchBox = true;
		$list->setData( $this->getDbData() );
	//	$this->setIdColumn( 'Status_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no status updates on this website.' );
		$list->createList(  
			array(
				'status' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Status_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'subject' => null, 
				'object' => null, 
				'timestamp' => array( 'filter' => 'Ayoola_Filter_Time' ), 
		//		'timestamp' => null, 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Status_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
