<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Fetch
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Fetch.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Select.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Fetch
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Fetch extends Ayoola_Dbase_Adapter_Xml_Table_Select
{
	
    /**
     * Switch to true to rearrange the  result array
     *
     * @var boolean
     */
    public $selectResultKeyReArrange = true;

	// END OF CLASS
}
