<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Locale
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Locale.php Sunday 5th of August 2018 01:59PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_Locale extends PageCarton_Table
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
  'locale_name' => 'INPUTTEXT',
  'native_name' => 'INPUTTEXT',
  'locale_code' => 'INPUTTEXT',
);


	// END OF CLASS
}
