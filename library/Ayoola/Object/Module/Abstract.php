<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Module_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see Ayoola_Abstract_Table
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Module_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Object_Module_Abstract extends Ayoola_Abstract_Table
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
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Ayoola_Object_Table_Module';
	
    /**
     * Key for the id column
     * 
     * @var string
     */
	protected $_identifierKeys = array( 'module_id' );
		
    /**
     * 
     *
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'module_name', 'description' => 'Name of Module', 'type' => 'InputText', 'value' => @$values['module_name'] ) );
		}
		
		$options = new Ayoola_Access_AuthLevel;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name' );
		$options = $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'auth_level', 'description' => 'Least User Level that can View', 'type' => 'Select', 'value' => @$values['auth_level'] ), $options );
		$fieldset->addRequirement( 'auth_level', array( 'Int' => null, 'InArray' => array_keys( $options )  ) );
		unset( $options );

		//	$fieldset->addElement( array( 'name' => 'backup_options', 'description' => 'Select what to back up', 'type' => 'selectMultiple', 'value' => @$values['backup_options'] ), self::getAvailableBackupOptions() );
		$fieldset->addRequirements( array( 'WordCount' => array( 1,200 ) ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		if( is_null( $values ) )
		{
			$fieldset->addRequirement( 'module_name', array( 'WordCount' => array( 3,100 ), 'Name' => null ) );
		}
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
	}
	// END OF CLASS
}
