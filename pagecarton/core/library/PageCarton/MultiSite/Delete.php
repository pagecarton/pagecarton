<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_MultiSite_Delete
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_MultiSite_Delete extends PageCarton_MultiSite_Abstract
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Delete a Site'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$link = '' . Ayoola_Page::getRootUrl() . '' . $data['directory'];
			$this->createConfirmationForm( 'Delete', 'Delete this site, "' . $link . '" and all its associated files?' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
            if( ! self::deleteFiles( $data['directory'] ) )
            {
     //           return false;
            }     
            
			if( $this->deleteDb() ){ $this->setViewContent(  '' . self::__( '<div class="goodnews">Site deleted successfully</div>' ) . '', true  ); } 

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
