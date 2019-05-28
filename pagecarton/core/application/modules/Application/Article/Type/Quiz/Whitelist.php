<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Quiz_Whitelist
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
 * @package    Application_Article_Type_Quiz_Whitelist
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Quiz_Whitelist extends Application_Article_Type_Quiz
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
	//		var_export( $data );
			 
			//	Only the valid editor can view scoreboard
			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::isOwner( $data['user_id'] ) && ! self::isAllowedToEdit( $data ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ) && Ayoola_Application::getUserInfo( 'username' ) !== $data['username'] ){ return false; }
			$this->setViewContent( '<h3>Build Invitation List!</h3>' );			
			$this->setViewContent( '<p>Create an invitation list for: "' . $data['article_title'] . '" or <a href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_Type_Quiz_WhitelistImport/?article_url=' . $data['article_url'] . '">Bulk email address Import>></a></p>' );		 	
			$this->createForm( 'Continue...', '', $data ); 
			$this->setViewContent( $this->getForm()->view() );
		//	var_export( $this->getForm()->getBadnews() );
		//	var_export( $this->getForm()->getValues() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			//	making options that have been disabled to still be active.
		//	var_export( $values );
		//	$values = array_merge( $data, $values ); 
			
			$values['whitelist_email_address'] = array_values( array_map( 'strtolower', array_unique( $values['whitelist_email_address'] ? : array() ) ) );

		//	var_export( $values );
			self::saveArticle( array_merge( $data, $values ) );
			
			
			
			$this->setViewContent( '<h3>Success!</h3>', true );
			$this->setViewContent( '<p>You now have a total of ' . count( @$values['whitelist_email_address'] ) . ' invitee(s) for this test titled "' . $data['article_title'] . '"</p>' );
			//	Send email
			
			//	Get the profile information to personalize email.
			$filename = Application_Profile_Abstract::getProfilePath( @$data['profile_url'] );
			if( ! $profileInfo = @include $filename )
			{
			//	continue;
			}				
			arsort( $values['whitelist_notify'] );
	//		var_export( $values['whitelist_notify'] );
			foreach( $values['whitelist_notify'] as $eachOne => $notify )
			{
				if( ! $notify )
				{
					break;
				}
				do
				{
					$billExaminer = true;  //	Bill the examiner for? Add to settings later for flexibility.
					if( ! $billExaminer  )
					{
						break;
					}
/* 					
					//	Bill only in private exams. Add to settings later for flexibility.
				//	if( ! in_array( 97, array_map( 'intval', (array) $data['auth_level'] ) ) )
					{
					//	break;
					}
					
					//	Bill new accounts only. Add to settings later for flexibility.
					if( in_array( $values['whitelist_email_address'][$eachOne], $data['whitelist_email_address'] ) )
					{
						break;
					}
					
					// bill the user
					// send to the admin
					$transferInfo['to'] = 'joywealth';
					$transferInfo['from'] = $data['username'];
					$transferInfo['amount'] = '1000';
					$transferInfo['notes'] = 'Test fees for "' . $values['whitelist_email_address'][$eachOne] . '" from ' . $data['username'] . '. Test title is "' . $data['article_title'] . '".' ;
					if( ! Application_Wallet::transfer( $transferInfo ) )
					{
						return false;
					}
 */					
/* 					//	Start new game
					$filename = Application_Profile_Abstract::getProfilePath( $data['profile_url'] );
					if( $profileInfo = @include $filename )
					{
						$profileInfo['post_whitelist_count'] = $profileInfo['post_whitelist_count'] ? : array();
						$profileInfo['post_whitelist_allowed'] = $profileInfo['post_whitelist_allowed'] ? : 0;
						if( $profileInfo['post_whitelist_allowed'] < 1 )
						{
							$this->setViewContent( '<p>The number of users can can be invited has been exhausted. Please contact administrator to reload your account with more passes.</p>' );
							break 2;
						}
						$profileInfo['post_whitelist_log'][] = $values['whitelist_email_address'][$eachOne];
						$profileInfo['post_whitelist_allowed']--;
						Application_Profile_Abstract::saveProfile( $profileInfo );
					}
					else
					{
						return false;
					}
 */				}
				while( false );
/* 				
				//	Save email address in the DB to be able to be able to process later when they login
				$emailTable = new Application_User_UserEmail_Table();
				$identifier = array( 'email' => $values['whitelist_email_address'][$eachOne] );
				if( ! $previousEmailInfo = $emailTable->selectOne( null, $identifier ) )
				{
					$emailTable->insert( $identifier + array( 'class' => array( __CLASS__ ) , 'invitees' => array( $data['username'] ) ) );
				}
				else
				{
					$previousEmailInfo['class'] = array_unique( $previousEmailInfo['class'] + array( __CLASS__ ) );
					$previousEmailInfo['username'] = array_unique( $previousEmailInfo['username'] + array( $data['username'] ) );
					$previousEmailInfo['data'] = is_array( $previousEmailInfo['data'] ) ? $previousEmailInfo['data'] : array();
					$previousEmailInfo['data']['iv_posts'] = is_array( $previousEmailInfo['data'['iv_posts']] ) ? $previousEmailInfo['data']['iv_posts'] : array();
					$previousEmailInfo['data']['iv_posts'][] = $data['article_url'];
					$emailTable->update( 
											array
											( 
												'class' => $previousEmailInfo['class'] + array( __CLASS__ ),
												'invitees' => $previousEmailInfo['username'] + array( $data['username'] ), 
												'data' => $previousEmailInfo['data'] 
											),						
											$identifier
										);
				}
 */				
		//		var_export( $values['whitelist_email_address'][$eachOne] );
		//		continue;
				$emailInfo['to'] = $values['whitelist_email_address'][$eachOne];
				$emailInfo['from'] = ( ( $profileInfo['display_name'] ) ? ( $profileInfo['display_name'] . ' on ' . Ayoola_Page::getDefaultDomain() ) : Ayoola_Page::getDefaultDomain() ) . ' <test-invite@' . Ayoola_Page::getDefaultDomain() . '>';
				$emailInfo['subject'] = 'Online Test Invitation';
				$emailInfo['body'] = 'You have been invited to take test titled "' . $data['article_title'] . '" by ' . ( @$profileInfo['display_name'] ? : 'the owner' ) . ',
			
You can take the test by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . strtolower( $data['article_url'] ) . '

We recommend you use a latest web browser to take the test. Also ensure you have access to an uninterrupted internet connection while you take the test. 

You need to have an account on ' . Ayoola_Page::getDefaultDomain() . ' to take this test. You will not be allowed to view this test if you are not registered with email address - "' . $values['whitelist_email_address'][$eachOne] . '". Once again, you can only take this test using an account created with email address "' . $values['whitelist_email_address'][$eachOne] . '". 
			
Learn more about the test host by viewing their personalized page: http://' . Ayoola_Page::getDefaultDomain() . '/' . @$data['profile_url'] . '


Best Regards,
' . $profileInfo['display_name'] . '
http://' . Ayoola_Page::getDefaultDomain() . '/' . @$data['profile_url'] . '
';
				@self::sendMail( $emailInfo );
				$this->setViewContent( '<p>Email notification has been sent to:  "' . $values['whitelist_email_address'][$eachOne] . '".</p>' );
			}

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
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
	//	$fieldset->placeholderInPlaceOfLabel = true;
	
		$i = 0; // question count
		//	Build a separate demo form for the previous group
		$questionForm = new Ayoola_Form( array( 'name' => 'questions...' )  );
		$questionForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
