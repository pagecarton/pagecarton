<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Cron_Run_Table
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Wednesday 20th of June 2018 03:16AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_Cron_Run_Table extends PageCarton_Table_Private 
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
  'cron_id' => 'INPUTTEXT',
  'runtime' => 'INT',
);


	// END OF CLASS
}
