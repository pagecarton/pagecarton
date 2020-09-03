<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_List extends Application_Subscription_Abstract
{
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
	//	$this->setViewContent( self::__( '<h3>Options:</h3>' ) );		
	//	$this->setViewContent( self::__( '<h4><a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Creator/" title="Add a product or service."> + </a></h4>' ) );		
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
		$list->listTitle = 'List of Subscription Options on this Application';
		$list->pageName = $this->getObjectName();
		$list->setData( $this->getDbData() );
		$list->setListOptions( array( 'Creator' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Creator/" title="Add a product or service."> + </a>' ) );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no products or services on this application yet.' );
		$list->createList(  
			array(
				'subscription_label' => '<a title="Edit %FIELD%" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Editor/' . $this->getIdColumn() . '/%KEY%/">[%FIELD%]</a>', 
			//	'subscription_description' => null,
				'document_url' => '<img height="32" alt="%FIELD%" title="%FIELD%" src="%FIELD%" />',
				'+' => '<a title="Add a category for this product or service." rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Level_Creator/' . $this->getIdColumn() . '/%KEY%/">+</a>', 
				'--' => '<a title="List available categories for this product or service" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Level_List/' . $this->getIdColumn() . '/%KEY%/">--</a>', 
				'<a  title="Delete product or service." rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Delete/' . $this->getIdColumn() . '/%KEY%/"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 	
	// END OF CLASS
}
