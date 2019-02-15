<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Upgrade_Check_Table
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Friday 28th of December 2018 01:16AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_Upgrade_Check_Table extends PageCarton_Table
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
  'domain_name' => 'INPUTTEXT',
  'remote_version' => 'INPUTTEXT',
  'version' => 'INPUTTEXT',
);


	// END OF CLASS
}
