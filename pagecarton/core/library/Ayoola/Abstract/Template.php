<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Menu_Editor_Abstract
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
 * @package    Ayoola_Menu_Editor_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Abstract_Template extends Ayoola_Abstract_Table
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
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'template_name';
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'template_name' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Menu_Template';

    /**
     * creates the form for creating and editing menu
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )  
    {
	//	var_export( $values );
		//	Form to create a new menu
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'id' => $this->getObjectName() . @$values['template_name'] ) ); 
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset->placeholderInPlaceOfLabel = false;
		$fieldset->addElement( array( 'name' => 'template_label', 'placeholder' => 'Choose a name for this template', 'type' => 'InputText', 'value' => @$values['template_label'] ) );
		$fieldset->addRequirement( 'template_label', array( 'WordCount' => array( 3, 50 )  ) );
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'template_name', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['template_name'] ) );
			$fieldset->addFilter( 'template_name', array( 'DefiniteValue' => Ayoola_Form::getGlobalValue( 'template_label' ), 'Name' => null  ) );
		}
		$fieldset->addElement( array( 'name' => 'markup_template', 'placeholder' => 'e.g. ' . htmlentities( '<li><a>{{{placeholder}}}</a></li>' ), 'type' => 'TextArea', 'value' => @$values['markup_template'] ) );
		$fieldset->addRequirement( 'markup_template', array( 'WordCount' => array( 3, 5000 )  ) );
		
		//	options
		$options =  array( 
							'screenshot' => 'Select a screenshot for this template.', 
							'css' => 'Add external CSS files', 
							'javascript' => 'Add external Javascript files', 
							'padding' => 'Pad the template with template prefix and suffix', 
						);
		$fieldset->addElement( array( 'name' => 'template_options', 'type' => 'Checkbox', 'value' => @$values['template_options'] ), $options );
		

		$fieldset->addLegend( $legend );
		$fieldset->addFilters( 'Trim' );
		$form->addFieldset( $fieldset );   
		if( is_array( Ayoola_Form::getGlobalValue( 'template_options' ) ) && in_array( 'screenshot', Ayoola_Form::getGlobalValue( 'template_options' ) ) )
		{
			//	Screenshots
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Choose a screenshot for this template' );
			$fieldset->addElement( array( 'name' => 'template_screenshot', 'label' => '', 'placeholder' => 'Screenshot for this template ', 'type' => 'Hidden', 'value' => $values['template_screenshot'] ) );
			$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'template_screenshot' ) : 'template_screenshot' );
			$uploadScreenshot = Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['screenshot'] ? : null ), 'field_name' => $fieldName, 'width' => '200', 'height' => '200', 'crop' => true, 'field_name_value' => 'url', 'call_to_action' => 'Change Screenshot...' ) );
			$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => $uploadScreenshot ) );
			$form->addFieldset( $fieldset );
		}
		if( is_array( Ayoola_Form::getGlobalValue( 'template_options' ) ) && in_array( 'padding', Ayoola_Form::getGlobalValue( 'template_options' ) ) )
		{
			//	padding
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Add template prefix and suffix' );
			
			$fieldset->addElement( array( 'name' => 'markup_template_prefix', 'placeholder' => 'e.g. ' . htmlentities( '<nav><ul>' ), 'type' => 'TextArea', 'value' => @$values['markup_template_prefix'] ) );

			$fieldset->addElement( array( 'name' => 'markup_template_median', 'placeholder' => 'e.g. ' . htmlentities( ',' ), 'type' => 'TextArea', 'value' => @$values['markup_template_median'] ) );
	//		$fieldset->addRequirement( 'markup_template_prefix', array( 'WordCount' => array( 3, 6000 )  ) );
			
			$fieldset->addElement( array( 'name' => 'markup_template_suffix', 'placeholder' => 'e.g. ' . htmlentities( '</ul></nav>' ), 'type' => 'TextArea', 'value' => @$values['markup_template_suffix'] ) );
		//	$fieldset->addRequirement( 'markup_template_suffix', array( 'WordCount' => array( 3, 6000 )  ) );
			
			$fieldset->addElement( array( 'name' => 'max_group_no', 'placeholder' => 'e.g. 4', 'type' => 'InputText', 'value' => @$values['max_group_no'] ) );
			$form->addFieldset( $fieldset );
		}
		if( is_array( Ayoola_Form::getGlobalValue( 'template_options' ) ) && in_array( 'javascript', Ayoola_Form::getGlobalValue( 'template_options' ) ) )
		{
			//	javascript 
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Add external Javascript files' );
			$fieldset->addElement( array( 'name' => 'javascript_files', 'label' => ' ', 'multiple' => ' ', 'data-multiple' => 1, 'placeholder' => 'e.g. /path/to/file.js', 'type' => 'Document', 'value' => @$values['javascript_files'] ), @$values['javascript_files'] );
	//		var_export( $values );

	///		$fieldset->addRequirement( 'javascript_files', array( 'IsFile' => array( 'base_directory' => Ayoola_Doc::getDocumentsDirectory() , 'allowed_extensions' => array( 'js', 'js' ) ) ) );
			$fieldset->addElement( array( 'name' => 'javascript_code', 'label' => 'Javascript Code', 'placeholder' => 'Paste your JavaScript Code here', 'type' => 'TextArea', 'value' => @$values['javascript_code'] ) );

			$form->addFieldset( $fieldset );
		}
		if( is_array( Ayoola_Form::getGlobalValue( 'template_options' ) ) && in_array( 'css', Ayoola_Form::getGlobalValue( 'template_options' ) ) )
		{
			//	css
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Add external CSS files' );
	//		$fieldset->addElement( array( 'name' => 'css_files', 'label' => ' ', 'placeholder' => 'e.g. /path/to/file.css', 'type' => 'MultipleInputText', 'onClick' => 'ayoola.image.formElement = this; ayoola.image.fieldNameValue = \'url\'; ayoola.image.clickBrowseButton( { accept: \'text/css\' } );', 'value' => @$values['css_files'] ), @$values['css_files'] );

			$fieldset->addElement( array( 'name' => 'css_files', 'label' => ' ', 'multiple' => ' ', 'data-multiple' => 1, 'placeholder' => 'e.g. /path/to/file.css', 'type' => 'Document', 'value' => @$values['css_files'] ), @$values['css_files'] );

//			$fieldset->addRequirement( 'css_files', array( 'IsFile' => array( 'base_directory' => Ayoola_Doc::getDocumentsDirectory() , 'allowed_extensions' => array( 'css', 'css' ) ) ) );
			$fieldset->addElement( array( 'name' => 'css_code', 'label' => 'CSS Code', 'placeholder' => 'Paste your CSS Code here', 'type' => 'TextArea', 'value' => @$values['css_code'] ) );

			$form->addFieldset( $fieldset );
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
