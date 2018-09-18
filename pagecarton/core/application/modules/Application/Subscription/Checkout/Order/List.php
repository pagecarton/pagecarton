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
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1, 98 );
	
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
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'Orders';
		$cur = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' );
		$listInfo = array(
							'order_id' => '%KEY% <a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Checkout_Order_View/?' . $this->getIdColumn() . '=%KEY%">details</a>', 
							'email' => null, 
							'status' => array( 'field' => 'order_status', 'value' => '%FIELD%', 'filter' => '', 'value_representation' => static::$checkoutStages ), 
			//				'method' => array( 'field' => 'order_api', 'value' => '%FIELD%', 'filter' => '' ), 				
							'total' => $cur . ' %FIELD%', 
							'time' => array( 'field' => 'time', 'value' => '%FIELD%', 'filter' => 'Ayoola_Filter_Time' ), 
							' ' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Checkout_Order_Editor/?' . $this->getIdColumn() . '=%KEY%"> update </a>', 
							'  ' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Checkout_Order_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			);

		if( ! self::hasPriviledge( 98 ) )
		{
			unset( $listInfo['email'] );
			unset( $listInfo['method'] );
			unset( $listInfo[' '] );
			unset( $listInfo['  '] );
			$this->_dbWhereClause['username'] = strtolower( Ayoola_Application::getUserInfo( 'username' ) );
			$list->listTitle = 'Orders by ' . Ayoola_Application::getUserInfo( 'username' );
		}
		if( ! empty( $_GET['article_url'] ) AND $postInfo = Application_Article_Abstract::loadPostData( $_GET['article_url'] ) )
		{
			$this->_dbWhereClause['article_url'] = $_GET['article_url'];
			$list->listTitle = 'Orders for ' . $postInfo['article_title'];
		}
		require_once 'Ayoola/Paginator.php';
		$data = $this->getDbData();
	//	self::v( $data );  
		$list->setData( $data );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No orders placed yet.' );


		$list->createList( $listInfo );
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
