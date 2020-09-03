<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Domain_Registration_Price_Abstract
 */
 
require_once 'Application/Domain/Registration/Price/Abstract.php';


/**
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration_Price_List extends Application_Domain_Registration_Price_Abstract
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
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'Domain registration price list';
		$list->setData( $this->getDbData() );
	//	$this->setIdColumn( 'price_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not created a price list for domain registration.' );
		$list->createList(  
			array(
				'extension' => null, 
				'price' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Registration_Price_Editor/?' . $this->getIdColumn() . '=%KEY%">[%FIELD%]</a>', 
				'<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Registration_Price_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
