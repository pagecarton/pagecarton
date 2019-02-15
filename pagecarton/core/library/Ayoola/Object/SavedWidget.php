<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Object_SavedWidget
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SavedWidget.php Wednesday 26th of December 2018 05:09PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Ayoola_Object_SavedWidget extends PageCarton_Table
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
  'class_name' => 'INPUTTEXT',
  'parameters' => 'JSON',
  'widget_name' => 'INPUTTEXT',
);


	// END OF CLASS
}
