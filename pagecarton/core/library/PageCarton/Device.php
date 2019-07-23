<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Device
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Device.php Tuesday 23rd of July 2019 10:09PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_Device extends PageCarton_Table
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
  'device_name' => 'INPUTTEXT',
  'environment_key' => 'JSON',
  'environment_value' => 'JSON',
  'equator' => 'JSON',
);


	// END OF CLASS
}
