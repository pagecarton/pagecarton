<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_CommentBox_ShowComments
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowComments.php Friday 22nd of December 2017 11:16AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_CommentBox_ShowComments extends Application_CommentBox_Abstract
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
	protected static $_objectTitle = 'Show Comments'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            $articleUrl = Ayoola_Application::$GLOBAL['post']['article_url'] ? : $_REQUEST['article_url'];
            if( $articleUrl )
            {
                $where = array( 'article_url' => $articleUrl );
                $title = Ayoola_Application::$GLOBAL['post']['article_title'];
                $link = Ayoola_Application::getUrlPrefix() . $articleUrl;
            }
            else
            {
                $currentUrl = rtrim( Ayoola_Application::getRuntimeSettings( 'real_url' ), '/' ) ? : '/';
                $where = array( 'url' => $currentUrl );
				$pageInfo = Ayoola_Page::getInfo( $currentUrl );
				if( ! $pageInfo['title'] )
				{
					$pageInfo['title'] = array_pop( array_map( 'ucwords', explode( '/', str_replace( '-', ' ',	 $pageInfo['url'] ) ) ) );  
				}
                $title = $pageInfo['title'];
                $link = Ayoola_Application::getUrlPrefix() . $currentUrl;
            }
            $where['parent_comment'] = '';
            $where['hidden'] = 0;
            if( $this->getParameter( 'show_all_site_comments' ) )
            {
                $where = null;
            }
            if( $data = $this->getDbTable()->select( null, $where ) )
            {
                if( ! $this->getParameter( 'show_all_site_comments' ) )
                {
                    krsort( $data );
                }
            }
            Application_Style::addFile( '/css/comment-box.css' );
            $html = null;
            $html .= '<div class="comments-container">
                        <ul id="comments-list" class="comments-list">';
        //    self::v( $data );
      //      var_export( $this->getDbTable()->select() );
            $filter = new Ayoola_Filter_Time();
        //    $this->_objectTemplateValues = $data
            $limit = $this->getParameter( 'no_of_post_to_show' ) ? : 10;
            $counter = 0;
            $done = array();
            foreach( $data as $each )
            {
                if( $this->getParameter( 'show_all_site_comments' ) )
                {
                    if( ! empty( $done[$each['article_url']] ) )
                    {
                        continue;
                    }
                    if( $each['article_url'] )
                    {
                        $done[$each['article_url']] = true;
                        if( $postInfo = Application_Article_View::loadPostData( $each ) )
                        {
                            $each += $postInfo;
                        }
                    }
                    
                }
                if( $counter++ > $limit )
                {
                    break;
                }

                $this->_objectData[] = $each;
                $this->_objectTemplateValues[] = $each; 
            //    self::v( $each );
                self::filterCommentData( $each );
                $each['creation_time'] = $filter->filter( $each['creation_time'] );
                $html .= '<li>
                            <div class="comment-main-level">
                                <!-- Avatar -->
                                <div class="comment-avatar"><a target="_blank" href="' . $each['website'] . '"><img src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_PhotoViewer/profile_url/' . $each['profile_url'] . '?max_width=300&max_height=300&extension=png" alt=""></a></div>
                                <!-- Contenedor del Comentario -->
                                <div class="comment-box">
                                    <div class="comment-head">
                                        <h6 class="comment-name "><a target="_blank" href="' . $each['website'] . '">' . $each['display_name'] . '</a> <span class="comment-user-level">' . $each['auth_level'] . '</span></h6>
                                        <span>' . $each['creation_time'] . '</span>
                                        ' . ( ( ! empty( Ayoola_Application::$GLOBAL['post'] ) && Application_Article_Abstract::isAllowedToEdit( Ayoola_Application::$GLOBAL['post'] ) ) || self::hasPriviledge( 98 ) ? '
                                        <a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_CommentBox_HideComment/?table_id=' . $each['table_id'] . '\' );" title="Hide Comment" onclick="" href="javascript:"><i class="fa fa-times"></i></a>
                                        ' : null ) . '
                                        <a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_CommentBox/?article_url=' . $where['article_url'] . '&url=' . $where['url'] . '&parent_comment=' . $each['table_id'] . '\' );" href="javascript:"><i class="fa fa-reply"></i></a>
                                       
                                    </div>
                                    <div class="comment-content">
                                        ' . nl2br( strip_tags( $each['comment'] ) ) . '
                                    </div>
                                </div>
                            </div>
				<!-- Respuestas de los comentarios -->
				<ul class="comments-list reply-list">';
                $where['parent_comment'] = $each['table_id'];
                $innerData = $this->getDbTable()->select( null, $where, array( '' => 'sdfc-wdwdd' ) );
         //    var_export( $this->getDbTable()->select( null, array( 'parent_comment' => $each['table_id'] ) ) );
       //     var_export( $innerData );
      //      var_export( $where );
                foreach( $innerData as $eachInnerData )
                {
                    $eachInnerData['creation_time'] = $filter->filter( $eachInnerData['creation_time'] );
                    self::filterCommentData( $eachInnerData );
                    $html .= '
                                <li>
                                    <!-- Avatar -->
                                    <div class="comment-avatar"><a target="_blank" href="' . $eachInnerData['website'] . '"><img src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_PhotoViewer/profile_url/' . $eachInnerData['profile_url'] . '?max_width=300&max_height=300&extension=png" alt=""></a></div>
                                    <!-- Contenedor del Comentario -->
                                    <div class="comment-box">
                                        <div class="comment-head">
                                            <h6 class="comment-name "><a target="_blank" href="' . $eachInnerData['website'] . '">' . $eachInnerData['display_name'] . '</a> <span class="comment-user-level">' . $eachInnerData['auth_level'] . '</span></h6>
                                            <span>' . $eachInnerData['creation_time'] . '</span>
                                            
                                        </div>
                                        <div class="comment-content">
                                            ' . nl2br( strip_tags( $eachInnerData['comment'] ) ) . '
                                        </div>
                                    </div>
                                </li>';
                }
                $html .= '</ul></li>';
				
            }
            $html .= '</ul></div>';

            $this->setViewContent( $html );
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( '<p class="badnews">Theres an error in the code</p>' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
