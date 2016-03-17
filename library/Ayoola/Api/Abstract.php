<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Api_Abstract
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Api_Abstract
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Api_Abstract extends Ayoola_Api
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
	protected $_identifierKeys = array( 'api_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Api_Api';
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = 'Save' ;
	//	$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		
		$fieldset->addElement( array( 'name' => 'api_label', 'placeholder' => 'Unique name for API', 'type' => 'InputText', 'value' => @$values['api_label'] ) );
		if( ! $values )
		{
			$fieldset->addElement( array( 'name' => 'api_url', 'placeholder' => 'URL for the API', 'type' => 'InputText', 'value' => @$values['api_url'] ) );
		}
		$fieldset->addRequirements( array( 'WordCount' => array( 4, 100 ) ) );
		$fieldset->addElement( array( 'name' => 'application_id', 'placeholder' => 'Unique App ID', 'type' => 'InputText', 'value' => @$values['application_id'] ) );
		$fieldset->addElement( array( 'name' => 'private_key', 'placeholder' => '', 'type' => 'InputText', 'value' => @$values['private_key'] ) );
		$fieldset->addElement( array( 'name' => 'public_key', 'placeholder' => '', 'type' => 'InputText', 'value' => @$values['public_key'] ) );
		$fieldset->addElement( array( 'name' => 'application_salt', 'placeholder' => '', 'type' => 'InputText', 'value' => @$values['application_salt'] ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
