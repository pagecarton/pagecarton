<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Price_Exception 
 */
 
require_once 'Application/Domain/Registration/Price/Exception.php';


/**
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Domain_Registration_Price_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = 99;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'extension',  );
	
    /**
     * 
     * @var string
     */
	protected $_idColumn = 'extension';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Domain_Registration_Price';
	
	
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
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;

		//	extension
		if( ! $values )
		{
			$fieldset->addElement( array( 'name' => 'extension', 'label' => 'Extension', 'style' => 'display:inline;margin-left:0;', 'placeholder' => 'e.g. .com', 'type' => 'InputText', 'value' => @$values['extension'] ) );
		}
		
		//	Currency
		$currency = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => " $currency " ) );

		//	Price
		$fieldset->addElement( array( 'name' => 'price', 'label' => 'Domain price (per yr.)', 'style' => ' min-width:50px; max-width:50px;display:inline;margin-left:0;', 'placeholder' => '0.00', 'type' => 'InputText', 'value' => @$values['price'] ) );	
		
		$fieldset->addRequirement( 'price', array( 'WordCount' => array( 1, 10 )  ) );
		$fieldset->addFilter( 'price', array( 'float' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
//		$form->setParameter( array( 'prices' => 'email-address, phone-number' ) );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
