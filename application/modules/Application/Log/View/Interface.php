<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Log_View_Interface
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Interface.php 1.22.12 10.11 ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   Ayoola
 * @package    Application_Log_View_Interface
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

interface Application_Log_View_Interface
{
		
    /**
     * clears the log
     *
     * @param void
     */
    public static function clearLog();	
		
    /**
     * Returns the log
     *
     * @param void
     * @return string 
     */
    public static function viewLog();	
	// END OF INTERFACE
}
