<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Exception 
 */
 
require_once 'Ayoola/Page/Menu/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Page_Menu_Abstract extends Ayoola_Abstract_Table
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
	protected $_idColumn = 'menu_id';
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'menu_id' );
	
    /**
     * Class for Table
     * 
     * param string
     */
	protected $_tableClass = 'Ayoola_Page_Menu_Menu';
	
    /**
     * creates the form for creating and editing menu
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
		
		//	We don't allow editing UNIQUE Keys
	//	if( is_null( $values ) )
		{		
			$fieldset->addElement( array( 'name' => 'menu_label', 'type' => 'InputText', 'value' => @$values['menu_label'] ) );
			$fieldset->addRequirement( 'menu_label', array( 'WordCount' => array( 3, 50 )  ) );
		}	
		
/* 		require_once 'Ayoola/Doc.php';		
		$doc = new Ayoola_Doc_Document;
		$doc = $doc->select();
		$filter = new Ayoola_Filter_FileExtention();
		foreach( $doc as $key => $each )
		{
			if( $filter->filter( $each['document_url'] ) != 'css' ){ unset( $doc[$key] ); }
		}
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'document_url', 'document_name' );
		$doc = $filter->filter( $doc );	
		$fieldset->addElement( array( 'name' => 'document_url', 'label' => 'Menu style', 'type' => 'Select', 'value' => @$values['document_url'] ), $doc );
	//	$fieldset->addRequirement( 'document_url', array( 'InArray' => array_keys( $doc )  ) );
		unset( $doc );
 */		
		$options =  array( 'logged_in_hide' => 'Hide from logged inn users', 'logged_out_hide' => 'Hide from logged out users', 'private' => 'Hide options on sub-domains', 'disable' => 'Disable Menu', 'category' => 'Add links from a post category' );
		$fieldset->addElement( array( 'name' => 'menu_options', 'label' => 'Menu Options', 'type' => 'Checkbox', 'value' => @$values['menu_options'] ), $options );
		
/* 		$options =  array( 'No', 'Yes' );
		$fieldset->addElement( array( 'name' => 'enabled', 'type' => 'Select', 'value' => @$values['enabled'] ), $options );
 */	//	$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		
	//	$fieldset->addRequirement( 'enabled', array( 'Range' => array( 0, 1 ) ) );
		$fieldset->addFilters( array( 'Trim' => null ) );
		$fieldset->addLegend( $legend );
		
		$fieldset->addFilters( 'StripTags::Trim' ); 
		$form->addFieldset( $fieldset );   
//		if( is_null( $values ) ){ $fieldset->addFilter( 'menu_name', array( 'Name' => null ) ); }
		if( is_array( Ayoola_Form::getGlobalValue( 'menu_options' ) ) && in_array( 'category', Ayoola_Form::getGlobalValue( 'menu_options' ) ) )
		{
			if( self::hasPriviledge() )
			{
				$adminOptions = '<button type="button" class="" title="Add a new category" rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_List/\' )"> Category Options </button> ';
			}
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Add links from a post category' . $adminOptions );
	//		$fieldset->addElement( array( 'name' => 'keywords', 'placeholder' => 'Comma-separated keywords for search engines', 'type' => 'InputText', 'value' => @$values['keywords'] ) );
			
			$options = new Application_Category;
			$options = $options->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
			$options = $filter->filter( $options );
			$fieldset->addElement( array( 'name' => 'category_name', 'label' => 'Select post categories to list as links', 'type' => 'Checkbox', 'value' => @$values['category_name'] ), $options ); 
			$fieldset->addRequirement( 'category_name', array( 'InArray' => array_keys( $options )  ) );
			unset( $options );
			$form->addFieldset( $fieldset );
		}

		$this->setForm( $form );
    } 
	// END OF CLASS
}
