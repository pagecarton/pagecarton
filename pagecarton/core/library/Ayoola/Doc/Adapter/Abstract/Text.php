<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Abstract_Text
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Text.php 1.21.2012 5.13pm ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Abstract_Text
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Doc_Adapter_Abstract_Text extends Ayoola_Doc_Adapter_Abstract
{
		
    /**
     * The Default Content Type to be used for the Documents
     *
     * @var string
     */
	protected $_defaultContentType = 'text/';
		
    /**
     * This method outputs the document inline with HTML
     *
     * @param void
     * @return mixed
     */
    public function viewInline()
    {
		$content = null;
		foreach( $this->getPaths() as $path )
		{
			// Default method is to include the document
			$content .= file_get_contents( $path );
		}
		return $content; 
    } 

	// END OF CLASS
}
