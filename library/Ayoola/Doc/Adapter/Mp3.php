<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Mp3
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Mp3.php 5.15.12 8.49 ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract_Octet
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract/Octet.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Mp3
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Adapter_Mp3 extends Ayoola_Doc_Adapter_Abstract
{
	
    /**
     * Content type for this document
     *
     * @var string
     */
	protected $_contentType = 'audio/mpeg';
	
	// END OF CLASS
}
