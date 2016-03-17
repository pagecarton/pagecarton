<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see 
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Form_Abstract extends Ayoola_Abstract_Table
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'form_name' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Form_Table';
	
    /**
     * 
     * @var string
     */
	protected $_idColumn = 'form_name';
	
    /**
     * creates the form for creating and editing form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )  
    {
	//	var_export( $values );
		//	Form to create a new form
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
	//	$fieldset->placeholderInPlaceOfLabel = false;
		$fieldset->addElement( array( 'name' => 'form_title', 'placeholder' => 'Enter a title...', 'type' => 'InputText', 'value' => @$values['form_title'] ) );
		$fieldset->addRequirement( 'form_title', array( 'WordCount' => array( 3, 50 )  ) );
		if( is_null( $values ) )
		{		
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '-';
	//		$values['menu_name'] = strtolower( $filter->filter( $values['menu_label'] ) );
			$fieldset->addElement( array( 'name' => 'form_name', 'type' => 'Hidden', 'value' => @$values['form_name'] ) );
			$fieldset->addFilter( 'form_name', array( 'DefiniteValue' => strtolower( $filter->filter( $this->getGlobalValue( 'form_title' ) ) )  ) );
		}	

		$fieldset->addElement( array( 'name' => 'form_description', 'placeholder' => 'Enter a short description explaining the purpose of this form. This information will be displayed to users ', 'type' => 'TextArea', 'value' => @$values['form_description'] ) );
		$fieldset->addElement( array( 'name' => 'form_success_message', 'placeholder' => 'Enter a short success message. This information will be displayed to users when they have filled the form. ', 'type' => 'TextArea', 'value' => @$values['form_success_message'] ) );
		
		$options =  array( 
							'send_mail' => 'Send me an e-mail of the form data when someone fills the form.', 
							
							//	Saving all information to db by default 
							'database' => 'Save the data filled by user to database.', 
							'group' => 'Group this form into fieldsets', 
							'requirements' => 'This form has some requirements.', 
							'callbacks' => 'Perform some actions (Call some PHP classes) after the form is filled.', 
						);
		$fieldset->addElement( array( 'name' => 'form_options', 'label' => 'Form Options', 'type' => 'Checkbox', 'value' => @$values['form_options'] ), $options );
		
		//	Auth Level
		
		$authLevel = new Ayoola_Access_AuthLevel;
		$authLevel = $authLevel->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$authLevel = $filter->filter( $authLevel );
	//	self::v( $values['auth_level'] );
	//	self::v( $authLevel ); 
		$fieldset->addElement( array( 'name' => 'auth_level', 'label' => 'Which user groups should this form be available to?', 'type' => 'Checkbox', 'value' => @$values['auth_level'] ? : array_keys( $authLevel ) ), $authLevel ); 
//		$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $authLevel )  ) ); 
		unset( $authLevel );
		
		$fieldset->addLegend( $legend );
	//	$fieldset->addFilters( 'StripTags::Trim' );
		$form->addFieldset( $fieldset );   
		if( @$values['email'] ||  is_array( $this->getGlobalValue( 'form_options' ) ) && in_array( 'send_mail', $this->getGlobalValue( 'form_options' ) ) )
		{ 
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Enter the e-mail to send the form data to:' );
			$fieldset->addElement( array( 'name' => 'email', 'placeholder' => 'e.g. info@myCompany.com, support@myDepartment.com', 'type' => 'InputText', 'value' => @$values['email'] ? : Ayoola_Application::getUserInfo( 'email' ) ) );
			$form->addFieldset( $fieldset );
		}
		if( @$values['groups'] ||  is_array( $this->getGlobalValue( 'form_options' ) ) && in_array( 'group', $this->getGlobalValue( 'form_options' ) ) )
		{ 
			$i = 0;
			$newForm = new Ayoola_Form( array( 'name' => 'xxx', ) );
			$newForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true, 'no_required_fieldset' => true ) );
			$newForm->wrapForm = false;
			do
			{
				$newFieldSet = new Ayoola_Form_Element;
				$newFieldSet->container = 'span';
				$newFieldSet->allowDuplication = true;
				$newFieldSet->addLegend( 'Create form groups' );
				$newFieldSet->addElement( array( 'name' => 'group_names',  'multiple' => 'multiple', 'placeholder' => 'e.g. Group 1', 'type' => 'InputText', 'value' => @$values['group_names'][$i] ) );
				$newFieldSet->addElement( array( 'name' => 'group_ids',  'multiple' => 'multiple', 'label' => 'Group ID', 'placeholder' => 'e.g. group-1', 'type' => 'InputText', 'value' => @$values['group_ids'][$i] ) );
				
				$newForm->addFieldset( $newFieldSet );    
			//	self::v( $i );   
			//	var_export();
				$i++;
			}
			while( ! empty( $values['group_names'][$i] ) );
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addElement( array( 'name' => 'xxxx', 'type' => 'Html', 'value' => '' ), array( 'html' => $newForm->view(), 'fields' => 'group_names,group_ids' ) );
			$form->addFieldset( $fieldset );    
		}
		if( @$values['requirements'] ||  is_array( $this->getGlobalValue( 'form_options' ) ) && in_array( 'requirements', $this->getGlobalValue( 'form_options' ) ) )
		{ 
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Requirements of this form:' );
			$options = new Ayoola_Form_Requirement;
			$options = $options->select();
			if( $options ) 
			{
				require_once 'Ayoola/Filter/SelectListArray.php';
				$filter = new Ayoola_Filter_SelectListArray( 'requirement_name', 'requirement_label' );
				$options = $filter->filter( $options );
				$fieldset->addElement( array( 'name' => 'requirements', 'label' => 'Select information required from viewers of this "' . $this->getGlobalValue( 'form_title' ) . '" (advanced)', 'type' => 'Checkbox', 'value' => @$values['requirements'] ), $options );
			}
			$form->addFieldset( $fieldset );
		}
		if( @$values['callbacks'] ||  is_array( $this->getGlobalValue( 'form_options' ) ) && in_array( 'callbacks', $this->getGlobalValue( 'form_options' ) ) )
		{ 
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Callback objects for this form:' );
			$fieldset->addElement( array( 'name' => 'callbacks', 'placeholder' => 'e.g. Application_User_Creator', 'type' => 'MultipleInputText', 'value' => @$values['callbacks'] ), @$values['callbacks'] );
			$form->addFieldset( $fieldset ); 
		}
	//	if( is_array( $this->getGlobalValue( 'form_options' ) ) && in_array( 'send_mail', $this->getGlobalValue( 'form_options' ) ) )
		{ 
			$i = 0;
			$newForm = new Ayoola_Form( array( 'name' => 'xxx', ) );
			$newForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true, 'no_required_fieldset' => true ) );
		//	$newForm->setParameter( array( 'no_form_element' => true ) );
			$newForm->wrapForm = false;
			do
			{
				$newFieldSet = new Ayoola_Form_Element;
				$newFieldSet->container = 'span';
				$newFieldSet->allowDuplication = true;
				$newFieldSet->addLegend( 'Build Form Elements' );
				$newFieldSet->addElement( array( 'name' => 'element_title',  'multiple' => 'multiple', 'placeholder' => 'e.g. Full Name', 'type' => 'InputText', 'value' => @$values['element_title'][$i] ) );
				$newFieldSet->addElement( array( 'name' => 'element_name',  'multiple' => 'multiple', 'label' => 'Unique Name (Optional)', 'placeholder' => 'e.g. full_name', 'type' => 'InputText', 'value' => @$values['element_name'][$i] ) );
				$newFieldSet->addElement( array( 'name' => 'element_default_value',  'multiple' => 'multiple', 'label' => 'Default Value (Optional)', 'placeholder' => 'e.g. John C. Smith', 'type' => 'InputText', 'value' => @$values['element_default_value'][$i] ) );
				
			//	$newFieldSet->addRequirement( 'element_name', array( 'Name' => null  ) ); 
				$newFieldSet->addElement( array( 'name' => 'element_placeholder',  'multiple' => 'multiple', 'placeholder' => 'e.g. John Smith', 'type' => 'InputText', 'value' => @$values['element_placeholder'][$i] ) );
				$newFieldSet->addElement( array( 'name' => 'element_type',  'multiple' => 'multiple', 'type' => 'Select', 'value' => @$values['element_type'][$i] ), array( 'text' => 'Text Input', 'hidden' => 'Hidden Field', 'radio' => 'Radio Button', 'Select' => 'Select Option', 'email' => 'Email Input', 'textarea' => 'Text Area', 'submit' => 'Submit Button', 'file' => 'File', 'document' => 'Document', 'image' => 'Image', 'profile_picture' => 'Profile Picture', 'audio' => 'Audio', ) );
				$this->getGlobalValue( 'group_names' ) ? $newFieldSet->addElement( array( 'name' => 'element_group_name',  'multiple' => 'multiple', 'label' => 'Group Name (Optional)', 'placeholder' => 'e.g. personal', 'type' => 'Select', 'value' => @$values['element_group_name'][$i] ), array_combine( $this->getGlobalValue( 'group_ids' ), $this->getGlobalValue( 'group_names' ) ) ) : null; 
				$newForm->addFieldset( $newFieldSet );    
			//	self::v( $i );   
			//	var_export();
				$i++;
			}
			while( ! empty( $values['element_title'][$i] ) );
			$fieldset = new Ayoola_Form_Element;
		//	$fieldset->allowDuplication = true;
			$fieldset->addElement( array( 'name' => 'xxxx', 'type' => 'Html', 'value' => '' ), array( 'html' => $newForm->view(), 'fields' => 'element_title,element_name,element_group_name,element_default_value,element_placeholder,element_type' ) );
	//		self::v( $newForm->view() );  
	//		$fieldset->addElement( array( 'name' => 'xxxx', 'type' => 'Html', 'value' => '' ), array( 'html' => $newForm->view(), 'fields' => '' ) );
			$form->addFieldset( $fieldset );    
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
