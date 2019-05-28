<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Object_PageWidget
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PageWidget.php Wednesday 22nd of May 2019 09:36AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Ayoola_Object_PageWidget extends PageCarton_Table
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
  'class_name' => 'INPUTTEXT',
  'url' => 'INPUTTEXT',
  'parameters' => 'JSON',
  'parameters_key' => 'INPUTTEXT',
  'widget_name' => 'INPUTTEXT',
  'history' => 'JSON',
);


	// END OF CLASS
}
