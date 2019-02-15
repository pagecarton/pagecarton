<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Hook
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Hook.php Monday 14th of May 2018 01:10AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_Hook extends PageCarton_Table
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
  'class_name' => 'INPUTTEXT',
  'hook_class_name' => 'INPUTTEXT',
);


	// END OF CLASS
}
