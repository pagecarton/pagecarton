<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_NotificationMessage_Exception 
 */
 
require_once 'Application/User/NotificationMessage/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_NotificationMessage_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'notificationmessage_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_User_NotificationMessage';
		
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( 'name=>' . $this->getObjectName() );
		$fieldset = new Ayoola_Form_Element;
	//	var_export( $values );
		$fieldset->addElement( array( 'name' => 'from', 'description' => 'Specify the default sender', 'type' => 'InputText', 'value' => @$values['from'] ) );
	//	$fieldset->addRequirement( 'from', array( 'EmailAddress' => null ) );
		$fieldset->addElement( array( 'name' => 'to', 'description' => 'Specify the default recievers. comma separated list', 'type' => 'InputText', 'value' => @$values['to'] ) );
		$fieldset->addElement( array( 'name' => 'subject', 'description' => 'Enter the Subject', 'type' => 'InputText', 'value' => @$values['subject'] ) );
		$fieldset->addRequirement( 'subject', array( 'WordCount' => array( 5, 100 ) ) );
		$fieldset->addElement( array( 'name' => 'body', 'description' => 'Body of the message', 'rows' => 10, 'type' => 'TextArea', 'value' => @$values['body'] ) );
		$fieldset->addRequirement( 'body', array( 'WordCount' => array( 50, 2000 ) ) );
	//	$fieldset->addElement( array( 'name' => 'placeholders', 'description' => 'Generic placeholder used in message e.g. @@@NAME@@@', 'type' => 'InputText', 'value' => @$values['placeholders'] ) );
		$options = array( 1 => 'Email' );
		$fieldset->addElement( array( 'name' => 'mode_id', 'description' => 'select the mode', 'type' => 'Select', 'value' => @$values['mode_id'] ), $options );
		$time = $values ? 'modified_date' : 'creation_date';
		$fieldset->addElement( array( 'name' => $time, 'type' => 'Hidden' ) );
		$fieldset->addFilter( $time, array( 'DefiniteValue' => time() ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
