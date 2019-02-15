<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Hook_Interface
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Interface.php Monday 14th of May 2018 09:49AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Hook_Sample
 */

interface PageCarton_Hook_Interface
{
	


    /**
     * Hook to another widget
     * 
     * param PageCarton_Widget The Widget to Hook to
     * param string Current Method Where Hook is Running
     * param array Arguments Passed to the method
     * 
     */
	public static function hook( Ayoola_Abstract_Viewable $object, $functionName = null, $arguments = null );
    
	// END OF CLASS
}