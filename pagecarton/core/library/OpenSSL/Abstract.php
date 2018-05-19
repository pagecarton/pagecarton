<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    OpenSSL_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see 
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @category   PageCarton CMS
 * @package    OpenSSL_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class OpenSSL_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'encryption_name' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'OpenSSL_Table';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_idColumn = 'encryption_name';
	
    /**
     * creates the form for creating and editing form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
	//	var_export( $values );
		//	Form to create a new form
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
	//	$fieldset->placeholderInPlaceOfLabel = false;
		if( is_null( $values ) )
		{		
			$fieldset->addElement( array( 'name' => 'encryption_name', 'placeholder' => 'Give the encrytion a name...', 'type' => 'InputText', 'value' => @$values['encryption_name'] ) );
			$fieldset->addRequirement( 'encryption_name', array( 'WordCount' => array( 3, 50 )  ) );
			$ciphers = openssl_get_cipher_methods();
			$ciphers_and_aliases = openssl_get_cipher_methods( true );
			$cipher_aliases = array_unique( array_map( 'strtolower', array_intersect( $ciphers_and_aliases, $ciphers) ) );	
	//		$cipher_aliases = array_unique( array_intersect( $ciphers_and_aliases, $ciphers) );	
	//		var_export( $cipher_aliases );
			$options = array_combine( $cipher_aliases, $cipher_aliases ) ;		
			$options += array( 
								'RSA' => 'RSA using private and public keys', 
							);
			$fieldset->addElement( array( 'name' => 'encryption_type', 'type' => 'Select', 'value' => @$values['encryption_type'], ), $options );
		}	
		$options = array();
		$options += $values ? array() : 
										array( 
												'predefined_key' => 'Do not auto-generate the required keys; I will manually enter a predefined encryption keys.', 
												);
		$options ? $fieldset->addElement( array( 'name' => 'encryption_options', 'type' => 'Checkbox', 'value' => @$values['encryption_options'], ), $options ) : null;
		if( is_array( Ayoola_Form::getGlobalValue( 'encryption_options' ) ) && in_array( 'predefined_key', Ayoola_Form::getGlobalValue( 'encryption_options' ) ) )
		{
			switch( Ayoola_Form::getGlobalValue( 'encryption_type' ) )
			{
				case 'RSA':
					$fieldset->addElement( array( 'name' => 'public_key', 'placeholder' => '', 'type' => 'TextArea', 'value' => @$values['public_key'] ) );
					@$values['private_key'] ? : $fieldset->addElement( array( 'name' => 'private_key', 'placeholder' => '', 'type' => 'TextArea', 'value' => @$values['private_key'] ) );
				break;
				default:
					$fieldset->addElement( array( 'name' => 'pre_shared_key', 'placeholder' => '', 'type' => 'TextArea', 'value' => @$values['pre_shared_key'] ) );
				break;
			}
		}

				
		
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
