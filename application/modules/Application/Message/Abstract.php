<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @Message   Ayoola
 * @package    Application_Message_Abstract
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Message_Exception 
 */
 
require_once 'Application/Message/Exception.php';


/**
 * @Message   Ayoola
 * @package    Application_Message_Abstract
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

abstract class Application_Message_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'message_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Message';
			
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->useDivTagForElement = false; 
		$form->submitValue = $submitValue;
		$fieldset->addElement( array( 'name' => 'message', 'placeholder' => 'Start typing your message here...', 'type' => 'TextArea', 'value' => @$values['message'] ) );
		$fieldset->addRequirement( 'message', array( 'WordCount' => array( 3, 1000 ) ) );
		
		$fieldset->addElement( array( 'name' => 'to', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['to'] ? : @Ayoola_Application::$GLOBAL['username'] ) );
		$fieldset->addElement( array( 'name' => 'from', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['from'] ? : Ayoola_Application::getUserInfo( 'username' ) ) );
		$fieldset->addElement( array( 'name' => 'reference', 'multiple' => 'multiple', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['reference'] ? : Ayoola_Application::getUserInfo( 'username' ) ) );
	//	$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
