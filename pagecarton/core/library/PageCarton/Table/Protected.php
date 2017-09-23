<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Table_Protected
 * @copyright  Copyright (c) 2011-2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Protected.php 8.25.2017 12:14pm ayoola $
 */

/**
 * @see PageCarton_Table
 */


abstract class PageCarton_Table_Protected extends PageCarton_Table
{

    /**
     * The Accessibility of the Table
     *
     * @param string
     */
    protected $_accessibility = SELF::SCOPE_PROTECTED;    

	// END OF CLASS
}
