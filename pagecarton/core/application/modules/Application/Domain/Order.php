<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Domain_Order
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Order.php Saturday 25th of August 2018 07:41AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_Domain_Order extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.10';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'domain_name' => 'INPUTTEXT, UNIQUE',
  'api' => 'INPUTTEXT',
  'username' => 'INPUTTEXT',
  'user_id' => 'INPUTTEXT',
  'email' => 'INPUTTEXT',
  'street_address' => 'INPUTTEXT',
  'street_address2' => 'INPUTTEXT',   
  'city' => 'INPUTTEXT',
  'province' => 'INPUTTEXT',
  'country' => 'INPUTTEXT',
  'zip' => 'INPUTTEXT',
  'active' => 'INT',
  'order_date' => 'INPUTTEXT',
  'expiry_date' => 'INPUTTEXT',
);


	// END OF CLASS
}
