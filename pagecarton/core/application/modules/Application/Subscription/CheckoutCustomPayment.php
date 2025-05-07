<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_CheckoutCustomPayment
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Checkout.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_CheckoutCustom
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_CheckoutCustomPayment
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_CheckoutCustomPayment extends Application_Subscription_CheckoutCustom
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Payment Information';       

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
                return false;
            }

            $api = $cart['checkout_info']['checkoutoption_name'];

            $table = Application_Subscription_Checkout_CheckoutOption::getInstance();
            $checkoutInfo = $table->selectOne( null, array( 'checkoutoption_name' => $api ) );

            $this->_objectTemplateValues = array_merge( $cart['checkout_info'], $this->_objectTemplateValues );
            
            $api = self::filterApi( $api );
        
            if( empty( $api ) || ! class_exists( $api ) )
            {
                $this->setViewContent( 'Invalid payment information', true );
                return false;
            }
        
            $this->setViewContent( $api::viewInLine( $checkoutInfo ), true );
            
        }
		catch( Exception $e )
		{
			$this->getForm()->setBadnews( $e->getMessage() ); 
			$this->setViewContent( $this->getForm()->view(), true );
		}
    }

    // END OF CLASS
}
