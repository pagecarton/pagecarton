<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Wallet   Ayoola
 * @package    Application_Wallet_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Wallet_Exception 
 */
 
require_once 'Application/Wallet/Exception.php';


/**
 * @Wallet   Ayoola
 * @package    Application_Wallet_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Wallet_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );	
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'username' );

    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Ayoola_Access_AccessInformation';
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = 'Update' ;
		$fieldset = new Ayoola_Form_Element;
		$html = null;
	//	$html .= '<p><strong>Update wallet balance for ' . ( $values['username'] ? : $this->getIdentifier( 'username' ) ) . '</strong></p>';
		$html .= '' . ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$ ' ) . '';
		$html .= '';
		$fieldset->addElement( array( 'name' => 'html', 'placeholder' => '0.00', 'description' => 'Enter balance for this user', 'type' => 'Html', 'value' => @$values['html'] ), array( 'html' => $html ) );
		$fieldset->addElement( array( 'name' => 'wallet_balance', 'label' => '', 'style' => 'min-width:20px;max-width:60px;', 'placeholder' => '0.00', 'description' => 'Enter balance for this user', 'type' => 'InputText', 'value' => @$values['access_information']['wallet_balance'] ) );
		$fieldset->addElement( array( 'name' => 'username', 'type' => 'Hidden', 'value' => @$values['username'] ) );
		$fieldset->addRequirement( 'wallet_balance', array( 'WordCount' => array( 1, 10 )  ) );
		$fieldset->addFilter( 'wallet_balance', array( 'float' => null, 'Currency' => null ) ); 
		$fieldset->addFilters( array( 'trim' => null ) );
	//	$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
