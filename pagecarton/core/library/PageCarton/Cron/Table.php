<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Cron_Table
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Thursday 10th of May 2018 05:17PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_Cron_Table extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.3';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'class_name' => 'INPUTTEXT',
  'cron_parameters' => 'JSON',
  'cron_interval' => 'INT',
);


	// END OF CLASS
}
