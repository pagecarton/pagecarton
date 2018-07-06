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
	protected static $_accessLevel = array( 99, 98 );
 	
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
     * Overides the parent class
     * 
     */
	public function setIdentifierData( $identifier = null )
    {
		do
		{
			$table  = $this->getDbTable();
			if( $data = $this->getDbTable()->selectOne( null, $this->getIdentifier() ) )
			{ 
				break; 
			}
			
			//	look in parent tables
			$this->getDbTable()->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
			
			if( $data = $this->getDbTable()->selectOne( null, $this->getIdentifier(), array( 'work-arround-1-333' => true ) ) )
			{ 
				break; 
			}
		}
		while( false );
		$this->_identifierData = $data;  
    } 

    /**
     * creates the form for creating and editing menu
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		
		//	We don't allow editing UNIQUE Keys
	//	if( is_null( $values ) )
		{		
			$fieldset->addElement( array( 'name' => 'menu_label', 'label' => 'Menu Title', 'type' => @$_REQUEST['menu_label'] ? 'Hidden' : 'InputText', 'value' => @$values['menu_label'] ) );
			$fieldset->addRequirement( 'menu_label', array( 'WordCount' => array( 3, 50 )  ) );
		}	
		$options =  array( 
					//		'logged_in_hide' => 'Hide from logged inn users', 
					//		'logged_out_hide' => 'Hide from logged out users', 
					//		'private' => 'Hide options on sub-domains', 
					//		'disable' => 'Disable Menu',
							'auto_sub_menu' => 'Auto-Create sub menu for all options ', 
							'category' => 'Add post category links', 
							'sort_order' => 'Manually arrange the menu order' 
							);   
		$fieldset->addElement( array( 'name' => 'menu_options', 'label' => 'Menu Options', 'type' => 'Checkbox', 'value' => @$values['menu_options'] ), $options );
		
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
				$adminOptions = '<button type="button" class="pc-btn pc-btn-small" title="Add a new category" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Articles/\', \'page_refresh\' )"> Manage Categories </button> ';
			}
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Add links from a post category'  );
	//		$fieldset->addElement( array( 'name' => 'keywords', 'placeholder' => 'Comma-separated keywords for search engines', 'type' => 'InputText', 'value' => @$values['keywords'] ) );   
			
	//		$options = new Application_Category;
			$options = Application_Category_ShowAll::getPostCategories();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label', 'category_name' );
			$options = $filter->filter( $options );
			//	$adminOptions
			$fieldset->addElement( array( 'name' => 'category_name', 'label' => 'Select post categories to list as links', 'type' => 'SelectMultiple', 'footnote' => @$adminOptions, 'value' => @$values['category_name'] ), $options ); 
	//		$fieldset->addRequirement( 'category_name', array( 'InArray' => array_keys( $options )  ) );   

			$fieldset->addElement( array( 'name' => 'category_url', 'label' => 'Category Url e.g. /page/', 'type' => 'InputText', 'value' => @$values['category_url'] ) ); 
			$fieldset->addElement( array( 'name' => 'url_integration_type', 'label' => 'URL Integration type', 'type' => 'Select', 'value' => @$values['url_integration_type'] ), array( '' => 'Use Query Strings', 'pc_module_url_values_offset' => 'URL Suffix' ) );      
			unset( $options );
			$form->addFieldset( $fieldset );
		}
		if( is_array( Ayoola_Form::getGlobalValue( 'menu_options' ) ) && in_array( 'sort_order', Ayoola_Form::getGlobalValue( 'menu_options' ) ) )
		{
			if( @$values['menu_name'] )     
			{
				$optionTable = new Ayoola_Page_Menu_Option();
			//	var_export( $values['menu_name'] );
				$optionsList = $optionTable->select( null, array( 'menu_id' => $values['menu_id'] ) );
				$optionsList = array_map(create_function('$ar', 'return $ar["url"];'), $optionsList);
				sort( $optionsList );
		//		$optionsList = self::sortMultiDimensionalArray( $optionsList, 'url' );  
			}
		//	var_export( $optionsList );
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Sort Menu Options Order' );
			$fieldset->addElement( array( 'name' => 'default_order', 'readonly' => 'readonly', 'label' => 'Default Order', 'type' => 'TextArea', 'value' => implode( ',', $optionsList ) ) );
		//	var_export( $values );  
			$fieldset->addElement( array( 'name' => 'sort_order', 'label' => 'Comma-separated list of links in the order it would be displayed.', 'type' => 'TextArea', 'value' => @$values['sort_order'] ? : implode( ',', $optionsList ) ) );
			$form->addFieldset( $fieldset );
		}

		$this->setForm( $form );
    }   
	// END OF CLASS
}
