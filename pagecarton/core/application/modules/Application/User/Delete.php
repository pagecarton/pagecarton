<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Delete extends Application_User_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['username'],  'Delete User' );
			$this->setViewContent( $this->getForm()->view(), true );
			$namespace = 'Application_User_';
            if( ! $values = $this->getForm()->getValues() ){ return false; }
            
			$table = Ayoola_Access_LocalUser::getInstance();
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
				$database = 'file';
			}
            switch( $database )
            {
                case 'private':
                    // Find user in the LocalUser table
                    $table = "Ayoola_Access_LocalUser";
                    $table = $table::getInstance( $table::SCOPE_PRIVATE );
                    $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
                    $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
                break;
                case 'file':
                    // Find user in the LocalUser table
                    $table = "Ayoola_Access_LocalUser";
                    $table = $table::getInstance( $table::SCOPE_PUBLIC . "xyz" );
                    $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PUBLIC );
                    $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PUBLIC );
                break;
            }
			if( $info = $table->delete( array( 'username' => strtolower( $data['username'] ) ) ) )
			{
				$this->setViewContent(  '' . self::__( '<p class="goodnews">User deleted successfully on the local table</p>' ) . '', true  );
			}
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{

            }
			switch( $database )
			{
				case 'relational':
				if( $this->deleteDb( false ) ){ $this->setViewContent(  '' . self::__( '<p class="goodnews">User deleted successfully</p>' ) . '', true  ); }
				break;
			
			}
		}
		catch( Application_User_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
