<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Javascript
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Javascript.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_Javascript
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Javascript extends Ayoola_Abstract_Script
{
    /**
     * @var string
     */
	protected static $_codesOnLoad = array();
	
    /**
     * @var string
     */
	protected static $_filesOnLoad = array();
	
    /**
     * @var string
     */
	protected static $_jsMode = array();
	protected static $_filesToHead = array();
	protected static $_codesToHead = array();
	
    /**
     * All the Script files to include in the safe
     * 
     * @var array
     */
	protected static $_files = array();
	
    /**
     * All the Script code lines to write to script
     * 
     * @var array
     */
	protected static $_codes = array();	

	// END OF CLASS
}
