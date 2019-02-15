<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_UserBankAccount_Exception 
 */
 
require_once 'Application/User/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_UserBankAccount_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'userbankaccount_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_User_UserBankAccount';
	
    /**
     * Sets _dbData
     * 
     */
	public function setDbData()
    {
		$table = $this->getDbTable();
		$this->_dbData = (array) $table->fetchSQLQuery( 'SELECT * FROM `userbankaccount`, `bank`, `currency`, `country`, `user` WHERE userbankaccount.currency_id = currency.currency_id AND userbankaccount.bank_id = bank.bank_id AND bank.country_id = country.country_id AND userbankaccount.user_id = user.user_id AND userbankaccount.user_id = "' . Ayoola_Application::getUserInfo( 'user_id' ) . '"' );	
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
		$this->_identifierData = (array) array_pop( $table->fetchSQLQuery( 'SELECT * FROM `userbankaccount`, `bank`, `currency`, `country`, `user` WHERE userbankaccount.currency_id = currency.currency_id AND userbankaccount.bank_id = bank.bank_id AND bank.country_id = country.country_id AND userbankaccount.user_id = user.user_id AND userbankaccount.user_id = "' . Ayoola_Application::getUserInfo( 'user_id' ) . '" AND userbankaccount.userbankaccount_id = "' . $identifier['userbankaccount_id'] . '"', 1 ) );
	//	$this->_identifierData = (array) $table->selectOne( null, $identifier );
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
		require_once 'Ayoola/Form.php';
        $form = new Ayoola_Form( 'name=>' . $this->getObjectName() );
	//	$form->setSubmitButton( $submitValue );
		$form->submitValue = $submitValue ;
		//$form->setCaptcha( true ); // Adds captcha
		require_once 'Ayoola/Form/Element.php';
		$fieldset = new Ayoola_Form_Element;
			
		//	Bank Country
		$list = new Application_Country();
		$list = $list->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'country_id', 'country');
		$list = $filter->filter( $list );
		ksort( $list );
		$fieldset->addElement( array( 'name' => 'country_id', 'label' => 'Country', 'description' => 'Choose Bank Country', 'type' => 'Select', 'value' => @$values['country_id'] ), array( 0 => 'Select Country' ) + $list );
		$fieldset->addRequirement( 'country_id', array( 'InArray' => array_keys( $list )  ) );
	//		var_export( $list );
			
		//	user id
		$fieldset->addElement( array( 'name' => 'user_id', 'type' => 'Hidden', 'value' => 'xx' ) );
		$fieldset->addFilter( 'user_id', array( 'DefiniteValue' => Ayoola_Application::getUserInfo( 'user_id' ), 'Digits' => null, 'Escape' => null ) );
		
		//	Only bring this out if country has been selected
		if( @$_REQUEST['country_id'] || $values )
		{
			//	Bank Name
			$list = new Application_Bank();
			@$country_id = $_REQUEST['country_id'] ? : $values['country_id'];
			$list = $list->select( null, null, array( 'country_id' => $country_id ) );
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'bank_id', 'bank');
			$list = $filter->filter( $list );
			ksort( $list );
			$fieldset->addElement( array( 'name' => 'bank_id', 'label' => 'Bank Name', 'description' => 'Bank Name', 'type' => 'Select', 'value' => @$values['bank_id'] ), array( 0 => 'Select Bank' ) + $list );
			$fieldset->addRequirement( 'bank_id', array( 'InArray' => array_keys( $list )  ) );
			
			//	Account Type
			$list = new Application_BankAccountType();
			$list = $list->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'bankaccounttype_id', 'bankaccounttype');
			$list = $filter->filter( $list );
			ksort( $list );
			$fieldset->addElement( array( 'name' => 'bankaccounttype_id', 'label' => 'Account Type', 'description' => 'Select Account type', 'type' => 'Select', 'value' => @$values['bankaccounttype_id'] ), array( 0 => 'Select Account Type' ) + $list );
			$fieldset->addRequirement( 'bankaccounttype_id', array( 'InArray' => array_keys( $list )  ) );
			
			//	Account Currency
			$list = new Application_Currency();
			$list = $list->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'currency_id', 'currency' );
			$list = $filter->filter( $list );
			ksort( $list );
			$fieldset->addElement( array( 'name' => 'currency_id', 'label' => 'Currency', 'description' => 'Currency of bank account', 'type' => 'Select', 'value' => @$values['currency_id'] ), array( 0 => 'Select Account Currency' ) + $list );
			$fieldset->addRequirement( 'currency_id', array( 'InArray' => array_keys( $list )  ) );
			
			//	Name
			$fieldset->addElement( array( 'name' => 'account_name', 'description' => 'Account Name', 'type' => 'InputText', 'value' => @$values['account_name'] ) );
			$fieldset->addRequirement( 'account_name', array( 'WordCount' => array( 6, 50 )  ) );
			
			//	Number
			$fieldset->addElement( array( 'name' => 'account_number', 'description' => 'Account Number', 'type' => 'InputText', 'value' => @$values['account_number'] ) );			
			$fieldset->addRequirement( 'account_number', array( 'Digits' => null, 'WordCount' => array( 6, 50 ) ) );
			
			//	routing
			$fieldset->addElement( array( 'name' => 'routing_number', 'description' => 'Routing Number (If Required)', 'type' => 'InputText', 'value' => @$values['routing_number'] ) );
			$fieldset->addFilter( 'routing_number', array( 'Digits' => null, 'Escape' => null ) );
			
			//	routing
			$fieldset->addElement( array( 'name' => 'sort_code', 'description' => 'Sort Code (If Required)', 'type' => 'InputText', 'value' => @$values['sort_code'] ) );
			$fieldset->addFilter( 'sort_code', array( 'Digits' => null, 'Escape' => null ) );
		
		}
		$fieldset->addFilters( 'Trim::Escape' );
		$fieldset->addLegend( "$legend" );
		$form->addFieldset( $fieldset );
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
