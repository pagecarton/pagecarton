<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_SearchBox_Table
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Thursday 25th of October 2018 10:31PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_SearchBox_Table extends PageCarton_Table
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
  'username' => 'INPUTTEXT',
  'user_id' => 'INPUTTEXT',
  'query' => 'INPUTTEXT',
  'keywords' => 'JSON',
);


	// END OF CLASS
}
