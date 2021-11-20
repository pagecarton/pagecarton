<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
			return $this->setViewContent(  '' . self::__( '<p class="badnews">ERROR - You need to have an item in your shopping cart to confirm checkout</p>' ) . '', true  );
		}
			
		$data = Application_Subscription_Checkout_CheckoutOption::getInstance()->selectOne( null, array( 'checkoutoption_name' => $identifier['api'] ) );

		//	lets see if we can ask the gateway for status
		$className = $data['object_name'];

        $orderNumber = self::getOrderNumber( $identifier['api'] );
		if( $setOrderInfo = Application_Subscription_Checkout_Order::getInstance()->selectOne( null, array( 'order_id' => $orderNumber ) ) )
		{ 
            $identifier['status'] = $setOrderInfo['order_status'];
        }
		if( Ayoola_Loader::loadClass( $className ) )
		{ 
			if( method_exists( $className, 'checkStatus' ) )
			{
				if( $orderInfo = $className::checkStatus( $orderNumber ) )
				{
					switch( strtolower( $orderInfo['order_status'] ) )
					{ 
						case 'payment successful':
						case '99':
						case '100':
							$identifier['status'] = 1;
						break;   
					}
				}
			}
		}

		$this->setViewContent( "<br><h2>Thank you! Order Confirmed! </h2><br>" );
		$this->setViewContent( "
        <p><b>STATUS</b>: "  . self::$checkoutStages[intval( $identifier['status'] )] . " <br>
        <b>ORDER NUMBER</b>: " . $orderNumber . "</p><br>" 
        );
		$this->setViewContent( "<p>" . ( Application_Settings_Abstract::getSettings( 'Payments', 'order_confirmation_message' ) ? : "You can print this page for your records. Your order number is a unique identifier that should be mentioned when referencing this order." ) . "</p><br>" );
		
        if( ! empty( $data['logo'] ) )
        {
            $data['checkoutoption_logo'] = '<img height="100px" src="' . Ayoola_Application::getUrlPrefix() . $data['logo'] . '" alt="' . $data['checkoutoption_name'] . ' logo" >';		
        }
		
        if( ! empty( $data['checkoutoption_logo'] ) )
        {
            $this->setViewContent( "<h4>Payment Option</h4><br>" );   
            $data['checkoutoption_logo'] = htmlspecialchars_decode( $data['checkoutoption_logo'] );
            $this->setViewContent( "<p>{$data['checkoutoption_logo']}</p><br>" );		
        }
		if( $identifier['status'] )
		{
			$this->setViewContent( "<h4>Order Details</h4><br>" );
			$this->setViewContent( Application_Subscription_Cart::viewInLine() );
			$this->setViewContent( "
									<h4>What Next???</h4>
									<br>
									<p><a href='" . Ayoola_Application::getUrlPrefix() . "/widgets/Application_Subscription_Checkout_Order_View?order_id=" . $orderNumber . "'>Check order status</a>.</p>
									<p><a href='{$cart['settings']['return_url']}'>Go back</a>.</p>
									" 
									);
			$notes = Application_Settings_Abstract::getSettings( 'Payments', 'order_notes' );

			//Application_Subscription_Cart::clear();

			$notes ? $this->setViewContent( "<h4>Note:</h4><br>" ) : null;
			$notes ? $this->setViewContent( $notes ) : null;          
		}
		else
		{
			$this->setViewContent( "<h4>What Next???</h4><br><p>You can checkout with other payment methods.</p><br>" );
			$this->setViewContent( Application_Subscription_Checkout::viewInLine() );
			$this->setViewContent( "<br><br><h4>Order Details</h4><br>" );
			$this->setViewContent( Application_Subscription_Cart::viewInLine() . "<br><br><br><br>");
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
							'body' => '' . $this->view() . '',
		
		);
		$emailInfo['to'] = implode( ',', array_unique( $emailAddress ) );
		$emailInfo['html'] = true; 
		if( $emailAddress )
		{		
			@self::sendMail( $emailInfo );
		//	self::v( $emailInfo );
			
		}
		//	Notify Admin
		$emailInfo['to'] = 	Ayoola_Application_Notification::getEmails();

		$emailInfo['subject'] = 'Checkout Confirmation';
		$emailInfo['body'] = 'Someone just confirmed their checkout. Here is the cart content: <br> 
		' . $this->view() . ' <br>  

		ORDER INFORMATION
		' . self::arrayToString( $orderInfo ) . ' <br>  
		';
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
