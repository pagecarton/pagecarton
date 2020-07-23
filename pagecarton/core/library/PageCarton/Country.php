<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Country
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Country.php Monday 12th of August 2019 10:13PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_Country extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.2';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'country_name' => 'INPUTTEXT',
  'country_code' => 'INPUTTEXT',
  'dial_code' => 'INPUTTEXT',
);


	// END OF CLASS
}
