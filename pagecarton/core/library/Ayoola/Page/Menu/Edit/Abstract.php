<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_Edit_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Edit_Exception 
 */
 
require_once 'Ayoola/Page/Menu/Edit/Exception.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_Edit_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Page_Menu_Edit_Abstract extends Ayoola_Page_Menu_Abstract
{
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'option_id';
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'option_id', 'menu_id' );
	
    /**
     * Class for Table
     * 
     * param string
     */
	protected $_tableClass = 'Ayoola_Page_Menu_Option';
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function _process()
    {
		try{ $this->setIdentifier(); }
		catch( Ayoola_Page_Menu_Edit_Exception $e ){ return false; }
		return true;
    } 
	
    /**
     * Sets _dbData
     * 
     */
	public function setDbData()
    {
		//	Had a problem with conflicting menu_id as regarding the protected mode.
		//	Solve  the Ambiguity issue by switching to private mode
		$table = get_class( $this->getDbTable() );
	//	var_export( $table );
		$this->getDbTable()->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );
		$table = $this->getDbTable();
		$identifier = $this->getIdentifier();
		$identifier = array( 'menu_id' => $identifier['menu_id'] );
		$this->_dbData = (array) $table->select( null, $identifier );
    } 
	
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
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		
		//	We don't allow editing UNIQUE Keys
		$fieldset->addElement( array( 'name' => 'option_name', 'label' => 'Link Name', 'placeholder' => 'e.g. Contact Us', 'type' => 'InputText', 'value' => @$values['option_name'] ) );
		$fieldset->addRequirement( 'option_name', array( 'WordCount' => array( 1, 500 ) ) );
		$pages = Ayoola_Page::getAll();
		if( @$values['url'] )
		{
			$pages[$values['url']] = $values['url'];
		}
		$fieldset->addElement( array( 'name' => 'url', 'label' => 'URL', 'onchange' => 'if( this.value == \'\' ){ a = prompt( \'New Url\', \'/url\' ); if( ! a ) return false; var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }', 'placeholder' => $url, 'type' => 'Select', 'value' => @$values['url'] ), array_unique( $pages + array( '' => 'Custom URL' ) ) );
	//	$fieldset->addElement( array( 'name' => 'url', 'placeholder' => 'e.g. /site/contact', 'type' => 'InputText', 'value' => @$values['url'] ) );
		$options =  array( 
							'logged_in' => 'Show this menu option to logged inn users', 
							'logged_out' => 'Show this menu option to logged out users', 
							'append_previous_url' => 'Attach previous url to this link', 
					//		'disable' => 'Disable Link', 
							'spotlight' => 'Pop-up this link in Modal Box', 
							'new_window' => 'Open this link in a new window', 
					//		'advanced' => 'Show Advanced Settings',
					//		'sub_menu' => 'This option has a sub-menu', 
							);
		$fieldset->addElement( array( 'name' => 'link_options', 'label' => 'Link Options', 'type' => 'Checkbox', 'value' => @$values['link_options'] ? : array( 'logged_in', 'logged_out' ) ), $options );

		
		$fieldset->addElement( array( 'name' => 'menu_id', 'type' => 'Hidden' ) );
		$fieldset->addFilters( array( 'Trim' => null ) );
		//$this->setIdentifier();  
		$identifier = $this->getIdentifier();
		$fieldset->addFilter( 'menu_id', array( 'DefiniteValue' => $identifier['menu_id'] ) );
		//var_export( $identifier );
		$fieldset->addLegend( $legend );
	//	$form->addFieldset( $fieldset );

		//	Advanced
	//	if( is_array( $this->getGlobalValue( 'link_options' ) ) && in_array( 'advanced', $this->getGlobalValue( 'link_options' ) ) )
		{
		
//			$fieldset = new Ayoola_Form_Element;
//			$fieldset->addLegend( 'Advanced options for menu link.' );
			$authLevel = new Ayoola_Access_AuthLevel;
			$authLevel = $authLevel->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name' );
			$authLevel = $filter->filter( $authLevel );

			
			//	compatibility		
		//	var_export( $values['auth_level'] );  
		//	$values['auth_level'] = is_array( $values['auth_level'] ) ? $values['auth_level'] : array( $values['auth_level'] );
			$authLevel[99] = 'Admin';
			$authLevel[98] = 'Owner';
			$authLevel[1] = 'Signed in users';
			$authLevel[0] = 'Everyone';
	//		var_export( $values['auth_level'] );
			$fieldset->addElement( array( 'name' => 'auth_level', 'label' => 'Privacy', 'type' => 'SelectMultiple', 'value' => @$values['auth_level'] ? (array) $values['auth_level'] : array_keys( $authLevel ) ), $authLevel );
			$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $authLevel ) ) );
		//	unset( $authLevel ); 
	//		$fieldset->addElement( array( 'name' => 'title', 'placeholder' => 'e.g. Home Page', 'type' => 'InputText', 'value' => @$values['title'] ) );

		}

		//	Advanced
//		if( @$values['sub_menu_name'] || is_array( $this->getGlobalValue( 'link_options' ) ) && in_array( 'sub_menu', $this->getGlobalValue( 'link_options' ) ) )
		{
		
	//		$fieldset = new Ayoola_Form_Element;
	//		$fieldset->addLegend( 'Select a menu to use as submenu for this option' );

			//	Sub menu
			$options = new Ayoola_Page_Menu_Menu;
			$options = $options->select();
			$menuList = array();
			foreach( $options as $each )
			{
				if( $each['menu_id'] == $identifier['menu_id'] )
				{
					continue;
				}
				$menuList[$each['menu_name']] = $each['menu_label'];
			}
//			require_once 'Ayoola/Filter/SelectListArray.php';
//			$filter = new Ayoola_Filter_SelectListArray( 'menu_name', 'menu_label' );
			$time = time();
	//		$options = array( 'new_menu_' => $newSubmenuName, '' => 'NONE' ) + $filter->filter( $options );	
			$menuList = array(  '' => 'NONE', 'sub_menu_' . $time => 'New Menu' ) + $menuList;	

			//	Dont allow the parent menu to be selectable to avoid infinite loop
			$data = $this->getIdentifierData();
	//		var_export( $identifier );  
	//		var_export( $data );
	//		unset( $options[$data['menu_name']] );
			$fieldset->addElement( array( 'name' => 'sub_menu_name', 'label' => 'Sub Menu', 'type' => 'Select', 'value' => @$values['sub_menu_name'] ), $menuList );
//			$fieldset->addElement( array( 'name' => 'sub_menu_name', 'label' => 'Sub Menu (<a rel="spotLight" href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Page_Menu_List/">Manage Menu</a>)', 'type' => 'Select', 'value' => @$values['sub_menu_name'] ), $menuList );
		//	$fieldset->addRequirement( 'sub_menu_name', array( 'InArray' => array_keys( $menuList )  ) );
	//		$form->addFieldset( $fieldset );
		}
/* 		else
		{
			$fieldset->addElement( array( 'name' => 'auth_level', 'type' => 'Hidden', 'value' => '0' ) );
			$fieldset->addElement( array( 'name' => 'sub_menu_name', 'type' => 'Hidden', 'value' => null ) );
		}
 */		
 		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
