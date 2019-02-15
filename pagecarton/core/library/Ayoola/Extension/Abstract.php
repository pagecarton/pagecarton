<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Extension_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Exception 
 */
 
require_once 'Ayoola/Page/Layout/Exception.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Extension_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Extension_Abstract extends Ayoola_Abstract_Table
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
     * 
     *
     * @var string
     */
	protected $_idColumn = 'extension_name';  
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'extension_name' );
 		
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Extension';
	

    /**
     * creates the form for creating and editing subscription package
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		
	
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$fieldset->addElement( array( 'name' => 'extension_title', 'label' => 'Plugin Name', 'placeholder' => 'e.g. My Super Plugin', 'onClick' => '', 'type' => 'InputText', 'value' => @$values['extension_title'] ) );
		$fieldset->addRequirement( 'extension_title', array( 'WordCount' => array( 3,100, 'badnews' => 'A Plugin name should be made up of 3 to 100  alphanumeric characters.' ) ) ); 
/*
		$options = array( 
							'modules' => 'Modules',
							'databases' => 'Database Tables Data',
							'documents' => 'Documents & Files',
					//		'settings' => 'Settings',
							'pages' => 'Pages',
					//		'templates' => 'Themes',
						);
		$fieldset->addElement( array( 'name' => 'components', 'label' => 'Plugin Components', 'required' => 'required', 'type' => 'Checkbox', 'value' => @$values['components'] ), $options );
		$fieldset->addRequirement( 'components', array( 'ArrayKeys' => $options + array( 'badnews' => 'You cannot create an empty Plugin. Please select components to include.' ) ) );
*/		

		$filter = new Ayoola_Filter_FilenameToClassname();
		
	//	if( is_array( Ayoola_Form::getGlobalValue( 'components' ) ) && in_array( 'modules', Ayoola_Form::getGlobalValue( 'components' ) ) )
		{
			try
			{
			//	$options = Ayoola_Doc::getFiles( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . '/modules', array( 'return_directories' => true ) );  
				$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'modules';  
			//	var_export( $directory );
				$options = Ayoola_Doc::getFilesRecursive( $directory );  
			}
			catch( Exception $e )
			{
				$options = array(); 
			}
			$files = array();
			$classes = array();
			foreach( $options as $file )
			{
				$directory = str_ireplace( DS, '/', $directory );
				$file = str_ireplace( DS, '/', $file );
	//			var_export( $directory );
	//			var_export( $file );
				$file = str_ireplace( $directory, '', $file );
				
				//	The label is transformed into the class value
				$className = $filter->filter( $file );
				$files[$file] = $className;
				$classes[$className] = $className;
			}
			ksort( $classes );
			asort( $files );
			$fieldset->addElement( array( 'name' => 'modules', 'required' => 'required', 'label' => 'Plugin Modules', 'type' => 'SelectMultiple', 'value' => @$values['modules'] ), $files );
			if( $files ) 
			{
			//	$fieldset->addRequirement( 'modules', array( 'ArrayKeys' => $files + array( 'badnews' => 'Please select the modules you want to include in the Plugin' )  ) );
			}
			$fieldset->addElement( array( 'name' => 'settings_class', 'label' => 'Settings Module', 'type' => 'Select', 'value' => @$values['settings_class'] ), array( '' => 'No Settings' ) + $classes );
 	
		}
/*		else
		{
			$fieldset->addElement( array( 'name' => 'modules', 'type' => 'Hidden', 'value' => '' ) );
			$fieldset->addElement( array( 'name' => 'settings_class', 'type' => 'Hidden', 'value' => '' ) );
		}
*/		
//		if( is_array( Ayoola_Form::getGlobalValue( 'components' ) ) && in_array( 'databases', Ayoola_Form::getGlobalValue( 'components' ) ) )
		{
 			$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'databases';  
		//	var_export( $options );
			try
			{
			//	$options = Ayoola_Doc::getFiles( $options, array( 'return_directories' => true ) );  
				$options = Ayoola_Doc::getFilesRecursive( $directory );  
			}
			catch( Exception $e )
			{
				$options = array(); 
			}
			$files = array();
			foreach( $options as $file )
			{
				$directory = str_ireplace( DS, '/', $directory );
				$file = str_ireplace( DS, '/', $file );
	//			var_export( $directory );
	//			var_export( $file );
				$file = str_ireplace( $directory, '', $file );
				
				//	The label is transformed into the class value
				$className = $filter->filter( $file );
				if( stripos( $className, '__' ) )
				{
					continue;
				}
				$files[$file] = $className;
			}
			asort( $files );
			if( $files ) 
			{
				$fieldset->addElement( array( 'name' => 'databases', 'required' => 'required', 'label' => 'Database Table Data', 'type' => 'SelectMultiple', 'value' => @$values['databases'] ), $files );
			//	$fieldset->addRequirement( 'databases', array( 'ArrayKeys' => $files + array( 'badnews' => 'Please select the database tables you want to include in the Plugin' )  ) );
			}
 	
		}
