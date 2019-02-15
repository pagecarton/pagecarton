<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Cron_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Cron_Abstract
 */
 
require_once 'Application/Cron/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Cron_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Cron_ShowAll extends Application_Cron_List
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
		$list->hideCheckbox = true;
	//	$list->hideNumbering = true;
		$list->pageName = $this->getObjectName();
		$list->setData( self::getCron() );
	//	$list->setData( array( 'We', 'are', 4, 67, ) );
		$list->setKey( 'task' );
		$list->setNoRecordMessage( 'There are no scheduled task on this application yet.' );
		$list->createList(  
			array(
				'task' => null, 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
