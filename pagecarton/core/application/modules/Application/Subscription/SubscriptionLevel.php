<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_SubscriptionLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SubscriptionLevel.php 4.19.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_SubscriptionLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_SubscriptionLevel extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.07';

	protected $_dataTypes = array
	( 
		'subscriptionlevel_id' => 'INPUTTEXT, RELATIVES = Application_Subscription_Price',
		'subscriptionlevel_name' => 'INPUTTEXT',
		'subscriptionlevel_description' => 'TEXTAREA',
		'subscription_id' => 'INPUTTEXT, FOREIGN_KEYS = Application_Subscription_Subscription',
		'document_url' => 'INPUTTEXT',
		'enabled' => 'INT'
	);
	// END OF CLASS
}
