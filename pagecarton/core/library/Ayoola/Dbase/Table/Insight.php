<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_Insight
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Insight.php Tuesday 26th of November 2019 09:07AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Dbase_Table_Insight extends PageCarton_Widget
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
	protected static $_objectTitle = 'Get database table analytics'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            $class = 'Application_Log_View_Access_Log';
            $timeVariation = 60 * 60 * 24 * 1;
            $noOfDatasets = 6;

            $currentTime = time();
            $currentDataTime = $currentTime;
            $data = array();
            $labels = array();
            $color = array();
            $borderColor = array();
            $filter = new Ayoola_Filter_Time();
            for( $i = 0; $i < $noOfDatasets; $i++ )
            {
                $from = $currentDataTime;
                $currentDataTime = $currentDataTime - $timeVariation;
                $to = $currentDataTime;
                $result = $class::getInstance()->select( null, array( 'creation_time' => array( $from, $to ) ), array( 'creation_time_operator' => 'range', 'disable_cache' => true ) );
            //    $result = $class::getInstance()->select(  );
            //    var_export( $resultx );
                
                $labels[] = $filter->filter( $to );
                $data[] = count( $result );
                $c1 = rand( 0, 255 );
                $c2 = rand( 0, 255 );
                $c3 = rand( 0, 255 );
                $bgColor[] = 'rgba( ' . $c1 . ', ' . $c2 . ', ' . $c3 . ', 0.2 )';
                $borderColor[] = 'rgba( ' . $c1 . ', ' . $c2 . ', ' . $c3 . ', 1 )';
            }
            


            //  Output demo content to screen
            
            $sampleData = "{
                type: 'bar',
                data: {
                    labels: " . json_encode( $labels ) . ",
                    datasets: [{
                        label: 'records',
                        data: " . json_encode( $data ) . ",
                        backgroundColor: " . json_encode( $bgColor ) . ",
                        borderColor: " . json_encode( $borderColor ) . ",
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            }";
            $data = $sampleData;
            Application_Javascript::addFile( 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js' );
            Application_Javascript::addCode 
            (  
                '
                var ctx = document.getElementById( "myChart" );
                var myChart = new Chart( ctx, ' . $data . ' );
                ' 
            );
            $this->setViewContent( '<canvas id="myChart" width="400" height="400"></canvas>' ); 
    
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
