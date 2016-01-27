<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Advert_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Advert_Abstract
 */
 
require_once 'Application/Advert/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Advert_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Advert_List extends Application_Advert_Abstract
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
		$list->listTitle = 'List of Adverts on this Application';
		$list->setData( $this->getDbData() );
	//	$this->setIdColumn( 'advert_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no adverts on this application yet.' );
		$list->createList(  
			array(
				'advert_title' => '<a rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Advert_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'X' => '<a title="Delete" rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Advert_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
