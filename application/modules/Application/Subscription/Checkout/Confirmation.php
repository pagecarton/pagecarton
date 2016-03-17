<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Confirmation
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Confirmation.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';  


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Confirmation
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Confirmation extends Application_Subscription_Checkout_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'api', 'status' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected static $_status = array( 1 => 'Your order was successful.', 0 => '<span class="badnews">PAYMENT FAILED</span>' );
	
    /**
     * Table where to store orders
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_Order';
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {		
		//	Identifiers are required
		if( ! $identifier = $this->getIdentifier() ){ return false; }
		if( ! $cart = self::getStorage()->retrieve() )
		{ 
			return $this->setViewContent( '<p class="badnews">ERROR - You need to have an item in your shopping cart to confirm checkout</p>', true );
		}
			
	//	self::v( $cart );
		$this->setViewContent( "<h3>Thank you! Your Order is Confirmed. </h3>" );
		$this->setViewContent( "<h4>STATUS: "  . self::$_status[intval( $identifier['status'] )] . "</h4>" );
		$this->setViewContent( "<h4>ORDER NUMBER: " . self::getOrderNumber( $identifier['api'] ) . "</h4>" );
		$this->setViewContent( "<p>You can print this page for your records. Your order number is a unique identifier that should be mentioned when referencing this order.</p>" );
		$this->setViewContent( "<h4>Payment Option</h4>" );
		$table = new Application_Subscription_Checkout_CheckoutOption();
		$data = $table->selectOne( null, array( 'checkoutoption_name' => $identifier['api'] ) );
		$data['checkoutoption_logo'] = htmlspecialchars_decode( $data['checkoutoption_logo'] );
		$this->setViewContent( "<p>{$data['checkoutoption_logo']}</p>" );		
		if( $identifier['status'] )
		{
			$this->setViewContent( "<p></p><h4>Order Details</h4>" );
			$this->setViewContent( Application_Subscription_Cart::viewInLine() );
			self::getStorage()->clear(); 
		//	self::getOrderNumber(); //	Clear order number history
			$this->setViewContent( "<h4>What Next???</h4><p>Go back to <a href='{$cart['settings']['return_url']}'>Previous Page</a>.</p>" );
			$notes = Application_Settings_Abstract::getSettings( 'Payments', 'order_notes' );

			$notes ? $this->setViewContent( "<h4>Note:</h4>" ) : null;
		//	$this->setViewContent( "<h4>Note:</h4><p>Orders can take up to 24 hours after payment is confirmed for fufillment ( depending on the payment method ). Please be patient.</p>" );
			$notes ? $this->setViewContent( $notes ) : null;
		}
		else
		{
			$this->setViewContent( "<h4>What Next???</h4><p>You can checkout with other payment methods.</p>" );
			$this->setViewContent( Application_Subscription_Checkout::viewInLine() );
			$this->setViewContent( "<p></p><h4>Order Details</h4>" );
			$this->setViewContent( Application_Subscription_Cart::viewInLine() );
		}
		//	SEND THE user AN EMAIL IF HE IS LOGGED INN
		$emailAddress = array();
		if( Ayoola_Application::getUserInfo( 'email' ) )
		{
			$emailAddress[] = Ayoola_Application::getUserInfo( 'email' );
		}
		if( @$cart['checkout_info']['email'] )
		{
			$emailAddress[] = $cart['checkout_info']['email'];   
		}

		$emailInfo = array(
							'subject' => 'Order Notification',
							'body' => '<html><body>' . $this->view() . '</body></html>',
		
		);
		$emailInfo['to'] = implode( ',', array_unique( $emailAddress ) );
		$emailInfo['from'] = '' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . ' <no-reply@' . Ayoola_Page::getDefaultDomain() . '>"';
		$emailInfo['html'] = true; 
		if( $emailAddress )
		{		
			@self::sendMail( $emailInfo );
		//	self::v( $emailInfo );
			
		}
		//	Notify Admin
	//	$mailInfo = array();
		$emailInfo['to'] = Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'email' );
		$emailInfo['subject'] = 'Checkout Confirmation';
		$emailInfo['body'] = '<html><body>Someone just confirmed their checkout. Here is the cart content: <br> 
		' . $this->view() . ' <br>  
		Subscription options are available on: http://' . Ayoola_Page::getDefaultDomain() . '/ayoola/subscription/<br>
		</body></html>';
		try
		{
		//	var_export( $emailInfo );
			@self::sendMail( $emailInfo );
		//	Ayoola_Application_Notification::mail( $emailInfo );
		}
		catch( Ayoola_Exception $e ){ null; }
		
    } 
	// END OF CLASS
}
