<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Price_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Price_Abstract
 */
 
require_once 'Application/Subscription/Price/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Price_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Price_List extends Application_Subscription_Price_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'subscriptionlevel_id' );
	
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
     * Overrides the parent's || Sets _identifierData
     * 
     */
	public function setIdentifierData( $identifier = NULL )
    {
		$table = $this->getDbTable();
		$this->_identifierData = (array) $table->select( null, $this->getIdentifier() );
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
		$this->setIdentifierData();
	//	var_export( $this->_identifierData );
		if( $this->_identifierData )
		{
			$list->listTitle = 'List of Price Options for "' . $this->_identifierData[0]['subscription_label'] . '" (' . $this->_identifierData[0]['subscriptionlevel_name'] . ')';
		}
		$list->setData( $this->getIdentifierData() );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'This product category does not have a listed price yet.' );
		$list->createList(  
			array(
				'price' => '<a title="%FIELD%" rel="shadowbox;height=300px;width=300px;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Price_Editor/?' . $this->getIdColumn() . '=%KEY%&' . http_build_query( $this->getIdentifier() ) . '">%FIELD%</a>', 
				'<a rel="shadowbox;height=300px;width=300px;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Price_Delete/?' . $this->getIdColumn() . '=%KEY%&' . http_build_query( $this->getIdentifier() ) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
