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
		$this->setViewContent( '<h2>Details of Order #'  . $identifierData['order_id'] . '</h2>', true );

		$class = new Application_Subscription_Cart( array( 'cart' => $identifierData['order'] ) );
		$this->setViewContent( $class->view() );
//		if( $this->updateDb() ){ $this->setViewContent( 'Order edited successfully', true ); }
    } 
	// END OF CLASS
}
