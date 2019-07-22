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
		//	var_export( Application_HashTag_Abstract::get( 'articles' ) );
			
			//	Only the valid editor can view scoreboard
			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::isOwner( $data['user_id'] ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ) && Ayoola_Application::getUserInfo( 'username' ) !== $data['username'] )
			{ 
			//	var_export( Ayoola_Application::getUserInfo( 'username' ) );
		//		var_export( Ayoola_Application::$GLOBAL['username'] );
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
			//		if( $info = $table->selectOne( null, array( "username" => strtolower( $values["username"] ) ), array( "disable_cache" => true ) ) )
					{ 
						if( $info["user_information"] )  
						{
							$info = $info["user_information"];  
						}
					}
					else
					{
						$values = false;  
						return false;
					}
					$values = ( $values ? : array() ) + ( ( $info ) ? : array() );
				'
			); 
			$table = Application_Article_Type_Quiz_Table::getInstance();
			
			$scores = array();
			if( Ayoola_Application::getUserInfo( 'username' ) )
			{
				$scores = $table->select( null, array( 'article_url' => $_REQUEST['article_url'] ), array( 'result_filter_function' => $sortFunction2, 'disable_cache' => true ) );
		//		$scores = $table->select( null, array( 'article_url' => $_REQUEST['article_url'] ), array( 'result_filter_function' => $sortFunction2 ) );
		//		var_export( $scores );      
			}
			$scores = self::sortMultiDimensionalArray( $scores, $this->getParameter( 'sort_column' ) ? : 'timestamp' );
			krsort( $scores );     
			
		//	self::v( $scores );
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
			//var_export( $list );
		//	return $list;
			$this->setViewContent( $list, true );  
		}
		catch( Application_Article_Exception $e )
		{ 
		//	$this->_parameter['markup_template'] = null;
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		//	return $this->setViewContent( self::__( '<p class="badnews">Error with article package.</p>' ) ); 
		}
		catch( Exception $e )
		{ 
			//	self::v( $e->getMessage() );
		//	$this->_parameter['markup_template'] = null;
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		//	return $this->setViewContent( self::__( '<p class="blockednews badnews centerednews">Error with article package.</p>' ) ); 
		}
	
    } 
	
    /**
     * Used to sanitize a status update
     * 
     */
/* 	public function sanitizeStatus( $statusInfo )
    {
		$statusInfo
	}
 */	
    /**
     * Form to display poll
     * 
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = true;
		$form->submitValue = $submitValue ;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		$pollData = $this->getParameter( 'data' );
		$pollData['poll_options'] = is_array( $pollData['poll_options'] ) ? array_combine( array_map( 'self::getOptionId', $pollData['poll_options'] ), $pollData['poll_options'] ) : array();
//		var_export( $pollData['poll_options'] );
		
		//	Question
		$fieldset->addElement( array( 'name' => 'poll_answer', 'label' => @$pollData['poll_question'], 'type' => 'Radio', 'value' => @$values['poll_answer'] ), $pollData['poll_options'] );
		$fieldset->addElement( array( 'name' => 'article_url', 'type' => 'Hidden', 'value' => @$pollData['article_url'] ) );
	//	$fieldset->addRequirement( 'poll_answer', array( 'ArrayKeys' => $pollData['poll_options'] ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );

    } 
	
	// END OF CLASS
}
