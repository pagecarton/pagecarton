<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Table
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Wednesday 6th of September 2017 05:39PM  $
 */

/**
 * @see PageCarton_Table
 */


class Ayoola_Doc_Table extends PageCarton_Table
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
  'url' => 'INPUTTEXT',
  'username' => 'INPUTTEXT',
  'access_level' => 'JSON',
  'upload_time' => 'INT',
);


	// END OF CLASS
}
