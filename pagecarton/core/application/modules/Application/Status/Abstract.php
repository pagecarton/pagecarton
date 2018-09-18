<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Status   Ayoola
 * @package    Application_Status_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Status_Exception 
 */
 
require_once 'Application/Status/Exception.php';


/**
 * @Status   Ayoola
 * @package    Application_Status_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Status_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'status_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Status';
			
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->useDivTagForElement = false;
		$form->submitValue = $submitValue;
		$fieldset->addElement( array( 'name' => 'status', 'placeholder' => 'Start typing your status update here...', 'type' => 'TextArea', 'value' => @$values['status'] ) );
		$fieldset->addRequirement( 'status', array( 'WordCount' => array( 3, 300 ) ) );
		
		$fieldset->addElement( array( 'name' => 'class_name', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['class_name'] ) );
		$fieldset->addElement( array( 'name' => 'object', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['object'] ? : @Ayoola_Application::$GLOBAL['profile']['username'] ) );
		$fieldset->addElement( array( 'name' => 'subject', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['subject'] ? : Ayoola_Application::getUserInfo( 'username' ) ) );
		$fieldset->addElement( array( 'name' => 'reference', 'multiple' => 'multiple', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['reference'] ? : Ayoola_Application::getUserInfo( 'username' ) ) );
	//	$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );

		//	Admin
/* 		if( self::hasPriviledge() )
		{
			$fieldset = new Ayoola_Form_Element;
			$fieldset->placeholderInPlaceOfLabel = true;
			$fieldset->useDivTagForElement = false;
			$fieldset->addElement( array( 'name' => 'featured', 'label' => 'Make this a featured post', 'type' => 'Radio', 'value' => @$values['featured'] ), array( 'No', 'Yes' ) );
		//	$fieldset->addRequirement( 'featured', array( 'Range' => array( 0, 1 ) ) );
			$fieldset->addLegend( $legend );
			$form->addFieldset( $fieldset );
		}
 */		
		$form->setFormRequirements( array( 'user-registration' ) );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
