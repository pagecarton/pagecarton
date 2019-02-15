<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Js
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Js.php 1.18.12 8.49 ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract_Text
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract/Text.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Js
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Adapter_Js extends Ayoola_Doc_Adapter_Abstract_Text
{

    /**
     * Default Content Type Description
     *
     * @var string
     */
	protected $_defaultContentTypeDesc = 'javascript';
	
		
    /**
     * The Default Content Type to be used for the Documents
     *
     * @var string
     */
	protected $_defaultContentType = 'application/';
	// END OF CLASS
}
