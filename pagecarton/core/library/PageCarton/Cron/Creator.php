<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Cron_Creator
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php Wednesday 20th of December 2017 03:23PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Cron_Creator extends PageCarton_Cron_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'New Cron Task'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			$this->createForm( 'Add...', 'Create a cron task' );
			$this->setViewContent( $this->getForm()->view() );

		//	self::v( $_POST );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			$values['cron_next_run_time'] = time();
			$values['cron_parameters'] = $values['cron_parameters'] ? json_decode( $values['cron_parameters'], true ) : $values['cron_parameters'];
			
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = 'A cron task created';
			$mailInfo['body'] = 'A new cron task has been created on your PageCarton Installation with the following information: "' . htmlspecialchars_decode( var_export( $values, true ) ) . '". 
			
			';
			try
			{
		//		var_export( $mailInfo );
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
		//	if( ! $this->insertDb() ){ return false; }
			if( $this->insertDb( $values ) )
			{ 
				$this->setViewContent( '<div class="goodnews">Cron task created successfully. </div>', true ); 
			}
		//	$this->setViewContent( $this->getForm()->view() );
            


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
