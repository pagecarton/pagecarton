<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Validator_IsFile
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: IsFile.php 24-02-2013 2.17pm ayoola $
 */

/**
 * @see Ayoola_Validator_Abstract
 */
 
require_once 'Ayoola/Validator/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Validator_IsFile
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Validator_IsFile extends Ayoola_Validator_Abstract
{
	
    /**
     * This method does the main validation
     *
     * @param mixed
     * @return 
     */
    public function validate( $value )
    {
		if( is_array( $this->_parameters['allowed_extensions'] ) )
		{
			$filter = new Ayoola_Filter_FileExtention();
 		//	var_export( $value );
		//	var_export( $filter->filter( $value ) );
		//	var_export( $this->_parameters['allowed_extensions'] );
 			if( ! in_array( $filter->filter( $value ), $this->_parameters['allowed_extensions'] ) )
			{
				return false;
			}
		}
		$value = $this->_parameters['base_directory'] . $value;
	//	var_export( $value );
		return is_file( $value ); 
    } 
	
    /**
     * Returns the error message peculiar for this validation
     *
     * @param void
     * @return string
     */	
    public function getBadnews()
    {
        return "You have selected an invalid file for %value%.";
    }
	
    /**
     * Automated fill the parameters
     *
     * @param array
     * @return void
     */
	public function autofill( array $parameters )
    {
		$this->_parameters = $parameters;
    }
	// END OF CLASS
}
