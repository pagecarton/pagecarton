<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Cron_Editor
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Cron_Editor extends PageCarton_Cron_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Edit a cron task'; 

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
			$this->createForm( 'Save', 'Edit cron task', $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }

	//		$values['cron_next_run_time'] = time();
			$values['cron_parameters'] = $values['cron_parameters'] ? json_decode( $values['cron_parameters'], true ) : $values['cron_parameters'];

			if( $this->updateDb( $values ) ){ $this->setViewContent( '<div class="goodnews">Cron task updated successfully</div>', true ); } 

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
