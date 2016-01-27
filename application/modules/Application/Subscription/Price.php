<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Price
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Price.php 4.19.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Price
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Price extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.06';

	protected $_dataTypes = array
	( 
		'subscriptionlevel_id' => 'INPUTTEXT, FOREIGN_KEYS = Application_Subscription_SubscriptionLevel',
		'cycle_id' => 'INPUTTEXT, FOREIGN_KEYS = Application_Subscription_Cycle',
		'min_quantity' => 'INT',
		'max_quantity' => 'INT',
		'allowed_multiples' => 'INT',
		'price' => 'FLOAT',
	);
	// END OF CLASS
}
