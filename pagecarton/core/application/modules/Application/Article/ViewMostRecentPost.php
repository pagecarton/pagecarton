<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_ViewMostRecentPost
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ViewMostRecentPost.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_ViewMostRecentPost
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_ViewMostRecentPost extends Application_Article_View
{
	
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
				if( ! $all = $this->getDbData() )
				{
					return false;
				}

				//	make sure the recent ones are on top
				arsort( $all );
		//		var_export( $all );		      		
				$i = 0;
				
				//	Try to find a post 10 times
				while( $i < 10 )
				{
					$i++;
					$each = array_shift( $all );
			//	var_export( $key );
			//		$data = $data[$key];
					
			//		$data = @include $each;
				//	var_export( $each );
					$data = Application_Article_Abstract::loadPostData( $each );
				//	var_export( $data );
					$data['auth_level'] = array_map( 'intval', (array) $data['auth_level'] );
					if( 
						(  empty( trim( $data['publish'] ) ) && ! in_array( 'publish', @$data['article_options'] ) ) //	not published
						|| 
						! in_array( 0, $data['auth_level'] ) //	Not public
					)
					{	
						$data = false;
						continue;
					}
					else
					{
						//	We found a post
						break;
					}
				}
		//		$storage->store( $data );
		
			if( $data )
			{
				$this->setParameter( array( 'article_url' => $data['article_url'] ) );   
				$this->setIdentifierData();
				parent::init();
			}
 
 		}
		catch( Exception $e )
		{ 
			$this->setViewMostRecentPostContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
			return $this->setViewMostRecentPostContent( '<p class="badnews">Error with article package.</p>' ); 
		}
	//	var_export( $this->_xml );
    } 
	
	// END OF CLASS
}
