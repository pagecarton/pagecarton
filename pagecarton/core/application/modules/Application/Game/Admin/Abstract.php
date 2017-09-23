<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Game   Ayoola
 * @package    Application_Game_Admin_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Game_Admin_Exception 
 */
 
require_once 'Application/Game/Exception.php';


/**
 * @Game   Ayoola
 * @package    Application_Game_Admin_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Game_Admin_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'level_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Game';
	
	
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
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true; 
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'level', 'placeholder' => 'Enter level...', 'type' => 'InputText', 'value' => @$values['level'] ) );
		$fieldset->addElement( array( 'name' => 'level_description', 'placeholder' => 'Describe this level in a few words...', 'type' => 'TextArea', 'value' => @$values['level_description'] ) );
		$fieldset->addElement( array( 'name' => 'payout_amount', 'placeholder' => 'Enter the payout amount for this level...', 'type' => 'InputText', 'value' => @$values['payout_amount'] ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
