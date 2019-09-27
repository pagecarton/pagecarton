<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Notification
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Notification.php Friday 27th of September 2019 09:57AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_Notification extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.1';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'username' => 'INPUTTEXT',
  'from' => 'INPUTTEXT',
  'body' => 'INPUTTEXT',
  'subject' => 'INPUTTEXT',
  'to' => 'JSON',
  'cc' => 'JSON',
  'bcc' => 'JSON',
);


	// END OF CLASS
}
