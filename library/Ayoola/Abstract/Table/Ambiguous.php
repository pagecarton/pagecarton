<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Table_Ambiguous
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php 4.26.2012 10.08am ayoola $
 */

/**
 * @see Ayoola_Exception 
 * @see Ayoola_Abstract_Playable 
 */
 
require_once 'Ayoola/Exception.php';
require_once 'Ayoola/Abstract/Table.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Table_Ambiguous
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Abstract_Table_Ambiguous extends Ayoola_Abstract_Table
{
	
    /**
     * Sets _dbData
     * 
     */
	public function setDbData()
    {
		//	Had a problem with conflicting menu_id as regarding the protected mode.
		//	Solve  the Ambiguity issue by switching to private mode
		$table = get_class( $this->getDbTable() );
		$this->getDbTable()->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );
		$table = $this->getDbTable();
		$identifier = $this->getIdentifier();
		$identifier = array( parent::ID_COLUMN => $identifier[parent::ID_COLUMN] );
		$this->_dbData = (array) $table->select( null, $identifier );
    } 
	// END OF CLASS
}
