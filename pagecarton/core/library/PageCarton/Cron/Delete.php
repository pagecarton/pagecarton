<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Cron_Delete
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Cron_Delete extends PageCarton_Cron_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Delete a cron task'; 

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
			$this->createConfirmationForm( 'Delete', 'Delete cron task' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
            
			if( $this->deleteDb() ){ $this->setViewContent( '<div class="goodnews">Cron task deleted successfully</div>', true ); } 

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
