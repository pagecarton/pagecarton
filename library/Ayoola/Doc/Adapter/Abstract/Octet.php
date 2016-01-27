<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Abstract_Octet
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Octet.php 5.15.2012 5.13pm ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Abstract_Octet
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Doc_Adapter_Abstract_Octet extends Ayoola_Doc_Adapter_Abstract
{

    /**
     * The Default Content Type to be used for the Documents
     *
     * @var string
     */
	protected $_defaultContentType = 'application/octet-stream';
	
	// END OF CLASS
}
