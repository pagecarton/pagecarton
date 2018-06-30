<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Order_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Checkout_Order_Abstract
 */
 
require_once 'Application/Subscription/Checkout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Order_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Order_List extends Application_Subscription_Checkout_Order_Abstract
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
		$list->listTitle = 'List of Orders Placed on this Application';
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No one has placed an order yet.' );

		if( ! self::hasPrivilege( 98 ) )
		{
			$this->_dbWhereClause['username'] = Ayoola_Application::getUserInfo( 'username' );
		}
		if( ! empty( $_GET['article_url'] ) AND $postInfo = Application_Article_Abstract::loadPost( $_GET['article_url'] ) )
		{
			$this->_dbWhereClause['article_url'] = $_GET['article_url'];
		}

		$cur = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' );
		$list->createList(  
			array(
				'order_id' => '%KEY% <a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Checkout_Order_View/?' . $this->getIdColumn() . '=%KEY%">details</a>', 
				'email' => null, 
		//		'user' => array( 'field' => 'username', 'value' => '%FIELD%', 'filter' => '' ), 
				'status' => array( 'field' => 'order_status', 'value' => '%FIELD%', 'filter' => '' ), 
				'Method' => array( 'field' => 'order_api', 'value' => '%FIELD%', 'filter' => '' ), 				
				'total' => $cur . ' %FIELD%', 
				'time' => array( 'field' => 'time', 'value' => '%FIELD%', 'filter' => 'Ayoola_Filter_Time' ), 
				' ' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Checkout_Order_Editor/?' . $this->getIdColumn() . '=%KEY%"> update </a>', 
				'  ' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Checkout_Order_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
