<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Log_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Exception 
 */
 
require_once 'Application/Log/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Log_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Log_Abstract extends Ayoola_Abstract_Table
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'log_name' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Log_Log';
	
    /**
     * Array of Log Content
     * 
     * @var array
     */
	protected $_log;
	
    /**
     * Sets the log
     * 
     */
	public function setLog()
	{
	//	var_export( $_SERVER );
		$data = $this->getIdentifierData();
		$logViewer = $data['log_viewer'];
		if( $path = Ayoola_Loader::checkFile( $logViewer ) )
		{
			$log = file( $path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
			krsort( $log );	// Let it start from the least entries
		}
		else
		{
			//	log viewer is a class
			if( ! $class = Ayoola_Loader::loadClass( $logViewer ) )
			{
				throw new Application_Log_Exception( 'INVALID LOG VIEWER' );
			}
			$log = $logViewer::viewLog();
		}
		$this->_log = $log;	
    }
	
    /**
     * Gets the log
     * 
     */
	public function getLog()
	{
		if( is_null( $this->_log ) ){ $this->setLog(); }
		return $this->_log;
    }
	
    /**
     * creates the form for creating and editing Log package
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
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'log_name', 'description' => 'Name this log', 'type' => 'InputText', 'value' => @$values['log_name'] ) );
		}
		$fieldset->addElement( array( 'name' => 'log_viewer', 'description' => 'Filename or Class to that populates this log', 'type' => 'InputText', 'value' => @$values['log_viewer'] ) );
		$fieldset->addElement( array( 'name' => 'log_description', 'description' => 'Describe this log', 'type' => 'TextArea', 'value' => @$values['log_description'] ) );
		$authLevel = new Ayoola_Access_AuthLevel;
		$authLevel = $authLevel->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name' );
		$authLevel = $filter->filter( $authLevel );
		$fieldset->addElement( array( 'name' => 'auth_level', 'description' => 'Least user-level to view log', 'type' => 'Select', 'value' => @$values['auth_level'] ), $authLevel );
		$fieldset->addRequirement( 'auth_level', array( 'Int' => null, 'InArray' => array_keys( $authLevel )  ) );
		unset( $authLevel );
		$fieldset->addRequirements( array( 'WordCount' => array( 1,200 ) ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		if( is_null( $values ) )
		{
			$fieldset->addRequirement( 'log_name', array( 'WordCount' => array( 4,100 ) ) );
		}
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
