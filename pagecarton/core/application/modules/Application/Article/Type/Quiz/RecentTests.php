<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Quiz_RecentTests
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: RecentTests.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract  
 */      
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_Quiz_RecentTests
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Quiz_RecentTests extends Application_Article_Type_Quiz
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1, 98 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	try
		{
		//	if( ! $data = self::getIdentifierData() ){ return false; }
		//	var_export( Application_HashTag_Abstract::get( 'articles' ) );
			
			//	Only the valid editor can view scoreboard
			//	Check settings
/* 			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::isOwner( $data['user_id'] ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ) ){ return false; }
 */			
			$table = Application_Article_Type_Quiz_Table::getInstance( );
			
			//	Filter the result to save time
			$sortFunction2 = create_function
			( 
				'& $key, & $values', 
				'
				//	var_export( $key );
				//	var_export( $values );
					$key = $values["article_url"];
				//	$values = $values["article_url"];
					$values["allow_raw_data"] = true;
					$postInfo = Application_Article_Abstract::loadPostData( $values["article_url"] );
/* 					$filename = Application_Article_Abstract::getFolder() . $values["article_url"];
					if( ! is_file( $filename ) )
					{
						$key = false;
						$values = false;
						return false;
					}
 */					
					$values = ( is_array( $values ) ? $values : array() ) + ( is_array( $postInfo ) ?  $postInfo : array() );
					
					//	If Score is not available
					if( empty( $values["quiz_options"] ) )
					{
					
					}
					elseif( in_array( "no_correction", $values["quiz_options"] ) || in_array( "hide_result", $values["quiz_options"] ) )   
					{
					//	var_export( $values["score"] );
						$values["score"] = "";
					}
			//		var_export( $values );
				'
			); 
			$scores = array();
			if( Ayoola_Application::getUserInfo( 'username' ) )
			{
				$scores = $table->select( null, array( 'username' => Ayoola_Application::getUserInfo( 'username' ) ), array( 'result_filter_function' => $sortFunction2 ) );
			}
		//	self::v( $scores );
		//	$scores = $table->select( null, array( 'username' => Ayoola_Application::getUserInfo( 'username' ) ), );
			
			//	Show it with the regular class
			$class = new Application_Article_ShowAll( array( 'no_init' => true ) );
			$class->setDbData( $scores );
			$class->setParameter( $this->getParameter() );
			$class->init();  
			
			//	Return the favor
			$this->setParameter( $class->getParameter() );
			
			
			  
		}
    } 
	
	
	// END OF CLASS
}
