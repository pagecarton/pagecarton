<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Game   Ayoola
 * @package    Application_Game_Admin_ListLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Level.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Game_Admin_Abstract
 */
 
require_once 'Application/Game/Abstract.php';


/**
 * @Game   Ayoola
 * @package    Application_Game_Admin_ListLevel 
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Game_Admin_ListLevel extends Application_Game_Admin_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'level' );
	
    /**     
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Game_Level';
	
		
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
		$list->listTitle = 'List of Games on this Application';
		$list->showPagination = false;
		$list->showSearchBox = false;
		$list->setData( $this->getDbData() );
	//	$this->setIdColumn( 'Game_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No game levels listed yet.' );
		$list->createList(  
			array(
				'level' => '%FIELD%', 
				'payout_amount' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Game_Admin_EditorLevel/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'edit' => '<a title="Edit" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Game_Admin_EditorLevel/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Game_Admin_DeleteLevel/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
