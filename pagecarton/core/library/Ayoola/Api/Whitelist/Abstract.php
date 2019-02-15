<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Api_Whitelist_Abstract
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Api_Whitelist_Abstract
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Api_Whitelist_Abstract extends Ayoola_Api_Abstract
{	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'whitelist_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Api_Whitelist';
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = 'Save' ;
	//	$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		
		$fieldset->addElement( array( 'name' => 'api_label', 'placeholder' => 'Unique name for API', 'type' => 'InputText', 'value' => @$values['api_label'] ) );
		$fieldset->addElement( array( 'name' => 'hash', 'placeholder' => '', 'type' => 'InputText', 'value' => @$values['hash'] ) );
		$fieldset->addElement( array( 'name' => 'public_key', 'placeholder' => '', 'type' => 'InputText', 'value' => @$values['public_key'] ) );
		$fieldset->addRequirements( array( 'WordCount' => array( 4, 100 ) ) );

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
