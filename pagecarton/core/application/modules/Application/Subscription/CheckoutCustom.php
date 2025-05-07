<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_CheckoutCustom
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Checkout.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Checkout
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_CheckoutCustom
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_CheckoutCustom extends Application_Subscription_Checkout
{

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

            $values = array();
            if( ! empty( $_POST ) && ! empty( $_POST['checkoutoption_name'] ) )
            {
                var_export( $_POST );
                $values = $_POST;
                foreach( $values as $key => $each )
                {
                    $values[$key] = strip_tags( $values[$key] );
                }
                $cart['checkout_info'] = $values;
                self::getStorage()->store( $cart );  
            }

            $this->_objectTemplateValues = array_merge( $this->_objectTemplateValues, $this->_objectTemplateValues );

            if( $user = Ayoola_Application::getUserInfo( 'user_id' ) )
            {
                if( $lastOrder = Application_Subscription_Checkout_Order::getInstance()->selectOne( null, array( 'user_id' => $user ) ) )
                {
                    if( isset( $lastOrder['order']['checkout_info'] ) && is_array( $lastOrder['order']['checkout_info'] ) )
                    {
                        $values = $lastOrder['order']['checkout_info'];
                    }
                }
            }
            $this->_objectTemplateValues = array_merge( $values, $this->_objectTemplateValues );

            $methods = array();
            foreach( Application_Subscription_Checkout_CheckoutOption::getInstance()->select() as $each )
            {
                $methods[] = $each;
            }
            $this->_objectTemplateValues['payment_methods'] = $methods;

            self::v( $this->_objectTemplateValues );

            if( ! $nextUrl = $this->getParameter( 'next_url' ) )
            {
                $nextUrl = Ayoola_Page::getHomePageUrl() . '/widgets/' . __CLASS__ . 'Payment';
            }
            header( $this->getParameter( 'next_url' ) );


        }
		catch( Exception $e )
		{
			$this->getForm()->setBadnews( $e->getMessage() ); 
			$this->setViewContent( $this->getForm()->view(), true );
		}
    }

    // END OF CLASS
}
