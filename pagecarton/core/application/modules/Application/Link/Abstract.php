<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Link_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Link_Exception 
 */
 
require_once 'Application/Link/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Link_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Link_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'link_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Link';
	
	
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
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'link_url', 'description' => 'Link Url', 'type' => 'InputText', 'value' => @$values['link_url'] ) );
		}
		$fieldset->addElement( array( 'name' => 'link_domain', 'description' => 'Link Domain', 'type' => 'InputText', 'value' => @$values['link_domain'] ) );
		$fieldset->addElement( array( 'name' => 'link_priority', 'description' => 'Link Priority', 'type' => 'Select', 'value' => @$values['link_priority'] ), array_combine( range( 9, 1 ), range( 9, 1 ) ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirement( 'link_priority', array( 'Range' => array( 0, 10 ) ) );
		$fieldset->addRequirement( 'link_domain', array( 'WordCount' => array( 5,2048 ) ) );
		if( is_null( $values ) )
		{
			$fieldset->addRequirement( 'link_url', array( 'WordCount' => array( 5,2048 ) ) );
		}
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
