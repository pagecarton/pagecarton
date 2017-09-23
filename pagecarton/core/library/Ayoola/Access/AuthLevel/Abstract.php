<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_AuthLevel_Abstract
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
 * @package    Ayoola_Access_AuthLevel_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Access_AuthLevel_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Ayoola_Access_AuthLevel';
	
    /**
     * Key for the id column
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'auth_name' );
	
    /**
     * 
     * @var string
     */
	protected $_idColumn = 'auth_name';
	  
	//	This is to implement the abstract method of the parent class. Not all inheriting classes needs a form
	public function createForm( $submitValue, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'auth_name', 'placeholder' => 'Choose a name for level', 'type' => 'InputText', 'value' => @$values['auth_name'] ) );
		//	var_export( $values['auth_level'] );
			$fieldset->addElement( array( 'name' => 'auth_level', 'placeholder' => 'Rate this level from 0 - 99', 'type' => 'InputText', 'value' => @$values['auth_level'] ) );
		}
		$fieldset->addRequirements( array( 'WordCount' => array( 1,200 ) ) );  
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addElement( array( 'name' => 'auth_description', 'placeholder' => 'Describe this new user group in a few words', 'type' => 'TextArea', 'value' => @$values['auth_description'] ) );
		$fieldset->addElement( array( 'name' => 'storage_size', 'label' => 'Storage Size (in bytes)', 'placeholder' => 'e.g. 1024', 'type' => 'InputText', 'value' => @$values['storage_size'] ) );   
		$fieldset->addElement( array( 'name' => 'max_allowed_posts', 'label' => 'Maximum Allowed Posts', 'placeholder' => 'e.g. 100', 'type' => 'InputText', 'value' => @$values['max_allowed_posts'] ) );
		$fieldset->addElement( array( 'name' => 'max_allowed_posts_private', 'label' => 'Maximum Allowed Private Posts', 'placeholder' => 'e.g. 5', 'type' => 'InputText', 'value' => @$values['max_allowed_posts_private'] ) );
		
		$options =  array( 
							'allow_signup' => 'Allow users to be able to select this group while signing up.', 
							'inherit' => 'Allow the users in this group to inherit priviledges from other groups. (Will prompt for other group in next step)', 
							'attach_forms' => 'Request for additional information from this group of user using additional forms.', 
							'display_picture' => 'Upload a default display picture for this user group.', 
						);
		$fieldset->addElement( array( 'name' => 'auth_options', 'type' => 'Checkbox', 'value' => @$values['auth_options'] ), $options );
		if( is_null( $values ) )
		{
		//	$fieldset->addRequirement( 'auth_name', array( 'WordCount' => array( 4,100 ), 'Name' => null ) );
			$fieldset->addRequirement( 'auth_name', array( 'WordCount' => array( 4,100 ) ) );
			$fieldset->addRequirement( 'auth_level', array( 'InArray' => range( 0, 99 ) ) );
		}
	//	$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		if( is_array( Ayoola_Form::getGlobalValue( 'auth_options' ) ) && in_array( 'inherit', Ayoola_Form::getGlobalValue( 'auth_options' ) ) )
		{
			$fieldset = new Ayoola_Form_Element;
			$fieldset->id = __CLASS__ . 'settings';
			$authLevel = new Ayoola_Access_AuthLevel;
			$authLevel = $authLevel->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$options = array();
			foreach( $authLevel as $each )
			{
				if( $each['auth_level'] < 10 )
				{
					$options[$each['auth_level']] =  "{$each['auth_name']}: {$each['auth_description']}";
				}
			}
			$fieldset->addElement( array( 'name' => 'parent_access_level', 'label' => 'Inherit Priviledges from', 'type' => 'Checkbox', 'optional' => 'optional', 'value' => @$values['parent_access_level'] ), $options );
			$fieldset->addRequirement( 'parent_access_level', array( 'Int' => null, 'InArray' => array_keys( $options )  ) );
			unset( $authLevel );
			$fieldset->addLegend( "Select the user levels to inherit priviledges from" );
			$form->addFieldset( $fieldset );
		}
		if( is_array( Ayoola_Form::getGlobalValue( 'auth_options' ) ) && in_array( 'attach_forms', Ayoola_Form::getGlobalValue( 'auth_options' ) ) )
		{
			$fieldset = new Ayoola_Form_Element;
	//		$fieldset->id = __CLASS__ . 'settings';
			$options = new Ayoola_Form_Table;
			$options = $options->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'form_name', 'form_title');
			$options = $filter->filter( $options );
			$fieldset->addElement( array( 'name' => 'additional_forms', 'label' => 'Additional forms to request for group-specific information', 'type' => 'Checkbox', 'optional' => 'optional', 'value' => @$values['additional_forms'] ), $options );
		//	$fieldset->addRequirement( 'additional_forms', array( 'Int' => null, 'InArray' => array_keys( $options )  ) );
			unset( $options );
			$fieldset->addLegend( 'Request for additional information from this group of user using additional forms. <a href="javascript:" class="boxednews greynews" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Creator/\' );" title="Create a new form">Add new form</a>' );
			$form->addFieldset( $fieldset );
		}
		if( is_array( Ayoola_Form::getGlobalValue( 'auth_options' ) ) && in_array( 'display_picture', Ayoola_Form::getGlobalValue( 'auth_options' ) ) )
		{
			$fieldset = new Ayoola_Form_Element;
		//	$fieldset->id = __CLASS__ . 'settings';
		
			//	Cover photo
			$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'display_picture' ) : 'display_picture' );
		//	var_export( $link );
			$fieldset->addElement( array( 'name' => 'display_picture', 'label' => '', 'placeholder' => 'Upload a default display picture for this user group.', 'type' => 'Hidden', 'value' => @$values['display_picture'] ) );
			$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['display_picture'] ? : $this->getGlobalValue( 'display_picture' ) ), 'field_name' => $fieldName, 'width' => '300', 'height' => '300', 'crop' => true, 'field_name_value' => 'url', 'preview_text' => 'Display Picture', 'call_to_action' => 'Change picture' ) ) ) ); 
			$fieldset->addLegend( "Upload a default display picture for this user group." );
			$form->addFieldset( $fieldset );
		}
		$this->setForm( $form );
	}
	// END OF CLASS
}
