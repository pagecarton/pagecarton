<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Cron_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Cron_Abstract
 */
 
require_once 'Application/Cron/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Cron_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Cron_List extends Application_Cron_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
		
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
		$list->listTitle = 'List of Scheduled Tasks on this Application';
		$list->setData( $this->getDbData() );
		$list->setListOptions( array( 'Creator' => '<a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Cron_Creator/" title="Schedule a task with cron">+</a> | <a rel="spotlight;height=300px;width=600px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Cron_ShowAll/" title="Show all scheduled tasks.">Show all</a>' ) );
	//	$this->setIdColumn( 'advert_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no scheduled task on this application yet.' );
		$list->createList(  
			array(
				'task' => '<a rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Cron_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'<a title="Delete" rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Cron_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
