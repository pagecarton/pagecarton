<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Subscription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Subscription.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Subscription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Subscription extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.10';

	protected $_dataTypes = array
	( 
		'subscription_id' => 'INPUTTEXT, RELATIVES = Application_Subscription_SubscriptionLevel',
		'subscription_name' => 'INPUTTEXT,UNIQUE',
		'subscription_label' => 'INPUTTEXT',
		'subscription_object_name' => 'INPUTTEXT',
		'subscription_requirements' => 'JSON',
		'checkout_requirements' => 'JSON',
		'subscription_description' => 'TEXTAREA',
		'document_url' => 'INPUTTEXT',
		'auth_level' => 'INT, FOREIGN_KEYS = Ayoola_Access_AuthLevel',
		'enabled' => 'INT', 
		'creation_date' => 'INT', 'modified_date' => 'INT',
	);
	// END OF CLASS
}
