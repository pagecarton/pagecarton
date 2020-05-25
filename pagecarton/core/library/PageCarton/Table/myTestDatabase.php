<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    myTestDatabase
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: myTestDatabase.php Monday 25th of May 2020 10:34AM Oladitisodiq@gmail.com $
 */

/**
 * @see PageCarton_Table
 */


class myTestDatabase extends PageCarton_Table
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
  'id' => 'INT',
  'name' => 'INPUTTEXT',
  'regdate' => 'INPUTTEXT',
);


	// END OF CLASS
}
