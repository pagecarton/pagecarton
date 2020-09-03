<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Abstract
 */
 
require_once 'Ayoola/Page/Menu/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_List extends Ayoola_Page_Menu_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Navigations'; 
	
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
		$list->listTitle = self::getObjectTitle();
		if( @$_GET['get_all_menu'] )
		{
			$table = $this->getTableClass();
			$table = $table::getInstance( $table::SCOPE_PROTECTED );
			$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PROTECTED );
			$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PROTECTED );
			$list->setData( $table->select() );
		//	$response = $table->select();
		}
		else
		{
			$list->setData( $this->getDbData() );
		}

		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No navigation menu has been created on this site' ); 
		$list->setListOptions( 
								array( 
										'Manage Menu Templates' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Menu_List/\' );" title=""> Manage Menu Templates </a>',
										'All' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_List/?get_all_menu=1\' );" title=""> Default Navigations </a>',
									) 
							);
		$list->createList(  
			array(
				'menu_label' => '<a title="Edit link options for: %FIELD%." rel="shadowbox;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_List/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'menu_name' => null, 
		//		'document_url' => null,
				' ' => '<a title="Add a link option to this menu." rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_Creator/?' . $this->getIdColumn() . '=%KEY%">Add Link Option</a>', 
				'  ' => '<a title="Edit Menu Information" title="Edit Menu Information" title="Edit Menu Information" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Editor/?' . $this->getIdColumn() . '=%KEY%">Manage Options</a>', 
				'X' => '<a title="Delete Menu from site." rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
