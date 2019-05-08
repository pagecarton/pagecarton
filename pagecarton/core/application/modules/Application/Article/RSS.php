<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_RSS
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Audio.php Thursday 21st of December 2017 01:17PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Article_RSS extends Application_Article_Abstract
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
	protected static $_objectTitle = 'Outputs RSS Feeds in XML formart'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here..

                if( ! empty( $_REQUEST['pc_post_list_id'] ) )
                {
                    $where['pc_post_list_id'] = $_REQUEST['pc_post_list_id'];
                }
                else
                {
                    $where = array();
                    if( $this->getParameter( 'true_post_type' ) )
                    {
                        $where['true_post_type'] = $this->getParameter( 'true_post_type' );
                    }
                    elseif( ! empty( $_REQUEST['true_post_type'] ) )
                    {
                        $where['true_post_type'] = $_REQUEST['true_post_type'];
                    }
                    if( $this->getParameter( 'article_types' ) )
                    {
                        $where['article_types'] = $this->getParameter( 'article_types' );
                    }
                    elseif( ! empty( $_REQUEST['article_types'] ) )
                    {
                        $where['article_types'] = $_REQUEST['article_types'];
                    }

                    if( $this->getParameter( 'category' ) )
                    {
                        $where['category'] = $this->getParameter( 'category' );
                    }
                    elseif( ! empty( $_REQUEST['category'] ) )
                    {
                        $where['category'] = $_REQUEST['category'];
                    }

                    if( $this->getParameter( 'post_switch' ) )
                    {
                        $switch = $this->getParameter( 'post_switch' );
                    }
                    elseif( ! empty( $_REQUEST['post_switch'] ) )
                    {
                        $switch = $_REQUEST['post_switch'];
                    }
                    
                }
                $class = new Application_Article_ShowAll( $where );
                $class->initOnce();
                $chunk = $class->getDbData();
             //   krsort( $chunk );
                $xml = new Ayoola_Xml();
                $rss = $xml->createElement( 'rss' );
                $rss->setAttribute( 'version', '2.0' );
                $rss->setAttribute( 'xmlns:media', 'http://search.yahoo.com/mrss' );
                $rss->setAttribute( 'xmlns:content', 'http://purl.org/rss/1.0/modules/content/' );
                $rss->setAttribute( 'xmlns:dc', 'http://purl.org/dc/elements/1.1/' );
                $xml->appendChild( $rss );
                $channel = $xml->createElement( 'channel' );  
                $rss->appendChild( $channel );
                $settings = Application_SiteInfo::getInfo();
                $title = $xml->createElement( 'title', $settings['site_headline'] );
                $channel->appendChild( $title );
                
                $description = $xml->createElement( 'description', $settings['site_description'] );
                $channel->appendChild( $description );
                
                $ttl = $xml->createElement( 'ttl', 1 );
                $channel->appendChild( $ttl );
                
                $image = $xml->createElement( 'image' );
                $channel->appendChild( $image );
                $url = Ayoola_Page::getHomePageUrl() . '/img/logo.png';
                $imageUrl = $xml->createElement( 'url', $url );
                $image->appendChild( $imageUrl );
                $title = $xml->createElement( 'title', $url );
                $image->appendChild( $title );
                $linkImage = $xml->createElement( 'link', $url );
                $image->appendChild( $linkImage );
                $linkDescription = $xml->createElement( 'description', $url );
                $image->appendChild( $linkDescription );

                $urlX = null;
                foreach( $where as $each )
                {
                    $urlX = '/' . $each . '';
                }
                $url = Ayoola_Page::getHomePageUrl() . '/posts' . $urlX;
                $link = $xml->createElement( 'link', $url );
                $channel->appendChild( $link );

                foreach( $chunk as $data )
                {
                    $data = self::loadPostData( $data );
                    if( ! empty( $switch ) )
                    {
                        if( empty( $data[$switch] ) )
                        {
                            continue;  
                        }
                    }
              //      var_export( $data );
                    $item = $xml->createElement( 'item' );
                    $channel->appendChild( $item );
               //     $data['article_title'] = preg_replace('/[^\x20-\x7F]+/', '', $data['article_title']);
                //    $data['article_description'] = preg_replace('/[^\x20-\x7F]+/', '', $data['article_description']);
            //        $data['article_description'] = preg_replace('/[^\x20-\x7F]+/', '', $data['article_description']);
                    $title = $xml->createElement( 'title' );
                    $item->appendChild( $title );
                    $text = $xml->createTextNode( $data['article_title'] );
                    $title->appendChild( $text );

                    $description = $xml->createElement( 'description' );
                    $item->appendChild( $description );
                    $text = $xml->createTextNode( $data['article_description'] );
                    $description->appendChild( $text );

                    if( ! empty( $data['profile_url'] ) )
                    {
                        $content = $xml->createElement( 'dc:creator' );
                        $item->appendChild( $content );
                        $profileInfo = Application_Profile_Abstract::getProfileInfo( $data['profile_url'] );
                    //    var_export( $profileInfo );
                        $text = $xml->createCDataSection( $profileInfo['display_name'] ? : $data['profile_url'] );
                        $content->appendChild( $text );  
                    }

                    if( ! empty( $data['article_content'] ) )
                    {
                        $content = $xml->createElement( 'content:encoded' );
                        $item->appendChild( $content );
                        $data['article_content'] = Ayoola_Page_Editor_Text::addDomainToAbsoluteLinks( $data['article_content'] );
                        $text = $xml->createCDataSection( $data['article_content'] );
                        $content->appendChild( $text );  
                    }

                    $url = Ayoola_Page::getHomePageUrl() . $data['article_url'];
                    $postUrl = $xml->createElement( 'link', $url );
                    $item->appendChild( $postUrl );

                    $guid = $xml->createElement( 'guid', $data['article_url'] );
                    $guid->setAttribute( 'isPermaLink', 'false' );
                    $item->appendChild( $guid );

                    $pubDate = $xml->createElement( 'pubDate', date( 'r', $data['article_creation_date'] ) );
                    $item->appendChild( $pubDate );

                    $mediaType = ucfirst( strtolower( $data['true_post_type'] ) );
                  //  var_export
                    $coverPhoto = Ayoola_Page::getHomePageUrl() . '/widgets/Application_Article_PhotoViewer/?article_url=' . $data['article_url'] . '';
                    $content = $xml->createElement( 'media:content' );
                    $content->setAttribute( 'url', $coverPhoto );
                    $content->setAttribute( 'medium', 'image' );
                    $item->appendChild( $content );

                    $content = $xml->createElement( 'media:content' );
                    $content->setAttribute( 'url', $coverPhoto );
                    $content->setAttribute( 'type', 'image/jpg' );
                    $item->appendChild( $content );
                    switch( $mediaType )
                    {
                        case 'Audio':
                        case 'Video':
                            $credit = $xml->createElement( 'media:credit', $data['profile_url'] );
                            $credit->setAttribute( 'role', 'author' );
                            $item->appendChild( $credit );

                            $content = $xml->createElement( 'media:content' );
                            $playUrl = Ayoola_Page::getHomePageUrl() . '/widgets/Application_Article_Type_' . $mediaType . '_Play/?article_url=' . $data['article_url'] . '&auto_download=1';
                            $content->setAttribute( 'url', $playUrl );
                            $content->setAttribute( 'type', strtolower( $mediaType ) . '/mpeg' );
                            $item->appendChild( $content );

                            $thumbnail = $xml->createElement( 'media:thumbnail' );
                            $thumbnail->setAttribute( 'url', Ayoola_Page::getHomePageUrl() . $data['document_url'] );
                            $thumbnail->setAttribute( 'type', 'image/jpeg' );
                            $item->appendChild( $thumbnail );
                        break;
                    }
                    
                }
                header( 'Content-Type: application/rss+xml' );
                echo $xml->view();
                exit();
			//	var_export( $postListId );
			//	var_export( $postList );
		//		var_export( $postList );
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
