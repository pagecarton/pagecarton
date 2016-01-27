<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_DirectDeposit_Account
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Account.php 4.19.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_DirectDeposit_Account
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_DirectDeposit_Account extends Ayoola_Dbase_Table_Abstract_Xml_Private
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.02';

	protected $_dataTypes = array
	( 
		'account_info' => 'TEXTAREA',
		'account_name' => 'TEXTAREA, UNIQUE',
		'account_country_code' => 'INPUTTEXT',
		'account_currency' => 'INPUTTEXT',
		'account_enabled' => 'INPUTTEXT',
	);
	// END OF CLASS
}
