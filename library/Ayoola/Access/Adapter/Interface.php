<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Adapter_Interface
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Interface.php 1.22.12 10.11 ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Adapter_Interface
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

interface Ayoola_Access_Adapter_Interface
{
	
    /**
     * The Ayoola Access will be authenticating against this method 
     *
     * @param array Authentication Credentials
     * @return boolean
     */
    public function authenticate( Array $credentials );

    /**
     * Constructor 
     *
     * @param array Credentials Used in Authentication 
     * @param string  The Device Name e.g. Dbase Table Name
     * 
     */
    public function __construct( $credentials = null, $deviceName = null );

	// END OF CLASS
}
