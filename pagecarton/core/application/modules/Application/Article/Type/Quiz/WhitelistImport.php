<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Quiz_WhitelistImport
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
 * @package    Application_Article_Type_Quiz_WhitelistImport
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Quiz_WhitelistImport extends Application_Article_Type_Quiz
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
			if( ! self::isOwner( $data['user_id'] ) && ! self::isOwner( $data['user_id'] ) && ! self::isAllowedToEdit( $data ) && Ayoola_Application::getUserInfo( 'username' ) !== $data['username'] ){ return false; }
			$this->setViewContent( '<h3>Bulk Email Address Invitation List!</h3>' );			
			$this->setViewContent( '<p>Create an invitation list for: "' . $data['article_title'] . '" or <a href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_Type_Quiz_Whitelist/?article_url=' . $data['article_url'] . '">Standard Import List>></a></p>' );	  		
			$this->setViewContent( '<p>Paste email addresses below</p>' );			
			$this->createForm( 'Continue...', '', $data );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			//	making options that have been disabled to still be active.
		//	var_export( $values );
		//	$values = array_merge( $data, $values ); 
		
			#	http://stackoverflow.com/questions/3901070/in-php-how-do-i-extract-multiple-e-mail-addresses-from-a-block-of-text-and-put
			$string = $values['whitelist_bulk_email_address']; // Load text file contents
			$matches = array(); //create array
			$pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i'; //regex for pattern of e-mail address
			preg_match_all( $pattern, $string, $matches ); //find matching pattern			
			$values['whitelist_email_address'] = $matches[0];
			$values['whitelist_notify'] = array_fill_keys( range( 0, count( $values['whitelist_email_address'] ) - 1 ), $values['whitelist_bulk_notify'] );
			if( empty( $values['whitelist_email_address'] ) )
			{
				return false;
			}
			$class = new Application_Article_Type_Quiz_Whitelist();
			$parameters = array( 'fake_values' => $values ); 
			$class->setParameter( $parameters );
			$class->fakeValues = $values;
			$class->init();
			$this->setViewContent( $class->view(), true );			
			
		//	var_export( $class->view() );
		//	var_export( $values );

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
	//	var_export( $values );
//	  
		
		
		//	Put the questions in a separate fieldset
		$fieldset = new Ayoola_Form_Element; 
		$fieldset->allowDuplication = false;
		$fieldset->addElement( array( 'name' => 'whitelist_bulk_email_address', 'label' => 'Email Addresses', 'type' => 'TextArea', 'value' => implode( ', ', @$values['whitelist_email_address'] ? : array() ) ) );  
		$fieldset->addElement( array( 'name' => 'whitelist_bulk_notify', 'label' => 'Send Invitation to Email?', 'type' => 'Select', 'value' => 0 ), array( 'No', 'Yes' ) );
	//	$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->container = 'span';
		
		//	add previous questions if available

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );

    } 
	
	// END OF CLASS
}
