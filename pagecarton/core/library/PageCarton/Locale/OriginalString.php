<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Locale_OriginalString
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: OriginalString.php Tuesday 7th of August 2018 01:21AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_Locale_OriginalString extends PageCarton_Table
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
  'string' => 'INPUTTEXT',
  'trimmed_string' => 'INPUTTEXT',
  'pages' => 'JSON',
);


	// END OF CLASS
}
