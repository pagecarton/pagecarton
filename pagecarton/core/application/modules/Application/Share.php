<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Share
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CommentBox.php Friday 22nd of December 2017 11:03AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Share extends Application_Share_Abstract
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
	protected static $_objectTitle = 'Share Page'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            $values['creation_time'] = time();
            $values['article_url'] = strtolower( @$_REQUEST['article_url'] );
            $values['url'] = strtolower( @$_REQUEST['url'] );

            $url = $values['url'] ? : $values['article_url'];
			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$values['profile_url'] = @$userInfo['profile_url'];
			$values['user_id'] = @$userInfo['user_id'];
			$values['username'] = @$userInfo['username'];
			if( empty( $_REQUEST['title'] ) )
			{
				if( $this->getParameter( 'title' ) )
				{
					$title = $this->getParameter( 'title' );
				}
				elseif( $values['article_url'] )
				{
					$postData = Application_Article_Abstract::loadPostData( $values );
					$title = $postData['article_title'];
				}
				else
				{
					$pageInfo = Ayoola_Page::getInfo( $values['url'] );
					if( ! $pageInfo['title'] )
					{
						$pageInfo['title'] = array_pop( array_map( 'ucwords', explode( '/', str_replace( '-', ' ',	 $pageInfo['url'] ) ) ) );  
					}
					$title = $pageInfo['title'];
				}
			}
			else
			{
				$title = $_REQUEST['title'];
			}
            
			if( $url )
			{ 
				$this->getDbTable()->insert( $values );
				header( 'Location: https://www.addtoany.com/share#url=' . htmlentities( Ayoola_Page::getHomePageUrl() . $url ) . '&title=' . htmlentities( $title ) . '' );
				exit();
			}
			else
			{
	//			$currentUrl = $this->getParameter( 'url' ) ? : ( rtrim( Ayoola_Application::getRuntimeSettings( 'real_url' ), '/' ) ? : '/' );
		//         var_export( $currentUrl );
				if( $articleUrl = $this->getParameter( 'article_url' ) ? : ( Ayoola_Application::$GLOBAL['article_url'] ? : $_REQUEST['article_url'] ) )
				{
					$currentUrl = '';
				}
				else
				{
					$currentUrl = rtrim( Ayoola_Application::getRequestedUri(), '/' );
			//		var_export( $currentUrl  );
					$currentUrl = $this->getParameter( 'url' ) ? : ( $currentUrl ? : '/' );
					$articleUrl = '';
				}
				$count = count( $this->getDbTable()->select( null, array( 'article_url' => strtolower( $articleUrl ), 'url' => strtolower( $currentUrl ) ) ) );
				$values['share_count'] = $count;
				$values['share_url'] = '/tools/classplayer/get/name/' . __CLASS__ . '/?url=' . htmlentities( $currentUrl ) . '&article_url=' . htmlentities( $articleUrl ) . '&title=' . htmlentities( $title ) . '';
				$values['share_link'] = '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . $values['share_url'] . '\' );" href="javascript:" >' . $count . ' Shares</a>';
				$this->setViewContent( $values['share_link'] );
			}
		//	var_export( $values );
			$this->_objectTemplateValues = array_merge( $values ? : array(), $this->_objectTemplateValues ? : array() );
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>', true ); 
            $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
