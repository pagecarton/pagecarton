<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_ViewRandomPost
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ViewRandomPost.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_ViewRandomPost
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_ViewRandomPost extends Application_Article_View
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
			//	Randomize every day
			$storage = $this->getObjectStorage( array( 'id' => $this->getParameter( 'time_out' ) ? : date( 'd M Y' ), 'device' => 'File', 'time_out' => $this->getParameter( 'time_out' ) ? : 86400, ) );
			if( $data = $storage->retrieve() )
			{ 
				 
			}
			else
			{
				if( ! $data = $this->getDbData() )
				{
					return false;
				}
				
				$i = 0;
				
				//	Try to find a post 10 times
				while( $i < 10 )
				{
					$i++;
					$key = array_rand( $data );
			//	var_export( $key );
					$data = $data[$key];
					
				//	$data = @include $data;
					$data = Application_Article_Abstract::loadPostData( $data );
					$data['auth_level'] = array_map( 'intval', (array) $data['auth_level'] );
					if( 
						(  empty( $data['publish'] ) && ! in_array( 'publish', @$data['article_options'] ) ) //	not published
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
				$storage->store( $data );
			}
	//		var_export( array( 'article_url' => $data ) );
			
/*  			$class = new Application_Article_View();
			$class->setParameter( $this->getParameter() +  );
			$class->setIdentifierData();
			$class->init();
			
 */			
			if( $data )
			{
				$this->setParameter( array( 'data' => $data ) );   
				$this->setIdentifierData();
				parent::init();
			}
 
 		}
		catch( Exception $e )
		{ 
			$this->setViewRandomPostContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
			return $this->setViewRandomPostContent( '<p class="badnews">Error with article package.</p>' ); 
		}
	//	var_export( $this->_xml );
    } 
	
	// END OF CLASS
}
