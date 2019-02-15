<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Settings_SettingsName_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton
 * @package    Application_Settings_SettingsName_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Settings_SettingsName_Abstract extends Ayoola_Abstract_Table
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
	protected $_tableClass = 'Application_Settings_SettingsName';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'settingsname_name' );
	
    /**
     * creates the form for creating and editing cycles
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue;
		//	We don't allow editing UNIQUE Keys
		if( is_null( $values ) )
		{		
	//		$fieldset->addElement( array( 'name' => 'settingsname_name', 'label' => 'Give this settings a name', 'type' => 'InputText', 'value' => @$values['settingsname_name'] ) );
		}

	//	$fieldset->addElement( array( 'name' => 'document_url', 'description' => 'Thumbnail for this settings', 'type' => 'InputText', 'value' => @$values['document_url'] ) );
		
/* 		$list = new Ayoola_Object_Table_ViewableObject();
		$list = $list->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'object_name', 'class_name');
		$list = $filter->filter( $list );
		$fieldset->addElement( array( 'name' => 'object_name', 'description' => 'Which object will play this settings', 'type' => 'Select', 'value' => @$values['object_name'] ), array( 0 => 'Select Object' ) + $list );
		$fieldset->addRequirement( 'object_name', array( 'InArray' => array_keys( $list )  ) );
		unset( $list );
 */		
 		$fieldset->addElement( array( 'name' => 'settingsname_title', 'placeholder' => 'Settings Title', 'type' => 'InputText', 'value' => @$values['settingsname_title'] ? : @$values['settingsname_name']  ) );
		$fieldset->addRequirement( 'settingsname_title', array( 'WordCount' => array( 3, 50 ) ) );
/* 
		$filter = new Ayoola_Filter_FilenameToClassname();
			try
			{
				$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'modules';  
			//	var_export( $directory );
				$options = array();  
				if( is_dir( $directory ) )
				{
					$options += Ayoola_Doc::getFilesRecursive( $directory ) ? : array();  

				}
				$directory = Ayoola_Application::getDomainSettings( APPLICATION_DIR ) . DS . 'library';  
				if( is_dir( $directory ) )
				{
					$options += Ayoola_Doc::getFilesRecursive( $directory ) ? : array();  
				}
		//		var_export( $options );
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
	//			$files[$file] = $className;
				$classes[$className] = $className;
			}
		ksort( $classes );
 */ 		$fieldset->addElement( array( 'name' => 'class_name', 'placeholder' => 'Class name', 'type' => 'Select', 'value' => @$values['class_name'] ), Ayoola_Object_Embed::getWidgets() );
		if( is_null( $values ) )
		{		
	//		$fieldset->addRequirement( 'settingsname_name', array( 'Name' => null, 'WordCount' => array( 3,100 )  ) );
		}
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
