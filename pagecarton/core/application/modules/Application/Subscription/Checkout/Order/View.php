<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Order_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Checkout_Order_Abstract
 */
 
require_once 'Application/Subscription/Checkout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Order_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Order_View extends Application_Subscription_Checkout_Order_Abstract
{	
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try{ $this->setIdentifier(); }
		catch( Application_Subscription_Checkout_Order_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
	//	$this->createForm( 'Edit Order', 'Edit ' . $identifierData['order_id'], $identifierData );

		#
		$this->setViewContent( '<h2>Order no '  . $identifierData['order_id'] . '</h2>', true );


		$this->setViewContent( '<h3>Order Details</h3>' );
	//	var_export( $identifierData );
//		var_export( $identifierData['order']['checkout_info'] );
		$class = new Application_Subscription_Cart( array( 'cart' => $identifierData['order'] ) );
		$this->setViewContent( $class->view() );
		$data = Application_Subscription_Checkout_CheckoutOption::getInstance()->selectOne( null, array( 'checkoutoption_name' => $identifierData['order_api'] ) );
		$this->setViewContent( '<h3>Payment Method</h3>' );
		$this->setViewContent( '<div>'  . $data['checkoutoption_name'] . '<br> '  . $data['checkoutoption_logo'] . '</div>' );
		$this->setViewContent( '<h3>Order  Status</h3>' );
		$this->setViewContent( '<p>'  . $identifierData['order_status'] . '</p>' );
		$this->setViewContent( '<h3>Customer Information</h3>' );
		$this->setViewContent( self::arrayToString( $identifierData['order']['checkout_info'] ) );

//		var_export( $data );


//		if( $this->updateDb() ){ $this->setViewContent( 'Order edited successfully', true ); }
    } 
	// END OF CLASS
}
