<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Gender
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Gender.php Monday 27th of August 2018 06:48AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_Gender extends PageCarton_Table
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
  'gender' => 'INPUTTEXT',
);


	// END OF CLASS
}
