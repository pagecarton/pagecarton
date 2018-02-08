<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Validator_DuplicateUser
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DuplicateUser.php 24-02-2013 2.17pm ayoola $
 */

/**
 * @see Ayoola_Validator_Abstract
 */
 
require_once 'Ayoola/Validator/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Validator_DuplicateUser
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Validator_DuplicateUser extends Ayoola_Validator_DuplicateRecord
{
	
    /**
     * column to verify against Whether to check for email or username
     *
     * @var string
     */
	public $columnName = 'username';
	
    /**
     * Username Blacklist
     *
     * @var array
     */
	protected static $_blacklist = array( 'username', 'profile', 'ayoola', 'pagecarton', 'admin', 'administrator', 'account', 'home', 'index' );
	
    /**
     * This method does the main validation
     *
     * @param mixed
     * @return 
     */
    public function validate( $value )
    {
		if( ! $value ){ return false; }
		if( in_array( $value, self::$_blacklist ) ){ return false; }
		if( is_numeric( $value[0] ) ){ return false; }
		$this->_value = $value;
		$data = array();
		switch( strtolower( $this->columnName ) )
		{
			case 'email':
				$data = array( 'email' => $this->_value );
			break;
			case 'username':
				$data = array( 'username' => $this->_value );				
			break;			
		}
	//	var_export( $data );
		if( ! $data ){ return false; }
	//	$response = Ayoola_Access_Api_CheckDuplicateInfo::send( $data );
	//	var_export( $response );
	//	if( $response['data'] === true )
		{ 
	//		return false; 
		}
		// Find user in the LocalUser table
//		$table = new Ayoola_Access_LocalUser();

		//	var_export( $table->select() ); 
	//	var_export( $hashedCredentials );
		if( $info = Application_User_Abstract::getLocalTable()->selectOne( null, array_map( 'strtolower', $data ) ) )
		{
	//	var_export( $info );
			return false;  
		}
        if( ! empty( $data['username'] ) && Application_Profile_Abstract::getProfileInfo( $data['username'] ) )
		{
			return false;
		}
	//	var_export( $info );
	
		// No page must be of same username
		$table = Ayoola_Page_Page::getInstance();
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );

		//	var_export( $table->select() ); 
	//	var_export( $hashedCredentials );
		$data = array( 'url' => '/' . trim( strtolower( $this->_value ), '/ ' ) );   
	//	var_export( $data );
		if( $info = $table->selectOne( null, $data, array( __CLASS__ . '-work-around-to-have-personal-cache' => true ) ) )
		{
	//	var_export( $info );
			return false;  
		}
		$filename = Application_Profile_Abstract::getProfilePath( $this->_value );
//		var_export( $filename );
		$info = @include $filename;
//		var_export( $info );
		if( $info )
		{ 
			return false; 
		}
	//	return false;  
	//	var_export( $info );
		return true;
    } 
	
	
    /**
     * Automated fill the parameters
     *
     * @param array
     * @return void
     */
	public function autofill( array $parameters )
    {
		//$args = array_slice( $args, 0, 2 );
		$this->columnName = $parameters[0];
    }
	// END OF CLASS
}