//		$form->oneFieldSetAtATime = false;
		$questionForm->wrapForm = false;
	//	var_export();
	
		//	Reshuffle to make sure the keys are chronological
		$values['whitelist_email_address'] = array_values( @$values['whitelist_email_address'] ? : array() );
//	
		do
		{
		//	var_export( $i );
			
			//	Put the questions in a separate fieldset
			$questionFieldset = new Ayoola_Form_Element; 
			$questionFieldset->allowDuplication = true;
			$questionFieldset->duplicationData = array( 'add' => '+ Add New Invitee Below', 'remove' => '- Remove Above Invitee', 'counter' => 'invitee_counter', );
			$questionFieldset->container = 'span';
							
			$questionFieldset->addElement( array( 'name' => 'whitelist_email_address', 'label' => 'Email Address', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['whitelist_email_address'][$i] ? : $this->getGlobalValue( 'whitelist_email_address', null , $i ) ) );
		//	var_export( $values['whitelist_email_address'][$i] );
		//	$questionFieldset->addElement( array( 'name' => 'whitelist_first_name', 'label' => 'First Name', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['whitelist_first_name'][$i] ? : $this->getGlobalValue( 'whitelist_first_name', null , $i ) ) );
//$questionFieldset->addElement( array( 'name' => 'whitelist_last_name', 'label' => 'Last Name', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['whitelist_last_name'][$i] ? : $this->getGlobalValue( 'whitelist_last_name', null , $i ) ) );  
		//	$questionFieldset->addElement( array( 'name' => 'whitelist_phone_number', 'label' => 'Phone Number', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['whitelist_phone_number'][$i] ? : $this->getGlobalValue( 'whitelist_phone_number', null , $i ) ) );
			$questionFieldset->addElement( array( 'name' => 'whitelist_notify', 'label' => 'Send Invitation to Email?', 'type' => 'Select', 'multiple' => 'multiple', 'value' => 0 ), array( 'No', 'Yes' ) );
			
			//	We need to save the keys to use later so this information may save in the real fieldset
			
			$i++;
			
			$questionFieldset->addLegend( 'Invitee <span name="invitee_counter">' . $i . '</span> of <span name="invitee_counter_total">' . ( count( @$values['whitelist_email_address'] ) ? : 1 ) . '</span>' );						  
			$questionForm->addFieldset( $questionFieldset );
		//	self::v( $i );  
		}
		while( isset( $values['whitelist_email_address'][$i] ) );
		//	self::v( $values['whitelist_email_address'] );  
		
		
		//	Put the questions in a separate fieldset
		$fieldset = new Ayoola_Form_Element; 
		$fieldset->allowDuplication = false;
	//	$questionFieldset->placeholderInPlaceOfLabel = true;
		$fieldset->container = 'span';
		
		//	add previous questions if available
		$fieldset->addElement( array( 'name' => 'previous_forms', 'type' => 'Html', 'value' => '' ), array( 'html' => ( '' . $questionForm->view() ), 'fields' => 'whitelist_email_address, whitelist_first_name, whitelist_last_name, whitelist_phone_number, whitelist_notify' ) );
		$fieldset->addRequirement( 'whitelist_email_address', array( 'EmailAddress' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );

    } 
	
	// END OF CLASS
}
