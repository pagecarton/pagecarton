<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_List extends Ayoola_Extension_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Plugins Built on this Installation'; 
	
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
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = $this->getObjectTitle();
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );  
		$list->setListOptions( 
								array( 
										'Creator' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Creator/\', \'' . __CLASS__ . '\' );" title="Build a new plugin">Build New Plugin</a>',
										'DB Table' => '<a rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_CreateFile/?file_type=table\' );" title="">New DB Table</a>',  
										'Settings' => '<a rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_CreateFile/?file_type=settings\' );" title="">New Settings</a>',  
										'Widget' => '<a rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_CreateFile/\' );" title="">New Widget</a>',    
									) 
							);
		$list->setNoRecordMessage( 'No plugins built yet.' );
		$list->createList(  
			array(
			//	'extension_title' => '%FIELD%',   
				'extension_title' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" title="Edit plugin" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Editor/?' . $this->getIdColumn() . '=%KEY%" href="javascript:;">%FIELD%</a>', 
				'download' => '<a title="Download Plugin" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Download/?' . $this->getIdColumn() . '=%KEY%\' );" href="javascript:;">Download</a>', 
				'x' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
