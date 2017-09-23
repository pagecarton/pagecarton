<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Table_Abstract_Xml_Public
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Public.php 4.9.12 11.52 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 * @see Ayoola_Dbase_Table_Abstract_Exception
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';
require_once 'Ayoola/Dbase/Table/Abstract/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Table_Abstract_Xml_Public
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Dbase_Table_Abstract_Xml_Public extends Ayoola_Dbase_Table_Abstract_Xml 
{

    /**
     * The Accessibility of the Table
     *
     * @param string
     */
    protected $_accessibility = SELF::SCOPE_PUBLIC;
	// END OF CLASS
}
