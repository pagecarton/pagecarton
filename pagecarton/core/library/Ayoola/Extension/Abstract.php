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
		
		//	
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$fieldset->addElement( array( 'name' => 'extension_title', 'label' => 'Plugin Name', 'placeholder' => 'e.g. My Super Plugin', 'onClick' => '', 'type' => 'InputText', 'value' => @$values['extension_title'] ) );
		$fieldset->addRequirement( 'extension_title', array( 'WordCount' => array( 3,100, 'badnews' => 'A Plugin name should be made up of 3 to 100  alphanumeric characters.' ) ) ); 
		$filter = new Ayoola_Filter_FilenameToClassname();
		
		{
			try
			{
				$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'modules';  
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

				$file = str_ireplace( $directory, '', $file );
				
				//	The label is transformed into the class value
				$className = $filter->filter( $file );
				$files[$file] = $className;
				$classes[$className] = $className;
			}
			ksort( $classes );
			asort( $files );
			$fieldset->addElement( array( 'name' => 'modules', 'required' => 'required', 'label' => 'Widgets to Include in Plugin', 'type' => 'SelectMultiple', 'value' => @$values['modules'] ), $files );
			if( $files ) 
			{

			}
			$fieldset->addElement( array( 'name' => 'settings_class', 'label' => 'Settings Widgets', 'type' => 'Select', 'value' => @$values['settings_class'] ), array( '' => 'No Settings' ) + $classes );
 	
		}
		{
 			$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'databases';  

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
				$fieldset->addElement( array( 'name' => 'databases', 'required' => 'required', 'label' => 'Database Data to Include in Plugin', 'type' => 'SelectMultiple', 'value' => @$values['databases'] ), $files );

			}
 	
		}
		{
 			$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'documents';  

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

				$file = str_ireplace( $directory, '', $file );
				$files[$file] = $file;
			}
			asort( $files );
			$fieldset->addElement( array( 'name' => 'documents', 'required' => 'required', 'label' => 'Documents & Files  to Include in Plugin', 'type' => 'SelectMultiple', 'value' => @$values['documents'] ), $files );

			if( $files ) 
			{

			}
 	
		}

		
		{
			$option = new Ayoola_Page_Page;
			$option = $option->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
			$option = $filter->filter( $option );
			asort( $option );
			$fieldset->addElement( array( 'name' => 'pages', 'required' => 'required', 'label' => 'Pages to Include in Plugin', 'type' => 'SelectMultiple', 'value' => @$values['pages'] ), $option );
			if( $option )     
			{

			}
 	
		}
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
