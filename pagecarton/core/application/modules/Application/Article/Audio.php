<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Audio
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Audio.php Thursday 21st of December 2017 01:17PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Article_Audio extends Application_Article_Abstract
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
	protected static $_objectTitle = 'Play Audio Posts'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

			{

                if( ! empty( $_REQUEST['pc_post_list_id'] ) )
                {
                    $postListId = $_REQUEST['pc_post_list_id'];
                }
                else
                {
                    //	Prepare post viewing for next posts
                    $storageForSinglePosts = self::getObjectStorage( array( 'id' => 'post_list_id' ) );
                    
                    $postListId = $storageForSinglePosts->retrieve();
                    if( ! $postListId )
                    {
                        $class = new Application_Article_ShowAll( array( 'true_post_type' => 'audio' ) );
                        $class->initOnce();
                        $postListId = $storageForSinglePosts->retrieve();
                    }               
                }
				$postList = Application_Article_ShowAll::getObjectStorage( array( 'id' => $postListId, 'device' => 'File' ) );
			//	var_export( $postListId );
			//	var_export( $postList );
				$postList = $postList->retrieve();
		//		var_export( $postList );
				if( ! empty( $postList['single_post_pagination'] ) )
				{
                    do
                    {
                //     var_export( $postList['single_post_pagination'] );
                        $postData = array_shift( $postList['single_post_pagination'] );
              //       var_export( $postData );
                       $postData = self::loadPostData( $postData );
                    }
                    while( $postData['true_post_type'] !== 'audio' );
             //       var_export( $postData['article_url'] );
                    $url = Ayoola_Application::getUrlPrefix() . $postData['article_url'] . '?pc_post_list_id=' . $postListId . '&autoplay=1&autoplay_next=1&pc_post_type_to_show=audio';
                    header( 'Location: ' . $url );
                    exit();

				}
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
	// END OF CLASS
}
