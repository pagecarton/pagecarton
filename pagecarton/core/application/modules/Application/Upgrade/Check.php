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

            if( ! empty( $_REQUEST['pc_domain'] ) )
            {
                $info = array( 'domain_name' => $_REQUEST['pc_domain'], 'remote_version' => $_REQUEST['version'], 'version' => PageCarton::VERSION, );
                Application_Upgrade_Check_Table::getInstance()->insert( $info );  
                echo PageCarton::VERSION;
                exit();
            }

            if( self::hasPriviledge() )
            {
                $serverName = 'updates.pagecarton.org';
                $storage = self::getObjectStorage( array( 'id' => 'upgrade', 'device' => 'File', 'time_out' => 86400, ) );
                //    var_export( $storage->retrieve() );
                //    var_export( $storage );
                if( ! $versionFromServer = $storage->retrieve() )
                {
                    $server = 'http://' . $serverName .  '/widgets/Application_Upgrade_Check/?pc_domain=' . DOMAIN . '&version=' . PageCarton::VERSION;
            ///    var_export( $server );
                    $versionFromServer = array();
                    $versionFromServer['time'] = time();
                    if( ! $response = self::fetchLink( $server ) )
                    {
                        $server = 'http://s1.' . $serverName .  '/widgets/Application_Upgrade_Check/?pc_domain=' . DOMAIN . '&version=' . PageCarton::VERSION;
                        if( ! $response = self::fetchLink( $server ) )
                        {
                            $server = 'http://s2.' . $serverName .  '/widgets/Application_Upgrade_Check/?pc_domain=' . DOMAIN . '&version=' . PageCarton::VERSION;
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

                $versionFromServerX = explode( '.', $versionFromServer['response'] );
                $myVersionX = explode( '.', PageCarton::VERSION );

                if( empty( $versionFromServer['response'] ) )
                {
                    $this->setViewContent(  '<div  style="font-size:smaller;" class="badnews">' . sprintf( self::__( 'PageCartion is not able to check for updates (cURL error). %s' ), '' . $lastChecked . ''  ) . '</div>' , true  ); 
                }
                elseif( $myVersionX[0] > $versionFromServerX[0] || $myVersionX[1] > $versionFromServerX[1] || $myVersionX[2] > $versionFromServerX[2] || $myVersionX[3] > $versionFromServerX[3] )
                {
                    $this->setViewContent( '<div class="badnews">' . sprintf( self::__( 'You are running PageCarton %s. This is still a future version of PageCarton that is yet to be released.' ), '' . PageCarton::VERSION . ''  ) . ' 
                                                
                                                <a style="font-size:smaller;" target="_blank" href="https://www.pagecarton.org/posts?category=releases" class="">' . self::__( 'What Changed?' ) . '</a></div>', true ); 
                }
                elseif( $versionFromServer['response'] != PageCarton::VERSION )
                {
                    $this->setViewContent(  '<div class="badnews">' . sprintf( self::__( 'PageCarton %s is available for download. %s' ), '' . $versionFromServer['response'] . '', $lastChecked ) . ' 
                                                
                                                <a style="font-size:smaller;" onClick="ayoola.spotLight.showLinkInIFrame( this.href, \'page_refresh\' ); return false;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_Upgrade?" class="">Begin Upgrade!</a> 
                                                <a style="font-size:smaller;" target="_blank" href="https://www.pagecarton.org/posts?category=releases" class="">' . self::__( 'What Changed?' ) . '</a></div>', true ); 
                }
                else
                {
                    $this->setViewContent( '<p  style="font-size:smaller; text-align:center;" class="goodnews">' . sprintf( self::__( 'PageCarton Up-to-date (%s). %s' ), '' . $versionFromServer['response'] . '', $lastChecked ) . '</p>' . '', true  ); 
                //    $this->setViewContent( self::__( '' ) ); 
                }

            }
            if( self::hasPriviledge( 98 ) )
            {
                //  check update for themes
                foreach( Ayoola_Page_PageLayout::getInstance()->select() as $layout )
                {
                    if( empty( $layout['article_url'] ) )
                    {
                        //  only check update for one that is in repo
                        continue;
                    }
                    $url = 'https://themes.pagecarton.org/tools/classplayer/get/name/Application_Article_View?article_url=' . $layout['article_url'] . '&pc_widget_output_method=JSON';
                    $feed = self::fetchLink( $url, array( 'time_out' => 2, 'connect_time_out' => 2, ) );
                    $layoutInfo = json_decode( $feed, true );
                    $version = 0;
                    if( ! empty( $layoutInfo['article_editor_username'] ) )
                    {
                        $version = count( $layoutInfo['article_editor_username'] );
                        $lastEdited = $layoutInfo['article_modified_date'];    
                    }
                    if( $lastEdited > $layout['modified_time'] )
                    {
                        $this->setViewContent( '<div  style="font-size:smaller;" class="badnews">' . sprintf( self::__( '%s theme version %s is available. ' ), '' . $layout['layout_label'] . '', $version ) . ' <a style="font-size:smaller;" onClick="ayoola.spotLight.showLinkInIFrame( this.href, \'page_refresh\' ); return false;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Layout_Repository?title=' . $layout['layout_label'] . '&layout_type=upload&install=' . $layout['article_url'] . '&update=' . $layout['article_url'] . '" class="">' . self::__( 'Update Now' ) . '</a>   </div>' ); 
                    }
                }
                //  check update for themes
                foreach( Ayoola_Extension_Import_Table::getInstance()->select() as $plugin )
                {
                    if( empty( $plugin['article_url'] ) )
                    {
                        //  only check update for one that is in repo
                        continue;
                    }
                //    var_export( $plugin );
                    $url = 'https://plugins.pagecarton.org/tools/classplayer/get/name/Application_Article_View?article_url=' . $plugin['article_url'] . '&pc_widget_output_method=JSON&y=l1y';
                    $feed = self::fetchLink( $url, array( 'time_out' => 2, 'connect_time_out' => 2, ) );
                    $pluginInfo = json_decode( $feed, true );
                //    var_export( $url );
                //    var_export( $feed );
                //    var_export( $pluginInfo );
                //    $version = count( $pluginInfo['article_editor_username'] );
                    $lastEdited = $pluginInfo['article_modified_date'];
                    if( $lastEdited > $plugin['modified_time'] )
                    {
                        $this->setViewContent( '<div  style="font-size:smaller;" class="badnews">' . sprintf( self::__( 'A new version of %s plugin is available. ' ), $plugin['extension_title'] ) . ' <a style="font-size:smaller;" onClick="ayoola.spotLight.showLinkInIFrame( this.href, \'page_refresh\' ); return false;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Extension_Import_Repository?title=' . $plugin['extension_title'] . '&layout_type=upload&install=' . $plugin['article_url'] . '&update=' . $plugin['article_url'] . '" class="">' . self::__( 'Update Now' ) . '</a>   </div>' ); 
                    }
                }
            }
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( 'Theres an error in the code' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
