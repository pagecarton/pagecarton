<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Jar
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Jar.php 1.18.12 8.49 ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract_Image
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract/Image.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Jar
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Adapter_Jar extends Ayoola_Doc_Adapter_Abstract_Octet
{
	
    /**
     * Content type for this document
     *
     * @var string
     */
	protected $_contentType = 'application/x-java-archive';
	
	// END OF CLASS
}
