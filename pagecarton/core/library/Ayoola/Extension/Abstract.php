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
		$fieldset->addElement( array( 'name' => 'extension_title', 'label' => 'Give the Plugin a Name', 'placeholder' => 'e.g. My Super Plugin', 'onClick' => '', 'type' => 'InputText', 'value' => @$values['extension_title'] ) );
		$fieldset->addRequirement( 'extension_title', array( 'WordCount' => array( 3,100, 'badnews' => 'Plugin name should be made up of 3 to 100 alphanumeric characters.' ) ) ); 
		$filter = new Ayoola_Filter_FilenameToClassname();
        
        //  widgets
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
            if( is_subclass_of( $className, PageCarton_Settings ) )
            {
                $classes[$className] = $className;
            }
            else
            {
                $files[$file] = $className;
            }
        }
        ksort( $classes );
        asort( $files );

        if( $files )
        {
            $fieldset->addElement( array( 'name' => 'modules', 'required' => 'required', 'label' => 'Plugin Widgets', 'type' => 'SelectMultiple', 'value' => @$values['modules'] ), $files );
        }

        //  settings
        if( $files )
        {
            $fieldset->addElement( array( 'name' => 'settings_class', 'label' => 'Plugin Settings Widget', 'type' => 'Select', 'value' => @$values['settings_class'] ), array( '' => 'No Settings' ) + $classes );
        }

        //  database
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
            $fieldset->addElement( array( 'name' => 'databases', 'required' => 'required', 'label' => 'Plugin Databases', 'type' => 'SelectMultiple', 'value' => @$values['databases'] ), $files );
        }

        //  documents
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
        if( $files )
        {
            $fieldset->addElement( array( 'name' => 'documents', 'required' => 'required', 'label' => 'Plugin Documents', 'type' => 'SelectMultiple', 'value' => @$values['documents'] ), $files );
        }


        //  Pages
        $option = Ayoola_Page_Page::getInstance()->select();
        $filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
        $option = $filter->filter( $option );
        asort( $option );
        if( $option )
        {
            $fieldset->addElement( array( 'name' => 'pages', 'required' => 'required', 'label' => 'Plugin Pages', 'type' => 'SelectMultiple', 'value' => @$values['pages'] ), $option );
        }

        //  Dependencies
        $option = Ayoola_Extension_Import_Table::getInstance()->select();
        $filter = new Ayoola_Filter_SelectListArray( 'article_url', 'extension_title');
        $option = $filter->filter( $option );
        asort( $option );
        if( $option )
        {
            $fieldset->addElement( array( 'name' => 'dependencies', 'required' => 'required', 'label' => 'Plugin Dependencies', 'type' => 'SelectMultiple', 'value' => @$values['dependencies'] ), $option );
        }
 	
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
