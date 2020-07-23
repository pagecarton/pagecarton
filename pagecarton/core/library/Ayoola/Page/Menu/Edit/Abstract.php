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
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Site navigation options'; 
 	
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
	//	var_export( $pages );
		$pages = array_combine( $pages, $pages ) ? : array( '/' => '/' );
		if( @$values['url'] )
		{
			$pages[$values['url']] = $values['url'];
		} 
        $fieldset->addElement( array( 'name' => 'url', 'label' => 'Link URL',  'onchange' => 'ayoola.div.manageOptions( { database: "Ayoola_Page_Page", listWidget: "Ayoola_Page_List", values: "url", labels: "url", element: this } );', 'placeholder' => $url, 'type' => 'Select', 'value' => @$values['url'] ), array_unique( $pages + array( '__manage_options' => '[Manage Pages]', '__custom' => '[Custom URL]' ) ) );
        
        
        $options = new Ayoola_Page_Menu_Menu;
        $menuItems = $options->select();
        if( $identifier = $this->getIdentifier() )
        {
            $fieldset->addElement( array( 'name' => 'menu_id', 'type' => 'Hidden' ) );
            $fieldset->addFilter( 'menu_id', array( 'DefiniteValue' => $identifier['menu_id'] ) );
            //	var_export( $identifier );
            $fieldset->addLegend( $legend );
        }
        else
        {
            //	Sub menu
            $table = new Ayoola_Page_Menu_Menu;
            //	look in parent tables
            $table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
            $menuItems = $table->select();
            $menuList = array();
            foreach( $menuItems as $each )
            {
                $menuList[$each['menu_id']] = $each['menu_label'];
            }
            $fieldset->addElement( array( 'name' => 'menu_id', 'label' => 'Add link option to', 'type' => 'Select', 'value' => @$values['menu_id'] ? : '0-0-19' ), $menuList );
        }

        if( $values )
        {

            $options =  array( 
                                'logged_in' => 'Visible to logged inn users', 
                                'logged_out' => 'Visible to logged out users', 
                                'append_previous_url' => 'Attach previous url to this link', 
                                'spotlight' => 'Pop-up this link in Modal Box', 
                                'new_window' => 'Open this link in a new window', 
                            );
            $fieldset->addElement( array( 'name' => 'link_options', 'label' => 'Link Options', 'type' => 'Checkbox', 'value' => @$values['link_options'] ? : array( 'logged_in', 'logged_out' ) ), $options );

            
            $fieldset->addFilters( array( 'Trim' => null ) );

		
			$authLevel = new Ayoola_Access_AuthLevel;
			$authLevel = $authLevel->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name' );
			$authLevel = $filter->filter( $authLevel );

			
			//	compatibility		
			$authLevel[99] = 'Admin';
			$authLevel[98] = 'Owner';
			$authLevel[1] = 'Signed in users';
			$authLevel[0] = 'Everyone';
			$fieldset->addElement( array( 'name' => 'auth_level', 'label' => 'Who can see this option', 'type' => 'SelectMultiple', 'value' => @$values['auth_level'] ? (array) $values['auth_level'] : array_keys( $authLevel ) ), $authLevel );
			$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $authLevel ) ) );

                //	Sub menu
            $menuList = array();
            foreach( $menuItems as $each )
            {
                if( $each['menu_id'] == $identifier['menu_id'] )
                {
                    continue;
                }
                $menuList[$each['menu_name']] = $each['menu_label'];
            }
            $time = time();
            $menuList = array(  '' => 'NONE', 'sub_menu_' . $time => 'New Menu' ) + $menuList;	


            //	Dont allow the parent menu to be selectable to avoid infinite loop
            $menuList ? $fieldset->addElement( array( 'name' => 'sub_menu_name', 'label' => 'Sub Menu', 'type' => 'Select', 'value' => @$values['sub_menu_name'] ), $menuList ) : null;
        }
		
 		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
