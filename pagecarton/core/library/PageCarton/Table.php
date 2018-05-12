<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Table
 * @copyright  Copyright (c) 2011-2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php 8.25.2017 12:14pm ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */


class PageCarton_Table extends PageCarton_Table_Protected
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
	protected $_dataTypes = array
	( 
		'sample_field_name' => 'INPUTTEXT, UNIQUE',
		'sample_field_to_store_array_and_other_data' => 'JSON',
		'another_sample_field' => 'INPUTTEXT',
	);


	// END OF CLASS
}
