<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
     * @var array
     */
	protected static $_accessLevel = array( 0 );
			
    /**
     *
     * @var boolean
     */
	protected static $_ran = false;
		

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

            if( self::$_ran )
            {
                //  don't do this more than once in a row
                return false;
            }

            if( $this->getParameter( 'mode') === 'onsite' && stripos( Ayoola_Application::getRuntimeSettings( 'url' ), __CLASS__ )  )
            {
                return false;
            }

            self::$_ran = true;


            if( PHP_SAPI === 'cli' && $_SERVER['HTTP_AYOOLA_PLAY_CLASS'] === __CLASS__ && ! Ayoola_Application::getConfig( 'disable_auto_cron' ) ) 
            {
                //  detect if this is a native cron run
                //  then switch off normal onsite run
                Ayoola_Application::setSiteConfiguration( array(
                    'disable_auto_cron' => true
                ) );
            }

            if( ! empty( $_REQUEST['table_id'] ) && self::hasPriviledge( 98 ) )
            {
			    if( ! $data = $this->getIdentifierData() ){ return false; }
                $this->createConfirmationForm( 'Run', 'Run cron task' );
                $this->setViewContent( $this->getForm()->view(), true );
                if( ! $values = $this->getForm()->getValues() ){ return false; }
                if( $html = self::task( $data ) )
                {
                    $this->setViewContent( $html, true );
                    $this->setViewContent( self::__( '<div class="goodnews">Cron task processed successfully</div>' ) );
                }
            }
            else
            {
                $tasks = PageCarton_Cron_Table::getInstance()->select();
                $u = 0;
                $cTime = time();
                foreach( $tasks as $data )
                {
                    //  check if we have any pending job
                    if( $unfinshed = PageCarton_Cron_Run_Table::getInstance()->selectOne( null, array( 'cron_id' => $data['table_id'], 'done' => 0 ) ) )
                    {
                        if( ( $cTime - ( 60 * 60 * 1 ) ) > $unfinshed['runtime'] )
                        {
                            //  continue if it seems like a zombie process
                            //  after one hour
                        }
                        else
                        {
                            //  we have a pending job
                            continue;
                        }
                    }

                    if( $runHistory = PageCarton_Cron_Run_Table::getInstance()->select( null, array( 'cron_id' => $data['table_id'], ), array( 'sort_column' => 'runtime' ) ) )
                    {
                        $lastRunInfo = array_pop( $runHistory );
                        $lastRunTime = $lastRunInfo['runtime'];
                        $nextRunTime = $lastRunTime + $data['cron_interval'];
                    }
                    else
                    {
                        $nextRunTime = $cTime;
                    }
                    $filter = new Ayoola_Filter_Time();
                    if( $nextRunTime > $cTime )
                    {
                       continue;
                    }
                    if( count( $runHistory ) > 10 )
                    {
                        PageCarton_Cron_Run_Table::getInstance()->delete( array( 'cron_id' => $data['table_id'] ) );
                    }
                    $runData = array( 'cron_id' => $data['table_id'], 'runtime' => $cTime, );
                    $runI = PageCarton_Cron_Run_Table::getInstance()->insert( $runData );
                    if( $html = self::task( $data ) )
                    {
                        $this->setViewContent( $html, true );
                    }
                    PageCarton_Cron_Run_Table::getInstance()->update( array( 'done' => 1 ), array( 'table_id' => $runI['table_id'] ) );
                    $u++;
                }
                $this->setViewContent( self::__( '<div class="goodnews">' . sprintf( PageCarton_Widget::__( "%s cron tasks processed successfully" ), $u ) . '</div>' ) );
            }

            //  if you are not admin, don't see updates.
            if( ! self::hasPriviledge( 98 ) )
            {
                $this->setViewContent(  '' . self::__( '<div class="goodnews">Done</div>' ) . '', true  );
            }
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( '<p class="badnews">Theres an error in the code</p>' ) . '', true  ); 
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
		//	    $this->setViewContent( self::__( '<div class="goodnews">Cron task processed successfully</div>' ) );
            }
	//		$data['cron_run_time_history'] = $data['cron_run_time_history'] ? : array();
	//		$data['cron_run_time_history'][] = time();
	//		$data['cron_next_run_time'] = time() + $data['cron_interval'];
    //        PageCarton_Cron_Table::getInstance()->update( $data, array( 'table_id' => $data['table_id'] ) );
            return $html;
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            return false; 
        }
	}
	// END OF CLASS
}
