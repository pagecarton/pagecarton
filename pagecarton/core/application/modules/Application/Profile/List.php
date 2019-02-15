<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_List extends Application_Profile_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
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
		$list->listTitle = 'List of Profiles on this Application';
		$table = "Application_Profile_Table";
		$table = $table::getInstance( $table::SCOPE_PRIVATE );
		$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
		$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
		$list->setData( $table->select( null, null, array( 'x' => 'workaround-to-avoid-cache' ) ) );  
		$this->setIdColumn( 'profile_url' );
		$list->setKey( 'profile_url' );
		$list->setNoRecordMessage( 'You have not created any profile yet. <a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_Creator/">Create!</a>' );
		$list->createList(  
			array(
				'profile_url' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				' ' => '<a title="View profile" target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/%KEY%">preview</a>', 
				'X' => '<a title="Delete" rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
