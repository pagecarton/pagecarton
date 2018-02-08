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
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Checkout Confirmation';       
	
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
		$table = Application_Subscription_Checkout_CheckoutOption::getInstance();
		$data = $table->selectOne( null, array( 'checkoutoption_name' => $identifier['api'] ) );

		//	lets see if we can ask the gateway for status
	//	var_export( $data );
		$className = $data['object_name'];

	//	var_export( $identifier );
		$orderNumber = self::getOrderNumber( $identifier['api'] );
		if( Ayoola_Loader::loadClass( $className ) )
		{ 
			if( method_exists( $className, 'checkStatus' ) )
			{
				if( $orderInfo = $className::checkStatus( $orderNumber ) )
				{
			//		var_export( $orderInfo );
					if( $orderInfo['order_status'] === 'Payment Successful' )
					{
						$identifier['status'] = 1;
					}
					else
					{
						$identifier['status'] = 0;
					}
				}
			}
		}


		$this->setViewContent( "<br><h3>Thank you! Order Confirmed! </h3><br>" );
		$this->setViewContent( "<h4>STATUS: "  . self::$_status[intval( $identifier['status'] )] . "</h4><br>" );
		$this->setViewContent( "<h4>ORDER NUMBER: " . $orderNumber . "</h4><br>" );
		$this->setViewContent( "<p>You can print this page for your records. Your order number is a unique identifier that should be mentioned when referencing this order.</p><br>" );
		$this->setViewContent( "<h4>Payment Option</h4><br>" );   
		$data['checkoutoption_logo'] = htmlspecialchars_decode( $data['checkoutoption_logo'] );
		$this->setViewContent( "<p>{$data['checkoutoption_logo']}</p><br>" );		
		if( $identifier['status'] )
		{
			$this->setViewContent( "<h4>Order Details</h4><br>" );
			$this->setViewContent( Application_Subscription_Cart::viewInLine() );
			self::getStorage()->clear(); 
		//	self::getOrderNumber(); //	Clear order number history
			$this->setViewContent( "<h4>What Next???</h4><br><p>Go back to <a href='{$cart['settings']['return_url']}'>Previous Page</a>.</p>" );
			$notes = Application_Settings_Abstract::getSettings( 'Payments', 'order_notes' );

			$notes ? $this->setViewContent( "<h4>Note:</h4><br>" ) : null;
		//	$this->setViewContent( "<h4>Note:</h4><p>Orders can take up to 24 hours after payment is confirmed for fufillment ( depending on the payment method ). Please be patient.</p>" );
			$notes ? $this->setViewContent( $notes ) : null;
		}
		else
		{
			$this->setViewContent( "<h4>What Next???</h4><br><p>You can checkout with other payment methods.</p><br>" );
			$this->setViewContent( Application_Subscription_Checkout::viewInLine() );
			$this->setViewContent( "<h4>Order Details</h4><br>" );
			$this->setViewContent( Application_Subscription_Cart::viewInLine() );
		}
		//	SEND THE user AN EMAIL IF HE IS LOGGED INN
		$emailAddress = array();
		if( Ayoola_Application::getUserInfo( 'email' ) )
		{
			$emailAddress[] = Ayoola_Application::getUserInfo( 'email' );
		}
		@$checkoutEmail = $cart['checkout_info']['email'] ? : $cart['checkout_info']['email_address'];
		if( $checkoutEmail )
		{
			$emailAddress[] = $checkoutEmail;   
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

		ORDER INFORMATION
		' . self::arrayToString( $orderInfo ) . ' <br>  
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