/*		else
		{
			$fieldset->addElement( array( 'name' => 'databases', 'type' => 'Hidden', 'value' => '' ) );
		}
*/		
//		if( is_array( Ayoola_Form::getGlobalValue( 'components' ) ) && in_array( 'documents', Ayoola_Form::getGlobalValue( 'components' ) ) )
		{
 			$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'documents';  
		//	var_export( $options );
			try
			{
				$options = Ayoola_Doc::getFilesRecursive( $directory ); 
			}
			catch( Exception $e )
			{
				$options = array(); 
			}
			$files = array();
			foreach( $options as $file )
			{
				$directory = str_ireplace( DS, '/', $directory );
				$file = str_ireplace( DS, '/', $file );
	//			var_export( $directory );
	//			var_export( $file );
				$file = str_ireplace( $directory, '', $file );
				$files[$file] = $file;
			}
			asort( $files );
			$fieldset->addElement( array( 'name' => 'documents', 'required' => 'required', 'label' => 'Documents & Files', 'type' => 'SelectMultiple', 'value' => @$values['documents'] ), $files );
	//		$fieldset->addElement( array( 'name' => 'upload_document', 'label' => ' ', 'type' => 'document', 'value' => @$values['upload_document'] ) );
			if( $files ) 
			{
			//	$fieldset->addRequirement( 'documents', array( 'ArrayKeys' => $files + array( 'badnews' => 'Please select documents you want to include in the Plugin' )  ) );
			}
 	
		}
/*		else
		{
			$fieldset->addElement( array( 'name' => 'documents', 'type' => 'Hidden', 'value' => '' ) );
		}
*/		

		
	//	if( is_array( Ayoola_Form::getGlobalValue( 'components' ) ) && in_array( 'pages', Ayoola_Form::getGlobalValue( 'components' ) ) )
		{
			$option = new Ayoola_Page_Page;
			$option = $option->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
			$option = $filter->filter( $option );
			asort( $option );
			$fieldset->addElement( array( 'name' => 'pages', 'required' => 'required', 'label' => 'Pages', 'type' => 'SelectMultiple', 'value' => @$values['pages'] ), $option );
			if( $option )     
			{
			//	$fieldset->addRequirement( 'pages', array( 'ArrayKeys' => $option + array( 'badnews' => 'Please select pages you want to include in the Plugin' )  ) );
			}
 	
		}
/*		else
		{
			$fieldset->addElement( array( 'name' => 'pages', 'type' => 'Hidden', 'value' => '' ) );
		}
*//*		
		if( is_array( Ayoola_Form::getGlobalValue( 'components' ) ) && in_array( 'templates', Ayoola_Form::getGlobalValue( 'components' ) ) )
		{
			$option = new Ayoola_Page_PageLayout;
			$option->getDatabase()->setAccessibility( $option::SCOPE_PRIVATE );      
			$option = $option->select( null, null, array( 'work-arround-333' => true ) );
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'layout_name', 'layout_label');
			$option = $filter->filter( $option );
			asort( $option );
			$fieldset->addElement( array( 'name' => 'templates', 'required' => 'required', 'label' => 'Select the layout themes <a href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Page_Layout_List/" target="_blank">(themes)</a>', 'type' => 'SelectMultiple', 'value' => @$values['templates'] ), $option );
			if( $option ) 
			{
				$fieldset->addRequirement( 'templates', array( 'ArrayKeys' => $option + array( 'badnews' => 'Please select themes you want to include in the Plugin' )  ) );
			}
 	
		}
		else
		{
			$fieldset->addElement( array( 'name' => 'templates', 'type' => 'Hidden', 'value' => '' ) );
		}
*/		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
