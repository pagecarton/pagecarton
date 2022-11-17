<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Checkout.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout extends Application_Subscription_Abstract
{
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Unique Order Number
     *
     * @var string
     */
	protected static $_orderNumber;
	
    /**
     * Unique Order Number
     *
     * @var string
     */
	protected static $checkoutStages = array( 0 => 'Order Failed', 'Payment Failed' => 'Payment Failed', 1 => 'Checkout Completed', 'Checkout Attempted' => 'Checkout Attempted', 2 => 'Payment Disputed', 'Payment Disputed' => 'Payment Disputed', 99 => 'Payment Successful', 'Payment Successful' => 'Payment Successful', 100 => 'Completed', 'Completed' => 'Completed' );
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_CheckoutOption';
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
            if( ! $cart = self::getStorage()->retrieve() )
            { 
                return $this->setViewContent(  '' . self::__( '<span class="boxednews centerednews badnews">You have no item in your shopping cart.</span>' ) . '', true  );
            }

            $userInfo = Ayoola_Application::getUserInfo();

            $this->createForm( null, null , $userInfo ? : null );

            //	Record in the orders table
            $notes = Application_Settings_Abstract::getSettings( 'Payments', 'order_notes' );

            $notes ? $this->setViewContent( $notes ) : null;
            $this->setViewContent( $this->getForm()->view() );
            if( ! $values = $this->getForm()->getValues() ){ return false; }

            //	Dont save plaintext password
            unset( $values['password'] );
            unset( $values['password2'] );
                
            //	Put the checkout info in the cart
            $cart = self::getStorage()->retrieve();
            $cart['checkout_info'] = $values;
            self::getStorage()->store( $cart );

            //  save checkout info to user account
            if( $userInfo )
            {
                $userInfo['checkout_info'] = $values;
                Ayoola_Access_Localize::info( $userInfo );
            }
            
            //	Notify Admin
            $mailInfo = array();
            $mailInfo['subject'] = 'Checkout Attempted';
            $mailInfo['html'] = true; 
            $mailInfo['body'] = '   
                            Someone just attempted to checkout. Here is the cart content<br>
                            ' . Application_Subscription_Cart::viewInLine() . '<br>
                            The information entered by the user is as follows:
                            ' . self::arrayToString( $values ) . '<br>			
            ';
            try
            {
                $mailInfo['to'] = Ayoola_Application_Notification::getEmails();
                @self::sendMail( $mailInfo );
            }
            catch( Ayoola_Exception $e ){ null; }
            
            //	Refresh order number on every attempt to checkout
            $checkoutInfo = array();
            if( ! $api = self::getApi( $values['checkoutoption_name'] ) )
            {
                $table = Application_Subscription_Checkout_CheckoutOption::getInstance();
                $checkoutInfo = $table->selectOne( null, array( 'checkoutoption_name' => $values['checkoutoption_name'] ) );
                
            
                switch( $checkoutInfo['checkout_type'] )
                {
                    case 'http_post':
                        $api = 'Application_Subscription_Checkout_HttpPost';  
                    break;
                    default:
                        $api = $checkoutInfo['object_name'];  
                    break;
                }
            }
            if( empty( $values['checkoutoption_name'] ) )
            {
                $api = $values['checkoutoption_name'] = 'Application_Subscription_Checkout_Default';
            }
            //  generate order number
            $orderNumberGenerated = Application_Subscription_Checkout::getOrderNumber( $values['checkoutoption_name'] );
            if( $api && Ayoola_Loader::loadClass( $api ) )
            { 
                $this->setViewContent( $api::viewInLine( $checkoutInfo ), true );
		    }
		}
		catch( Exception $e )
		{
			$this->getForm()->setBadnews( $e->getMessage() ); 
			$this->setViewContent( $this->getForm()->view(), true );
		}
    } 

    /**
     * Plays the API that is selected
     * 
     */
	public static function changeStatus( $response )
    {
		$table = Application_Subscription_Checkout_Order::getInstance();
		if( ! $orderInfo = $table->selectOne( null, array( 'order_id' => $response['order_id'] ) ) )
		{ 
			return false; 
		}

		if( $orderInfo['order_status'] == $response['order_status'] )
		{ 
			return false; 
		}

        //	Treat the callback methods
		if( ! is_array( $orderInfo['order'] ) )
		{
			//	compatibility
			$orderInfo['order'] = unserialize( $orderInfo['order'] );			
		}
	    //  	$values = $orderInfo['order'];
		$output = null;
		foreach( $orderInfo['order']['cart'] as $cartKey => $each )
		{ 
			//	call backs
            if( ! isset( $each['callback'] ) ){ continue; }
			$each['full_order_info'] = $orderInfo;
			$each['order_status'] = $response['order_status'];
			$each['transactionmethod'] =  $orderInfo['order_api'];
            $each['currency_abbreviation'] = $values['settings']['currency_abbreviation'];
            if( is_scalar( $each['callback'] ) )
            {
                $callback = array_map( 'trim', explode( ',', $each['callback'] ) );
            }
			foreach( $callback as $eachCallback )
			{
				//	Let's treat callbacks'
				if( ! $eachCallback ){ continue; }
				if( ! Ayoola_Loader::loadClass( $eachCallback ) )
				{ 
					continue;
                }
                $parameters = $each;
				if( method_exists( $eachCallback, 'callback' ) )
				{
                    $eachCallback::callback( $parameters ); 
				}
				else
				{
					$eachCallback = new $eachCallback( $parameters );
					$eachCallback->initOnce();
					$output .= $eachCallback->view();
                }
                if( $parameters !== $each )
                {
                    //  callback can change the parameters. Let it reflect in cart
                    $orderInfo['order']['cart'][$cartKey] = $parameters;
                }
			}
			
		}
		$update = array( 'order_random_code' => $response['order_random_code'], 'order_status' => $response['order_status'] );
		$update = array_merge( $orderInfo, $update);  
		$table->update( $update, array( 'order_id' => $response['order_id'] )  );

        $title = null;
        $message = null;
        if( $stageInfo = Application_Subscription_Checkout_Order_Status::getInstance()->selectOne( null, array( 'code' => $response['order_status'] ) ) )
        {

            $title = ' (' . $stageInfo['title'] . ') ';
            
            $message = $stageInfo['message'];
            if( strip_tags( $message ) === $message )
            {
                $message = nl2br( $message );
            }
            $message = self::replacePlaceholders( $message, $response );
            $title = self::replacePlaceholders( $title, $response );
        }


		//	Notify shopper
		$mailInfo = array();
		$mailInfo['subject'] = 'Status change for order no ' . $response['order_id'] . $title;
		$mailInfo['body'] = null;
		$mailInfo['body'] .= $message ? : ( '' . self::arrayToString( $orderInfo ) . '' );
		$mailInfo['body'] .= $output;
		@$checkoutEmail = ( ( $response['order']['checkout_info']['email'] ? : $response['order']['checkout_info']['email_address'] ) ? : $response['order']['checkout_info']['Email Address'] ) ? : $response['email'];
		@Ayoola_Application_Notification::mail( $mailInfo );
		$mailInfo['to'] = $checkoutEmail;
		@self::sendMail( $mailInfo );

		return true;
    } 
		
    /**
     * Plays the API that is selected
     * 
     */
	public static function getApi( $checkoutOptionName = null )
    {
		$table = Application_Subscription_Checkout_CheckoutOption::getInstance();
		$data = $table->selectOne( null, array( 'checkoutoption_name' => $checkoutOptionName ) );
		$className = __CLASS__ . '_' . $data['checkoutoption_name'];
		require_once 'Ayoola/Loader.php';
		if( ! Ayoola_Loader::loadClass( $className ) )
		{ 
			return false;
		}
		return $className;
    } 
		
    /**
     * Returns the current order number
     * 
     */
	public static function getCurrentOrderInfo()
    {
		return self::getObjectStorage( 'order_info' )->retrieve() ? : array();
    }

    /**
     * Returns the current order number
     * 
     */
	public static function getOrderNumber( $orderApi = null, $newOrderNumber = false )
    {
		$storage = self::getObjectStorage( 'order_info' );
		if( ! $orderApi )
		{ 
			//$storage->clear(); 
			//return;
		}
		if( $newOrderNumber )
		{ 
			$storage->clear(); 
			self::$_orderNumber = null;
		}
		$email = strtolower( ( Ayoola_Form::getGlobalValue( 'email_address' ) ? : Ayoola_Form::getGlobalValue( 'email' ) ) ? : Ayoola_Application::getUserInfo( 'email' ) );
        $orderInfo = $storage->retrieve();
		
        //	Store order number to avoid multiple table insert
        $cart = self::getStorage()->retrieve();
        if( 
            (
                ! $orderInfo
                || empty( $orderInfo['order_number'] )
                || $orderInfo['cart_id'] != md5( serialize( $cart ) )
            )
            &&
            (
                ! empty( $orderApi )
            ) 
            //  it doesn't make sense to have new order number because of the change of payment method
            //|| ( $orderInfo['order_api'] != $orderApi )
        )
        {
            $table = new Application_Subscription_Checkout_Order();
            $insert = array( 
                                'order' => $cart, 
                                'currency' => $cart['settings']['currency_abbreviation'], 
                                'order_api' => $orderApi, 
                                'username' => strtolower( Ayoola_Application::getUserInfo( 'username' ) ), 
                                'user_id' => Ayoola_Application::getUserInfo( 'user_id' ), 
                                'email' => $email, 
                                'time' => time(), 
                                'total' => $cart['settings']['total'], 
                                'order_status' => 1,   
                                'article_url' => array_unique( $cart['settings']['article_url'] ),
                                );
            $insertInfo = $table->insert( $insert );
            $orderNumber = $insertInfo['insert_id'];
            $orderInfo = array();
            $orderInfo['cart_id'] = md5( serialize( $cart ) );
            $orderInfo['order_number'] = $orderNumber;
            $orderInfo['order_api'] = $orderApi;

            $storage->store( $orderInfo ); 
            if( $email )
            {
                $mailInfo['to'] = $email;
                $mailInfo['subject'] = 'Your order no ' . $orderInfo['order_number'];
                $mailInfo['body'] = '';
                $mailInfo['body'] .= Application_Subscription_Checkout_Order_View::viewInLine( array( 'order_id' => $orderInfo['order_number'] ) );
                self::sendMail( $mailInfo );
            }
        }

        self::$_orderNumber =  $orderInfo['order_number'];
		return self::$_orderNumber;
    } 
	
    /**
     * Checks if the checkout api supports our currency,
     * 
     */
	public static function isValidCurrency( $currency = null )
    {		
		if( is_null( $currency ) )
		{
			if( ! $values = self::getStorage()->retrieve() ){ return; }
			$currency = $values['settings']['currency_abbreviation'];
		}
		if
		( 
			( ( stripos( static::$_currency['whitelist'], $currency ) !== false ) 
				|| ( stripos( static::$_currency['whitelist'], 'ALL' ) !== false )
			)
			||
			( ( stripos( static::$_currency['blacklist'], $currency ) === false ) 
				&& ( stripos( static::$_currency['blacklist'], 'ALL' ) === false ) 
			)
		) 
		{
			return true;
		}		
		return false;
    } 
	
    /**
     * Creates the form for checkout
     * 
     */
	public function createForm( $submitValue = NULL, $legend = NULL, array $values = NULL )
    {
		$form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$formIncluded = array();
		
		$orderForm = Application_Settings_CompanyInfo::getSettings( 'Payments', 'order_form' );
        $orderFormClass = null;
        if( $orderForm )
        {
            $parameters = array( 'form_name' => $orderForm );
            $formIncluded[] = $orderForm;
            $orderFormClass = new Ayoola_Form_View( $parameters );
        }
		if( $orderFormClass && $orderFormClass->getForm() && $orderFormClass->getForm()->getFieldsets() )
		{        
			foreach( $orderFormClass->getForm()->getFieldsets() as $each )
			{
				$form->addFieldset( $each );
			}
			$form->submitValue = 'Continue checkout...';
		}
		else
		{
			$fieldset = new Ayoola_Form_Element();
			$fieldset->addElement( array( 'name' => 'firstname', 'label' => 'First Name', 'placeholder' => 'e.g. John', 'type' => 'InputText', 'value' => @$values['firstname'] ) );
			$fieldset->addElement( array( 'name' => 'lastname', 'label' => 'Last Name', 'placeholder' => 'e.g. Bello', 'type' => 'InputText', 'value' => @$values['lastname'] ) );
			$fieldset->addElement( array( 'name' => 'email_address', 'label' => 'Customer Email', 'placeholder' => 'e.g. email@example.com', 'type' => 'email', 'value' => @$values['email_address'] ) );
			$fieldset->addElement( array( 'name' => 'phone_number', 'label' => 'Customer Phone Number', 'placeholder' => 'e.g. +1-202-555-1234', 'type' => 'InputText', 'value' => @$values['phone_number'] ) );
			$fieldset->addRequirements( array( 'NotEmpty' => null ) );
			$form->addFieldset( $fieldset );
		}
		$cart = self::getStorage()->retrieve();
		if( ! empty ( $cart['cart'] ) )
		{ 
			//	Look for checkout requirements
			$requirements = array();
			foreach( $cart['cart'] as $name => $value )
			{
				if( ! empty( $value['checkout_form'] ) && ! in_array( $value['checkout_form'], $formIncluded ) )
				{
					$formIncluded[] = $value['checkout_form'];
					$parameters = array( 'form_name' => $value['checkout_form'] );
					$orderFormClass = new Ayoola_Form_View( $parameters );
					foreach( $orderFormClass->getForm()->getFieldsets() as $each )
					{
						$form->addFieldset( $each );
					}
					$form->submitValue = 'Continue checkout...';
				}
 				$value['checkout_requirements'] = is_string( $value['checkout_requirements'] ) ? array_map( 'trim', explode( ',', $value['checkout_requirements'] ) ) : $value['checkout_requirements'];
				$value['checkout_requirements'] = is_array( $value['checkout_requirements'] ) ? $value['checkout_requirements'] : array();
				$requirements += @$value['checkout_requirements'];
				
 				$globalRequirements = $this->getParameter( 'checkout_requirements' ) ? array_map( 'trim', explode( ',', $this->getParameter( 'checkout_requirements' ) ) ) : null;
				$globalRequirements = is_array( $globalRequirements ) ? $globalRequirements : array();
				$requirements += $globalRequirements;
 			}
			if( $requirements )
			{
				if( ! $this->getParameter( 'all_form_elements_at_once' ) )
				{
					$form->oneFieldSetAtATime = true;
					$form->submitValue = 'Continue checkout...';
				}
				$form->submitValue = 'Continue checkout...';
			}
			else
			{
				$form->submitValue = 'Continue checkout...'; 
			}
			self::setFormRequirements( $form, $requirements );
		}
		$fieldset = new Ayoola_Form_Element();		
		if( ! empty( intval( $cart['settings']['total'] ) ) )
		{
			$table = 'Application_Subscription_Checkout_CheckoutOption';
			$table = $table::getInstance( $table::SCOPE_PRIVATE );
			$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
			$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
            $privateOptions = $table->select( null, null, array( 'workaround-to-avoid-cache' ) );
            $publicOptions = $table::getInstance()->select();
            $allowedOptions = Application_Settings_Abstract::getSettings( 'Payments', 'allowed_payment_options' ) ? : array();
            $options = $publicOptions;
            if( empty( $allowedOptions )  )
            {
                $options = $privateOptions;
            }
			foreach( $options as $key => $each )
			{
                $api = $each['object_name'];
				if( Ayoola_Loader::loadClass( $api ) )
				{ 
                    if( method_exists( $api, 'checkoutEligibility' ) )
                    {
                        if( ! $api::checkoutEligibility( $each ) )
                        {
                            unset( $options[$key] ); 
                            continue;
                        }
                    }
                }

                $options[$key]['checkoutoption_logo'] = '<div style="margin:  2em 1em; display:inline-block">' . ( $each['checkoutoption_logo'] 
                    ? ( '' . ( $each['checkoutoption_logo'] ? : $each['checkoutoption_name'] ) . '' ) 
                    : ( '<img height="64" src="' . Ayoola_Application::getUrlPrefix() . '' . $each['logo'] . '?width=64&height=64" alt="' . $each['checkoutoption_name'] . '"> ' . $each['checkoutoption_name'] ) ) . '</div>';    
				if( $allowedOptions && ! in_array( $options[$key]['checkoutoption_name'], $allowedOptions ) )
				{ 
					unset( $options[$key] ); 
				}
			}
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'checkoutoption_name', 'checkoutoption_logo');    
			$options = $filter->filter( $options );
													
            $editLink = self::hasPriviledge( 98 ) ? ( '<a class="" rel="spotlight;" title="Change organization contact information" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Payments/">(edit payment informaton)</a>' ) : null; 
            $label = '';
			if( count( $options ) >= 1 )
			{
				@$values['checkoutoption_name'] = array_pop( array_keys( $options ) ); 
            }
            else
            {
                $label = 'Select Payment Option';
            }
			$options ? $fieldset->addElement( array( 'name' => 'checkoutoption_name', 'label' => $label , 'type' => 'Radio', 'value' => @$values['checkoutoption_name'] ), $options ) : null;
		}
		if( $cart['settings']['terms_and_conditions'] )
		{
			$options = array( 'Agree' => 'I agree to above terms and conditions' );
			$fieldset->addElement( array( 'name' => 'terms_and_conditions', 'readonly' => 'readonly', 'style' => 'overflow:auto;resize: vertical;min-height:200px;', 'label' => 'Terms and Conditions' , 'type' => 'textarea', 'value' => $cart['settings']['terms_and_conditions'] ) );
			$fieldset->addElement( array( 'name' => 'terms', 'label' => ' ' , 'type' => 'Checkbox', 'value' => null ), $options );
			$fieldset->addElement( array( 'name' => 'checkterms', 'label' => ' ' , 'type' => 'Hidden', 'value' => null ) );
			if( ! $this->getGlobalValue( 'terms' ) )
			{
				$fieldset->addRequirement( 'checkterms', array( 'NotEmpty' => array( 'badnews' => 'You must agree to the terms and conditions before completing your order' ) ) );
			}
		}
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    }
	// END OF CLASS
}
