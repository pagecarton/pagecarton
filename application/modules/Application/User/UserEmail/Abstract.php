<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserEmail_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_UserEmail_Exception 
 */
 
require_once 'Application/User/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserEmail_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_UserEmail_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 1;
	
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
	protected $_identifierKeys = array( 'useremail_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_User_UserEmail';
	
    /**
     * Sets _dbData
     * 
     */
	public function setDbData()
    {
		$table = $this->getDbTable();
		return $this->_dbData = self::getUserRecord( $table );
    } 
	
    /**
     * Sets _identifierData
     * 
     */
	public function setIdentifierData( $identifier = null )
    {
		if( is_null( $identifier ) ){ $identifier = $this->getIdentifier(); }
		$table = $this->getDbTable();
		return $this->_identifierData = self::getUserRecord( $table, $identifier );
    } 
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		require_once 'Ayoola/Form.php';
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
	//	$form->setSubmitButton( $submitValue );
	//	self::v( $values );
		$form->formNamespace = get_class( $this );
		$form->submitValue = $submitValue ;
		require_once 'Ayoola/Form/Element.php';
		$fieldset = new Ayoola_Form_Element;
		$fieldset->id = __CLASS__;
//		$fieldset->placeholderInPlaceOfLabel = true;	
		@$values['email'] = @$values['email'] ? : ( Ayoola_Form::getDefaultValues( 'email' ) ? : Ayoola_Application::getUserInfo( 'email' ) );
		$fieldset->addElement( array( 'name' => 'email', 'label' => 'Email Address', 'placeholder' => 'e.g. email@example.com', 'type' => 'InputText', 'value' => @$values['email'] ) );
		$options = array( 'Yes' => 'Sign up for our newsletter to receive updates or news from us.' );
	//	var_export( $values['add_email_to_mailing_list'] );
	//	var_export( $options );
		$fieldset->addElement( array( 'name' => 'add_email_to_mailing_list', 'label' => '', 'type' => 'Checkbox', 'value' => @$values['add_email_to_mailing_list'] ? : array_keys( $options ) ), $options );
		$fieldset->addRequirement( 'email', array( 'EmailAddress' => null ) );
		$fieldset->addFilters( 'Trim::Escape' );
		$fieldset->addLegend( "$legend" );
		$form->addFieldset( $fieldset );
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
