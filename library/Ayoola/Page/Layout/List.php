<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_List extends Ayoola_Page_Layout_Abstract
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
		$list->listTitle = 'List of layout templates on this application';
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );  
	//	$list->setListOptions( array( 'Creator' => '<a class="goodnews" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Creator/" title="Create a new layout template">+</a>' ) );
		$list->setNoRecordMessage( 'No layout yet on this application.' );
		$list->createList(  
			array(
				'layout_label' => '%FIELD%',   
				'Screenshot' => '<a title="%KEY%" href="javascript:;"><img src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=%KEY%" style="height:50px;float:left;" alt="%KEY%" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?' . $this->getIdColumn() . '=%KEY%\' );" /></a>', 
				'edit' => '<a title="Edit with a plain text editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?' . $this->getIdColumn() . '=%KEY%\' );" href="javascript:;">Edit</a>', 
				'WYSIWYG' => '<a title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/ayoola/page/edit/layout/?url=/layout/%KEY%/template\' );" href="javascript:;">WYSIWYG</a>', 
				'export' => '<a title="Export template" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Export/?' . $this->getIdColumn() . '=%KEY%\' );" href="javascript:;">Export</a>', 
				'x' => '<a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
