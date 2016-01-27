<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Link_List
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Link_Abstract
 */
 
require_once 'Application/Link/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Link_List
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Link_List extends Application_Link_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	$this->setViewContent( '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/linkCreator/">Create new Link</a>', true );
		$this->setViewContent( $this->getList() );
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
		$list->listTitle = 'List of Links on this Application';
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not created any link yet' );
		$list->createList(  
			array(
				'link_name' => '<a rel="spotlight;height=300px;width=600px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Link_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'link_priority' => null, 
				'X' => '<a title="Delete" rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Link_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
