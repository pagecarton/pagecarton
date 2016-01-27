<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Impersonate
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Impersonate.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Impersonate
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Impersonate extends Application_User_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
		//	if( $data['access_level'] == 99 ){ return false; }
			
			$userInfo = Ayoola_Application::getUserInfo();
			if( @intval( $data['access_level'] ) === 99 || ! Ayoola_Access_Login::login( $data ) )
			{
				// return this user
				Ayoola_Access_Login::login( $userInfo );
				$this->setViewContent( '<span class="boxednews badnews centerednews">Impersonation not successful!</span>' ); 
				
			}
			else
			{
				$this->setViewContent( '<span class="boxednews normalnews centerednews"> You are signed in as ' . $data['username'] . '!</span>' ); 
				$this->setViewContent( '<a class="boxednews goodnews centerednews" href="' . Ayoola_Application::getUrlPrefix() . '/account">Go to account! ' . $data['username'] . '!</a>' ); 
			}
	//		var_export( $data );
		}
		catch( Application_User_Exception $e ){ return false; }
    } 
	
    /**
     * This method does the database operation
     *
     * @param void
     * @return boolean
     */
    protected function updateDb()
    {
		
		if( ! $this->_validate() ){ return false; }
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( ! $data = self::getIdentifierData() ){ return false; }
		
	//	var_export( $values['password'] );
	//	return;
	//	var_export( $this->getIdentifier() );
/* 		if( ! $this->getDbTable()->update( $values, $this->getIdentifier() ) )
		{
			$this->getForm()->setBadnews( 'System error from - ' . $this->getObjectName() );
			return false;
		}
 */		$this->_otherTables = array_combine( array_values( $this->_otherTables ), $this->_otherTables ); 
		if( ! $values['password'] ){ unset( $this->_otherTables['UserPassword'] ); }
		else
		{
			require_once 'Ayoola/Filter/Hash.php';
			$filter = new Ayoola_Filter_Hash( 'sha512' );
			$values['password'] = $filter->filter( $values['password'] );
		
		}
		unset( $this->_otherTables['UserActivation'] );
	//	var_export( $values['password'] );
	//	var_export( $this->_otherTables );
		$namespace = 'Application_User_';
		foreach( $this->_otherTables as $each )
		{
			$table = $namespace . $each;
			$table = new $table();
			if( ! $table->update( $values, $this->getIdentifier() ) )
			{
				$this->getForm()->setBadnews( 'Error while updating ' . $each );
				return false;
			}
		}
		return true;
    } 
	// END OF CLASS
}
