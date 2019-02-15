<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Validator_RegisteredUser
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: RegisteredUser.php 24-02-2013 2.17pm ayoola $
 */

/**
 * @see Ayoola_Validator_Abstract
 */
 
require_once 'Ayoola/Validator/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Validator_RegisteredUser
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Validator_RegisteredUser extends Ayoola_Validator_Abstract
{
	
    /**
     * User Info to verify against
     *
     * @var array
     */
	public $userInfo;
	
    /**
     * This method does the main validation
     *
     * @param mixed
     * @return 
     */
    public function validate( $value )
    {
		//	By default, authenticate for a standard user
	//	if( ! $value ){ $value = 1; }
		//	Check the user
		if( ! $value )
		{
			return false;
		}
		if( ! $userInfo = Ayoola_Access::getAccessInformation( $value ) )
		{
			return false;
		}
		return true;
    } 
	
    /**
     * Returns the error message peculiar for this validation
     *
     * @param void
     * @return string
     */	
    public function getBadnews() 
    {
        return "Invalid user information";
    }
	
    /**
     * Automated fill the parameters
     *
     * @param array
     * @return void
     */
	public function autofill( array $parameters )
    {
		$this->userInfo = $parameters;
    }
	// END OF CLASS
}
