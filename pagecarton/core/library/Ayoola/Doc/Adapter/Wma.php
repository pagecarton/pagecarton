<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Wma
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Wma.php 5.15.12 8.49 ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract_Octet
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract/Octet.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Wma
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Adapter_Wma extends Ayoola_Doc_Adapter_Abstract
{
	
    /**
     * Content type for this document
     *
     * @var string
     */
	protected $_contentType = 'audio/x-ms-wma';
	
	// END OF CLASS
}
