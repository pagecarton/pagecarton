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
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0, 98 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		if( ! self::hasPriviledge( 98 ) )
		{
			$this->_dbWhereClause['username'] = strtolower( Ayoola_Application::getUserInfo( 'username' ) );
		}
		try{ $this->setIdentifier(); }
		catch( Application_Subscription_Checkout_Order_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }

		#
		$this->setViewContent( '<h2>Order number '  . $identifierData['order_id'] . '</h2>', true );


		$this->setViewContent( '<h3>Details</h3>' );
//		var_export( $identifierData );   
//		var_export( $identifierData['order']['checkout_info'] );
		$class = new Application_Subscription_Cart( array( 'cart' => $identifierData['order'] ) );
		$this->setViewContent( $class->view() );
		$data = Application_Subscription_Checkout_CheckoutOption::getInstance()->selectOne( null, array( 'checkoutoption_name' => $identifierData['order_api'] ) );
		$this->setViewContent( '<h3>Payment Method</h3>' );
		$this->setViewContent( '<div>'  . $data['checkoutoption_name'] . '<br> '  . $data['checkoutoption_logo'] . '</div>' );
		$this->setViewContent( '<h3>Order  Status</h3>' );
		$this->setViewContent( '<p>'  . self::$checkoutStages[$identifierData['order_status']] . '</p>' );
		$this->setViewContent( '<h3>Customer Information</h3>' ); 
	//	var_export( $identifierData );
		$orderForm = Application_Settings_CompanyInfo::getSettings( 'Payments', 'order_form' );
	//	var_export( $identifierData['order'] );
		if( ! $orderForm )
		{
			if( $firstProduct = array_pop( $identifierData['order']['cart'] ) )
			{
			//	var_export( $firstProduct );
				if( $firstProduct['checkout_form'] )
				{
					$orderForm = $firstProduct['checkout_form'];
				}
			}
		}
		$this->setViewContent( self::arrayToString( $identifierData['order']['checkout_info'] ) );
		$formViewer = new Ayoola_Form_View( array( 'form_name' => $orderForm, 'form_data' => $identifierData['order']['checkout_info'] ) );
		$formX = '<a href="javascript:" onclick="this.nextSibling.style.display=\'block\';this.nextSibling.elements[0].focus();this.nextSibling.scrollIntoView();this.style.display=\'none\';">Show Form Data</a><form style="display:none;" class="pc-form">';
		if( is_object( $formViewer->getForm() ) )  
		{
			foreach( $formViewer->getForm()->getFieldsets() as $fieldset )
			{
				$formX .= $fieldset->view();   
			//	$this->setViewContent(  );
			}
		}
		$formX .= '</form>';
		$formX = str_ireplace( Ayoola_Form::getPlaceholders(), '', $formX );
		$this->setViewContent( $formX );
//		$this->setViewContent( $formViewer->view() );

//		var_export( $data );


//		if( $this->updateDb() ){ $this->setViewContent( 'Order edited successfully', true ); }
    } 
	// END OF CLASS
}
