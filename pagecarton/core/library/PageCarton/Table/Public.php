<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Table_Public
 * @copyright  Copyright (c) 2011-2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Public.php 8.25.2017 12:14pm ayoola $
 */

/**
 * @see PageCarton_Table
 */


abstract class PageCarton_Table_Public extends Ayoola_Dbase_Table_Abstract_Xml_Public
{

    /**
     * The Accessibility of the Table
     *
     * @param string
     */
    protected $_accessibility = SELF::SCOPE_PUBLIC;    

	// END OF CLASS
}
