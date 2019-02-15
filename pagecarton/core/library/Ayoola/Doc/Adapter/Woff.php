<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Woff
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Woff.php 1.18.12 8.49 ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract_Text
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract/Text.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Woff
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Adapter_Woff extends Ayoola_Doc_Adapter_Abstract_Text
{
	
    /**
     * Content type for this document
     *
     * @var string
     */
	protected $_contentType = 'application/font-woff';
	
	// END OF CLASS
}
