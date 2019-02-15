<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Filter_HtmlSpecialChars
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: HtmlSpecialCharsDecode.php 10-25-2011 3.25pm ayoola $
 */

/**
 * @see Ayoola_Filter_Interface
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Filter_HtmlSpecialChars
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
 
class Ayoola_Filter_HtmlSpecialChars implements Ayoola_Filter_Interface
{

    public $loopFilter = true;  
	
    public function filter( $value )     
	{
	//	var_export( $value );  
		$result = htmlspecialchars($value);
		return $result;
	}
 
}
