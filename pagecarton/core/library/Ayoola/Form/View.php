<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $ 
 */

/**
 * @see Ayoola_Form_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Form_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Form_View extends Ayoola_Form_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'View Form'; 
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'form_name' );
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{
		//	if( ! $data = $this->getIdentifierData() ){ null; }
		//	if( ! $data = $this->getIdentifierData() ){ return false; }
			if( ! $data = $this->getIdentifierData() ){  }
			if( ! $data  
				|| ! self::hasPriviledge( $data['auth_level'] )
			)
			{
			//	var_export( $data );
		//		var_export( $this->getDbData() );
				$this->setViewContent( '<p class="boxednews badnews">The requested form was not found on the server. Please check the URL and try again. </p>', true );
				if( self::hasPriviledge() )
				{
					$editLink = '<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Creator/\' )" class="boxednews goodnews">New</a>';
					$editLink = '<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_List/\' )" class="boxednews normalnews">Manage Forms</a>';
					$this->setViewContent( $editLink );
				//	$this->setViewContent(  );
				}
				return false;
			//	self::setIdentifierData( $data );
			}
	//	if( empty( $values['form_name'] ) )
		{
	//		return false;
		}
		//	var_export( $data );
			$this->createForm( 'Continue...' );          
			$this->setViewContent( '', true );
			
			//	We show form information by default
			if( ! $this->getParameter( 'hide_form_information' ) && ! $_POST )
			{
				$this->setViewContent( '<h1 class="pc-heading">' . $data['form_title'] . '</h1>', true );
				$this->setViewContent( '<p>' . ( strip_tags( $data['form_description'] ) === $data['form_description'] ? nl2br( $data['form_description'] ) : $data['form_description'] ) . '</p>'  );
			}
			if( self::hasPriviledge() )
			{
/*				$manageForms = '
					<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Delete/?form_name=' . $data['form_name'] . '\' )" class="pc-btn pc-btn-small">Delete</a>
					<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Editor/?form_name=' . $data['form_name'] . '\' )" class="pc-btn pc-btn-small">Edit</a>
					<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Creator/\' )" class="pc-btn pc-btn-small">New</a>
					<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_List/\' )" class="pc-btn pc-btn-small">Manage Forms</a>
					';
*/			
	//			$this->setViewContent( '<p>' . $manageForms . '</p>' );
			}
			$this->setViewContent( $this->getForm()->view() );
			
			$pageInfo = array(
				'description' => Ayoola_Page::getCurrentPageInfo( 'description' ) . $data['form_description'] ,
				'title' => trim( $data['form_title'] . ' ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
			);
			//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
			Ayoola_Page::setCurrentPageInfo( $pageInfo );
			
		//	var_export( $data );
		//	var_export( $this->getForm()->getValues() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			
		//	if( ! $this->updateDb( $values ) ){ return false; }
		
			//	Save to table
			if( $data['form_options'] && in_array( 'database', $data['form_options'] ) )
			{
				$table = new Ayoola_Form_Table_Data();
				$infoToInsert = $data + array( 'form_data' => $values );
			//		var_export( $table->select() );
		//		var_export( $infoToInsert );
				$table->insert( $infoToInsert );
			}     
			
	//		var_export( $data );
		//	$this->setViewContent( 'Form Edited Successfully', true ); 
			//	Add all fieldsets
			foreach( @$data['callbacks'] as $class ) 
			{
				if( ! Ayoola_Loader::loadClass( $class ) )
				{
					continue;
				}
			//	self::v( $class ); 
			//	self::v( $class->view() ); 
				$class = new $class();
				$parameters = array( 'fake_values' => $values );
				$class->setParameter( $parameters );
				$class->fakeValues = $values;
				$class->init();
			//	$this->setViewContent( $class->view(), true );    
				
			//	return false;
			}
		
			//	Clear plain text password for security reasons
			unset( $values['password'], $values['password2'], $values['document_url_base64'], $values['download_url_base64'] );
			
			//	Notify Admin
			$link = 'http://' . Ayoola_Page::getDefaultDomain() . '/object/name/Ayoola_Form_View/?form_name=' . $data['form_name'] . '';
			$mailInfo = array();
			$mailInfo['subject'] = 'Form filled [' . $data['form_title'] . ']';
			$mailInfo['body'] = 'Form titled "' . $data['form_title'] . '" has been filled on your website with the following information: "' . htmlspecialchars_decode( var_export( $values, true ) ) . '". 
			
			Preview the form on: ' . $link . '
			';
			try
			{
		//		var_export( $mailInfo );
				if( $data['email'] )
				{
					@self::sendMail( $mailInfo + array( 'to' => $data['email'] ) ); 
				}
			//	@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
		//	if( ! $this->insertDb() ){ return false; }
		//	$this->setViewContent( '<h1>Thank you!</h1>', true );
			$this->setViewContent( ' ', true );
			$this->setViewContent( '<p>' . ( strip_tags( $data['form_success_message'] ) === $data['form_success_message'] ? nl2br( $data['form_success_message'] ) : $data['form_success_message'] ) . '</p>', true );
			
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with article package.</p>' ); 
		}
    } 
	
    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		@$object['option'] = $object['option'] ? : $object['view_option'];
	//	$html .= "<span data-parameter_name='view' >{$object['view']}</span>";
		
		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object
	//	var_export( $object );
		$options = $object['class_name'];
		$options = new $options( array( 'no_init' => true ) );
