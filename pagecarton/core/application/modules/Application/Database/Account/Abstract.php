<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Database_Account_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Database_Account_Exception 
 */
 
require_once 'Application/Database/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Database_Account_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Database_Account_Abstract extends Ayoola_Abstract_Table
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'account_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Database_Account';
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
	//	var_export( $values['days_of_the_week'] );
	
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'username', 'type' => 'InputText', 'value' => @$values['username'] ) );
		
		$fieldset->addElement( array( 'name' => 'password', 'placeholder' => '', 'type' => 'InputText', 'value' => @$values['password'] ) );
		$fieldset->addElement( array( 'name' => 'hostname', 'placeholder' => '', 'type' => 'InputText', 'value' => @$values['hostname'] ) );
		$fieldset->addRequirements( array( 'WordCount' => array( 3, 50 ) ) );
		$options = array( 'Mysql' => 'MySQL', 'Xml' => 'XML (experimental)' );
		$fieldset->addElement( array( 'name' => 'adapter', 'type' => 'Radio', 'value' => @$values['adapter'] ), $options );
		$fieldset->addRequirement( 'adapter', array( 'InArray' => array_keys( $options )  ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$form->submitValue = $submitValue;
		$this->setForm( $form );
    } 
	// END OF CLASS
}
