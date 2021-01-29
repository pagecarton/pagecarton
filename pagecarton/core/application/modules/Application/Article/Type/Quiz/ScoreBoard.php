<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Quiz_ScoreBoard
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Quiz.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract  
 */      
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_Quiz_ScoreBoard
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Quiz_ScoreBoard extends Application_Article_Type_Quiz
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
		try
		{
			if( ! $data = self::getIdentifierData() ){ return false; }
			
			//	Only the valid editor can view scoreboard
			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::isOwner( $data['user_id'] ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ) && Ayoola_Application::getUserInfo( 'username' ) !== strtolower( $data['username'] ) )
			{ 
				return false; 
			}
			
			$sortFunction2 = create_function
			( 
				'& $key, & $values', 
				'
					if( ! $values["username"] )
					{
						$values = false;
						return false;
					}
					$table = Ayoola_Access_LocalUser::getInstance();
					if( $info = $table->selectOne( null, array( "username" => strtolower( $values["username"] ) ) ) )
					{ 
						if( $info["user_information"] )  
						{
							$info = $info["user_information"];  
						}
					}
					$values = ( $values ? : array() ) + ( ( $info ) ? : array() );
				'
			); 
			$table = Application_Article_Type_Quiz_Table::getInstance();
			
			$scores = array();
		//	if( Ayoola_Application::getUserInfo( 'username' ) )
			{
				$scores = $table->select( null, array( 'article_url' => $_REQUEST['article_url'] ), array( 'result_filter_function' => $sortFunction2, 'disable_cache' => true ) );
			}
			$scores = self::sortMultiDimensionalArray( $scores, $this->getParameter( 'sort_column' ) ? : 'timestamp' );
			krsort( $scores );     
			
			require_once 'Ayoola/Paginator.php';
			$list = new Ayoola_Paginator();
			$list->pageName = $this->getObjectName();
			$list->listTitle = 'Score board for - "' . $data['article_title'] . '"';
			$list->setData( $scores );
			$list->setKey( $this->getIdColumn() );
			$list->setNoRecordMessage( 'No one has attempted this test yet.' );
			$list->createList
			(  
				array(  
					'username' => '%FIELD%', 
					'email' => '%FIELD%', 
					'firstname' => '%FIELD%', 
					'lastname' => '%FIELD%', 
					'score' => '%FIELD%', 
					'timestamp' => array( 'filter' => 'Ayoola_Filter_Time', ),    
				)
			);
			$this->setViewContent( $list, true );  
		}
		catch( Application_Article_Exception $e )
		{ 
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}
		catch( Exception $e )
		{ 
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}
	
    }		
	// END OF CLASS
}
