<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
                Application_Upgrade_Check_Table::getInstance()->insert( $info );  
                echo PageCarton::VERSION;
                exit();
            }
            $storage = self::getObjectStorage( array( 'id' => 'diskspace', 'device' => 'File', 'time_out' => 86400, ) );
        //    var_export( $storage->retrieve() );
        //    var_export( $storage );
            if( ! $versionFromServer = $storage->retrieve() )
            {
                $server = 'http://' . $serverName . '/object/name/Application_Upgrade_Check/?pc_domain=' . DOMAIN . '&version=' . PageCarton::VERSION;
          ///    var_export( $server );
                $versionFromServer = array();
                $versionFromServer['time'] = time();
                if( ! $response = self::fetchLink( $server ) )
                {
                    $server = 'http://s1.' . $serverName . '/object/name/Application_Upgrade_Check/?pc_domain=' . DOMAIN . '&version=' . PageCarton::VERSION;
                    if( ! $response = self::fetchLink( $server ) )
                    {
                        $server = 'http://s2.' . $serverName . '/object/name/Application_Upgrade_Check/?pc_domain=' . DOMAIN . '&version=' . PageCarton::VERSION;
                        if( ! $response = self::fetchLink( $server ) )
                        {
                            // we don try;
                        }
                    }
               }
                $versionFromServer['response'] = strlen( $response ) > 6 || strlen( $response ) < 2 ? 0 : $response;
			//	var_export( $versionFromServer );
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
            $this->setViewContent( '<div class="badnews"> 
                                        PageCarton ' . $versionFromServer['response']  . ' is available for download. ' . $lastChecked . ' 
                                        <a style="font-size:smaller;" onClick="ayoola.spotLight.showLinkInIFrame( this.href, \'page_refresh\' ); return false;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_Upgrade?" class="">Begin Upgrade!</a> 
                                        <a style="font-size:smaller;" target="_blank" href="https://www.pagecarton.org/posts?category=releases" class="">What Changed?</a></div>', true ); 
        }
        else
        {
            $this->setViewContent( '<p  style="font-size:smaller; text-align:center;" class="goodnews"> PageCarton Up-to-date (' . $versionFromServer['response']  . '). ' . $lastChecked . '</p>', true ); 
        //    $this->setViewContent( '' ); 
        }

        //  check update for themes
        foreach( Ayoola_Page_PageLayout::getInstance()->select() as $layout )
        {
            if( empty( $layout['article_url'] ) )
            {
                //  only check update for one that is in repo
                continue;
            }
            $url = 'https://themes.pagecarton.org/tools/classplayer/get/name/Application_Article_View?article_url=' . $layout['article_url'] . '&pc_widget_output_method=JSON';
            $feed = self::fetchLink( $url, array( 'time_out' => 288000, 'connect_time_out' => 288000, ) );
            $layoutInfo = json_decode( $feed, true );
        //   var_export( array_pop( $layoutInfo['modified_time'] ) );
       //   var_export( $layout['modified_time'] );
            $lastEdited = array_pop( $layoutInfo['modified_time'] );
       //     var_export( $layoutInfo['article_title'] );
        //    var_export( $layoutInfo['modified_time'] );
      //     var_export( $layout['modified_time'] );
            if( $lastEdited > $layout['modified_time'] )
            {
                $this->setViewContent( '<div  style="font-size:smaller;" class="badnews">' . $layout['layout_label'] . ' theme is outdated. <a style="font-size:smaller;" onClick="ayoola.spotLight.showLinkInIFrame( this.href, \'page_refresh\' ); return false;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Layout_Repository?title=' . $layout['layout_label'] . '&layout_type=upload&install=' . $layout['article_url'] . '&update=' . $layout['article_url'] . '" class="">Update now!</a>   </div>', true ); 
            }
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
