<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
				//	Site Wide Storage of this value so we don't have to worry about session timeouts
				$storageNamespace = 'random_questions_' . $data['article_url'] . @$_POST['question_type'];
				$storage = $this->getObjectStorage( array( 'id' => $storageNamespace, 'device' => 'File', 'time_out' => 99999, ) );
				if( $x = $storage->retrieve() )
				{ 
					$data = $x; 
				}
				else
				{
					return false;
				//	throw new Application_Article_Type_Exception( "TEST DOESN'T HAVE A VALID SESSION" );
				}
				
/* 				do
				{
					$billExaminer = true;  //	Bill the examiner for? Add to settings later for flexibility.
					if( ! $billExaminer  )
					{
						break;
					}
					
					//	Bill only in private exams. Add to settings later for flexibility.
					if( ! in_array( 97, array_map( 'intval', (array) $data['auth_level'] ) ) )
					{
						break;
					}
					
					// bills
					// bill the user
					// send to the admin
					$transferInfo['to'] = 'joywealth';
				//	$transferInfo['from'] = Ayoola_Application::getUserInfo( 'username' );
					$transferInfo['from'] = $data['username'];
					$transferInfo['amount'] = '1000';
					$transferInfo['notes'] = 'Test fees for "' . Ayoola_Application::getUserInfo( 'email' ) . '" from ' . $data['username'] . '. Test title is "' . $data['article_title'] . '".' ;
					if( ! Application_Wallet::transfer( $transferInfo ) )
					{
						return false;
					}
					
					
				}
				while( false );
 */				
		//		echo $data;
				//	Prepare result to send to client-side
				$dataToSend = array();
				
				//	In real test administration, this may not be needed
				if( ! in_array( 'no_correction', $data['quiz_options'] ) && ! in_array( 'hide_result', $data['quiz_options'] )  )   
				{
					$dataToSend['quiz_correct_option'] = $data['quiz_correct_option'];
				}
				$data['quiz_score'] = count( array_intersect_assoc( $data['quiz_correct_option'], $_POST ) );
				$data['quiz_percentage'] = intval( ( $data['quiz_score'] / count( $data['quiz_correct_option'] ) ) * 100 );
				
				
				//	Save Test Scores in DB
				if( ! empty( $data['quiz_correct_option'] ) )   
				{
					//	Log into the database
					$table = Application_Article_Type_Quiz_Table::getInstance();
					$table->insert( array(
											'username' => strtolower( Ayoola_Application::getUserInfo( 'username' ) ),
											'article_url' => $data['article_url'],
											'score' => $data['quiz_percentage'],
											'timestamp' => time(),
									) 
					);   
				}
				
				//	Retrieve the answered questions
				//	Send the score
				$dataToSend['link_to_result_sheet'] = 'http://' . Ayoola_Page::getDefaultDomain() . '' . strtolower( $data['article_url'] ) . '?' . http_build_query( array( 'a' => $_POST ) );
				
				if( 
						! empty( $data['quiz_correct_option'] ) 
				//		&& ! in_array( 'no_correction', $data['quiz_options'] ) 
						&& ! in_array( 'hide_result', $data['quiz_options'] ) 
				)
				{
				//	var_export( $data['quiz_correct_option'] );
			//		var_export( $_POST );
					unset( $_POST['article_url'] );
					
			//		var_export( $data );
					$dataToSend['quiz_score'] = $data['quiz_score'];
					$dataToSend['quiz_percentage'] = $data['quiz_percentage'];

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
					}
								//	var_export( $mailInfo );
					//	SEND THE CANDIDATE AN EMAIL IF HE IS LOGGED INN
					if( $access->isLoggedIn() )
					{
						$table = Application_User_NotificationMessage::getInstance();
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
			
				}

				return false;
			}
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
			//	self::v( $data ); 
			if( 
				! is_array( $data ) || 
				! self::isAllowedToView( $data )
			)
			{
				
				return $this->setViewContent(  '' . self::__( '<p class="badnews">The requested article was not found on the server. Please check the URL and try again. ' . self::getQuickLink() . '</p>' ) . '', true  );
			//	self::setIdentifierData( $data );
			}
			
			//	Client side
			//	Send JSON Object to client side
	//		$dataToSend = array( 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'], 'quiz_question' => $data['quiz_question'],  );
		
			//	init this so that we can just build them up per group
			$testInfo = array();
			$testInfo['quiz_question'] = $testInfo['quiz_correct_option'] = $testInfo['quiz_option1'] = $testInfo['quiz_option2'] = $testInfo['quiz_option3'] = $testInfo['quiz_option4'] = array();
			$i = 0;
			$randomKeys = array();
			if( ! $_POST )
			{
				while( $i <= count( @$data['quiz_subgroup_id'] ) && $i < 9 )
				{
					$eachGroupId = @$data['quiz_subgroup_id'][$i];
					if( empty( $data['quiz_subgroup_id'] ) && empty( $data['quiz_subgroup_question_max'][$i] ) ) 
					{

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
						//	compatibility
						//	Let old test go through this
						$data['quiz_subgroup_question_max'][$i] = $data['quiz_max_no_of_question'];
					}
					if( empty( $data['quiz_subgroup_question_max'][$i] ) || ! count( $data['quiz_question' . $eachGroupId] ) ) 
					{
						$i++;
						continue;
					}
					elseif( $data['quiz_subgroup_question_max'][$i] > count( $data['quiz_question' . $eachGroupId] ) ) 
					{
						$data['quiz_subgroup_question_max'][$i] = count( $data['quiz_question' . $eachGroupId] );
					}
			//		var_export( 'quiz_question' . $eachGroupId . "<br> \r\n" );
				//	var_export( count( $data['quiz_question' . $eachGroupId] ) );
				//	var_export( $data['quiz_subgroup_question_max'][$i] );
					
					$randomKeys = (array) array_rand( $data['quiz_question' . $eachGroupId], $data['quiz_subgroup_question_max'][$i] );
				//	var_export( $randomKeys );  
					shuffle( $randomKeys );
					
			//		var_export( $randomKeys );
					$randomKeys = array_combine( $randomKeys, $randomKeys );
					
					//	Take care of group questions
					$questions = array_values( array_intersect_key( $data['quiz_question' . $eachGroupId], $randomKeys ) );				
					if( ! trim( @$data['quiz_subgroup_question'][$i] ) )
					{
					//	var_export( $data['quiz_subgroup_question'][$i] . "\r\n" );
						foreach( $questions as &$eachQuestion )
						{
							$eachQuestion = '<blockquote>' . $data['quiz_subgroup_question'][$i] . "</blockquote>\r\n" . $eachQuestion . "\r\n <br>";
						}
					}
					else
					{
					
					}
					$testInfo['quiz_question'] = array_merge( $testInfo['quiz_question'], $questions );
					@$testInfo['quiz_correct_option'] =  array_merge( $testInfo['quiz_correct_option'], array_values( array_intersect_key( $data['quiz_correct_option' . $eachGroupId], $randomKeys ) ) );
					$testInfo['quiz_option1'] =  array_merge( $testInfo['quiz_option1'], array_values( array_intersect_key( $data['quiz_option1' . $eachGroupId], $randomKeys ) ) );
					$testInfo['quiz_option2'] =  array_merge( $testInfo['quiz_option2'], array_values( array_intersect_key( $data['quiz_option2' . $eachGroupId], $randomKeys ) ) );
					$testInfo['quiz_option3'] =  array_merge( $testInfo['quiz_option3'], array_values( array_intersect_key( $data['quiz_option3' . $eachGroupId], $randomKeys ) ) );
					$testInfo['quiz_option4'] =  array_merge( $testInfo['quiz_option4'], array_values( array_intersect_key( $data['quiz_option4' . $eachGroupId], $randomKeys ) ) );
					
					$i++;
				}
			}
		//	self::v( $data );
			
			$dataToSend = array_merge( $data, $testInfo );
			$dataToSend['container'] = $this->getParameter( 'question_container' ) ? : md5( __CLASS__ ); 
			$dataToSend['question_type'] = md5( serialize( $testInfo ) ); 
		//	var_export( $dataToSend );
			
			//	SAVE THIS QUESTIONS IN THE SESSION
		//	self::v( $data );
			//	In case we have previously sent random data, lets use it for marking the results.
			//	Site Wide Storage of this value so we don't have to worry about session timeouts
			$storageNamespace = 'random_questions_' . $data['article_url'] . @$dataToSend['question_type'];
			$storage = $this->getObjectStorage( array( 'id' => $storageNamespace, 'device' => 'File', 'time_out' => 99999, ) );
			$storage->store( $dataToSend );
			
			//	remove answers from data to send
			unset( $dataToSend['quiz_correct_option'] );
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
				document.getElementById( ayoola.post.quiz.container ).innerHTML = \'' . ( $this->getParameter( 'call_to_action' ) ? : '<button class="goodnews boxednews" onClick="ayoola.post.quiz.init( ayoola.post.quiz.jsonObjectFromServerForInit );">Total of ' . count( $testInfo['quiz_question'] ) . ' questions loaded! Click here to start test... (' . Ayoola_Filter_Time::splitSeconds( $dataToSend['quiz_time'] ? : 0, 2 ) . ') </button>' ) . '\';
				' 
			); 
		//	self::v( $dataToSend );
			Application_Javascript::addFile( '/ayoola/js/post/quiz.js' );
			Application_Javascript::addFile( '/ayoola/js/form.js' );
			Application_Javascript::addFile( '/ayoola/js/countdown.js' );
		//	var_export( @$dataToSend['container']);
		//	$this->setViewContent( self::__( '<p>' . $data['article_description'] . '</p>' ) );   
			//	Prompt user to login before they continue test
			
		//	if( ! $access->isLoggedIn() )
			{ 
		//		$this->setViewContent( self::__( '<h2 class="badnews">Notice!</h2>' ) );
		//		$this->setViewContent( self::__( '<p class="badnews boxednews">To save your score and other information about this test, please login with your username and password before you start the test.</p>' ) );
		//		$this->setViewContent( Ayoola_Access_AccountRequired::viewInLine() );
			}
			$this->setViewContent
			( 
				'
				<div id="' . @$dataToSend['container'] . '">
					<button class="badnews boxednews" onClick="alert( \'Please wait while the question loads...\' );">Please wait while ' . count( $dataToSend['quiz_question'] ) . ' question loads...</button>
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
 */	//	$this->setViewContent( self::__( '<p>' . $data['article_description'] . '</p>' ) );
	//	var_export( $data );
	//	var_export( $pollData );
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
