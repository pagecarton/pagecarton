<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @Message   Ayoola
 * @package    Application_Message_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Message_Exception 
 */
 
require_once 'Application/Message/Exception.php';


/**
 * @Message   Ayoola
 * @package    Application_Message_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
	//	$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->useDivTagForElement = false; 
		$form->submitValue = $submitValue;
        $to = @$values['to'] ? : @Ayoola_Application::$GLOBAL['profile']['profile_url'];
        $to = $to ? : $_GET['to'];
    //    var_export( $values['to'] );
    //    var_export( $_GET['to'] );
   //     var_export( $to );
		$fieldset->addElement( array( 'name' => 'message', 'label' => '', 'placeholder' => 'Start typing your message to @' . $to . ' here...', 'type' => 'TextArea', 'value' => @$values['message'] ) );
		$fieldset->addRequirement( 'message', array( 'WordCount' => array( 3, 1000 ) ) );
		
		$fieldset->addElement( array( 'name' => 'to', 'placeholder' => '', 'type' => 'Hidden', 'value' => $to ) );

        $profiles = Application_Profile_ShowAll::getMyProfiles();
        $profiles = array_combine( $profiles, $profiles );
		$fieldset->addElement( array( 'name' => 'from', 'placeholder' => '', 'type' => count( $profiles ) > 1 ? 'Select' : 'Hidden', 'value' => @$values['from'] ? : Ayoola_Application::getUserInfo( 'profile_url' ) ), $profiles );
	//	$fieldset->addElement( array( 'name' => 'reference', 'multiple' => 'multiple', 'placeholder' => '', 'type' => 'Hidden', 'value' => @$values['reference'] ? : Ayoola_Application::getUserInfo( 'profile_url' ) ) );
	//	$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
