<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_Card_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_UserBankAccount_Card_Exception 
 */
 
require_once 'Application/User/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_Card_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_UserBankAccount_Card_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'usercreditcard_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_User_UserCreditCard';
	
    /**
     * Sets _dbData
     * 
     */
	public function setDbData()
    {
		$table = $this->getDbTable();
		$this->_dbData = (array) $table->fetchSQLQuery( 'SELECT * FROM `usercreditcard`, `creditcardtype`, `user` WHERE usercreditcard.creditcardtype_id = creditcardtype.creditcardtype_id AND usercreditcard.user_id = user.user_id AND usercreditcard.user_id = "' . Ayoola_Application::getUserInfo( 'user_id' ) . '"' );	
	//	var_export();
    } 
	
    /**
     * Sets _identifierData
     * 
     */
	public function setIdentifierData( $identifier = null )
    {
		if( is_null( $identifier ) ){ $identifier = $this->getIdentifier(); }
		$table = $this->getDbTable();
		$this->_identifierData = (array) array_pop( $table->fetchSQLQuery( 'SELECT * FROM `usercreditcard`, `creditcardtype`, `user` WHERE usercreditcard.creditcardtype_id = creditcardtype.creditcardtype_id AND usercreditcard.user_id = user.user_id AND usercreditcard.user_id = "' . Ayoola_Application::getUserInfo( 'user_id' ) . '" AND usercreditcard.usercreditcard_id = "' . $identifier['usercreditcard_id'] . '"', 1 ) );
	//	$this->_identifierData = (array) $table->selectOne( null, $identifier );
    } 
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		require_once 'Ayoola/Form.php';
        $form = new Ayoola_Form( array( 'name' => 'UserBankAccountCreator' ) );
	//	$form->setSubmitButton( $submitValue );
		$form->submitValue = $submitValue ;
		//$form->setCaptcha( true ); // Adds captcha
		require_once 'Ayoola/Form/Element.php';
		$fieldset = new Ayoola_Form_Element;
//		$fieldset->hashElementName = false;
			
			
		//	user id
		$fieldset->addElement( array( 'name' => 'user_id', 'type' => 'Hidden', 'value' => 'xx' ) );
		$fieldset->addFilter( 'user_id', array( 'DefiniteValue' => Ayoola_Application::getUserInfo( 'user_id' ), 'Digits' => null, 'Escape' => null ) );

		if( is_null( $values ) )
		{ 
				
			//	Card Number
			$fieldset->addElement( array( 'name' => 'card_number', 'label' => 'Card Number', 'description' => '', 'type' => 'InputText', 'value' => @$values['card_number'] ) );
		//	$fieldset->addRequirement( 'card_number', array( 'WordCount' => array( 12, 18 )  ) );
			$fieldset->addRequirement( 'card_number', array( 'WordCount' => array( 12, 18 ), 'DuplicateRecord' => array('Application_User_UserCreditCard', 'card_number' ) ) );

			//	Card Type
			$list = new Application_CreditCardType();
			$list = $list->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'creditcardtype_id', 'creditcardtype' );
			$list = $filter->filter( $list );
			ksort( $list );
			$fieldset->addElement( array( 'name' => 'creditcardtype_id', 'label' => 'Card Type', 'description' => 'Choose Card Type', 'type' => 'Select', 'value' => @$values['creditcardtype_id'] ), array( 0 => 'Please Select' ) + $list );
			$fieldset->addRequirement( 'creditcardtype_id', array( 'InArray' => array_keys( $list )  ) );
		//		var_export( $list );
		}
			
		//	Expiry Month
		$list = range( 1, 12 );
		$list = array_combine( $list, $list );
		$fieldset->addElement( array( 'name' => 'expiry_month', 'label' => 'Expiry Month', 'description' => 'Choose Card Type', 'type' => 'Select', 'value' => @$values['expiry_month'] ), array( 0 => 'Please Select' ) + $list );
		$fieldset->addRequirement( 'expiry_month', array( 'InArray' => array_keys( $list )  ) );
			
		//	Expiry Year
		$year = date( "Y" );
		$list = range( $year, $year + 10 );
		$list = array_combine( $list, $list );
		$fieldset->addElement( array( 'name' => 'expiry_year', 'label' => 'Expiry Year', 'description' => 'Choose Card Type', 'type' => 'Select', 'value' => @$values['expiry_year'] ), array( 0 => 'Please Select' ) + $list );
		$fieldset->addRequirement( 'expiry_year', array( 'InArray' => array_keys( $list ) ) );
		
		
		$fieldset->addFilters( 'Trim::Escape' );
		$fieldset->addLegend( "$legend" );
		$form->addFieldset( $fieldset );
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
