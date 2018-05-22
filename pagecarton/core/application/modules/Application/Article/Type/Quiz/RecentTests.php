<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
	protected static $_accessLevel = 1;
	
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
			$table = Application_Article_Type_Quiz_Table::getInstance();
			
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
					$filename = Application_Article_Abstract::getFolder() . $values["article_url"];
					if( ! is_file( $filename ) )
					{
						$key = false;
						$values = false;
						return false;
					}
					$values = ( $values ? : array() ) + ( ( @include $filename ) ? : array() );
					
					//	If Score is not available
					if( empty( $values["quiz_options"] ) )
					{
					
					}
					elseif( in_array( "no_correction", $values["quiz_options"] ) || in_array( "hide_result", $values["quiz_options"] ) )   
					{
					//	var_export( $values["score"] );
						$values["score"] = "NA";
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
			
			
			  
/* 			require_once 'Ayoola/Paginator.php';
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
					'score' => '%FIELD%', 
					'timestamp' => array( 'filter' => 'Ayoola_Filter_Time', ),    
				)
			);
 */			//var_export( $list );
		//	return $list;
		//	$this->setViewContent( $list, true );  
		}
/* 	//	catch( Application_Article_Exception $e )
		{ 
		//	$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="badnews">Error with article package.</p>' ); 
		}
	//	catch( Exception $e )
		{ 
			//	self::v( $e->getMessage() );
		//	$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with article package.</p>' ); 
		}
 */	
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
