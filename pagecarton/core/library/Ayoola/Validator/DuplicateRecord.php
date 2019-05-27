<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Validator_DuplicateRecord
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DuplicateRecord.php 24-02-2013 2.17pm ayoola $
 */

/**
 * @see Ayoola_Validator_Abstract
 */
 
require_once 'Ayoola/Validator/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Validator_DuplicateRecord
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Validator_DuplicateRecord extends Ayoola_Validator_Abstract
{
	
    /**
     * The class for the table
     *
     * @var string
     */
	public $tableClass;
	
    /**
     * column to verify against
     *
     * @var string
     */
	public $columnName;
	
    /**
     * This method does the main validation
     *
     * @param mixed
     * @return 
     */
    public function validate( $value )
    {
		$this->_value = $value;
		if( ! Ayoola_Loader::loadClass( $this->tableClass ) )
		{ 
            return false;
	//		throw new Ayoola_Exception( 'INVALID CLASS FOR DUPLICATE RECORD VALIDATOR: ' . $this->tableClass  );
		}
		$table = new $this->tableClass;
		if( $table instanceof Ayoola_Dbase_Table_Abstract_Xml )
		{
			$value = $table->select( null, array( $this->columnName => $value ) );
		}
		else
		{
	//		$value = $table->select( null, null, array( $this->columnName => $value ) );
		}
		if( ! $value ){ return true; }
		return false;
    } 
	
    /**
     * Returns the error message peculiar for this validation
     *
     * @param void
     * @return string
     */	
    public function getBadnews()
    {
        return "Someone has already used {$this->_value} as %value%";
    }
	
	
    /**
     * Automated fill the parameters
     *
     * @param array
     * @return void
     */
	public function autofill( $parameters )
    {
		//$args = array_slice( $args, 0, 2 );
		$this->tableClass = $parameters[0];
		$this->columnName = $parameters[1];
    }
	// END OF CLASS
}
