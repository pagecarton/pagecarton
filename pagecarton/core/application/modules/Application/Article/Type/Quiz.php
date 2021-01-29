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

            if( $_POST && @$_POST['article_url'] )
			{	
				//	Allow the identifierData to be loaded automatically         
                $this->setParameter( array( 'article_url' => $_POST['article_url'] ) );
                if( ! $data = $this->getIdentifierData() )
                { 
                    return false; 
                }
				
				
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
				}
		
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
				$dataToSend['link_to_result_sheet'] = 'http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . strtolower( $data['article_url'] ) . '?' . http_build_query( array( 'a' => $_POST ) );
				
				if( 
						! empty( $data['quiz_correct_option'] ) 
						&& ! in_array( 'hide_result', $data['quiz_options'] ) 
				)
				{
					unset( $_POST['article_url'] );
					
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
						$mailInfo['to'] = $userInfo['email'];
					}
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
					}
					catch( Ayoola_Exception $e ){ null; }
					$this->_objectData = $dataToSend;
			
				}

				return false;
			}
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();

			if( 
				! is_array( $data ) || 
				! self::isAllowedToView( $data )
			)
			{
				
				return $this->setViewContent(  '' . self::__( '<p class="badnews">The requested article was not found on the server. Please check the URL and try again. ' . self::getQuickLink() . '</p>' ) . '', true  );
			}
			
			//	Client side
			//	Send JSON Object to client side
		
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

						
						//	50 is not a default, we may set another value in the article editor
						if( empty( $data['quiz_max_no_of_question'] ) || intval( $data['quiz_max_no_of_question'] ) > 500 )
						{
							$data['quiz_max_no_of_question'] = 500;
						}
						elseif( intval( $data['quiz_max_no_of_question'] ) < 2 )
						{
							$data['quiz_max_no_of_question'] = 2;
						}

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

					
					$randomKeys = (array) array_rand( $data['quiz_question' . $eachGroupId], $data['quiz_subgroup_question_max'][$i] );

					shuffle( $randomKeys );

					$randomKeys = array_combine( $randomKeys, $randomKeys );
					
					//	Take care of group questions
					$questions = array_values( array_intersect_key( $data['quiz_question' . $eachGroupId], $randomKeys ) );				
					if( ! trim( @$data['quiz_subgroup_question'][$i] ) )
					{

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

			
			$dataToSend = array_merge( $data, $testInfo );
			$dataToSend['container'] = $this->getParameter( 'question_container' ) ? : md5( __CLASS__ ); 
			$dataToSend['question_type'] = md5( serialize( $testInfo ) ); 
			
			//	SAVE THIS QUESTIONS IN THE SESSION
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
            $this->setViewContent(  '<h3 class="pc_give_space_top_bottom">' . sprintf( self::__( '%s Quiz' ), $data['article_title'] ) . '</h3>', true  );
            $options = null;

            if( count( $dataToSend['quiz_question'] ) )
            {
                $timeX = Ayoola_Filter_Time::splitSeconds( $dataToSend['quiz_time'] ? : 0, 2 );
                $timeString = $timeX ? ( '(' . $timeX . ')' ) : null;
                $dataToSendJson = json_encode( $dataToSend );
                $options .= '<a style="flex-basis: 50%;" href="javascript:" class="pc-btn" onClick="if( ayoola.post.quiz.jsonObjectFromServerForInit ){ ayoola.post.quiz.init( ayoola.post.quiz.jsonObjectFromServerForInit ); this.parentNode.removeChild( this );}">Start Quiz...</a>';
                $this->setViewContent(  '<p class="pc_give_space_top_bottom">
                Total Questions: ' . count( $dataToSend['quiz_question'] ) . '<br>
                Allocated Time: ' . $timeX . '<br>
                </p>'  );
    
                Application_Javascript::addCode
                ( 
                    '
    
                    ayoola.post.quiz.container = "' . $dataToSend['container'] . '"; 
                    ayoola.post.quiz.jsonObjectFromServerForInit = ' . $dataToSendJson . '; 
                    
                    //	Wait till this is loaded before user can click to start exam.
                    document.getElementById( ayoola.post.quiz.container ).innerHTML = \'\';
                    ' 
                ); 
                Application_Style::addCode
                ( 
                    '
                        .pc_quiz_timer
                        {
                            font-size:3em;
                        }
                    ' 
                ); 
                Application_Javascript::addFile( '/ayoola/js/post/quiz.js' );
                Application_Javascript::addFile( '/ayoola/js/form.js' );
                Application_Javascript::addFile( '/ayoola/js/countdown.js' );
                $this->setViewContent
                ( 
                    '
                    <div id="' . @$dataToSend['container'] . '">
                        <p class="pc-notify-info" onClick="alert( \'Please wait while quiz questions load...\' );">Please wait while quiz questions load completely...</p>
                    </div>' 
                );
            }
            else
            {
                $this->setViewContent(  '<p class=" pc_give_space_top_bottom badnews">' . self::__( 'There are no questions set for this quiz yet' ) . '</p>' );
            }
            if( self::hasPriviledge( $data['questions_auth_level'] ) || self::isAllowedToEdit( $data ) )
            {
                $options .= '<a style="flex-basis: 50%;" class="pc-btn" href="javascript:"  onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Type_Quiz_AddQuestion/?article_url=' . $data['article_url'] . '\', \'page_refresh\' );">' . self::__( 'Contribute Question' ) . '</a>';
            }
            if( self::isAllowedToEdit( $data ) )
            {
                $options .= ' <a style="flex-basis: 50%;" class="pc-btn" href="javascript:"  onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Editor/?article_url=' . $data['article_url'] . '\', \'page_refresh\' );">' . self::__( 'Manage Quiz' ) . '</a>';
                $options .= ' <a style="flex-basis: 50%;" class="pc-btn" href="javascript:"  onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Type_Quiz_ScoreBoard/?article_url=' . $data['article_url'] . '\' );">' . self::__( 'Score Board' ) . '</a>';
            }

            

            $this->setViewContent(  '<p style="display:flex;" class="pc_give_space_top_bottom">' . $options . '</p>'  );
		
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
