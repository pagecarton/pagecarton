<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
 * @package    Ayoola_Extension_Import_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Import_List extends Ayoola_Extension_Import_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Installed Plugins'; 

    /**	Whether to translate widget inner conetent
     *
     * @var bool
     */
	public static $translateInnerWidgetContent = true;
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		$this->setViewContent( $this->getList(), true );	
//		$this->setViewContent( Ayoola_Extension_List::viewInLine() );	

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
										'Creator' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Creator/\', \'' . __CLASS__ . '\' );" title="Import a new plugins">Upload New</a>',
										'Browse' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Repository\', \'' . $this->getObjectName() . '\' );" title="">Browse More Plugins</a>',
										'<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_List\', \'' . $this->getObjectName() . '\' );" title="">My Plugins</a>',
									) 
							);
		$list->setNoRecordMessage( 'No plugins installed yet.' );
		$list->createList(  
			array(
				'extension_title' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" title="Edit plugin" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Status/?' . $this->getIdColumn() . '=%KEY%" >%FIELD%</a>', 
				'status' => '%FIELD% <a rel="spotlight;changeElementId=' . $this->getObjectName() . '" title="Change Status" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Status/?' . $this->getIdColumn() . '=%KEY%" >change</a>', 
				'  ' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" title="Settings" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Settings/?' . $this->getIdColumn() . '=%KEY%">Settings</a>', 
				' ' => array( 'value' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" title="Change Status" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Repository?title=Update Plugin&layout_type=upload&install=%FIELD%">update</a>', 'field' => 'article_url' ), 
				'   ' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Extension_Import_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
