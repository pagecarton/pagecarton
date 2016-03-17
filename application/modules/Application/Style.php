<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Style
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Style.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Style
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Style extends Ayoola_Abstract_Script
{
	
    /**
     * Placeholder for type attribute
     * 
     * @var string
     */
	protected static $_type = 'text/css';
	
    /**
     * html markup with placeholder
     * 
     * @var array
     */
	protected static $_markup = array( 'file' => "\n<link rel='stylesheet' type='@@TYPE@@' href='@@CONTENT@@' />", 'code' => "\n<style type='@@TYPE@@'>@@CONTENT@@</style>" );
	
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

	// END OF CLASS
}
