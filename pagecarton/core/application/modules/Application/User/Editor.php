<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Editor extends Application_User_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1, 98 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
            if( ! $data = self::getIdentifier() )
            { 
                if( ! Ayoola_Application::getUserInfo( 'username' ) || self::hasPriviledge() )
                {
                    return false;
                }
            }
			if( strtolower( $data['username'] ) !== Ayoola_Application::getUserInfo( 'username' ) && ! self::hasPriviledge( 98 ) )
			{
				//	We are not the owner of data and we are not admin
				return false;
			}
            if( ! $data = self::getIdentifierData() )
            { 
                if( self::hasPriviledge() )
                {
                    return false;
                }
                $data = Ayoola_Application::getUserInfo();
            }
			
			if( strtolower( $data['username'] ) !== Ayoola_Application::getUserInfo( 'username' )  && ! self::hasPriviledge( 98 ) )
			{
				//	We are not the owner of data and we are not admin
				return false;
			}
				
			$this->createForm( 'Save...', $data['username'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	Empty password means we are not trying to update it
			unset( $values['password2'] );
			if( empty( $values['password'] ) )
			{ 
				unset( $values['password'] );   
			}
			
			if( $values['access_level'] == 99 )
			{
				if( ! self::hasPriviledge() )
				{
					//	We must be an admin to even try to do this
					return false;
				}
				//	We have a new administrator
				//	lets do a welcome service.
				$table = Application_User_NotificationMessage::getInstance(); 
				$emailInfo = $table->selectOne( null, array( 'subject' => 'Welcome to the Admin Group' ) );
				$replacements = array( 
									'firstname' => $data['firstname'], 
									'domainName' => Ayoola_Page::getDefaultDomain(), 
								);
				$emailInfo = self::replacePlaceholders( $emailInfo, $replacements );
				$emailInfo['to'] = $data['email'];
				$emailInfo['from'] = 'no-reply@' . Ayoola_Page::getDefaultDomain();
				@self::sendMail( $emailInfo );
			}
			$values = array_merge( $data, $values );
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
				$database = 'private';
			}
			
			switch( $database )
			{
				case 'cloud':
					$response = Ayoola_Api_UserEditor::send( $values );
					if( true === $response['data'] )
					{
						$this->setViewContent(  '' . self::__( '<div class="boxednews goodnews">User account changes saved successfully</div>' ) . '', true  );
						
						
						//	localize
						unset( $values['password'] ); 
						Ayoola_Access_Localize::info( $values );
					}
				break;
				case 'relational':
					if( $this->updateDb() )
					{
					//	$this->setViewContent(  '' . self::__( 'User account edited successfully' ) . '', true  );
						$this->setViewContent(  '' . self::__( '<div class="boxednews goodnews">User account changes saved successfully</div>' ) . '', true  );
					}
				break;
				case 'file':
				case 'private':
					try
					{
						$values = $values + $data;
						
						
						//	Retrieve the password hash
						$access = new Ayoola_Access();
						$hashedCredentials = $access->hashCredentials( $values );
						$values = $hashedCredentials + $values;
						if( Ayoola_Access_Localize::info( $values ) )
						{
							$this->setViewContent(  '' . self::__( '<div class="boxednews goodnews">User account changes saved successfully.</div>' ) . '', true  );
						}
					}
					catch( Exception $e )
					{

                    }
				break;
			
			}
		}
		catch( Application_User_Exception $e ){ return false; }
    } 
	
    /**
     * This method does the database operation
     *
     * @param void
     * @return boolean
     */
    protected function updateDb( array $autoValues = NULL )
    {
		
		if( ! $this->_validate() ){ return false; }
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( ! $data = self::getIdentifierData() ){ return false; }
		
		$this->_otherTables = array_combine( array_values( $this->_otherTables ), $this->_otherTables ); 
		if( ! $values['password'] ){ unset( $this->_otherTables['UserPassword'] ); }
		else
		{
			require_once 'Ayoola/Filter/Hash.php';
			$filter = new Ayoola_Filter_Hash( 'sha512' );
			$values['password'] = $filter->filter( $values['password'] );
		
		}
		unset( $this->_otherTables['UserActivation'] );

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
