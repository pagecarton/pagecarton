<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
    protected $_tableVersion = '0.1';  

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
  'cron_next_run_time' => 'INT',
  'cron_run_time_history' => 'JSON',
);


	// END OF CLASS
}
