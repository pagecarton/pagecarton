<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_MultiOptions
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: MultiOptions.php Friday 18th of May 2018 05:03PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Ayoola_Form_MultiOptions extends PageCarton_Table
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
  'multioptions_title' => 'INPUTTEXT',
  'multioptions_name' => 'INPUTTEXT',
  'db_table_class' => 'INPUTTEXT',
  'accessibility' => 'INPUTTEXT',
  'db_where' => 'JSON',
  'db_where_value' => 'JSON',
  'values_field' => 'INPUTTEXT',
  'label_field' => 'INPUTTEXT',
);


	// END OF CLASS
}
