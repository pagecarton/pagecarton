<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_MultiSite_Table
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Wednesday 20th of December 2017 03:22PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_MultiSite_Table extends PageCarton_Table_Public
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.5';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'directory' => 'INPUTTEXT',
  'parent_dir' => 'INPUTTEXT',
  'creation_time' => 'INPUTTEXT',
);


	// END OF CLASS
}
