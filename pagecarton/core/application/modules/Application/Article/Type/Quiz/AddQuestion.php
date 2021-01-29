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

class Application_Article_Type_Quiz_AddQuestion extends Application_Article_Type_Quiz
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
            if( ! $data = self::getIdentifierData() ){ return false; }
            if( ! self::hasPriviledge( $data['questions_auth_level'] ) && ! self::isAllowedToEdit( $data ) )
            {
                return false;
            }
			
            if( ! $this->requireRegisteredAccount() )
            {
                return false;
            }
			if( ! $this->requireProfile() )
			{
				return false;
			}
			
			$this->createForm( 'Save Questions...', 'Add a question to "' . $data['article_title'] . '"', $data );
			$this->setViewContent( $this->getForm()->view() );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
            
            $newData = $data;

            $invalidQuestions = array();
            foreach( $values['quiz_question'] as $key => $question )
            {
                if( ! trim( $question ) )
                {
                    $invalidQuestions[] = $key;
                }
            } 

            $lastCount = null;
            foreach( $values as $key => $value )
            {
                if( ! is_array( $data[$key] ) )
                {
                    $data[$key] = array();
                }

                //  remove data of invalid questions
                foreach( $invalidQuestions as $each )
                {
                    unset( $values[$key][$each] );
                }

                if( ! empty( $_GET['all'] ) )
                {
                    $data[$key] = $value;
                }
                else
                {
                    $data[$key] = array_merge( $data[$key], $values[$key] );
                }
                if( is_int( $lastCount ) )
                {
                    if( $lastCount !== count( $data[$key] ) )
                    {
                        //  data corrupt?
                        $data = false;
                        break;
                    }
                }
                $lastCount = count( $data[$key] );
            }
            
            if( $data  )
            {
                
                self::saveArticle( $data );


                //	Send e-mail to the quiz provider
                $mailInfo['subject'] = 'Quiz Question Contribution';
                $mailInfo['body'] = 'A online quiz titled "' . $data['article_title'] . '", has a new question set by a contributor. 
                
                ' . ( Ayoola_Application::getUserInfo( 'username' ) ? '
                ...
                CONTRIBUTOR DETAILS
                ...

                Username: ' . Ayoola_Application::getUserInfo( 'username' ) . ' 
                Email: ' . Ayoola_Application::getUserInfo( 'email' ) : null  ) . '
                
                ...
                CONTRIBUTION
                ...
                
                ' . var_export( $values, true ) . '
                                
                You may view, edit and administer the online test by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . strtolower( $data['article_url'] ) . '
                
                ';
                $mailInfo['to'] = Ayoola_Application::getUserInfo( 'email' );
                if( $data['username'] )
                {
                    $class = new Application_User_List();
                    $class->setIdentifier( array( 'username' => $data['username'] ) );
                    $userInfo = $class->getIdentifierData();
                    $mailInfo['to'] .= ',' . $userInfo['email'];
                }
                try
                {
                    @self::sendMail( $mailInfo );
                }
                catch( Ayoola_Exception $e ){ null; }

                $this->setViewContent( '<p class="goodnews">' . sprintf( self::__( '%s questions saved successfully' ), count( $values['quiz_question'] ) ) . '</p>', true ); 
                $this->setViewContent(  '<p class=" pc_give_space_top_bottom"><a class="" href="">' . self::__( 'Contribute another quiz question' ) . '</a></p>'  );
                return true;
            }
            $this->setViewContent( self::__( '<p class="badnews">An error occured while saving questions</p>' ) , true); 

		}
		catch( Exception $e )
		{ 
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}
	
    }	
    /**
     * Form to display poll
     * 
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
    
        if( ! empty( $_GET['all'] ) )
        {
            if( ! self::isOwner( $values['user_id'] )  && ! self::isAllowedToEdit( $values ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ? : 98 ) && Ayoola_Application::getUserInfo( 'username' ) !== strtolower( $values['username'] ) )
            { 
                //  don't expose existing 
                $values = false;
            }  
        }
        else
        {
            $values = false;
        }

		
		//	Put the questions in a separate fieldset
		$fieldset = new Ayoola_Form_Element; 
		$fieldset->allowDuplication = false;

        $questionForm = self::quizQuestions( $values );
        $questionElementList = 'quiz_question' . @$groupIds[$j] . ',quiz_option1' . @$groupIds[$j] . ',quiz_option2' . @$groupIds[$j] . ',quiz_option3' . @$groupIds[$j] . ',quiz_option4' . @$groupIds[$j] . ',quiz_correct_option' . @$groupIds[$j] . ',quiz_answer_notes' . @$groupIds[$j];
        $fieldset->addElement( array( 'name' => 'questions_and_answers', 'data-pc-ignore-field' => true, 'data-pc-element-whitelist-group' => 'questions_and_answers', 'type' => 'Html', 'value' => '' ), array( 'html' => ( $subGroupHeading . $questionForm->view() ), 'parameters' => array( 'data-pc-element-whitelist-group' => 'questions_and_answers' ), 'fields' => $questionElementList ) );

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );

    } 
	// END OF CLASS
}
