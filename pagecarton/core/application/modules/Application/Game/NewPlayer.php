<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Game   Ayoola
 * @package    Application_Game_NewPlayer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: New.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Game_Abstract
 */
 
require_once 'Application/Game/Abstract.php';


/**
 * @Game   Ayoola
 * @package    Application_Game_NewPlayer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Game_NewPlayer extends Application_Game_Abstract
{
	
    /**
     * Access level for player
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
		$this->createForm( 'Continue', 'Add a new player' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
	//	if( ! $this->insertDb( $values ) ){ return false; }
		
		
		$myInfo = Ayoola_Access::getAccessInformation();		
		$values['agent_profile'] = $myInfo['profile_url'];
		
		Application_Profile_Abstract::saveProfile( $values );
						
		$this->setViewContent( '<p>New player profile created.</p>', true );
   } 
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true; 
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'profile_url', 'placeholder' => 'e.g. PlayerBoy', 'type' => 'InputText', 'value' => @$values['profile_url'] ) );
		$fieldset->addRequirement( 'profile_url', array( 'NotEmpty' => array( 'badnews' => 'The profile URL cannot be left blank.', ), 'CharacterWhitelist' => array( 'badnews' => 'The allowed characters are lower case alphabets (a-z), numbers (0-9), underscore (_) and hyphen (-).', 'character_list' => '^0-9a-zA-Z-_', ), 'WordCount' => array( 4,20 ), 'DuplicateUser' => array( 'Username', 'username', 'badnews' => 'Someone else has already chosen "%variable%"', ) ) );
	//	$fieldset->addRequirement( 'profile_url', array( 'WordCount' => array( 4, 100 ) ) );

		$fieldset->addElement( array( 'name' => 'display_name', 'placeholder' => 'e.g. Tolulope Adegoke', 'type' => 'InputText', 'value' => @$values['display_name'] ) );
		$fieldset->addRequirement( 'display_name', array( 'WordCount' => array( 4, 100 ) ) );
		
		$fieldset->addElement( array( 'name' => 'display_picture_base64', 'placeholder' => 'Change Display Picture', 'type' => 'Document', 'data-document_type' => 'image', 'data-allow_base64' => true, 'label' => 'Choose a Display Picture', 'value' => @$values['display_picture_base64'] ) );
		
		$fieldset->addElement( array( 'name' => 'phone_number', 'placeholder' => 'e.g. 08005551234', 'type' => 'InputText', 'value' => @$values['phone_number'] ) );
		$fieldset->addElement( array( 'name' => 'email_address', 'placeholder' => 'e.g. user@yahoo.com', 'type' => 'InputText', 'value' => @$values['email_address'] ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
