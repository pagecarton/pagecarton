<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_ViewPagination
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';    


/**
 * @category   PageCarton CMS
 * @package    Application_Article_ViewPagination
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_ViewPagination extends Application_Article_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'View Post Pagination'; 
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'article_name',  );

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			//	var_export( $this->getIdentifierData() ); 
			if( ! $data = $this->getIdentifierData() )
			{
				return false;				
			}
			//	self::v( $data ); 
			if( ! self::isAllowedToView( $data ) )
			{				
				return false;
			}
		//	self::v( $data ); 

			{
				$pagination = null;
                if( ! empty( $_REQUEST['pc_post_list_id'] ) )
                {
                    $postListId = $_REQUEST['pc_post_list_id'];
                }
                else
                {
					//	Prepare post viewing for next posts
					$storageForSinglePosts = self::getObjectStorage( array( 'id' => 'post_list_id' ) );
					
					$postListId = $storageForSinglePosts->retrieve();
		//		var_export( $postListId );
					if( ! $postListId )
					{
						$class = new Application_Article_ShowAll( array( 'true_post_type' => $data['true_post_type'] ) );
						$class->initOnce();
						$postListId = $storageForSinglePosts->retrieve();
					}
                }

				$postListData = Application_Article_ShowAll::getObjectStorage( array( 'id' => $postListId, 'device' => 'File' ) );
		//		var_export( $postListId );
		//		var_export( $postListData );
				$postListData = $postListData->retrieve();
	//			var_export( $postListData );
				if( ! empty( $postListData['single_post_pagination'] ) )
				{
					$presentArticle = $data['article_url'];
                    do
                    {
                //     var_export( $postList['single_post_pagination'] );
						$postList = $postListData['single_post_pagination'][$presentArticle];
       //      			var_export( $postList['pc_next_post'] );
       	//	            var_export( $postListData['single_post_pagination'] );
/*						if( empty( $postList['pc_next_post'] ) )
						{
             			var_export( $postListData['single_post_pagination'] );
							$postListNew = array_shift( $postListData['single_post_pagination'] );
             			var_export( $postListNew );
							$postList['pc_next_post'] = $postListNew['pc_next_post'];
						}
             	//		var_export( $postList );
*/                     	if( ! @$postList['article_type'] || ! @$postList['pc_next_post'] )
						{
							break;
						}
                       	$postData = self::loadPostData( $postList );
						$presentArticle = $postList['pc_next_post'];
			//			var_export( );
                    }
                    while( $postData['article_type'] !== $data['article_type'] );
					if( ! empty( $postList['pc_next_post'] ) )
					{
						if( $nextPost = self::loadPostData( $postList['pc_next_post'] ) )
						{
							$this->_objectTemplateValues['paginator_next_page'] = Ayoola_Application::getUrlPrefix() . $postList['pc_next_post'];
							$this->_objectTemplateValues['paginator_next_page_button'] = '<a onclick="this.href=this.href + location.search;" class="pc_paginator_next_page_button pc-btn" href="' . $this->_objectTemplateValues['paginator_next_page'] . '">"' . $nextPost['article_title'] . '" Next  &rarr; </a>';       
						}
			//			var_export( $nextPost );

					}
					if( ! empty( $postList['pc_previous_post'] ) )
					{
						if( $previousPost = self::loadPostData( $postList['pc_previous_post'] ) )
						{
							$this->_objectTemplateValues['paginator_previous_page'] = Ayoola_Application::getUrlPrefix() . $postList['pc_previous_post'];
							$this->_objectTemplateValues['paginator_previous_page_button'] = '<a onclick="this.href=this.href + location.search;" class="pc_paginator_previous_page_button pc-btn" href="' . $this->_objectTemplateValues['paginator_previous_page'] . '"> &larr; Previous "' . $previousPost['article_title'] . '"</a>';
						}
					}
					$pagination .= @$this->_objectTemplateValues['paginator_previous_page_button'];
					$pagination .= @$this->_objectTemplateValues['paginator_next_page_button'];			
					$pagination = '<div class="pc_posts_distinguish_sets" id="' . $postListId . '">' . $pagination . '</div>';

				}
			//	var_export( $postList );
				$this->setViewContent( $pagination );
			}

		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
			return $this->setViewContent( '<p class="badnews">Error with article package.</p>' ); 
		}
	//	var_export( $this->_xml );
    } 
	// END OF CLASS
}
