<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Abstract_Storage_Interface
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Interface.php 1.22.12 10.11 ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Abstract_Storage_Interface
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

interface Ayoola_Abstract_Storage_Interface
{
	
    /**
     * Put data in Storage
     *
     * @param void
     * @return boolean
     */
    public function store( $data );
	
    /**
     * Retrieve Data from Storage
     *
     * @param void
     * @return boolean
     */
    public function retrieve();
	
    /**
     * Empties the Storage
     *
     * @param void
     * @return boolean
     */
    public function clear();
	
    /**
     * Switch if there is a record of a user in storage
     *
     * @param void
     * @return Ayoola_Abstract_Result
     */
    public function isLoaded();
	
	
	// END OF CLASS
}
