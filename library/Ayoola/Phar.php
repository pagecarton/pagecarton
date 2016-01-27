<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Phar
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Phar.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Phar
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Phar extends Phar
{
	
    /**
     * constructor
     * 
     */
	public function __construct( $fname, $flags = null, $alias = null )
    {
		if( ! extension_loaded( 'Phar' ) ){ throw new Ayoola_Phar_Exception( 'PHAR IS NOT LOADED' ); }
		parent::__construct( $fname, $flags = null, $alias = null );
    } 
	// END OF CLASS
}
