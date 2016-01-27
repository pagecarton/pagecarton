<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_AccessInformation_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see Ayoola_Abstract_Table
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_AccessInformation_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Access_AccessInformation_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Ayoola_Access_AccessInformation';
	
    /**
     * Key for the id column
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'accessinformation_id' );
	  
	//	This is to implement the abstract method of the parent class. Not all inheriting classes needs a form
	public function createForm( $submitValue, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
		//	$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => 'http://' . Ayoola_Page::getDefaultDomain() . ' ' ) );
			$option = array( Ayoola_Page::getDefaultDomain() => 'http://' . Ayoola_Page::getDefaultDomain() );
			$fieldset->addElement( array( 'name' => 'domain', 'style' => 'max-width:20%;', 'label' => 'Choose a profile URL', 'disabled' => 'disabled', 'type' => 'InputText', 'value' => 'http://' . Ayoola_Page::getDefaultDomain() . '/' ), $option );
			
			Application_Javascript::addCode
			(
				'
					ayoola.addShowProfileUrl = function( target )
					{
					//	var target = ayoola.events.getTarget( e );
					//	alert( 1 );
						var element = document.getElementById( "element_to_show_profile_url" );
						element = element ? element : document.createElement( "div" );
				///		alert( 2 );
						element.id = "element_to_show_profile_url";
					//	element = "boxednews greynews";
			//			alert( 3 );
						var a = false;
						if( target.value )
						{
							a = true;
						}
						if( a )
						{
							element.innerHTML = "<span class=\'boxednews greynews\'>The profile URL will be: <a href=\'/" + target.value + "\'>http://' . Ayoola_Page::getDefaultDomain() . '/" + target.value + "</a></span>";
						}
						else
						{
							element.innerHTML = "<span class=\'boxednews badnews\'>Please enter a valid profile URL in the space provided...</span>";  
						}
				//		element.innerHTML = "<span class=\'boxednews greynews\'>The profile URL will be</span> <span class=\'boxednews goodnews\'><a href=\'/" + target.value + "\'>http://' . Ayoola_Page::getDefaultDomain() . '/" + target.value + "</a></span>";
				//		alert( 4 );
						target.parentNode.insertBefore( element, target.nextSibling );
				//		alert( 5 );
					}
				'
			);
			$fieldset->addElement( array( 'name' => 'profile_url', 'style' => 'max-width:50%;', 'label' => '', 'onkeyup' => 'ayoola.addShowProfileUrl( this );', 'placeholder' => 'Enter your profile url here...', 'type' => 'InputText', 'value' => @$values['profile_url'] ) ); 
			$fieldset->addFilter( 'profile_url','Username' );
			$fieldset->addRequirement( 'profile_url', array( 'DuplicateUser' => array( 'Username', 'username', 'badnews' => 'Someone else has already chosen "%variable%"', ),'CharacterWhitelist' => array( 'badnews' => 'The allowed characters are lower case alphabets (a-z), numbers (0-9), underscore (_) and hyphen (-).', 'character_list' => '^0-9a-z-_', ), 'NotEmpty' => null, 'WordCount' => array( 6,20 ) ) );
		//	$fieldset->addElement( array( 'name' => 'name', 'placeholder' => 'Give this page a name', 'type' => 'InputText', 'value' => @$values['name'] ) );
		}
		$fieldset->addElement( array( 'name' => 'display_name', 'placeholder' => 'e.g. John Smith', 'type' => 'InputText', 'value' => @$values['display_name'] ? : ( $values['firstname'] . ' ' . $values['lastname'] ) ) );
		$fieldset->addElement( array( 'name' => 'profile_description', 'placeholder' => 'Enter your profile description here...', 'type' => 'TextArea', 'value' => @$values['profile_description'] ) );
		$fieldset->addLegend( "Update profile information..." );
		$form->addFieldset( $fieldset ); 

		//	Profile picture
		$fieldset = new Ayoola_Form_Element; 
	
		//	Cover photo
		$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'display_picture' ) : 'display_picture' );
	//	var_export( $link );
		$fieldset->addElement( array( 'name' => 'display_picture', 'label' => 'Profile Picture', 'placeholder' => 'Choose a profile picture...', 'type' => 'Document', 'value' => @$values['display_picture'] ) );  
	//	$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['display_picture'] ? : $this->getGlobalValue( 'display_picture' ) ), 'field_name' => $fieldName, 'width' => '160', 'height' => '160', 'crop' => true, 'field_name_value' => 'url', 'preview_text' => 'Display Picture', 'call_to_action' => 'Change picture' ) ) ) ); 
		$fieldset->addLegend( "Choose a profile picture..." );
		$form->addFieldset( $fieldset );
		
		$this->setForm( $form );
	}
	// END OF CLASS
}
