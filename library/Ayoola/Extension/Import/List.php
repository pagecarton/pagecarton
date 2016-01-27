<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Import_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Import_List extends Ayoola_Extension_Import_Abstract
{
	
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
		$list->listTitle = 'Imported PageCarton extensions on this application';
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );  
		$list->setListOptions( 
								array( 
										'Creator' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Creator/\' );" title="Import a new extension">Import Extension</a>',
									) 
							);
		$list->setNoRecordMessage( 'No extensions is on this application.' );
		$list->createList(  
			array(
			//	'extension_title' => '%FIELD%',   
				'extension_title' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" title="Edit extension" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Status/?' . $this->getIdColumn() . '=%KEY%" href="javascript:;">%FIELD%</a>', 
				'status' => '%FIELD% <a rel="spotlight;changeElementId=' . $this->getObjectName() . '" title="Change Status" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Status/?' . $this->getIdColumn() . '=%KEY%" href="javascript:;">change</a>', 
				'x' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
