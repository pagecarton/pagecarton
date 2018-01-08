<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Profile_Table
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Wednesday 27th of December 2017 10:46AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_Profile_Table extends PageCarton_Table
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
  'profile_url' => 'INPUTTEXT',
  'access_level' => 'INT',
  'profile_data' => 'JSON',
  'creation_time' => 'INT',
  'modified_time' => 'JSON',
  'creation_ip' => 'INPUTTEXT',
  'modified_ip' => 'JSON',
);


	// END OF CLASS
}
