<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Order_Status
 * @copyright  Copyright (c) 2021 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Status.php Wednesday 12th of May 2021 03:18PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_Subscription_Checkout_Order_Status extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.0';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'code' => 'INPUTTEXT',
  'title' => 'INPUTTEXT',
  'message' => 'INPUTTEXT',
);


	// END OF CLASS
}
