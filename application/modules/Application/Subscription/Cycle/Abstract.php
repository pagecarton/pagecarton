<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Cycle_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Cycle_Exception 
 */
 
require_once 'Application/Subscription/Cycle/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Cycle_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Cycle_Abstract extends Ayoola_Abstract_Table
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
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Cycle';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'cycle_id' );
	
    /**
     * creates the form for creating and editing cycles
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		
		//	We don't allow editing UNIQUE Keys
		if( is_null( $values ) )
		{		
			$fieldset->addElement( array( 'name' => 'cycle_name', 'description' => 'e.g. Yearly', 'type' => 'InputText', 'value' => @$values['cycle_name'] ) );
		}
		$fieldset->addElement( array( 'name' => 'cycle_label', 'description' => 'e.g. Year(s)', 'type' => 'InputText', 'value' => @$values['cycle_label'] ) );
	//	$options =  array( 'No', 'Yes' );
	//	$fieldset->addElement( array( 'name' => 'enabled', 'description' => 'Allow subscription to this package', 'type' => 'Select', 'value' => @$values['enabled'] ), $options );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addRequirements( array( 'NotEmpty' => null ,'WordCount' => array( 1,100 ) ) );
		if( is_null( $values ) )
		{		
			$fieldset->addRequirement( 'cycle_name', array( 'WordCount' => array( 3,100 )  ) );
		}
		$fieldset->addFilters( array( 'Trim' => null, 'Escape' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
