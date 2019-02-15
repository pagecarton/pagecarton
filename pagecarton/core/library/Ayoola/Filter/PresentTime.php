<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Filter_PresentTime
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PresentTime.php 10.13.2011 1:55PM ayoola $
 */

/**
 * @see Ayoola_
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Filter_PresentTime
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Filter_PresentTime implements Ayoola_Filter_Interface
{
	
    /**
     * This method does the main filtering biz.
     *
     * @param 
     * @return 
     */
    public function filter( $value )
    {
        return time();
    } 	
	
	// END OF CLASS
}
