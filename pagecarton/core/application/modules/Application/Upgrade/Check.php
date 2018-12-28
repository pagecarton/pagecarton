<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Upgrade_Check
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Check.php Friday 25th of August 2017 05:29PM  $
 */

/**
 * @see PageCarton_Widget_Sample
 */

class Application_Upgrade_Check extends PageCarton_Widget 
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
	protected static $_objectTitle = 'Check for PageCarton Updates'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 

            $serverName = 'updates.pagecarton.org';

            if( ! empty( $_REQUEST['pc_domain'] ) )
            {
                $info = array( 'domain_name' => $_REQUEST['pc_domain'], 'remote_version' => $_REQUEST['version'], 'version' => PageCarton::VERSION, );
                Application_Upgrade_Check_Table()->insert(  );
                echo PageCarton::VERSION;
                exit();
            }
            $storage = $this->getObjectStorage( array( 'id' => 'diskspace', 'device' => 'File', 'time_out' => 86400, ) );
            if( ! $versionFromServer = $storage->retrieve() )
            {
                $server = 'http://' . $serverName . '/object/name/Application_Upgrade_Check/?pc_domain=' . DOMAIN . '&version=' . PageCarton::VERSION;
          ///    var_export( $server );
                $versionFromServer['time'] = time();
                $response = self::fetchLink( $server );
			//	var_export( $response );
                $versionFromServer['response'] = strlen( $response ) > 6 || strlen( $response ) < 2 ? 0 : $response;
                $storage->store( $versionFromServer );
            }
        //    var_export( $versionFromServer );
        $filter = new Ayoola_Filter_Time();
        $lastChecked = null;
//        $lastChecked = '<span  class="" style="font-size:smaller;">Update last checked ' . $filter->filter( $versionFromServer['time'] )  . ' (' . $serverName . ')</span>';
        if( empty( $versionFromServer['response'] ) )
        {
            $this->setViewContent( '<div  style="font-size:smaller;" class="badnews">PageCartion is not able to check for updates (cURL error). ' . $lastChecked . '  </div>', true ); 
        }
        elseif( $versionFromServer['response'] != PageCarton::VERSION )
        {
            $this->setViewContent( '<div class="badnews"> PageCarton ' . $versionFromServer['response']  . ' is available for download. ' . $lastChecked . ' . <a style="font-size:smaller;" onClick="ayoola.spotLight.showLinkInIFrame( this.href, \'page_refresh\' ); return false;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_Upgrade?" class="">Begin Upgrade!</a></div>', true ); 
        }
        else
        {
            $this->setViewContent( '<p  style="font-size:smaller; text-align:center;" class="goodnews"> PageCarton Up-to-date (' . $versionFromServer['response']  . '). ' . $lastChecked . '</p>', true ); 
        //    $this->setViewContent( '' ); 
        }



             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
