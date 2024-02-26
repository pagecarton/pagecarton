<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Event_Calendar
 * @copyright  Copyright (c) 2024 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Calendar.php Monday 26th of February 2024 01:28PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_EventData extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Event Data'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            if( $events = Application_Article_Table::getInstance()->select( null, array( 'true_post_type' => 'event' ), array( 'row_id_column' => 'article_url' ) ) )
            {
                //var_export( $events );
                //asort( $events );
                $present = array();

                $start = strtotime( $_REQUEST['start'] );
                $end = strtotime( $_REQUEST['end'] );

                foreach( $events as $key => $value )
                {

                    //$eventDate = strtotime( $value['datetime'] );
                    $eventDate = $value['article_creation_date'];

                    if( $eventDate < $start || $eventDate > $end   )
                    {
                       // unset( $events[$key] );   
                    }
                    $present[] = array( 'id' => $value['article_url'], 'url' =>  Ayoola_Application::getUrlPrefix() . $value['article_url'], 'title' => $value['article_title'], 'start' => date( 'Y-m-d', $value['article_creation_date'] ) ); 
                }
            }
            header('Content-Type: application/json');
            echo json_encode( $present );
            exit();
             
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