//		$options = array();
		$options = $options->getDbData();;
		$filter = new Ayoola_Filter_SelectListArray( 'form_name', 'form_title');
		if( $options = $filter->filter( $options ) )
		{
	//		$options = (array) $options->getClassOptions();
			$html .= '<span style=""> Show  </span>';
			$html .= '<select data-parameter_name="form_name">';
			foreach( $options as $key => $value )
			{ 
				$html .=  '<option value="' . $key . '"';  
			//	var_export( $object['view'] );
				if( @$object['form_name'] == $key ){ $html .= ' selected = selected '; }
				$html .=  '>' . $value . '</option>';  
			}
			$html .= '</select>';
			$html .= '<span style=""> form.  </span>';
		}
		else
		{
			$html .= '<p>Create a form to display. You can manage all forms by clicking "Manage Forms"</p>'; 
			$html .= '<button onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Creator/\' );">New Form</button>'; 
		}
		return $html;
	}
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public function getStatusBarLinks( $object )
    {
		return '<a title="Manage Forms" class="title_button" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_List/\' );">Manage Forms</a>';
	}
	
    /**
     * creates the form for creating and editing form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )  
    {
	//	self::v( $values ); 
		//	Form to create a new form
		$formInfo = $this->getIdentifierData();
		$values = ( $values ? : array() ) + ( $this->getParameter( 'default_values' ) ? : array() );
		
	//	self::v( $this->getParameter( 'default_values' ) );   
		if( empty( $formInfo['form_name'] ) )
		{
			return false;
		}

        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'id' => $this->getObjectName() . @$formInfo['form_name'] ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
	//	$fieldset->placeholderInPlaceOfLabel = false;       
		$i = 0;
		do
		{
			
			//	Put the questions in a separate fieldset
			$key = md5( $formInfo['element_group_name'][$i] );
			if( empty( $fieldsets[$key] ) )
			{
				$fieldsets[$key] = new Ayoola_Form_Element; 
			}
		
			//	Question
			
			$requirement = null;
			$options = array();
			$type = 'InputText'; 
			if( strpos( $formInfo['element_name'][$i], 'base64' ) )
			{
				$options['data-allow_base64'] = true;
			}
			switch( $formInfo['element_type'][$i] )
			{
				case 'textarea': 
					$type = 'TextArea'; 
				break;
				case 'submit': 
					$type = 'submit'; 
				break;
				case 'hidden': 
					$type = 'hidden'; 
				break;
				case 'file': 
				case 'audio': 
				case 'video': 
				case 'image': 
				//	$requirement = array( 'Base64Image' => array() );
				case 'document': 
			//		var_export( strpos( $formInfo['element_name'][$i], 'base64' ) );
			//		var_export( $formInfo['element_name'][$i] );
					$type = 'Document'; 
					$docSettings = Ayoola_Doc_Settings::getSettings( 'Documents' );
					if( @$options['data-allow_base64'] )
					{
					//	$options['data-allow_base64'] = true;
						
					}
					elseif( Ayoola_Abstract_Table::hasPriviledge( @$docSettings['allowed_uploaders'] ) )
					{ 
						$requirement = array( 'IsFile' => array( 'base_directory' => Ayoola_Doc::getDocumentsDirectory() , 'allowed_extensions' => $this->getParameter( 'allowed_extensions' ) ? explode( ',', $this->getParameter( 'allowed_extensions' ) ) : null ) );
					}
					else
					{
						$type = 'InputText'; 
						
					}
				break;
				default: 
					$type = 'InputText'; 
				break;
			}
		//	var_export( $formInfo['element_name'][$i] . '<br>' );
		//	var_export( $type . '<br>' );
			@$formInfo['element_name'][$i] = $formInfo['element_name'][$i] ? : $formInfo['element_title'][$i];
			$fieldsets[$key]->addElement( array( 'name' => $formInfo['element_name'][$i] ? : $formInfo['element_title'][$i], 'label' => $formInfo['element_title'][$i], 'data-document_type' => $formInfo['element_type'][$i], 'placeholder' => $formInfo['element_placeholder'][$i], 'type' => $type, 'value' => @$values[$formInfo['element_name'][$i]] ? : @$formInfo['element_default_value'][$i] ) + $options );
			$requirement ? $fieldsets[$key]->addRequirement( $formInfo['element_name'][$i], $requirement ) : null;
							
			$i++;
		//	self::v( $i );  
		}
		while( ! empty( $formInfo['element_title'][$i] ) );
	//	self::v( $formInfo['requirements'] );  
	//	$fieldset->addElement( array( 'name' => 'form_name', 'type' => 'Hidden', 'value' => @$formInfo['form_name'] ) );

	//	$form->setFormRequirements( array( 'requirements' => $formInfo['requirements'] ) );
		$form->setParameter( array( 'requirements' => $formInfo['requirements'] ) );
		$form->requiredElements = $form->requiredElements + array( 'form_name' => $formInfo['form_name'] );
		
		//	Add all fieldsets
		foreach( $fieldsets as $each )
		{
		//	self::v( $each );  
		//	$each->addElement( array( 'name' => 'form_name', 'type' => 'hidden', 'value' => @$formInfo['form_name'] ) );
			$form->addFieldset( $each );
		}
		$this->setForm( $form );
    } 
}
