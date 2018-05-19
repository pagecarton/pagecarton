<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Import_Exception 
 */
 
require_once 'Ayoola/Page/Layout/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Extension_Import_Abstract extends Ayoola_Abstract_Table
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
     * 
     *
     * @var string
     */
	protected $_idColumn = 'extension_name';  
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'extension_name' );
 		
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Extension_Import_Table';
	
	
    /**
     * creates the form for creating and editing 
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
	
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;

		do 
		{
			$options = array( 
								'new' => 'Upload new Plugin',
								'update' => 'Update existing Plugin',
							);
		//	$fieldset->addElement( array( 'name' => 'import_type', 'label' => 'What are you trying to do?', 'required' => 'required', 'type' => 'Radio', 'value' => @$values['import_type'] ? : 'new' ), $options );
		//	$fieldset->addRequirement( 'import_type', array( 'ArrayKeys' => $options + array( 'badnews' => 'Please select what you are trying to do...' ) ) );
		//	var_export( $this->getGlobalValue( 'import_type' ) );
			if( @$_REQUEST['extension_name'] )
			{
				$option = new Ayoola_Extension_Import_Table;
				if( $option = $option->selectOne( null, array( 'extension_name' => $_REQUEST['extension_name'] ) ) )
				{
					$fieldset->addElement( array( 'name' => 'extension_name', 'type' => 'Hidden', 'value' => $option['extension_name'] ) );
					$fieldset->addLegend( 'Update Plugin (' . $option['extension_title'] . ')' );
				}
				else
				{
					$fieldset->addLegend( 'Upload new Plugin' );
				}
			}
			else
			{
				$fieldset->addLegend( 'Upload new Plugin' );
			}
		}
		while( false );
	//	$fieldset->addElement( array( 'name' => 'upload', 'label' => 'Plugin File (.tar.gz archive)', 'data-allow_base64' => true, 'data-document_type' => 'application', 'type' => 'Document', 'value' => @$values['upload'] ) );
		$fieldset->addElement( array( 'name' => 'plugin_url', 'label' => 'Plugin File (.tar.gz archive)', 'data-document_type' => 'application', 'type' => 'Document', 'value' => @$values['plugin_url'] ) );
		$form->addFieldset( $fieldset );
		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
