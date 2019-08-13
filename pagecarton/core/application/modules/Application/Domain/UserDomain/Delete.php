<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_UserDomain_Delete
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_UserDomain_Delete extends Application_Domain_UserDomain_Abstract
{

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            if( ! self::hasPriviledge() )
            {
                $this->_dbWhereClause['username'] = strtolower( Ayoola_Application::getUserInfo( 'username' ) );
                $this->_dbWhereClause['user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
            }
            //  Code that runs the widget goes here...
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete', 'Delete' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
            
			if( $this->deleteDb() ){ $this->setViewContent(  '' . self::__( '<div class="goodnews">Data deleted successfully</div>' ) . '', true  ); } 
			
			//	clear domain cache
			Ayoola_File_Storage::purgeDomain( $data['domain_name'] );

            

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( '<p class="badnews">Theres an error in the code</p>' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
