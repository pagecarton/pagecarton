<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @HashTag   Ayoola
 * @package    Application_HashTag_List
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_HashTag_Abstract
 */
 
require_once 'Application/HashTag/Abstract.php';


/**
 * @HashTag   Ayoola
 * @package    Application_HashTag_List
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_HashTag_List extends Application_HashTag_Abstract
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
		$list->listTitle = 'List of HashTags on this Application';
		$list->setData( $this->getDbData() );
	//	$this->setIdColumn( 'HashTag_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no HashTags on this application yet.' );
		$list->createList(  
			array(
				'HashTag_title' => '<a rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_HashTag_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'X' => '<a title="Delete" rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_HashTag_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
