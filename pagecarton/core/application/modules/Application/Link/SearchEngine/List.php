<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Link_SearchEngine_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Link_SearchEngine_Abstract
 */
 
require_once 'Application/Link/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Link_SearchEngine_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Link_SearchEngine_List extends Application_Link_SearchEngine_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	$this->setViewContent(  '' . self::__( '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/linkCreator/">Create new Link</a>' ) . '', true  );
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
		$list->listTitle = 'List of Search Engines on this Application';
		$list->setData( $this->getDbData() );
		$this->setIdColumn( 'searchengine_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not added any search engine yet' );
		$list->createList(  
			array(
				'searchengine_name' => '<a rel="spotlight;height=300px;width=600px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Link_SearchEngine_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'X' => '<a title="Delete" rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Link_SearchEngine_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
