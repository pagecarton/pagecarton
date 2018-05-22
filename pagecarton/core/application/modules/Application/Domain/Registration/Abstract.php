<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Domain_Registration_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Exception 
 */
 
require_once 'Application/Domain/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Registration_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Domain_Registration_Abstract extends Application_Domain_Abstract
{
	
    /**
     * 
     * @param array
     * 
     */
	protected static $_domainPrice = array();
	
    /**
     * Retrieve the price information of a domain TLD
     * 
     * @param string TLD
     * @param string price
     */
	public static function getTldPrice( $tld )
    {
/* 		//	Filter the price to display unit in domain price
		$filter = 'Ayoola_Filter_Currency';
		$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
		$filter = new $filter();
	//	$value['price'] = $filter->filter( $value['price'] );
 */
		$table = Application_Domain_Registration_Price::getInstance();
		if( empty( self::$_domainPrice[$tld] ) )
		{
		//	var_export( $tld );
			if( ! $value = $table->selectOne( null, array( 'extension' => $tld ) ) )
			{
				//	Default Price
				if( empty( self::$_domainPrice['default'] ) )
				{
					//	Default Price
				//	self::$_domainPrice['default'] = $table->selectOne( null, array( 'extension' => '*' ) );		
					$domainSettings = Application_Settings_Abstract::getSettings( 'Domains' ) ? : array();
					$domainSettings['price'] = @$domainSettings['domain_name_default_price'];  
					self::$_domainPrice['default'] = $domainSettings;		
				//	var_export( self::$_domainPrice );
			//		var_export( $table->select() );
				}
				$value = self::$_domainPrice['default'];		
			}
			self::$_domainPrice[$tld] = $value;
		}
	//	$value['price'] = $filter->filter( $value['price'] );
	//	var_export( self::$_domainPrice[$tld] );
		return @self::$_domainPrice[$tld]['price'];
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
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'domain_name', 'label' => 'Domain Name', 'type' => 'InputText', 'value' => @$values['domain_name'] ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirement( 'domain_name', array( 'WordCount' => array( 2, 100 ) ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
