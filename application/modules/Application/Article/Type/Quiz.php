<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Quiz
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Quiz.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Quiz
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Quiz extends Application_Article_Type_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			$access = new Ayoola_Access();
		//	var_export( $_POST );
			//	self::v( $data );
			if( $_POST && @$_POST['article_url'] )
			{	
				//	Allow the identifierData to be loaded automatically
				$_GET['article_url'] = @$_POST['article_url'];
				if( ! $data = $this->getIdentifierData() ){ return false; }
				
				
				//	In case we have previously sent random data, lets use it for marking the results.
				if( $x = $this->getObjectStorage( 'random_questions_' . $data['article_url'] )->retrieve() )
				{ 
					$data = $x; 
				}
				else
				{
					throw new Application_Article_Type_Exception( "TEST DOESN'T HAVE A VALID SESSION" );
				}
		//		echo $data;
				//	Prepare result to send to client-side
				$dataToSend = array();
				
				//	In real test administration, this may not be needed
				$dataToSend['quiz_correct_option'] = $data['quiz_correct_option'];
				
				//	Retrieve the answered questions
				//	Send the score
				$dataToSend['link_to_result_sheet'] = 'http://' . Ayoola_Page::getDefaultDomain() . '' . strtolower( $data['article_url'] ) . '?' . http_build_query( array( 'a' => $_POST ) );
				
				$dataToSend['quiz_score'] = count( array_intersect_assoc( $data['quiz_correct_option'], $_POST ) );
			//	var_export( $data['quiz_correct_option'] );
		//		var_export( $_POST );
				$dataToSend['quiz_percentage'] = intval( ( $dataToSend['quiz_score'] / count( $data['quiz_correct_option'] ) ) * 100 );
				unset( $_POST['article_url'] );

				//	Send e-mail to the quiz provider
				//	Default is to send it to the admin
				$mailInfo['subject'] = 'Online Test Attempted';
				$mailInfo['body'] = 'A online test titled "' . $data['article_title'] . '", has been attempted by a user. ' . ( Ayoola_Application::getUserInfo( 'username' ) ? 'Username: ' . Ayoola_Application::getUserInfo( 'username' ) . ' Firstname: ' . Ayoola_Application::getUserInfo( 'firstname' ) . ' Lastname: ' . Ayoola_Application::getUserInfo( 'lastname' ) : null  ) . '. 
				
				The user scored ' . $dataToSend['quiz_score'] . '. You can view the result sheet of the test by clicking this link: ' . $dataToSend['link_to_result_sheet'] . '
				
				You may view, edit and administer the online test by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . strtolower( $data['article_url'] ) . '
				
				';
				if( $data['username'] )
				{
					$class = new Application_User_List();
					$class->setIdentifier( array( 'username' => $data['username'] ) );
					$userInfo = $class->getIdentifierData();
			//		self::v( $userInfo );
					$mailInfo['to'] = $userInfo['email'];
	/* 				$response = Ayoola_Api_UserList::send( array( 'user_id' => $data['user_id'] ) );
			//		var_export( $response );
					if( is_array( $response['data'] ) )
					{
						$response = $response['data'];
						$mailInfo['to'] = $response['email'];
					}
	 */			}
							//	var_export( $mailInfo );
				//	SEND THE CANDIDATE AN EMAIL IF HE IS LOGGED INN
				if( $access->isLoggedIn() )
				{
					$table = new Application_User_NotificationMessage();
					$emailInfo = $table->selectOne( null, array( 'subject' => 'Computer Based Test Results' ) ); 
					$values = array( 
										'firstname' => Ayoola_Application::getUserInfo( 'firstname' ), 
										'domainName' => Ayoola_Page::getDefaultDomain(), 
										'LINK_TO_RESULT_SHEET' => $dataToSend['link_to_result_sheet'], 
										'TOTAL_NO_OF_QUESTIONS' => count( $data['quiz_correct_option'] ), 
										'SCORE' => $dataToSend['quiz_score'], 
										'PERCENTAGE' => $dataToSend['quiz_percentage'] . '%', 
										'ARTICLE_LINK' => 'http://' . Ayoola_Page::getDefaultDomain() . '' . strtolower( $data['article_url'] ), 
									);
					$emailInfo = self::replacePlaceholders( $emailInfo, $values );
				//	var_export( $emailInfo );
					$emailInfo['to'] = Ayoola_Application::getUserInfo( 'email' );
					$emailInfo['from'] = 'no-reply@' . Ayoola_Page::getDefaultDomain();
					@self::sendMail( $emailInfo );
					
					//	Log into the database
					$table = new Application_Article_Type_Quiz_Table();
					$table->insert( array(
											'username' => Ayoola_Application::getUserInfo( 'username' ),
											'article_url' => $data['article_url'],
											'score' => $dataToSend['quiz_percentage'],
											'timestamp' => time(),
									) 
					);
					
					//	Status update
					$class = new Application_Status_Update();
					$status = array( 
										'status' => ( 'Scored ' . $dataToSend['quiz_percentage'] . '% in <a title="Click here to view the online test questions and answers" href="' . $data['article_url'] . '">' . $data['article_title'] . '.</a>' ), 
										'reference' => array
										(
											'article_url' => $data['article_url'],
									//		'score' => $dataToSend['quiz_percentage'],
										), 
									); 
					$parameters = array( 'fake_values' => $status );
					$class->setParameter( $parameters );  
					$class->fakeValues = $status; 
					$class->init();
				}

				try
				{
					@self::sendMail( $mailInfo );
				//	@Ayoola_Application_Notification::mail( $mailInfo );
				}
				catch( Ayoola_Exception $e ){ null; }
			//	$dataToSend = json_encode( $dataToSend );
				$this->_objectData = $dataToSend;
			//	$this->_playMode = static::PLAY_MODE_JSON;
			//	echo $dataToSend;
			//	exit();
	/* 			var_export( $gotRight );
				var_export( $_POST );
				var_export( $this->getIdentifierData() );
	 */			
				return false;
			}
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
			
			//	Client side
			//	Send JSON Object to client side
	//		$dataToSend = array( 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'],  );

			//	DONT ALLOW MORE THAN 50 QUESTIONS IN QUIZ
			//	self::v( $data ); 
			
			//	50 is not a default, we may set another value in the article editor
			if( empty( $data['quiz_max_no_of_question'] ) || intval( $data['quiz_max_no_of_question'] ) > 500 )
			{
				$data['quiz_max_no_of_question'] = 500;
			}
			elseif( intval( $data['quiz_max_no_of_question'] ) < 2 )
			{
				$data['quiz_max_no_of_question'] = 2;
			}
		//	self::v( $data );
			
			if( count( $data['quiz_question'] ) > $data['quiz_max_no_of_question'] && $data['quiz_max_no_of_question'] > 1 && ! $_POST )
			{
				//	When the max number of question is one, it causes issues here.
				$data['quiz_max_no_of_question'] = $data['quiz_max_no_of_question'] == 1 ? '2' : $data['quiz_max_no_of_question'];
				$randomKeys = array_rand( $data['quiz_question'], $data['quiz_max_no_of_question'] );
				shuffle( $randomKeys );
		//		var_export( $randomKeys );
				$randomKeys = array_combine( $randomKeys, $randomKeys );
			//	self::v( $data['quiz_question'] );
			//	self::v( $data['quiz_max_no_of_question'] );
			//	self::v( $randomKeys );
		//		var_export( $randomKeys );
			//	var_export( array_intersect_key( $data['quiz_question'], $randomKeys ) );  
				
				$data['quiz_question'] = array_values( array_intersect_key( $data['quiz_question'], $randomKeys ) );
				@$data['quiz_correct_option'] =  array_values( array_intersect_key( $data['quiz_correct_option'], $randomKeys ) );
				$data['quiz_option1'] =  array_values( array_intersect_key( $data['quiz_option1'], $randomKeys ) );
				$data['quiz_option2'] =  array_values( array_intersect_key( $data['quiz_option2'], $randomKeys ) );
				$data['quiz_option3'] =  array_values( array_intersect_key( $data['quiz_option3'], $randomKeys ) );
				$data['quiz_option4'] =  array_values( array_intersect_key( $data['quiz_option4'], $randomKeys ) );
			}
		//	self::v( $data );
			
			$dataToSend = $data;
			$dataToSend['container'] = $this->getParameter( 'question_container' ) ? : md5( __CLASS__ ); 
		//	var_export( $dataToSend );
			
			//	SAVE THIS QUESTIONS IN THE SESSION
		//	self::v( $data );
			$this->getObjectStorage( 'random_questions_' . $data['article_url'] )->store( $data );
			
			//	remove answers from data to send
			unset( $data['quiz_correct_option'] );
			if( is_array( @$_GET['a'] ) )
			{
				$dataToSend['a'] = $_GET['a'];
			}

		//	self::v( $dataToSend );
			$dataToSendJson = json_encode( $dataToSend );
			Application_Javascript::addCode
			( 
				'
				//	alert( "You are welcome..." );
				ayoola.post.quiz.container = "' . $dataToSend['container'] . '"; 
				ayoola.post.quiz.jsonObjectFromServerForInit = ' . $dataToSendJson . '; 
				
				//	Wait till this is loaded before user can click to start exam.
				document.getElementById( ayoola.post.quiz.container ).innerHTML = \'' . ( $this->getParameter( 'call_to_action' ) ? : '<button class="goodnews boxednews" onClick="ayoola.post.quiz.init( ayoola.post.quiz.jsonObjectFromServerForInit );">Total of ' . count( $data['quiz_question'] ) . ' questions loaded! Click here to start test... (' . Ayoola_Filter_Time::splitSeconds( $data['quiz_time'] ? : 0, 2 ) . ') </button>' ) . '\';
				' 
			); 
		//	self::v( $dataToSend );
			Application_Javascript::addFile( '/ayoola/js/post/quiz.js' );
			Application_Javascript::addFile( '/ayoola/js/form.js' );
			Application_Javascript::addFile( '/ayoola/js/countdown.js' );
		//	var_export( @$dataToSend['container']);
		//	$this->setViewContent( '<p>' . $data['article_description'] . '</p>' );   
			//	Prompt user to login before they continue test
			
		//	if( ! $access->isLoggedIn() )
			{ 
		//		$this->setViewContent( '<h2 class="badnews">Notice!</h2>' );
		//		$this->setViewContent( '<p class="badnews boxednews">To save your score and other information about this test, please login with your username and password before you start the test.</p>' );
		//		$this->setViewContent( Ayoola_Access_AccountRequired::viewInLine() );
			}
			$this->setViewContent
			( 
				'
				<div id="' . @$dataToSend['container'] . '">
					<button class="badnews boxednews" onClick="alert( \'Please wait while the question loads...\' );">Please wait while ' . count( $data['quiz_question'] ) . ' question loads...</button>
				</div>' 
			);
		
	//	var_export( $dataToSend );
//		$this->createForm( 'Continue', 'Quiz' );
	//	$form = $this->getForm()->view();
	//	$values = $this->getForm()->getValues();
	//	var_export( $_POST );
/* 		if( ! $values = $this->getForm()->getValues() )
		{ 
			//	var_export( 123 );
			//	show form
			
			$this->setViewContent( $form );
			//	return false; 
		}
		else
		{
			
		}
 */	//	$this->setViewContent( '<p>' . $data['article_description'] . '</p>' );
	//	var_export( $data );
	//	var_export( $pollData );
		}
		catch( Application_Article_Exception $e )
		{ 
		//	$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="badnews">Error with article package.</p>' ); 
		}
		catch( Exception $e )
		{ 
			//	self::v( $e->getMessage() );
		//	$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with article package.</p>' ); 
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
	public function createForm( $submitValue, $legend = null, Array $values = null )
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
