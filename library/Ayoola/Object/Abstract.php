<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Abstract
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
 * @package    Ayoola_Object_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Object_Abstract extends Ayoola_Abstract_Table
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
     * Error messages
     * 
     * @var string
     */
	const ERROR_MESSAGE_OBJECT_NOT_INDICATED = 'NO OBJECT';
	const ERROR_MESSAGE_OBJECT_NOT_FOUND = 'OBJECT NOT FOUND';
	const ERROR_MESSAGE_OBJECT_NOT_PLAYABLE = 'OBJECT NOT PLAYABLE';
	const ERROR_MESSAGE_ACCESS_DENIED = 'ACCESS DENIED';
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Ayoola_Object_Table_ViewableObject';
	
    /**
     * Key for the id column
     * 
     * @var string
     */
	protected $_identifierKeys = array( 'object_name' );
		
    /**
     * Checks Object
     *
     * @param array Object Info
     */
    public static function checkObject( Array $objectInfo = null )
    {
		if( empty( $objectInfo['object_name'] ) ){ throw new Ayoola_Object_Exception( self::ERROR_MESSAGE_OBJECT_NOT_INDICATED ); }
		
		//	Make sure Classname is not injected
		$objectInfo['auth_level'] = ! isset( $objectInfo['auth_level'] ) ? 99 : intval( $objectInfo['auth_level'] );
		//	Check If I am authorized to play this class
		require_once 'Ayoola/Access.php';
		Ayoola_Access::restrict( $objectInfo['auth_level'] );
//		var_export( $objectInfo['auth_level'] );
		
		if( ! Ayoola_Loader::loadClass( $objectInfo['class_name'] ) )
		{ 
			throw new Ayoola_Exception( 'OBJECT NOT FOUND'  );
		}
		//	Instantiate Class
		$object = strpos( $objectInfo['class_name'], '::' ) ? $objectInfo['class_name'] : new $objectInfo['class_name'];
		if( ! $object instanceof Ayoola_Object_Interface_Playable ){ throw new Ayoola_Object_Exception( self::ERROR_MESSAGE_OBJECT_NOT_PLAYABLE ); }
    }
	
	//	This is to implement the abstract method of the parent class. Not all inheriting classes needs a form
	public function createForm( $submitValue, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( 'name=>' . $this->getObjectName() );
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'class_name', 'label' => 'PHP Class Name', 'placeholder' => 'Name of Class', 'type' => 'InputText', 'value' => @$values['class_name'] ) );
		//	$fieldset->addElement( array( 'name' => 'object_name', 'label' => 'Unique Name', 'placeholder' => 'Give this class a nickname', 'type' => 'InputText', 'value' => @$values['object_name'] ) );
		}
		$fieldset->addElement( array( 'name' => 'view_parameters', 'label' => 'Display Name', 'placeholder' => 'Describe this Object', 'type' => 'InputText', 'value' => @$values['view_parameters'] ) );
/* 		
		$options = new Ayoola_Access_AuthLevel;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name' );
		$options = $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'auth_level', 'placeholder' => 'Minimum User level to view', 'type' => 'Select', 'value' => @$values['auth_level'] ), $options );
		$fieldset->addRequirement( 'auth_level', array( 'Int' => null, 'InArray' => array_keys( $options )  ) );
		unset( $options );

 */		
/* 		$options = new Ayoola_Object_Table_Module;
		$options = $options->select();
	//	var_export( $options );
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'module_id', 'module_name' );
		$options = $filter->filter( $options );
	//	var_export( $options );
		$fieldset->addElement( array( 'name' => 'module_id', 'label' => '', 'description' => 'Add this class to a module stack. <a rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_Module_Creator/">Add Module</a>', 'type' => 'Select', 'value' => @$values['module_id'] ), $options );
		$fieldset->addRequirement( 'module_id', array( 'Int' => null, 'InArray' => array_keys( $options )  ) );
		unset( $options );
 */
		//	$fieldset->addElement( array( 'name' => 'backup_options', 'description' => 'Select what to back up', 'type' => 'selectMultiple', 'value' => @$values['backup_options'] ), self::getAvailableBackupOptions() );
		$fieldset->addRequirements( array( 'WordCount' => array( 3,200 ) ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		if( is_null( $values ) )
		{
			$fieldset->addRequirement( 'class_name', array( 'WordCount' => array( 4,100 ), 'Name' => null ) );
	//		$fieldset->addRequirement( 'object_name', array( 'WordCount' => array( 4,100 ), 'Name' => null ) );
		}
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
	}
	// END OF CLASS
}
