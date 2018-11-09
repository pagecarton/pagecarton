<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_CheckoutOption
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CheckoutOption.php 4.19.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_CheckoutOption
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * 
 */


/**
 *
 *	Children need to inherit. Because we can't install plugin twice
 *
 * 
 */

class Application_Subscription_Checkout_CheckoutOption extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.03';

	protected $_dataTypes = array
	( 
		'checkoutoption_name' => 'INPUTTEXT, UNIQUE',
		'checkoutoption_logo' => 'TEXTAREA',
		'object_name' => 'INPUTTEXT',
		'checkout_type' => 'INPUTTEXT',  
		'default_parameter_fields' => 'JSON',  
		'custom_parameter_fields' => 'JSON',  
		'form_attribute_name' => 'JSON',  
		'form_attribute_value' => 'JSON',  
		'default_form_field_name' => 'JSON',  
		'default_form_field_value' => 'JSON',  
		'enabled' => 'INT',
	);
	// END OF CLASS
}
