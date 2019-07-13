<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Wallet_Fund
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Fund.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Wallet_Abstract
 */
 
require_once 'Application/Wallet/Abstract.php'; 


/**
 * @category   PageCarton
 * @package    Application_Wallet_Fund
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Wallet_Fund extends Application_Wallet_Abstract 
{
		
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1, 98 );

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			$this->createForm( 'Continue', 'Add funds to my wallet' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			$class = new Application_Subscription();
		//	$confirmation = $class::getConfirmation();
			$data['subscription_name'] = 'Add funds to wallet';
			$data['subscription_label'] = 'Add ' . ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$' ) . $values['amount'] . ' to your wallet.';
			$data['price'] = $values['amount'];
			$data['cycle_name'] = 'each';
			$data['cycle_label'] = '';
			$data['price_id'] = $data['subscription_name'];
			$data['username'] = Ayoola_Application::getUserInfo( 'username' );
			$data['classplayer_link'] = '/tools/classplayer/get/object_name/' . __CLASS__ . '/';
			$data['url'] = $this->getParameter( 'return_url' ) ? : $data['classplayer_link'];
			$data['checkout_requirements'] = $this->getParameter( 'checkout_requirements' ); //"billing_address";
			//	''
			//	After we checkout this is where we want to come to
			$data['return_url'] = $data['url'];
			$data['callback'] = __CLASS__;
			$data['classplayer_link'] = $data['url'];
			$data['object_id'] = $data['subscription_name'];
			$data['multiple'] = 1;
			$class->subscribe( $data );
		//	var_export( $data );
			$this->setViewContent( $class::getConfirmation(), true );
		}
		catch( Exception $e )
		{ 
		
			$this->setViewContent( '<p class="badnews boxednews">' . $e->getMessage() . '</p>', true ); 
			$this->setViewContent( self::__( '<p class="badnews boxednews">Error with Wallet package</p>' ) ); 
		}
	//	var_export( $this->getDbData() );
    } 
	
    /**
     * Performs funds transfer when user payment is completed
     * 
     * param array Order information
     */
	public static function callback( $orderInfo )
    {
	//	var_export( $orderInfo );
		switch( $orderInfo['order_status'] )
		{
			case 'Payment Successful':
				$transferInfo = array();
				$transferInfo['allow_ghost_sender'] = true;
				$transferInfo['to'] = $orderInfo['username'];
				$transferInfo['from'] = null;
				$transferInfo['amount'] = $orderInfo['price'] * ( $orderInfo['multiple'] ? : 1 );
			//	$transferInfo['notes'] = '';
				Application_Wallet::transfer( $transferInfo );
			break;
		}
	}
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
		$form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
  //	$form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
	//	$form->submitValue = $this->getParameter( 'button_value' ) ? : 'Add funds' ;
		$fieldset = new Ayoola_Form_Element;
		$html = null;
	//	$html .= '<p><strong>Update wallet balance for ' . ( $values['username'] ? : $this->getIdentifier( 'username' ) ) . '</strong></p>';
		$html .= '' . ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$ ' ) . '';
		$html .= '';
		$fieldset->addElement( array( 'name' => 'html', 'label' => '', 'placeholder' => $html, 'style' => 'min-width:30px;max-width:50px;display:inline;', ' disabled' => 'disabled', 'type' => 'InputText', 'value' => $html ), array( 'html' => $html ) );
		$fieldset->addElement( array( 'name' => 'amount', 'label' => '', 'style' => 'min-width:20px;max-width:90px;display:inline;', 'placeholder' => '0.00', 'description' => '', 'type' => 'InputText', 'value' => @$values['amount'] ? : $this->getParameter( 'amount' ) ) );
		$fieldset->addElement( array( 'name' => 'submit', 'style' => 'min-width:20px;max-width:120px;', 'type' => 'Submit', 'value' => $this->getParameter( 'button_value' ) ? : 'Add funds' ) );
		$fieldset->addRequirement( 'amount', array( 'MinMax' => array( 2, 1000000 ), 'NotEmpty' => array( 'blacklist' => array( 0, 0.00, '0', '0.00' ) )  ) );
		$fieldset->addFilter( 'amount', array( 'float' => null ) ); 
		$fieldset->addFilters( array( 'trim' => null ) );
	//	$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form ); 
    } 
	// END OF CLASS
}
