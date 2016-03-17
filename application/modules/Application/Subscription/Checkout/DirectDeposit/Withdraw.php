<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_DirectDeposit_Withdraw
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Withdrawal.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Checkout_DirectDeposit_Abstract
 */
 
require_once 'Application/Subscription/Checkout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_DirectDeposit_Withdraw
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_DirectDeposit_Withdraw extends Application_Subscription_Checkout_DirectDeposit_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {

    } 
	
    /**
     * Returns the Fieldsets needed for creating a form
     * 
     * return array Fieldsets
     */
	public static function getFieldsets()
    {
		//	Form to create a new page
		$fieldsets = array();
		$fieldset = new Ayoola_Form_Element;
		
		//	Do it one after the other.
		do
		{
			//	Bank Accounts
			$table = new Application_User_UserBankAccount_List();
			$accounts = $table->getDbData();
			$storage = new Ayoola_Storage();
			$storage->storageNamespace = self::$withdrawalNamespace;
			$withdrawalInfo = $storage->retrieve();
		//	var_export( $accounts );
		//	var_export( $withdrawalInfo );
		
			//	Withdrawal is only allowed on local currency and verified bank accounts
			foreach( $accounts as $key => $account )
			{
				$accounts[$key]['bank'] = $accounts[$key]['bank'] . ' [' . $accounts[$key]['account_number'] . '] '; 
				if( $account['currency_id'] != $withdrawalInfo['currency_id'] )
				{
					unset( $accounts[$key] );
				}
				elseif( $account['verified'] == 'no' )
				{
				//	unset( $accounts[$key] );
				}
			}
		//	if( ! $accounts ){ throw new Application_Subscription_Checkout_DirectDeposit_Exception( "YOU DO NOT HAVE ANY VERIFIED BANK ACCOUNTS FOR THIS CURRENCY TYPE ON FILE " ); }
			$options = $accounts;
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'userbankaccount_id', 'bank');
			$options = $filter->filter( $options );
			$fieldset->addElement( array( 'name' => 'userbankaccount_id', 'label' => 'Bank Account', 'required' => 'required', 'description' => 'Choose a bank account account to use for withdrawal. <a rel="spotlight;height=300px;width=600px;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserBankAccount_List/">Manage Bank Accounts</a>', 'type' => 'Select', 'value' => @$values['account_id'] ), $options );
			$fieldset->addRequirement( 'userbankaccount_id', array( 'InArray' => array_keys( $options ) ) );

			if( ! @$_REQUEST['account_id'] )
			{	
				break;
			}
			
			
		//	$fieldset->addRequirements( array( 'NotEmpty' => null  ) );
		}
		while( false );
				
		$fieldset->addFilters( array( 'trim' => null, 'Escape' => null ) );
		$fieldset->addLegend( 'Bank Information' );
		$fieldsets[] = $fieldset;	//	Add the fieldset to the list of fieldsets
		return $fieldsets;
	} 
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {

    } 
	// END OF CLASS
}
