<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_Interface
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Interface.php 3.5.2012 6.04pm ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_Interface
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

interface Ayoola_Dbase_Table_Interface
{
	
    /**
     * Gets a _database property 
     *
     * @param void
     * @return Ayoola_Database
     */
    public function getDatabase();

    /**
     * Sets the _database property 
     *
     * @param Ayoola_Dbase
     */
    public function setDatabase( Ayoola_Dbase $database );
}
