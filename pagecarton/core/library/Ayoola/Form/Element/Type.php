<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_Element_Type
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Type.php Friday 7th of August 2020 01:59PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Ayoola_Form_Element_Type extends PageCarton_Table
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
  'type_name' => 'INPUTTEXT',
  'type_widget' => 'INPUTTEXT',
);


	// END OF CLASS
}
