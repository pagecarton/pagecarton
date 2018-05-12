<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Cron_Run
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Run.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Cron_Run extends PageCarton_Cron_Abstract
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
	protected static $_objectTitle = 'Run a cron task'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            if( ! empty( $_REQUEST['table_id'] ) && self::hasPriviledge( 98 ) )
            {
			    if( ! $data = $this->getIdentifierData() ){ return false; }
                $this->createConfirmationForm( 'Run', 'Run cron task' );
                $this->setViewContent( $this->getForm()->view(), true );
                if( ! $values = $this->getForm()->getValues() ){ return false; }
                if( $html = self::task( $data ) )
                {
                    $this->setViewContent( $html, true );
                    $this->setViewContent( '<div class="goodnews">Cron task processed successfully</div>' );
                }
            }
            else
            {
                $tasks = PageCarton_Cron_Table::getInstance()->select();

                foreach( $tasks as $data )
                {
                    if( $data['cron_next_run_time'] > time() || ! empty( $data['cron_interval'] ) )
                    {
                        continue;
                    }
                    if( $html = self::task( $data ) )
                    {
                        $this->setViewContent( $html, true );
                    }
                }
                $this->setViewContent( '<div class="goodnews">All cron tasks processed successfully</div>' );
            }

            //  if you are not admin, don't see updates.
            if( ! self::hasPriviledge( 98 ) )
            {
                $this->setViewContent( '<div class="goodnews">Done</div>', true );
            }
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>', true ); 
            return false; 
        }
	}

    /**
     * Performs the whole widget running process
     * 
     */
	public static function task( $data )
    {    
		try
		{ 
            $class = $data['class_name'];
            if( Ayoola_Loader::loadClass( $class ) )
            {
                $parameters = null;
                if( $data['cron_parameters'] )
                {
                    $parameters = $data['cron_parameters'];
                }
                $html = $class::viewInline(  $parameters );
		//	    $this->setViewContent( $html, true );
		//	    $this->setViewContent( '<div class="goodnews">Cron task processed successfully</div>' );
            }
			$data['cron_run_time_history'] = $data['cron_run_time_history'] ? : array();
			$data['cron_run_time_history'][] = time();
			$data['cron_next_run_time'] = time() + $data['cron_interval'];
            PageCarton_Cron_Table::getInstance()->update( $data, array( 'table_id' => $data['table_id'] ) );

            return $html;
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
