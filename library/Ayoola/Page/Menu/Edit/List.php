<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Edit_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Edit_Abstract
 */
 
require_once 'Ayoola/Page/Menu/Edit/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Edit_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Edit_List extends Ayoola_Page_Menu_Edit_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'menu_id' );
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList()
    {
		$data = array();
		try
		{ 
			$data = $this->getDbData();
			$menuTable = new Ayoola_Page_Menu_Menu();
			$menuInfo = $menuTable->selectOne( null, $this->getIdentifier() );
		}
		catch( Ayoola_Page_Menu_Edit_Exception $e ){ null; }    
		$list = new Ayoola_Paginator( $data );
		$list->listTitle = 'Menu options for "' . $menuInfo['menu_label'] . '"';
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no options on this menu' );
		$identifier = http_build_query( $this->getIdentifier() );
		$list->setListOptions( 
								array( 
										'Creator' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_Creator/?menu_id=' . $menuInfo['menu_id'] . '\' );" title=""> Add link option </a>',
									) 
							);
		$list->createList(  
			array(
				'option_name' => '<a title="Click to edit this link option" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_Editor/?' . $this->getIdColumn() . '=%KEY%&' . $identifier . '">%FIELD%</a>', 
				'url' => '<a title="Click to edit this link option" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_Editor/?' . $this->getIdColumn() . '=%KEY%&' . $identifier . '">%FIELD%</a>', 
				'x' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_Delete/?' . $this->getIdColumn() . '=%KEY%&' . $identifier . '">x</a>',   
			)
		);
	//	var_export( $list );
		return $list;
    } 
	
    /**
     * Produces the HTML output of the object
     * 
     * @param void
     * @return string
     */
	public function view()
    {
		return $this->getList(); 
    } 
	// END OF CLASS
}
