<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @whois   Ayoola
 * @package    Application_Domain_Registration_Whois_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Whois_Exception 
 */
 
require_once 'Application/Domain/Registration/Whois/Exception.php';


/**
 * @whois   Ayoola
 * @package    Application_Domain_Registration_Whois_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Domain_Registration_Whois_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'extension',  );
	
    /**
     * 
     * @var string
     */
	protected $_idColumn = 'extension';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Domain_Registration_Whois';
	
	
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
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;

		//	extension
		if( ! $values )
		{
			$fieldset->addElement( array( 'name' => 'extension', 'label' => 'Extension', 'style' => 'display:block;margin-left:0;', 'placeholder' => 'e.g. .com', 'type' => 'InputText', 'value' => @$values['extension'] ) );
		}

		//	server
		$fieldset->addElement( array( 'name' => 'server', 'label' => 'Server for Whois', 'style' => 'display:block;margin-left:0;', 'placeholder' => 'server.net', 'type' => 'InputText', 'value' => @$values['server'] ) );	

		//	server
		$fieldset->addElement( array( 'name' => 'badnews_length', 'label' => 'Length of error message. Used in detecting validation of domain names', 'style' => 'display:block;margin-left:0;', 'placeholder' => 'e.g. 20', 'type' => 'InputText', 'value' => @$values['badnews_length'] ) );	
	//	$fieldset->addRequirement( 'whois', array( 'WordCount' => array( 1, 10 )  ) );
	//	$fieldset->addFilter( 'whois', array( 'float' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
//		$form->setParameter( array( 'whoiss' => 'email-address, phone-number' ) );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
